<script type="text/javascript">
function checkFile1(){ldelim}
        if (document.gedimp_01.gedcom.value == '')
        {ldelim}
                alert('File Name should be entered');
                return false;
        {rdelim}
        return true;
{rdelim}
function checkFile2(){ldelim}
        if (document.gedimp_02.gedcompart.value == '')
        {ldelim}
                alert('File Name should be entered');
                return false;
        {rdelim}
        return true;
{rdelim}
function checkFile3(){ldelim}
        if (document.gedimp_03.gedcomftp.value == '')
        {ldelim}
                alert('File Name should be entered');
                return false;
        {rdelim}
        return true;
{rdelim}
function checkFile4(){ldelim}
        if (document.gedimp_04.gedcompart.value == '')
        {ldelim}
                alert('File Name should be entered');
                return false;
        {rdelim}
        return true;
{rdelim}
</script>

{if $mydata.ne == 1}

        <span class="normal">##The location specified does not exist or is inaccessible##</span>
        <br /><br />
        {* Back button removed
        <a href='import.php'><font class='normal'>Back</font></a>
*}
{ else}
        <table border="0" cellpadding="1" cellspacing="0" width="100%">
                <tr>
                        <td class="subtitle">##Import a GEDCOM file:##</td>
                </tr>
                <tr>
                   <td class="normal">
                                <form action="import_ged.php" method="post" enctype="multipart/form-data" name="gedimp_01" onsubmit="return checkFile1();">
                                        <input type="hidden" name="ID" value="{$mydata.ID}" />
                                        <input type="hidden" name="tu" value="1" />
                                        <input type="hidden" name="user" value="{$mydata.user}" />
                                   <table border="0" cellpadding="5" cellspacing="0" width="100%">
                                     <tr><td align="left" class="normal"><input name="gedcom" type="file" size="40" class="fileinput3" /></td></tr>
                                     <tr><td class="normal" align="left"><input class="normal" type="checkbox" name="append" />
                                           ##Append to existing tree##
                                     </td></tr>
                                     <tr><td class="normal" align="left">
                                          <input name="submit" type="submit" class="normal" value="Submit" />
                                          <input name="cancel" type="button" class="normal" onclick="javascript:window.location='load.php?ID={$mydata.ID}';" value="##Cancel##" />
                                     </td></tr>
                                    </table>
                                </form>
                    </td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr>
                   <td class="normal">
                                ##Importing a GEDCOM file to your tree will##
                                <span class="errorBody"> ##COMPLETELY##</span>
                                ##and##
                                <span class="errorBody">##PERMANENTLY##</span>
                                ##erase_all_data##
                  </td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>
            <!-- <tr><td colspan="2"><hr /></td></tr> -->
                <tr><td class="subtitle">##Import a big GEDCOM file:##</td></tr>
                <tr><td class="normal">
                                <form action="import_ged.php" method="post" name="gedimp_02" enctype="multipart/form-data" onsubmit="return checkFile2();">
                                        <input type="hidden" name="ID" value="{$mydata.ID}" />
                                        <input type="hidden" name="tu" value="2" />
                                        <input type="hidden" name="fr" value="{$mydata.fr}" />
                                        <input type="hidden" name="user" value="{$mydata.user}" />
                                   <table border="0" cellpadding="5" cellspacing="0" width="100%">
                                      <tr><td class="normal"><input class="fileinput3" name="gedcompart" size="40" type="file" /></td></tr>
                                      <tr>
                                         <td class="normal">
                                            <input name="submit" type="submit" class="normal" value="##Submit and Upload more##" />
                                            <input name="cancel" type="button" class="normal" onclick="location.href='index.php'" value="##All done##" />
                                            <input name="cancel" type="button" class="normal" onclick="javascript:window.location='load.php?ID={$mydata.ID}';" value="##Cancel##" />
                                         <td>
                                      </tr>
                                   </table>
                                </form>
                    </td>
                </tr>
                 <tr><td colspan="2">&nbsp;</td></tr>
                <tr>
                   <td class="normal">
                                ##big_upload_info1##<br /><br />
                                ##tags1##<br />##tags2##<br /><br />
                                ##big_upload_info2##
                                <span class="errorBody">##COMPLETELY##</span>
                                ##and## <span class="errorBody">##PERMANENTLY##</span>
                                ##big_upload_info3##
                   </td>
                 </tr>
                 <tr><td colspan="2">&nbsp;</td></tr>
             <!-- <tr><td colspan="2"><hr /></td></tr> -->
                 <tr><td class="subtitle">##Upload from FTP, HTTP or server path location:##</td></tr>
                 <tr>
                     <td class="normal">
                                <form action='import_ged.php' method="post" name="gedimp_03" onsubmit="return checkFile3();">
                                        <input type="hidden" name="ID" value="{$mydata.ID}" />
                                        <input type="hidden" name="tu" value="3" />
                                        <input type="hidden" name="user" value="{$mydata.user}" />
                                   <table border="0" cellpadding="5" cellspacing="0" width="100%">
                                     <tr>
                                        <td align="left" class="normal"><input name="gedcomftp" type="text" class="txtinput3" />
                                        </td>
                                     </tr>
                                     <tr>
                                        <td align="left" class="normal"><input class="normal" type="checkbox" name="append" />
                                        &nbsp;##Append to existing tree##
                                        </td>
                                     </tr>
                                     <tr>
                                        <td align="left" class="normal">
                                          <input name="submit" type="submit" class="normal" value="##Process##" />
                                          <input name="cancel" type="button" class="normal" id="cancel" onclick="javascript:window.location='load.php?ID={$mydata.ID}';" value="##Cancel##" />
                                        </td>
                                     </tr>
                                   </table>
                                </form>
                         </td>
                     </tr>
                     <tr>
                        <td class="normal">##ftp_upload_info##</td>
                     </tr>
                     <tr>
                        <td class="normal">
                                ##Append to existing tree##
                                <span class="errorBody"> ##COMPLETELY##</span>
                                ##and##
                                <span class="errorBody">##PERMANENTLY##</span>
                                ##erase_all_data##
                        </td>
                     </tr>
                     <tr>
                        <td class="normal">##unrecognized_gedcom_tags##</td>
                     </tr>
                     <tr><td colspan="2">&nbsp;</td></tr> 
                  <!--   <tr><td><hr /></td></tr> -->
                     <tr><td class="subtitle">##Import a tar.gz GEDCOM file:##</td></tr>
                     <tr>
                        <td class="normal">
                                <form action="import_ged.php" method="post" enctype="multipart/form-data" name="gedimp_04" onsubmit="return checkFile4();">
                                        <input type="hidden" name="ID" value="{$mydata.ID}" />
                                        <input type="hidden" name="tu" value="2" />
                                        <input type="hidden" name="compressed" value="1" />
                                        <input type="hidden" name="fr" value="{$mydata.fr}" />
                                        <input type="hidden" name="user" value="{$mydata.user}" />
                                   <table border="0" cellpadding="5" cellspacing="0" width="100%">
                                     <tr>
                                       <td align="left"><input name="gedcompart" type="file" size="40"  class="fileinput3" /></td>
                                     </tr>
                                     <tr>
                                       <td class="normal" align="left">
                                           <input class="normal" type="checkbox" name="append" />
                                           ##Append to existing tree##
                                       </td>
                                     </tr>
                                     <tr>
                                       <td class="normal" align="left">
                                        <input name="submit" type="submit" class="normal" value="##Submit##" />
                                        <input name="cancel" type="button" class="normal" onclick="javascript:window.location='load.php?ID={$mydata.ID}';" value="##Cancel##" />
                                       </td>
                                     </tr>
                                   </table>
                                </form>
                         </td>
                     </tr>
                     <tr>
                        <td class="normal" align="left">
                                ##cmp_upload_info1##<br /><br />
                                ##cmp_upload_info2##
                                <span class="errorBody"> ##COMPLETELY##</span>
                                ##and##
                                <span class="errorBody">##PERMANENTLY##</span>
                                ##cmp_upload_info3##
                        </td>
                     </tr>
        </table>

{/if}
<script type="text/javascript">
        var inmenu=3;
</script>