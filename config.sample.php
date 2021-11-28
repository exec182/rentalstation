<?php 
    // Datenbankverbindung
    $db_user                        = "dbsuer";
    $db_password                    = "dbpass";
    $db_host                        = "localhost";
    $db_port                        = 3306;
    $db_scheme                      = "rentstation";

    // Anzeigen
    $site_title                     = "Verleihcenter";
    $site_impressumURL              = "";

    // Security
    $allow_forgetpassword           = false;                // not working
    $tenantboundFQDN                = "your_domain.de";     // in DEV

    // Application defaults
    $assettype_rerentmax_default    = 3;
    $assettype_retimedays_default   = 90;
    $tenant_login_default           = "@".$tenantboundFQDN;

    // Debugging einschalten
    $debugshow                      = false;

?>
