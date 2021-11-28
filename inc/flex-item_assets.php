<div class="m_assets manage flex-item">
    <h1>Assets</h1>
    <table class="showtable">
        <tr>
            <th>ID</th>
            <th>Typ</th>
            <th>Name</th>
            <th>Operation</th>
        </tr>
        <?php foreach (get_assets() as $key => $value) { ?>
            <tr>
                <td><?php echo $value['ID']; ?></td>
                <td><?php echo $value['Type']; ?></td>
                <td><?php echo $value['Name']; ?></td>
                <td>
                    <form action="/?assetupd" method="POST">
                        <input type="hidden" name="id" value="<?php echo $value['dbid']; ?>">
                        <input class="negativ halfsize" type="submit" name="opt" value="Löschen" disabled="disabled">
                        <input class="halfsize" type="submit" name="opt" value="Bearbeiten">
                    </form>
                </td>
            </tr>
        <?php } ?>
        <tr>
            <form action="?insasset" method="POST">
                <input type="hidden" name="function" value="insertasset">
                <td>Wird automatisch generiert</td>
                <td>
                    <select name="devicetype">
                        <option value="0" <?php if (!isset($_POST['devicetype'])) echo "selected=\"selected\""; ?>>-- Bitte auswählen --</option>
                        <?php
                        foreach (get_assettypes_short(false) as $key => $value) {
                            if (isset($_POST['devicetype']) && $key == $_POST['devicetype'])
                                echo "<option value=\"" . $key . "\" selected=\"selected\">" . $value . "</option>";
                            else
                                echo "<option value=\"" . $key . "\">" . $value . "</option>";
                        }
                        ?>
                    </select>
                </td>
                <td><input type="text" name="name"></td>
                <td><input type="submit" value="hinzufügen"></td>
            </form>
        </tr>
    </table>
</div>
<?php if (isset($_POST["opt"]) && $_POST['opt'] = "Bearbeiten" && isset($_POST['id'])) { 
    $assetvalue = get_assets($_POST['id']);
    if (count($assetvalue) > 0) {
    ?>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $assetvalue[0]['dbid']; ?>" >
    <div class="overlay_bg">
        <div class="overlay form">
            <h1>Änderung</h1>
            <input type="hidden" name="function" value="editasset">
            <table style="width: 100%;">
                <tr>
                    <th>ID</th>
                    <td><?php echo $assetvalue[0]['ID']; ?></td>
                </tr>
                <tr>
                    <th>Typ<?php echo $assetvalue[0]['idassettype'] ?></th>
                    <td><select name="devicetype">
                            <option value="0" <?php if (!isset($_POST['devicetype'])) echo "selected=\"selected\""; ?>>-- Bitte auswählen --</option>
                            <?php
                            foreach (get_assettypes_short(false) as $key => $value) {
                                if ($key == $assetvalue[0]['idassettype'])
                                    echo "<option value=\"" . $key . "\" selected=\"selected\">" . $value . "</option>";
                                else
                                    echo "<option value=\"" . $key . "\">" . $value . "</option>";
                            }
                            ?>
                        </select></td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td><input type="text" name="name" value="<?php echo $assetvalue[0]['Name']; ?>"></td>
                </tr>
                <tr>
                    <th><input type="reset" value="zurücksetzen"></th>
                    <td><input type="submit" value="speichern"></td>
                </tr>
            </table>
        </div>
    </div>
    </form>
<?php } } ?>