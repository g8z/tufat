{if $mydata.step == "2"}
        <script type="text/javascript">
        function Check1()
        {ldelim}
                d = document.f;
                if (d.sdbprefix.value.length == 0)
                {ldelim}
                        alert('Please specify database prefix');
                        d.sdbprefix.focus();
                        return false;
                {rdelim}
                if (d.sdbname.value.length == 0)
                {ldelim}
                        alert('Please specify database name');
                        d.sdbname.focus();
                        return false;
                {rdelim}
                if (d.sdbuser.value.length == 0)
                {ldelim}
                        alert('Please specify database username');
                        d.sdbuser.focus();
                        return false;
                {rdelim}
                if (d.sdbhost.value.length == 0)
                {ldelim}
                        alert('Please specify database hostname');
                        d.sdbhost.focus();
                        return false;
                {rdelim}

                return true;
        {rdelim}
        </script>
{/if}

{if $mydata.step != ""}

        {if $mydata.step == 3}

                <font class="subtitle"><b>Step 3 - Database configuration</b></font>
                <br /><br />
                <table width='100%' class="normal" cellpadding="5">
                        <tr>
                                <td>
                                        TUFaT is now using the information that you provided in the previous step to access your database and create the necessary tables. If you see any red messages in the "Status" column, please go back to Step 2 and check the values that you entered with your website administrator.
                                </td>
                        </tr>
                        <tr>
                                <td>
                                        Common reasons for error include: non-existent database, incorrect database name or login information, or providing login information which doesn't provide access to the specified database.
                                </td>
                        </tr>
                </table>
                <br />
                <table class="normal" width='100%'>
                        <tr bgcolor='#cccccc'>
                                <td><b>Current task</b></td>
                                <td><b>Status</b></td>
                        </tr>

                        {if $mydata.sdbprefix != ''}

                                <tr>
                                        <td>Database connectivity</td>
                                        <td>
                                                {if $mydata.dbconnect == "FAIL"}

                                                        <font color='{$mydata.rR}'>Could not connect to the database with the username/password - FAIL</font>

                                                { else}

                                                        <font color='{$mydata.rG}'>Database connection - OK</font>

                                                {/if}

                                        </td>
                                </tr>
                                <tr>
                                        <td>Database account permissions</td>
                                        <td>
                                        {if $mydata.db == "FAIL"}
                                                        {if $mydata.dbcreate == "FAIL"}

                                                                <font color='{$mydata.rR}'>Database account permission do not allow database creation with name {$mydata.sdbname} - FAIL</font>
                                                        { else}
                                                                <font color='{$mydata.rR}'>Database account permission use of database {$mydata.sdbname} - FAIL</font>
                                                        {/if}
                                                { else}
                                                        <font color='{$mydata.rG}'>Database account permissions - OK</font>
                                                {/if}

                                        </td>
                                </tr>
                                <tr>
                                        <td>Create tables</td>
                                        <td>
                                                {if $mydata.crtables == "FAIL"}

                                                        <font color='{$mydata.rR}'>Could not create tables with username/password - FAIL</font>

                                                { else}

                                                        <font color='{$mydata.rG}'>Tables created/updated - OK</font>

                                                {/if}

                                        </td>
                                </tr>

                                <tr>
                                        <td>MySQL Version</td>
                                        <td>

                                                {if $mydata.mysql == "FAIL"}

                                                        <font color='{$mydata.rR}'>MySQL Version < 3.23 - FAIL</font>

                                                { else}

                                                        <font color='{$mydata.rG}'>MySQL Version {$mydata.mysqlver} - OK</font>

                                                {/if}

                                        </td>
                                </tr>

                        {/if}

                </table><br />
                {if $mydata.created == "yes"  and  $mydata.dbconnect == "OK"}
                <form action="install.php" onsubmit='return Check1()' method="post">
            	Database with tables already exist. How you want to continue installation?
		<select name="jp16" size="1">
 		<option value="2"> Clean Install </option>
 		<option value="3"> Upgrade </option>
		</select><br/>
		<input type="button" onclick='location.href="install.php?step=2"' value='&lt; Step 2' />
		<input type="submit" onclick='location.href="install.php?step=4"' value='Step 4 &gt;' />
		</form>
                { /if }
                {if $mydata.created <> "yes"  and  $mydata.dbconnect == "OK"}
		<input type="button" onclick='location.href="install.php?step=2"' value='&lt; Step 2' />
		<input type="button" onclick='location.href="install.php?step=4"' value='Step 4 &gt;' />
		{ /if }
               {if  $mydata.dbconnect == "FAIL"}
               <input type="button" onclick='location.href="install.php?step=2"' value='&lt; Step 2' />
               { /if }
        { elseif $mydata.step == 2}

                <font class="subtitle"><b>Step 2 - Database Information</b></font>
                <br /><br />
                <table width='100%' class="normal" cellpadding="0" cellspacing="0">
                        <tr>
                                <td align="left">
                                        TUFaT needs to know some information about how your database is setup. Please input the database name, login username and password, and hostname below. The "hostname" is NOT the domain name of your server. Usually, the hostname is "localhost", but you should contact your website administrator or web host to be sure. The "prefix" is the text that will be appended before the name of each database table.
                                </td>
                        </tr>
                </table>
                <br />
        <form action='install.php' name="f" onsubmit='return Check1()' method="post">
                <table class="normal" cellspacing="0" cellpadding="1" width='300'>
                <tr>
                          <td><font class='normal'>Database Name</font></td>
                          <td><input type="text" name="sdbname" value="{$mydata.mdbname}" class="txtinput1" />
                          </td>
                        </tr>
                        <tr>
                                <td><font class='normal'>Database Username</font></td>
                                <td><input type="text" name="sdbuser" value="{$mydata.mdbuser}" class="txtinput1" />
                                </td>
                        </tr>
                        <tr>
                                <td><font class='normal'>Database Password</font></td>
                                <td><input type="text" name="sdbpass" value="{$mydata.mdbpass}" class="txtinput1" />
                                </td>
                        </tr>
                        <tr>
                                <td><font class='normal'>Database Host</font></td>
                                <td><input type="text" name="sdbhost" value="{$mydata.mdbhost}" class="txtinput1" />
                                </td>
                        </tr>
                        <tr>
                                <td><font class='normal'>Database Table Prefix</font></td><td><input type="text" name="sdbprefix" value="{$mydata.mmprefix}" class="txtinput1" />
                                </td>
                        </tr>
                        <tr><td colspan="2">&nbsp;</td></tr>
                        <tr><td colspan="2">
                                <input type="hidden" name="installik" value="1" />
                                <input type="hidden" name="step" value="3" />
                                <input type="button" onclick='location.href="install.php?step=1"' value='&lt; Step 1' />
                                <input type="submit" value='Step 3 &gt;' />
                                </td>
                        </tr>
                </table>
                </form>

        { elseif $mydata.step == 5}


                <font class="subtitle"><b>Step 5 - Finalizing</b></font>
                <br /><br />
                <table width='100%' class="normal">
                        <tr>
                                <td>
                                        Congratulations! This is the final step of the installation wizard. If there is no error message displayed below, then you are done. If there is an error message in the "Status" column, then please make any necessary corrections. You can update the TUFaT configuration by directly editing the "config.php" file.
                                </td>
                        </tr>
                </table><br />
                <table class="normal" width='100%'>
                        <tr bgcolor='#cccccc'>
                                <td><b>Current task</b></td>
                                <td><b>Status</b></td>
                        </tr>
                        <tr>
                                <td>Update configuration file</td>
                                <td>

                                        {if $mydata.cfnw == 1}

                                                <font color='{$mydata.rR}'>Configuration file not writable - Fail</font>
                                    { else}
                                            <font color='{$mydata.rG}'>Update configuration file - OK</font>
                                        {/if}
                                </td>
                        </tr>
                        <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                        </tr>
                        <tr>
                                <td colspan="2">
                                        <input type="button" onclick='location.href="install.php?step=4"' value='&lt; Step 4' />
                                        <input type="button" onclick='location.href="index.php"' value='Start TUFaT' />
                                </td>
                        </tr>
                </table>

        { elseif $mydata.step == 4}

                <font class="subtitle"><b>Step 4 - Site Configuration</b></font>
                <br /><br />
                <table width='100%' class="normal" cellpadding="0" cellspacing="0">
                        <tr>
                                <td>
                                        Please specify some basic configuration parameters for how TUFaT should operate. These settings can be adjusted in the config.php file after installation, if necessary. The master password will provide access to all family trees, allow you to bypass any lock, and provide access to backup tools.
                                </td>
                        </tr>
                </table><br />
                <form action='install.php'  method="post">
                <table class="normal" cellspacing="0" cellpadding="1">
                        <tr>
                                <td width="170">
                                        <font class='normal'>Master Password</font></td>
                                <td align="left"><input type="text" name="smpass" value="{$mydata.mpass}" class="txtinput1" /></td>
                        </tr>
                        <tr>
                                <td><font class='normal'>Supervisor Name</font></td>
                                <td align="left"><input type="text" name="suname"  value="{$mydata.msuname}" class="txtinput1" /></td>
                        </tr>
                        <tr>
                                <td><font class='normal'>Supervisor E-Mail</font></td>
                                <td align="left"><input type="text" name="suemail" size="25" value="{$mydata.msuemail}" class="txtinput1" /></td>
                        </tr>
                        <tr>
                                <td><font class='normal'>Tree Name</font></td>
                                <td align="left"><input type="text" name="stn" value="{$mydata.mtn}" class="txtinput1" /></td>
                        </tr>
                        <tr>
                                <td><font class='normal'>Allow Cross Tree Searches</font></td>
                                <td align="left"><input type="checkbox" name="sacts" {if $mydata.macts == "1" or true}checked="checked"{/if} /></td>
                        </tr>
                        <tr>
                                <td><font class='normal'>Allow Multiple Tree Creation</font></td>
                                <td align="left"><input type="checkbox" name="sacr" {if $mydata.macr == "1" or true}checked="checked"{/if} /></td>
                        </tr>
                        <tr>
                                <td><font class='normal'>Show All Trees at Login</font></td>
                                <td align="left"><input type="checkbox" name="salltree" {if $mydata.malltree == "1"}checked="checked"{/if} /></td>
                        </tr>
                        <tr>
                                <td><font class='normal'>Use TUFaT to track</font></td>
                                <td align="left"><input type="radio"  {if $mydata.mhgen == "1"}checked="checked"{/if} name="shgen" value = '1' />
                                        <font class='normal'>Human Genealogy</font>
                                        <br />
                                        <input type="radio"  {if $mydata.mhgen=="0"}checked="checked"{/if} name="shgen" value='0' /><font class='normal'>&nbsp;Animal Genealogy</font></td>
                        </tr>
                        <tr>
                                <td><font class="normal">Choose Template</font></td>
                                <td align="left"><select name="stplid">
                                                {html_options options=$mydata.tempList selected=$mydata.mtplid}
                                        </select>
                                </td>
                        </tr>
                        <tr>
                                <td><font class='normal'>E-Mail Method</font><br /><small>If SMTP class is used, SMTP variables must be manually set in config.php after installation.</small></td>
                                <td align="left">
                                        
                                        <input type="radio"  {if $mydata.stmail eq '0' or $mydata.stmail eq ''}checked="checked"{/if} name="stmail" value='0' />
                                	<font class='normal'> PHP mail</font>
                                	
                                	<br />
                                	
					<input type="radio"  {if $mydata.stmail == "1"}checked="checked"{/if} name="stmail" value = '1' />
                                        <font class='normal'>SMTP class</font>
                                    </td>
                        </tr>
                        <tr><td colspan="2">&nbsp;</td>
                        </tr>
                        <tr>
                                <td><input type="hidden" name="installik" value="1" />
                                        <input type="hidden" name="step" value="5" />
                                        <input type="button" onclick='location.href="install.php?step=3"' value='&lt; Step 3' />
                                        <input type="submit" value='Step 5 &gt;' />
                                </td>
                        </tr>
                </table>
                </form>
        {/if}

{/if}