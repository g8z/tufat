{if $msg}{$msg}{/if}
<form method="post" name="newLoginForm" action="cnewtree.php">
        <table width="100%" border="0" cellpadding="2" cellspacing="0">
                <tr>
                        <td colspan="2" class="subtitle">##Complete This Information to Create a New Tree##
                        </td>
                </tr>
                <tr>
                  <td class="normal" colspan="2">##this_will_create##</td>
                </tr>
                <tr><td>&nbsp;</td></tr>
                <tr>
                        <td width="35%" class="normal" align="right">
                                ##Choose a User ID:##
                        </td>
                        <td width="65%"> 
                                <input name="newUsername" type="text" class="txtinput1" maxlength="20" value="{$mydata.newUsername}" />
                                <span class="normalSmall">##(20 char. max)##</span>
                        </td>
                </tr>
                <tr>
                        <td class="normal">
                                <div align="right">##General Use Password:##</div>
                        </td>
                        <td>
                                <input name="newPassword" type="password" class="txtinput1" maxlength="20" value="{$mydata.newPassword}" />
                                <span class="normalSmall">##(20 char. max)##</span>
                        </td>
                </tr>
                <tr>
                        <td height="25" class="normal">
                                <div align="right">##Read-only Password:##</div>
                        </td>
                        <td>
                                <input name="readOnlyPassword" type="password" class="txtinput1" maxlength="20"  value="{$mydata.readOnlyPassword}" />
                                <span class="normalSmall">##(20 char. max)##</span>
                        </td>
                </tr>
                <tr>
                        <td height="25" class="normal">
                                <div align="right">##Administrative Password:##</div>
                        </td>
                        <td>
                                <input name="adminPassword" type="password" class="txtinput1" maxlength="20" value="{$mydata.adminPassword}" />
                                <span class="normalSmall">##(20 char. max)##</span>
                        </td>
                </tr>
                <tr>
                        <td height="25" class="normal">
                                <div align="right">##Administrator's Name:##</div>
                        </td>
                        <td>
                                <input name="aname" type="text" class="txtinput2" value="{$mydata.aname}" />
                        </td>
                </tr>
                <tr>
                        <td height="25" class="normal">
                                <div align="right">##Descriptive Tree Name:##</div></td>
                                <td><input name="dname" type="text" class="txtinput2" value="{$mydata.dname}"  />
                        </td>
                </tr>
                <tr>
                        <td height="25" class="normal">
                                <div align="right">##Admin E-mail Address:##</div>
                        </td>
                        <td><input name="email" type="text" class="txtinput2" maxlength="100" value="{$mydata.email}" />
                        </td>
                </tr>

{* crossTreeSearch display  *}

                {if $allowCrossTreeSearch}

                        <tr >
                                <td height="25" class="normal" valign="top">
                                        <div align="right">##Allow Cross-tree Searches?##</div>
                                </td>
                                <td>
                                        <table border="0" cellpadding="1" cellspacing="0">
                                                <tr>
                                                        <td valign="top">
                                                                <input type="checkbox" name="crosstree" value="1" checked="checked" />
                                                        </td>
                                                        <td>
                                                                <span class="normalSmall">##checking_this_box##</span>
                                                        </td>
                                                </tr>
                                        </table>
                                </td>
                        </tr>

                {/if}

                <tr>
                        <td class="normal">
                                <div align="right">##I have read and agree with the guidelines:##</div>
                        </td>
                        <td>
                                <input name="guidelines" type="checkbox" id="guidelines" value="1" {if $guidelinesChecked}checked="checked"{/if} /><a href="#" onclick="launchCentered('guidelines.php', '600', '330', 'resizable=1, scrollbars=1')">##Please read these guidelines first##</a>
                        </td>
                </tr>
                <tr>
                        <td><input type="hidden" name="newTree" value="1" />
                        </td>
                        <td><input type="button" onclick="javascript:validateLogin(document.newLoginForm)" name="newTreeSubmitButton" value="##Create New Family Tree##" class="normal" />
                        </td>
                </tr>
        </table>
</form >
<table border="0" cellpadding="2" cellspacing="0" width="100%">
        <tr>
           <td colspan="2" class="normal">&nbsp;</td>
        </tr>
        <tr>
                <td colspan="2">
                        <span class="normal">##this_family##
                        <a href="http://www.microsoft.com/ie/" target="_blank">
                        ##latest version of Internet Explorer##</a>.
                        </span>
                </td>
        </tr>
</table>
