<?php 
    require_once('functions.php'); 
    require_once('forms_ops.php');
?>
<!DOCTYPE html>
<head>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <table>
            <tr>
                <td>
                    <h1><?php echo $site_title; ?></h1>
                </td>
                <td>
                    <?php if (isset($_SESSION['userid'])) { ?>
                    <nav>
                        <ul>
                            <a href="?page=start"><li>Start</li></a>
                            <a href="?page=rents"><li>Ausgeliehenes</li></a>
                            <a href="?page=assets"><li>Assets</li></a>
                            <a href="?page=assettype"><li>Assettypen</li></a>
                            <a href="?page=user"><li>Benutzer</li></a>
                            <a href="?logout"><li class="negativ_hover">Logout</li></a>
                        </ul>
                    </nav>
                    <?php } ?>
                    <?php if (isset($_SESSION['idtenant'])) { ?>
                    <nav>
                        <ul>
                            <a href="?logout"><li class="negativ_hover">Logout</li></a>
                        </ul>
                    </nav>
                    <?php } ?>
                </td>
            </tr>
        </table>
    </header>
    <div class="flex-container">
        <?php 
        if (!isset($_SESSION['page'])) $_SESSION['page'] = "start";
        if (isset($_SESSION['userid']))
        {
            if ($_SESSION['page'] == "start") {
                $_SESSION['page'] = "manage_start";
            }
            if (isset($_GET['page'])) $_SESSION['page'] = "manage_".$_GET['page'];
        }
        switch ($_SESSION['page']) {
            case "start":
                if (!isset($_SESSION['idtenant']))
                { 
                    include('inc/flex-item_inquiry.php');
                    include('inc/flex-item_tenantlogin.php');
                    include('inc/flex-item_login.php');
                } else {
                    include('inc/flex-item_tenantassets.php');
                }
                //include('inc/flex-item_availableassets.php');
                break;
            case "manage_start":
                $inc_var_rents_onlyoverdue = true;
                include('inc/flex-item_inquirys.php');
                include('inc/flex-item_rents.php');
                include('inc/flex-item_availableassets.php');
                break;
            case "manage_rents":
                include('inc/flex-item_rents.php');
                break;
            case "manage_inquiry":
                include('inc/flex-item_inquirys.php');
                break;
            case "manage_assets":    
                include('inc/flex-item_assets.php');
                break;
            case "manage_user":
                include('inc/flex-item_user.php');
                break;
            case "manage_assettype":
                include('inc/flex-item_assettype.php');
                break;
            default:
                echo "blöd";
                break;
        } ?>
    </div>
    <?php if ($usererrortext != "") { ?>
    <a href="/">
    <div class="overlay_bg">
    <div class="overlay error">
        <h1>Ups da ist ein Fehler passiert</h1>
        <p><?php echo $usererrortext.$userinfotext; ?></p>
        <p class="smallinfo">Klick mich zum schließen</p>
    </div>
    </div>
    </a>
    <?php } ?>
    <footer>
	Geschrieben von Christoph Ziegler
	| Lizenziert unter <a href="https://www.gnu.org/licenses/old-licenses/gpl-2.0.html">GPL 2.0 (General Public License)</a> 
    <?php if ($site_impressumURL != "") echo "| <a href=\"".$site_impressumURL."\">IMPRESSUM</a>"; ?>
	<?php if (isset($_SESSION['username']) &&  isset($_SESSION['userid'])) echo " | Angemeldet als ".$_SESSION['username']; ?>
    </footer>

    <?php 
    if ($debugshow) include('inc/debug.php'); 
    ?>
</body>
