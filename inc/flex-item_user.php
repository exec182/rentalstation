<div class="m_user manage flex-item">
    <h1>Benutzer</h1>
    <table class="showtable">
        <tr>
            <th>Benutzername</th><th>email</th><th>Operation</th>
        </tr>
        <?php foreach (get_users() as $key => $value) { ?>
        <tr>
            <td><?php echo $value['username']; ?></td>
            <td><?php echo $value['mail']; ?></td>
            <td>
                <form action="/?userupd" method="POST">
                <input type="hidden" name="id" value="<?php echo $value['iduser']; ?>" >
                <table class="formattable_noborder_nospacing">
                    <tr>
                        <td>
                            <input class="negativ" type="submit" value="Löschen" disabled="disabled">
                        </td>
                        <td>
                            <input class="" type="submit" value="Bearbeiten" disabled="disabled">
                        </td>
                        <td>
                            <input class="" type="submit" value="Passwort zurücksetzen" disabled="disabled">
                        </td>
                    </tr>
                </table>
                </form>
            </td>
        </tr>
        <?php } ?>
        
        <tr>
            <form action="?userinp" method="POST">
            <input type="hidden" name="function" value="createuser">
            <td><input type="text" value="username" name="username" class="doubleframe"><br><input type="password" name="password"  value="P4ssw0rt" class="doubleframe"></td>
            <td style="vertical-align: text-top;"><input type="email" name="mailadd" value="mustermann@ssc-services.de"  class="doubleframe"></td>
            <td style="vertical-align: text-top;"><input type="submit" name="opt" value="anlegen" class="doubleframe"></td>
            </form>
        </tr>
       
    </table>
</div>