<?php 
function myprint_r($my_array) {
    if (is_array($my_array)) {
        echo "<table border=1 cellspacing=0 cellpadding=3 width=100%>";
        echo '<tr><td colspan=2 style="background-color:#111;"><strong><font color=white>ARRAY</font></strong></td></tr>';
        foreach ($my_array as $k => $v) {
                echo '<tr><td valign="top" style="width:40px;background-color:#aaa;">';
                echo '<strong>' . $k . "</strong></td><td>";
                myprint_r($v);
                echo "</td></tr>";
        }
        echo "</table>";
        return;
    }
    echo $my_array;
}

if ((isset($debug) && $debug) || !isset($debug)) {
?>
<footer id=debug>
    <h1>DEBUGINFO</h1>
    <table border="1" width="100%">
        <tr>
            <th width="25%">GET DATA</th>
            <th width="25%">POST DATA</th>
            <th width="25%">SESSION DATA</th>
            <th width="25%">Sonstiges</th>
        </tr>
        <tr>
            <td valign="top"><?php myprint_r($_GET); ?></td>
            <td valign="top"><?php myprint_r($_POST); ?></td>
            <td valign="top"><?php myprint_r($_SESSION); ?></td>
            <td valign="top"><?php myprint_r($DebugOutput); ?><br><h3>JSON DATA</h3><div id="debugjson"></div></td>
        </tr>
    </table>
</footer><?php
}
?>