<div class="m_assets manage flex-item">
    <h1>Verfügbare Assets</h1>
    <table class="showtable">
        <tr>
            <th>Typ</th>
            <th>Anzahl</th>
        </tr>
        <?php foreach (get_available_assetcount() as $key => $value) { ?>
        <tr>
            <td><?php echo $value['Typename']; ?></td>
            <td><?php echo $value['Anzahl']; ?></td>
        </tr>
        <?php } ?>
    </table>
    <table class="showtable">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Typ</th>
        </tr>
        <?php foreach (get_available_asset() as $key => $value) { ?>
        <tr>
            <td><?php echo $value['prefix']; ?><?php echo $value['idasset']; ?></td>
            <td><?php echo $value['Assetname']; ?></td>
            <td><?php echo $value['Typename']; ?></td>
        </tr>
        <?php } ?>
    </table>
</div>