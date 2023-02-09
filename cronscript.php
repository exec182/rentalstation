
<?php
include('functions.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once 'class/phpmailer/Exception.php';
require_once 'class/phpmailer/PHPMailer.php';
require_once 'class/phpmailer/SMTP.php';


require_once('class/phpmailer/PHPMailer.php');
require_once('class/phpmailer/SMTP.php');
$header = 'From: rental@ssc-services.de' . "\r\n" .
'Reply-To: rental@ssc-services.de' . "\r\n" .
'X-Mailer: PHP/' . phpversion();

$overduelist = get_rents(true);
$admin_mailtext = "Hallo, \r\n\nes sind folgende Leihgaben überfällig\r\n";
$admin_mailhtml = "Hallo, <br><br>es sind folgende Leihgaben überfällig:<br><table><tr><th>Asset</th><th>Benutzer</th><th>Ablaufzeitpunkt</th></tr>";


if (count($overduelist) > 0) {
    print_r($overduelist);
    echo "<br><h1>Mails an Leiher</h1><br>";
    foreach ($overduelist as $key => $value) {
        echo "Mail an: ".$value["Mail"]."<br>";
        echo "Überfälliges Asset entdeckt<br>";
        echo "Asset: ".$value["Assetname"]." (".$value["prefix"].$value["idasset"].")<br>";
        echo "<br>";
        $admin_mailtext .= $value["Assetname"]." (".$value["prefix"].$value["idasset"].") von ".$value["Mail"]." seit ".$value["rentlimit"]."\r\n";
        $admin_mailhtml .= "<tr><td>".$value["Assetname"]." (".$value["prefix"].$value["idasset"].")</td><td>".$value["Mail"]."</td><td>".$value["rentlimit"]."</td></tr>";
        $mail             = new PHPMailer(true);
        try {
            $mail->IsSMTP(); // telling the class to use SMTP
            $mail->Host       = "mail.ssc-services.de"; // SMTP server
            $mail->Port       = 25;                    // set the SMTP port for the GMAIL server
            $mail->SMTPAuth   = false;                  // enable SMTP authentication
            $mail->CharSet   = 'UTF-8';
            $mail->Encoding  = 'base64';
            //$mail->Username   = "yourname@yourdomain"; // SMTP account username
            //$mail->Password   = "yourpassword";        // SMTP account password
            //$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
            $mail->SetFrom('rental@ssc-services.de', 'noreply - Rental Service');
            $mail->addBCC('c.ziegler@ssc-services.de');
            $mail->addAddress($value["Mail"]);
            $mail->Subject    = "[Rental] Überfälliges Asset";
            $mail->Body       = "Asset: ".$value["Assetname"]." (".$value["prefix"].$value["idasset"].") ist überfällig";
            $mail->AltBody    = "Mail an: ".$value["Mail"]."<br>"."Überfälliges Asset entdeckt<br>"."Asset: ".$value["Assetname"]." (".$value["prefix"].$value["idasset"].")<br>";
            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
    $admin_mailhtml .= "</table>";

    echo "<br><h1>Mails an Personal</h1><br>";

    echo nl2br(htmlspecialchars_decode($admin_mailtext));
    foreach (get_users() as $value) {
        $mail             = new PHPMailer(true);
        try {
            $mail->IsSMTP(); // telling the class to use SMTP
            $mail->Host       = "mail.ssc-services.de"; // SMTP server
            $mail->Port       = 25;                    // set the SMTP port for the GMAIL server
            $mail->SMTPAuth   = false;                  // enable SMTP authentication
            $mail->CharSet   = 'UTF-8';
            $mail->Encoding  = 'base64';
            //$mail->Username   = "yourname@yourdomain"; // SMTP account username
            //$mail->Password   = "yourpassword";        // SMTP account password
            //$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
                                            // 1 = errors and messages
                                            // 2 = messages only
            $mail->SetFrom('rental@ssc-services.de', 'noreply - Rental Service');
            $mail->addAddress($value["mail"]);
            $mail->Subject    = "[Rental] Statusbericht für ".$value["username"];

            $mail->Body       = $admin_mailhtml;
            $mail->AltBody    = $admin_mailtext;  //"To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
} else {
    echo "keine Mails notwendig!";
}
