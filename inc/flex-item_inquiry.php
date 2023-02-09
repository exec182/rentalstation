<div class="inquiry flex-item">
        <h1>Anfrage stellen</h1>
        <p>Hier hast du die Möglichkeit ein Leihgerät für dich anzufragen. Trage hierfür deine E-Mail-Adresse und den gewünschten Gerätetyp ein. Im Drop-down-Menü kannst du sehen, ob dein Wunschgerät für dich verfügbar ist.</p>
<p>Nach Klicken auf "Anfragen" bekommst du sofort die Rückmeldung, ob ein Gerät für dich reserviert wurde. Zeitnah erhältst du auch einen Termin für die Geräteausgabe.</p>

<!--Hier hast du die Möglichkeit ein Gerät für dich anzufragen. Gebe uns einfach deine Mail-Adresse und den Gewünschten Gerätetyp. Du bekommst sofort die Rückmeldung, ob noch ein Gerät für dich verfügbar ist und ein Termin wird dir von einem/r Mitarbeiter*in zugesendet.</p -->

        <?php if (isset($inc_inquiry_message) && $inc_inquiry_message != "") { ?>
        <div class="message positiv">
            <p><?php echo $inc_inquiry_message; ?></p>
        </div>
        <?php } ?>
		<?php if (!$inquirylock) { ?>
        <form method="post">
            <input type="hidden" name="function" value="inquiry" />
            <table class="onbottom formattable_noborder_nospacing">
                <tr>
                    <th>E-Mail</th>
                    <td><input type="email" name="email" value="<?php echo $tenant_login_default; ?>" required  /></td>
                </tr>
                <tr>
                    <th>Gerätetyp</th>
                    <td>
                        <select name="devicetype">
                            <option selectes="selected">-- Bitte auswählen --</option>
                            <?php 
                            foreach (get_assettypes_short() as $key => $value) {
                                // $arr[3] wird mit jedem Wert von $arr aktualisiert...
                                echo "<option value=\"".$key."\">".$value."</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th></th>
                    <td><input type="submit" value="Anfragen" /></td>
                </tr>
            </table>
        </form>
        <?php } else { ?> 
            <input type="hidden" name="function" value="inquiry" />
            <table class="onbottom formattable_noborder_nospacing">
                <tr>
                    <th>E-Mail</th>
                    <td><input type="email" name="email" value="<?php echo $tenant_login_default; ?>" required disabled="disabled" /></td>
                </tr>
                <tr>
                    <th>Gerätetyp</th>
                    <td>
                        <select name="devicetype" disabled="disabled">
                            <option selectes="selected">-- Bitte auswählen --</option>
                            <?php 
                            foreach (get_assettypes_short() as $key => $value) {
                                // $arr[3] wird mit jedem Wert von $arr aktualisiert...
                                echo "<option value=\"".$key."\">".$value."</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th></th>
                    <td><input type="reset" disabled="disabled" value="<?php echo $inquirylock_buttontext; ?>"></td>
                </tr>
            </table>
        <?php } ?>
    </div>
