<?php
if (session_id() !== null) {
    session_start();
}

if (isset($_GET['logout'])) {
    $_SESSION = array();
}

include('config.php');

$db = new mysqli($db_host, $db_user, $db_password, $db_scheme, $db_port);
if ($db->connect_errno) {
    die("Verbindung zur Datenbank fehlgeschlagen: " . $db->connect_error);
}

if (!$db->set_charset("utf8")) {
    printf("Error loading character set utf8: %s\n", $db->error);
    exit();
}

function randomPassword($len)
{
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = "";
    for ($i = 0; $i < $len; $i++) {
        $n = rand(0, strlen($alphabet) - 1);
        $pass .= $alphabet[$n];
    }
    return $pass;
}

function create_credentials($user, $password, $mail)
{
    global $db;
    $passwordhash = password_hash($password, PASSWORD_ARGON2I);
    $sql = "INSERT INTO `verleih`.`user` (`iduser`, `username`, `password`, mail) VALUES (NULL, ?, ?, ?);";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("sss", $user, $passwordhash, $mail);
    $stmt->execute();
    // echo $passwordhash;
    return $stmt->affected_rows;
}

function check_credentials($user, $password, $setsession = true)
{
    global $db;
    $credentials_sql = "SELECT u.password, u.iduser, u.username FROM user as u WHERE u.username = ?;";
    $credentials_stmt = $db->prepare($credentials_sql);
    $credentials_stmt->bind_param('s', $user); //, $password);
    $credentials_stmt->bind_result($passwordhash, $id, $username);
    $credentials_stmt->execute();
    $credentials_stmt->store_result();
    $output = false;
    if ($credentials_stmt->fetch()) {
        $output = password_verify($password, $passwordhash);
    }
    if ($setsession && $output) {
        $_SESSION['userid']    = $id;
        $_SESSION['username']  = $username;
    }
    return $output;
}

function change_password($user, $oldpassword, $newpassword)
{
    global $db;
    if (check_credentials($user, $oldpassword, false)) {
        $newpasswordhash = password_hash($newpassword, PASSWORD_ARGON2I);
        $sql2 = "UPDATE `user` SET `password` = ? WHERE `user`.`username` = ?;";
        $stmt2 = $db->prepare($sql2);
        $stmt2->bind_param('ss', $newpasswordhash, $user);
        $stmt2->execute();
        if ($stmt2->affected_rows == 1)
            return true;
        else
            return false;
    } else {
        return false;
    }
}

function recreate_password($user, $mail)
{
    global $db;
    $newpassword = randomPassword(20);
    $newpasswordhash = password_hash($newpassword, PASSWORD_ARGON2I);
    $sql2 = "UPDATE `user` SET `password` = ? WHERE `user`.`username` = ? AND `user`.`mail` = ?;";
    $stmt2 = $db->prepare($sql2);
    $stmt2->bind_param('sss', $newpasswordhash, $user, $mail);
    $stmt2->execute();
    //print_r($stmt2);
    if ($stmt2->affected_rows == 1) {
        return $newpassword;
    } else
        return false;
}

function forget_password($user, $mail)
{
    $newpassword = recreate_password($user, $mail);

    if ($newpassword != false) {
        $empfaenger = $mail;
        $betreff = 'Der Betreff';
        $header = 'From: webmaster@localhost' . "\r\n" .
            'Reply-To: webmaster@localhost' . "\r\n" .
            'X-Mailer: PHP/' . phpversion() . "\r\n" .
            'Mime-Version: 1.0' . "\r\n" .
            'Content-Type: text/plain; charset=utf-8' . "\r\n" .
            'Content-Transfer-Encoding: quoted-printable';


        $nachricht = "Hallo " . $user . ",\r\n";
        $nachricht .= "\r\n";
        $nachricht .= "Dein Passwort wurde neu generiert.\r\n";
        $nachricht .= "\r\n";
        $nachricht .= "Dein neues Kennwort lautet: " . $newpassword . "\r\n";
        $nachricht .= "\r\n";
        $nachricht .= "Viele Gruesze\r\n";
        $nachricht .= "Christoph Ziegler\r\n";

        if (mail($empfaenger, $betreff, $nachricht, $header)) {
            return true;
        } else die("Passwort konnte nicht versand werden. Es lautet: " . $newpassword);
    } else {
        return false;
    }
}


// if (!forget_password("exec", "exec@localhost")) echo "WTF";



function get_assettypes_short($available = true)
{
    $output = array();
    if ($available) {
        foreach (get_available_assetcount() as $key => $value) {
            $output[$value['idassettype']] = $value['Typename'] . " [" . $value['Anzahl'] . " verfügbar]";
        }
    } else {
        global $db;
        $sql = "SELECT idassettype,Name FROM `assettype` order by Name";
        $stmt = $db->prepare($sql);
        $stmt->bind_result($id, $name);
        $stmt->execute();
        while ($stmt->fetch()) {
            $output[$id] = $name;
        }
    }
    return $output;
}

function get_assets($id = null)
{
    global $db;
    $sql = "SELECT 
        concat(at.prefix, a.idasset) as ID,
        at.Name as Type,
        a.Name as Name,
        a.idasset as dbid,
        a.idassettype as idassettype
    FROM asset as a 
        join assettype as at on (a.idassettype = at.idassettype)";
    if ($id != null) $sql .= " WHERE a.idasset = " . $id;
    return $db->query($sql)->fetch_all(MYSQLI_ASSOC);
}

function set_asset($id, $name, $idassettype)
{
    global $db;
    $sql = "UPDATE `verleih`.`asset` SET `Name` = ?, `idassettype` = ? WHERE `asset`.`idasset` = ?;";
    if ($stmt = $db->prepare($sql)) {
        $stmt->bind_param('sii', $name, $idassettype, $id);
        $stmt->execute();
    } else {
        die("MYSQL Error: " . $db->errno . " " . $db->error);
    }
}

function insert_asset($idassettype, $name)
{
    global $db;
    $sql2 = "INSERT INTO `verleih`.`asset` (`idasset`, `Name`, `idassettype`) VALUES (NULL, ?, ?);";
    if ($stmt2 = $db->prepare($sql2) && $idassettype != 0) {
        $stmt2->bind_param('si', $name, $idassettype);
        $stmt2->execute();
        return ($stmt2->affected_rows == 1);
    } else return false;
}

function get_assettypes()
{
    global $db;
    $sql = "SELECT
        *
    FROM assettype";
    return $db->query($sql)->fetch_all(MYSQLI_ASSOC);
}

function get_inquiry()
{
    global $db;
    $sql = "SELECT * 
    FROM `rent` as r
        join asset as a on (r.idasset = a.idasset)
        join assettype as at on (a.idassettype = at.idassettype)
        join tenant as t on (t.idtenant = r.idtenant)
    WHERE r.`inquirydate` IS NOT NULL 
        AND r.`start` IS NULL 
        AND r.`end` IS NULL";
    return $db->query($sql)->fetch_all(MYSQLI_ASSOC);
}

function get_users()
{
    global $db;
    $sql = "SELECT * FROM user";
    return $db->query($sql)->fetch_all(MYSQLI_ASSOC);
}

function get_rents($overdue = false, $tenantid = null)
{
    global $db;
    $sql = "SELECT * FROM `rentlist` WHERE start IS NOT NULL AND end IS NULL";
    if ($overdue) {
        $sql .= " AND rentlimit < now()";
    }
    if ($tenantid != null) {
        $sql .= " AND idtenant = " . $tenantid;
    }
    $sql .= " Order by rentlimit ASC";
    return $db->query($sql)->fetch_all(MYSQLI_ASSOC);
}

function set_rent_renewal($id, $idtenant, $override = false)
{
    global $db;
    $sql = "SELECT renewals, renewals_max FROM `rentlist` WHERE `idrent` = ? AND `idtenant` = ?";
    $rent_renewals = 0;
    $rent_renewals_max = 0;
    if ($stmt = $db->prepare($sql)) {
        $stmt->bind_param('ii', $id, $idtenant);
        $stmt->bind_result($rent_renewals, $rent_renewals_max);
        $stmt->execute();
        //print_r($stmt);
        $stmt->fetch();
        $stmt->close();
        if (($rent_renewals < $rent_renewals_max) | $override) {
            $sql2 = "UPDATE `verleih`.`rent` SET `renewals` = ? WHERE `rent`.`idrent` = ?;";
            if ($stmt2 = $db->prepare($sql2)) {
                $newrents = $rent_renewals + 1;
                $stmt2->bind_param('ii', $newrents, $id);
                $stmt2->execute();
            } else {
                die("MYSQL Error: " . $db->errno . " " . $db->error);
            }
            return "JA " . $rent_renewals . " " . $rent_renewals_max;
        } else {
            return "NEIN " . $rent_renewals . " " . $rent_renewals_max;
        }
        //set_rent_end($id);
        /*
        $sql2 = "INSERT INTO `verleih`.`rent` (`idrent`, `idasset`, `idtenant`, `inquirydate`, `start`, `end`) VALUES (NULL, ?, ?, ?, now(), NULL);";
        if ($stmt2 = $db->prepare($sql2)) {
            $stmt2->bind_param('iis', $id, $idtenant, $rentlimit);
            $stmt2->execute();
        } else {
            die("MYSQL Error: ".$db->errno . " " . $db->error);
        } */
    } else {
        die("MYSQL Error: " . $db->errno . " " . $db->error);
    }
}


/// Assets
function get_available_asset()
{
    global $db;
    $sql = "SELECT * FROM `availableassets`";
    return $db->query($sql)->fetch_all(MYSQLI_ASSOC);
}

function get_available_assetcount()
{
    global $db;
    $sql = "SELECT 
        *,
        count(idasset) as Anzahl
    FROM `availableassets`
    Group by idassettype";
    return $db->query($sql)->fetch_all(MYSQLI_ASSOC);
}

function create_rent_inquiry($idtenant, $idasset)
{
    global $db;
    $sql = "INSERT INTO `verleih`.`rent` (`idrent`, `idasset`, `idtenant`, `inquirydate`, `start`, `end`) VALUES (NULL, ?, ?, now(), NULL, NULL);";
    if ($stmt = $db->prepare($sql)) {
        $stmt->bind_param('ss', $idasset, $idtenant);
        $stmt->execute();
        if ($stmt->affected_rows == 1)
            return true;
        else
            return false;
    } else {
        die("MYSQL Error: " . $db->errno . " " . $db->error);
    }
}

function set_rent_start($id)
{
    global $db;
    $sql2 = "UPDATE `rent` SET `start` = now() WHERE `rent`.`idrent` = ?;";
    if ($stmt2 = $db->prepare($sql2)) {
        $stmt2->bind_param('i', $id);
        $stmt2->execute();
        $_GET['rentstart'] = ">" . $stmt2->affected_rows;
        return ($stmt2->affected_rows == 1);
    } else {
        die("MYSQL Error: " . $db->errno . " " . $db->error);
    }
}

function set_rent_end($id)
{
    global $db;
    $sql2 = "UPDATE `rent` SET `end` = now() WHERE `rent`.`idrent` = ?;";
    $stmt2 = $db->prepare($sql2);
    $stmt2->bind_param('i', $id);
    $stmt2->execute();
    return ($stmt2->affected_rows == 1);
}

function set_rent_del($id)
{
    global $db;
    $sql2 = "DELETE FROM `verleih`.`rent` WHERE `rent`.`idrent` = ?;";
    $stmt2 = $db->prepare($sql2);
    $stmt2->bind_param('i', $id);
    $stmt2->execute();
    return ($stmt2->affected_rows == 1);
}

function create_tenant($mail)
{
    global $db;
    $sql = "INSERT INTO tenant (Mail) VALUES (?);";
    if ($stmt = $db->prepare($sql)) {
        $stmt->bind_param('s', $mail);
        $stmt->execute();
        if ($stmt->affected_rows == 1) {
            return $stmt->insert_id;
        } else {
            return false;
        }
    } else {
        die("MYSQL Error: " . $db->errno . " " . $db->error);
    }
}

function get_random_asset($idassettype)
{
    global $db;
    $sql = "SELECT idasset, concat(prefix, idasset) as id, Assetname FROM `availableassets` Where idassettype = ? Order by rand() limit 1";
    if ($stmt = $db->prepare($sql)) {
        $stmt->bind_param('i', $idassettype);
        $stmt->bind_result($idasset, $id, $assetname);
        $stmt->execute();
        $stmt->store_result();
        $output = false;
        if ($stmt->fetch()) {
            return $idasset;
        } else {
            return false;
        }
    } else {
        die("MYSQL Error: " . $db->errno . " " . $db->error);
    }
}

function find_tenant($mail, $rentid = null)
{
    global $db;
    $idtenant = 0;
    $sql_checktenant = "select idtenant from tenant where Mail = ? limit 1";
    if ($rentid != null) $sql_checktenant = "SELECT idtenant FROM `rentlist` WHERE `Mail` = ? AND `id` = ? limit 1";
    if ($stmt_checktenant = $db->prepare($sql_checktenant)) {
        if ($rentid == null)
            $stmt_checktenant->bind_param('s', $mail);
        else
            $stmt_checktenant->bind_param('ss', $mail, $rentid);
        $stmt_checktenant->bind_result($idtenant);
        $stmt_checktenant->execute();
        $_GET['findtenant_id'] = "wird ausgeführt".$idtenant;
        if ($stmt_checktenant->fetch()) {
            $_GET['findtenant_id'] = $idtenant;
            return $idtenant;
        } else {
            return false;
        }
    } else die("MYSQL Error: " . $db->errno . " " . $db->error);
}

function set_inquiry($mail, $idassettype)
{
    global $db;
    $_GET['state'] = "Start";
    // Prüfen ob ein Tenant schon existiert
    $idtenant = 0; // Speichert die TenantID
    $idasset = 0;  // Speichert die AssetID

    $idtenant = find_tenant($mail);
    if (!$idtenant) $idtenant = create_tenant($mail);

    if (get_rentcount($idtenant, $idassettype)["rest"] > 0) {
        $idasset = get_random_asset($idassettype);
        $_GET['userid'] = $idtenant;
        $_GET['randAsset'] = $idasset;
        if ($idtenant > 0 && $idasset > 0) {
            if (create_rent_inquiry($idtenant, $idasset)) {
                $_GET['state'] = "inquiry created";
                return true;
            } else {
                $_GET['state'] = "create inquiry has error";
                return false;
            }
        } else {
            $_GET['state'] = "can not create inquiry";
            return false;
        }
    } else {
        $_GET['state'] = "limit exeeded";
        return false;
    }
    // Device ermitteln und prüfen ob immer noch verfügbar und AssetID merken
    // Rent Inquiry erzeugen 
}

function create_asset($name, $idassettype)
{
}

function edit_asset($idasset, $name, $idassettype)
{
}

function delete_asset($idasset)
{
}

function get_rentcount($idtenant, $idassettype = null)
{
    global $db;
    $sql = "SELECT 
        MAX(ss.rentcount) AS rentcount,
        ss.renewals_max - MAX(ss.rentcount) AS openrents,
        group_concat(ss.Mail) AS mail,
        ss.Name,
        ss.idassettype
    FROM (
        SELECT 
            IF (t.idtenant IS NULL, 0, sum(r.renewals)) AS rentcount,
            t.Mail,
            t.idtenant,
            at.Name,
            at.renewals_max,
            at.idassettype
        FROM 
            `assettype` AS at
            LEFT JOIN asset AS a ON (a.idassettype = at.idassettype)
            LEFT JOIN rent AS r ON (r.idasset = a.idasset)
            LEFT JOIN tenant AS t ON (t.idtenant = r.idtenant AND t.idtenant = ?)
        GROUP BY t.idtenant, at.idassettype
        ) as ss
        JOIN assettype AS at ON (ss.idassettype = at.idassettype)
        GROUP BY at.idassettype";
    if ($idassettype != null) $sql .= " having at.idassettype = ?";
    if ($stmt = $db->prepare($sql)) {
        if ($idassettype != null) {
            $stmt->bind_param('ii', $idtenant, $idassettype);
        } else {
            $stmt->bind_param('i', $idtenant);
        }
        $stmt->bind_result($rentcount, $restrents, $mail, $name, $idassettypereturn);
        $stmt->execute();
        if ($idassettype != null) {
            $stmt->store_result();
            if ($stmt->fetch()) {
                $output = array();
                $output["rest"] = $restrents;
                $output["count"] = $rentcount;
                $output["name"] = $name;
                $output["mail"] = $mail;
                return $output;
            }
        } else
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    } else {
        die("MYSQL Error: " . $db->errno . " " . $db->error);
    }
}
