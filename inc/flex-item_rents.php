<?php
    if (!isset($inc_var_rents_onlyoverdue)) $inc_var_rents_onlyoverdue = false;
    $list = get_rents($inc_var_rents_onlyoverdue);
    if (count($list) > 0) {
?>
<div class="m_rental manage flex-item">
    <h1><?php if ($inc_var_rents_onlyoverdue) echo "Überfällige Assets"; else echo "geliehene Assets"; ?></h1>
    <table class="showtable">
        <tr>
            <th>geliehen seit</th>
            <th>Limit</th>
            <th>Verl.</th>
            <th>GeräteID</th>
            <th>Gerät</th>
            <th>mail</th>
            <th>Operation</th>
        </tr>
        <?php foreach ($list as $key => $value) { ?>
        <tr>
            <td><?php echo $value['start']; ?></td>
            <td><?php echo $value['rentlimit']; ?></td>
            <td><?php echo $value['renewals']." / ".$value['renewals_max']; ?></td>
            <td><?php echo $value['id']; ?></td>
            <td><?php echo $value['Assetname']; ?></td>
            <td><?php echo $value['Mail']; ?></td>
            <td>
                <form method="POST" action="?rentopt">
                    <input type="hidden" name="id" value="<?php echo $value['idrent']; ?>">
                    <input type="hidden" name="idtenant" value="<?php echo $value['idtenant']; ?>">
                    <input type="hidden" name="function" value="rentset">
                    <input class="halfsize" type="submit" name="opt" value="Verlängern">
                    <input class="positiv halfsize" type="submit" name="opt" value="Zurück">
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>
<?php } ?>