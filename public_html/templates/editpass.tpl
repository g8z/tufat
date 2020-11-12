{if $smarty.session.admin}

	{if $mydata.updmsg}
        <p>{$mydata.updmsg}</p>
	{/if}
	
        <form method="post" name="newLoginForm" action="editpass.php">
                <table width="100%" border="0" cellpadding="2" cellspacing="0">
                        <tr>
                                <td colspan="2" class="title">##Edit User Information##</td>
                        </tr>
                        <tr>
                        <td width="30%" class="normal" align="right">
                          ##User ID:##
                        </td>
                    <td width="70%">
                    <input type="text" class="normal" value='{$a.username}' name="suser" />
                    <input type="hidden" name="solduser" value='{$a.username}' />
                    </td>
                        </tr>
                        <tr>
                    <td class="normal" align="right">
                    ##General Use Password:##
                    </td>
                    <td><input name="pass" type="text" class="normal"  value="{$a.password}" size="20" maxlength="20" />
                                <span class="normalSmall">##(20 char. max)##</span>
                                </td>
                        </tr>
                        <tr>
                    <td height="25" class="normal" align="right">
                     ##Read-only Password:##
                    </td>
                    <td><input name="rpass" type="text" class="normal" value="{$a.read_only_password}" size="20" maxlength="20" />
                    <span class="normalSmall">##(20 char. max)##
                    </span>
                    </td>
                  </tr>
                  <tr>
                          <td height="25" class="normal" align="right">
                          ##Administrative Password:##
                          </td>
                    <td><input name="apass" type="text" class="normal"  value="{$a.admin_password}" size="20" maxlength="20" />
                      <span class="normalSmall">##(20 char. max)##</span>
                      </td>
                        </tr>
                        <tr>
                                <td height="25" class="normal" align="right">
                                ##Administrator's Name:##</td>
                    <td><input name="aname" type="text" class="normal"  value="{$a.aname}" size="30" />
              </td>
                        </tr>
                   <tr>
                           <td height="25" class="normal" align="right">
                            ##Descriptive Tree Name:##
                           </td>
                    <td><input name="dname" type="text" class="normal" value="{$a.dname}" size="30" />
                    </td>
                        </tr>
                  <tr>
                    <td height="25" class="normal" align="right">
                     ##Admin E-mail Address:##
                    </td>
                    <td><input name="email" type="text" class="normal" id="email" value="{$a.email}" size="30" maxlength="100" />
                    </td>
                  </tr>
                        {$crossTreeSearch}

                        <tr>
                    <td><input type="hidden" name="updatik" value="1" /></td>
                    <td><input type="button" onclick="javascript:Validate_passwords(document.newLoginForm);" name="newTreeSubmitButton" value='##Update##' class="normal" /></td>
                  </tr>
                </table>
        </form>
<!-- onclick="javascript:validate_passwords(document.newLoginForm);" -->
{ else}

   ##You should login as an Administrator##

{/if}
<script type="text/javascript">
        var inmenu=4;
</script>

{* Do NOT uncomment these lines *}
{* This is text passed by PHP, added here for the lang editor

##The Username $suser is already being used. Please choose another username and try again.##
##The username $solduser has been renamed to $suser##
##There has been some problem with renaming username $solduser to $suser##
##Information successfully updated##
##E-Mail not valid##

*}