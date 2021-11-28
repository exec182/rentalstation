<div class="login flex-item">
    <h1>Verwaltung</h1>
    <p>Nur für das Verleihpersonal</p>
    <form action="/?login" method="post">
        <input type="hidden" name="function" value="login" />
        <table class="onbottom formattable_noborder_nospacing">
            <tr>
                <th>Login</th>
                <td><input type="text" name="username" required /></td>
            </tr>
            <tr>
                <th>Passwort</th>
                <td><input type="password" name="password" required /></td>
            </tr>
            <tr>
                <th>
                    <?php if ($allow_forgetpassword) echo "<a href=\"?forgetpassword\">Passwort vergessen</a>"; ?>
                </th>
                <td><input type="submit" value="LOGIN" /></td>
            </tr>
        </table>
    </form>
</div>