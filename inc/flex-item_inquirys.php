<?php 
$list = get_inquiry();
if (count($list) > 0) { ?>
<div class="m_inquiry manage flex-item">
    <h1>Anfragen / Reservierungen</h1>
    <table class="showtable">
        <tr>
            <th>Angefragt am</th>
            <th>Gerät</th>
            <th>mail</th>
            <th>Operation</th>
        </tr>
        <?php foreach (get_inquiry() as $key => $value) { ?>
        <tr>
            <td><?php echo $value['inquirydate']; ?></td>
            <td><?php echo $value['prefix'].$value['idasset']." ".$value['Name']; ?></td>
            <td><?php echo $value['Mail']; ?></td>
            <td>
                <form action="/?inqiryupd" method="POST">
                <input type="hidden" name="id" value="<?php echo $value['idrent']; ?>" >
                <input type="hidden" name="idtenant" value="<?php echo $value['idtenant']; ?>" >
                <input type="hidden" name="function" value="rentset" >
                <input class="negativ halfsize" type="submit" name="opt" value="Stornieren">
                <input class="positiv halfsize" type="submit" name="opt" value="Ausgeben">
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>
<?php } ?>