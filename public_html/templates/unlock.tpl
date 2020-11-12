<form action="edit.php" method="post" name="unlockForm">
        <table width="100%" border="0" cellspacing="0" cellpadding="1">
                <tr>
                        <td colspan="2" class="title">
                                ##Unlock Record##
                        </td>
                </tr>
                <tr>
                        <td colspan="2" class="normal">&nbsp;</td>
                </tr>
                <tr>
                        <td colspan="2" class="normal">##the_record_that##
                        </td>
                </tr>
                <tr>
                        <td width="219">&nbsp;</td>
                        <td width="377">&nbsp;</td>
                </tr>
                <tr>
                        <td class="normal">##Lock password or admin password:##
                        </td>
                        <td><input name="lock_password" type="text" class="normal" id="lock_password" size="15" maxlength="20" />
            	<input name="unlock" type="submit" class="normal" id="unlock" value="##Continue##" />
           	</td>
                </tr>
                <tr>
                        <td colspan="2">&nbsp;</td>
                </tr>
        </table>
        <input type="hidden" name="ID" value="{$mydata.ID}" />
        <input type="hidden" name="personType" value="{$mydata.personType}" />
        <input type="hidden" name="personID" value="{$mydata.personID}" />
        <input type="hidden" name="chparent" value="{$mydata.chparent}" />
        <input type="hidden" name="isex" value="{$mydata.isex}" />
</form>
<script type="text/javascript">
        document.unlockForm.lock_password.focus();
</script>