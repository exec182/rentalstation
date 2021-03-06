<?php 
include('functions.php');

$overduelist = get_rents(true);
$admin_mailtext = "Hallo, \r\n\nes sind folgende Leihgaben überfällig\r\n";

if (count($overduelist) > 0) {
    print_r($overduelist);
    echo "<br><h1>Mails an Leiher</h1><br>";
    foreach ($overduelist as $key => $value) {
        echo "Mail an: ".$value["Mail"]."<br>";
        echo "Überfälliges Asset entdeckt<br>";
        echo "Asset: ".$value["Assetname"]." (".$value["prefix"].$value["idasset"].")<br>";
        echo "<br>";
        $admin_mailtext .= $value["Assetname"]." (".$value["prefix"].$value["idasset"].") von ".$value["Mail"]." seit ".$value["rentlimit"]."\r\n";

    }

    echo "<br><h1>Mails an Personal</h1><br>";

    echo nl2br(htmlspecialchars_decode($admin_mailtext));
    foreach (get_users() as $value) {
        
    }
} else {
    echo "keine Mails notwendig!";
}