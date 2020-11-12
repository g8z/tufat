{* This will display the login screen *}

{ literal}
   <script type="text/javascript">
      function bb()
           {
             window.open('fpass.php' , 'll' , 'width=400,height=250');
          }
   </script>
{ /literal}

{* Modified to incorporate Smarty and Formsess *}
<form name="loginForm" method="post" action="login.php">
        <input name="redirect" type="hidden" value="{$redirect}" />
        <table width="100%" border="0" cellpadding="3" cellspacing="0">
            {if $isTUFaT}
                <tr>
                        <td colspan="2" class="subtitle">
                                        <font color="#CC0000">##This is an ##</font>
                                        <font color="#000099">##demo##</font>
                                        <font color="#CC0000" >##of tufat##</font>
                                        <font color="#000099">##administrator##</font>
                                        <font color="#CC0000">##use_admin_pass##</font>
                        </td>
                </tr>
           {/if}
                <tr>
                       <td colspan="2" class="title">##Welcome to $treeName##</td>
                </tr>
                <tr>
                      <td colspan="2" class="normal">
                                ##with_this_online## ##text_e##
                                <a href='cnewtree.php'>##click here to create one##</a>.
                                ##logsp1## <a href="mailto:{$SupervisEmail}">##Site Supervisor##</a> ##logsp2##
                      </td>
                </tr>
                <tr>
                   <td colspan="2" class="normal">&nbsp;</td>
                </tr>

					{if $error_message}                
					<tr>
						<td colspan='2' class='normal'>
							<table>
								<tr><td><b>{$error_title|default:"##An Error Has Occurred##"}</b> - </td></tr>
								<tr><td class="errorBody">{$error_message}</td></tr>	
							</table>
						</td>
					</tr>
					{/if}
	
              {if $Lang_Loginhere}
                <tr>
                     <td class="subtitle" colspan="2">{$Lang_Loginhere}&nbsp;</td>
                </tr>
                <tr>
                   <td colspan="2">&nbsp;</td>
                </tr>
              {/if}
                <tr>
                        <td width="17%" class="normal" align="right">
                                ##User ID:##
                        </td>
                        <td width="83%">
                                <input name="username" type="text" class="txtinput1" id="username" maxlength="20" />
                                {if $allowCreate}
                                        <a href='cnewtree.php'>##Create a new family tree##</a>
                                {/if}
                        </td>
                </tr>
                <tr>
                        <td class="normal" align="right">
                                ##Password:##
                        </td>
                        <td><input name="password" type="password" class="txtinput1" id="password" value="" maxlength="20" />
                        <a href='#' onclick='launchCentered("fpass.php", "400", "300", "resizable=1, scrollbars=1")'>##Request lost password##</a>
                        </td>
                </tr>
                <tr>
                        <td class="normal">
                                &nbsp;
                        </td>
                        <td class="normal" align="left">
                                <label><input name="keep-alive" value="true" type="checkbox">##Remember me on this computer##</label>
                        </td>
                </tr>
                <tr>
                        <td>&nbsp;</td>
                        <td align="left">
                                <input type="submit" name="login" value="Login" class="normal" />
                        </td>
                </tr>
        </table>
</form>

{literal}
<script type="text/javascript">
        document.loginForm.username.focus();
</script>
{/literal}


{if  $ShowAllTrees }

        <table width='100%' class="normal">
                <tr>
                  <td class="subtitle" align="left"><b>##Family Tree##</b></td>
                  <td class="subtitle" align="left"><b>##adminname##</b></td>
        </tr>
        {foreach from=$ShowUsers key=key item=disprec}

        <tr bgcolor="{$disprec->BGCOLOR}">
                <td>
                        <a href="login.php?login=1&amp;username={$disprec->username}&amp;password={$disprec->read_only_password}">
                        {$disprec->dname}</a>
                </td>
                <td>
                        <a href="mailto:{$disprec->email}">{$disprec->aname}</a>
                </td>
        </tr>

        {/foreach}

        </table><br />

{/if}

<table border="0" cellpadding="2" cellspacing="0" width="100%">
        <tr>
                <td colspan="2"  class="normal">
                        ##this_family##
                        <a href="http://www.microsoft.com/ie/" target="_blank">##latest version of Internet Explorer##</a>.
                </td>
        </tr>
</table>

{* Do NOT uncomment these lines *}
{*  This is text passed by PHP, added here for the language editor

##Login Error##
##Please Read Guidelines##
##You must read and consent to the guidelines before creating a family tree.##
##The user name that you requested is already in use. Please enter a different one.##
##The user name and/or password that you entered could not be found or is invalid. Please try again.##

*}