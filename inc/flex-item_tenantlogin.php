<div class="show-rent flex-item">
    <h1>Verlängerung deines Gerätes</h1>
    <p>Hier kannst du dich einfach einloggen mit deiner Mailadresse und einem deiner geliehenen Geräte. Gebe einfach deine eMail Adresse und die AssetID von deinem Gerät ein und drücke auf Assets anzeigen.</p>
    <form method="post">
        <input type="hidden" name="function" value="extendrent" />
        <table class="onbottom formattable_noborder_nospacing">
            <tr>
                <th>eMail</th>
                <td><input type="email" name="email" value="<?php echo $tenant_login_default; ?>" required /></td>
            </tr>
            <tr>
                <th>AssetID</th>
                <td><input type="text" name="assetid" required /></td>
            </tr>
            <tr>
                <th></th>
                <td><input type="submit" value="Assets anzeigen" /></td>
            </tr>
        </table>
    </form>
</div>