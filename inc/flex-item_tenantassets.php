<div class="rerent flex-item">
    <h1>Deine aktuell geliehene Assets</h1>
    <table class="showtable">
        <tr>
            <th>#</th>
            <th>Assetid</th>
            <th>Beschreibung</th>
            <th>Verlängerungen</th>
            <th>Auslaufzeit</th>
            <th><th>
        <tr>
        <?php foreach (get_rents(false, $_SESSION['idtenant']) as $key => $element) { ?> 
        <tr>
            <td><?php echo $key + 1; ?></td>
            <td><?php echo $element['id']; ?></td>
            <td><?php echo $element['Assetname']; ?></td>
            <td><?php echo $element['renewals']; ?> von <?php echo $element['renewals_max']; ?></td>
            <td><?php echo $element['rentlimit']; ?></td>
            <td>
                <?php if ($element['renewals'] < $element['rentlimit']) { ?>
                <form method="POST" action="?rentopt">
                    <input type="hidden" name="id" value="<?php echo $element['idrent']; ?>">
                    <input type="hidden" name="idtenant" value="<?php echo $_SESSION['idtenant']; ?>">
                    <input type="hidden" name="function" value="rentset">
                    <input class="halfsize" type="submit" name="opt" value="Verlängern">
                </form>
                <?php } else { echo "Verlängern nicht mehr möglich"; } ?>
            <td>
        <tr>
        <?php } ?>
    </table>
</div>