<div class="m_rssettype manage flex-item">
    <h1>Assets Typen</h1>
    <table class="showtable">
        <tr>
            <th>Gerätetyp</th>
            <th>Prefix</th>
            <th>Ausleihdauermax</th>
            <th>Max. Verlängerungen</th>
            <th>Operation</th>
        </tr>
        <?php foreach (get_assettypes() as $key => $value) { ?>
        <tr>
            <td><?php echo $value['Name']; ?></td>
            <td><?php echo $value['prefix']; ?></td>
            <td><?php echo $value['renttimedays']; ?></td>
            <td><?php echo $value['renewals_max']; ?></td>
            <td>
                <form action="/?assettypupd" method="POST">
                <input type="hidden" name="id" value="<?php echo $value['idassettype']; ?>">
                <input class="negativ halfsize" type="submit" value="Löschen" disabled="disabled">
                <input class="halfsize" type="submit" value="Bearbeiten" disabled="disabled">
                </form>
            </td>
        </tr>
        <?php } ?>
        <tr>
            <td><input type="text"></td>
            <td><input type="text"></td>
            <td><input type="number" value="<?php echo $assettype_retimedays_default; ?>"></td>
            <td><input type="number" value="<?php echo $assettype_rerentmax_default; ?>"></td>
            <td><input type="submit" value="hinzufügen" disabled="disabled"></td>
        </tr>
    </table>
</div>