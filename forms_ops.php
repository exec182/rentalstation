<?php require_once('functions.php'); 
$usererrortext = "";
$userinfotext  = "";


if (isset($_POST['function'])) {
    switch ($_POST['function']) {
        case "login":
            if (isset($_POST['username']) and isset($_POST['password']))
            {
                if (check_credentials($_POST['username'], $_POST['password'], true))
                {
                    $userinfotext = "Login erfolgreich";
                } else {
                    $usererrortext = "Benutzername oder Passwort falsch";
                }
            } else {
                $usererrortext = "Funktion 'login' hat nicht alle Parameter erhalten";
            }
            break;

        case "rentset":
            if (isset($_POST['id']) && isset($_POST['idtenant']) && isset($_POST['opt'])) {
                switch ($_POST['opt']) {
                    case "Stornieren":
                        set_rent_del($_POST['id']);
                        break;
                    case "Ausgeben":
                        set_rent_start($_POST['id']);
                        break;
                    case "Verlängern":
                        set_rent_renewal($_POST['id'], $_POST['idtenant'], true);
                        break;
                    case "Zurück":
                        set_rent_end($_POST['id']);
                        break;
                    default:
                        $usererrortext = "SubFunktion nicht gefunden";
                        break;
                }
            }
            break;

        case "createuser":
            if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['mailadd']))
            create_credentials($_POST['username'], $_POST['password'], $_POST['mailadd']);
            break;
        
        case "inquiry":
            if (isset($_POST['email']) && isset($_POST['devicetype'])) {
                if (set_inquiry($_POST['email'], $_POST['devicetype'])) {
                    $inc_inquiry_message = "Ein Gerät wurde für dich reserviert. <br />Ein Mitarbeiter meldet sich bei dir für die Ausgabe.";
                } else {
                    $usererrortext = "Anfrage konnte nicht gestellt werden";
                }
            }
            break;
            
        case "insertasset":
            if (isset($_POST['devicetype']) && isset($_POST['name'])) {
                if (insert_asset($_POST['devicetype'], $_POST['name']))
                    $inc_m_assetinsert_message = "Asset wurde hinzugefügt";
                else 
                    $usererrortext = "Asset konnte nicht erstellt werden";
            }
            break;

        case "rentlogin":
            if (isset($_POST['email']) && isset($_POST['assetid'])) {
                $tenantid = find_tenant($_POST['email'], $_POST['assetid']);
                if ($tenantid != false) 
                    $_SESSION['idtenant'] = $tenantid;
                else 
                    $usererrortext = "Leider konnte die Kombination aus Mail und Asset nicht verifiziert werden";
                //print_r($tenantid);
            }
            break;
    
        case "editasset":
            if (isset($_POST['id']) && isset($_POST['devicetype']) && isset($_POST['name'])) {
                set_asset($_POST['id'], $_POST['name'], $_POST['devicetype']);
            }
            break;
        
        default:
            $usererrortext = "Funktion nicht gefunden";
            break;
    }
}
