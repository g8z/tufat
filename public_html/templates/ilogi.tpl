{* This is the template file for iloti.php *}

<script type="text/javascript">
function verform1()
{ ldelim}
    if (document.f1.spass.value.length < 1)
    { ldelim}
        alert("##Please enter a password for this new login##");
        document.f1.spass.focus();
        return false;
    { rdelim}
    return true;
{ rdelim}
</script>

<table  cellspacing="0" cellpadding="5" width='100%'>
{if isset( $smarty.session.admin ) || isset( $smarty.session.master )}
{* only admin and master allowed to take necessary actions *}

        {if $mydata.add == 1}
             <tr>
               <td class="title"><b>##Add new logins##</b></td>
             </tr>
                {if $mydata.adik == 1}
                   <tr>
                     <td class="normal">{$mydata.messg}
{*                  <br />          <a href='ilogi.php'>##Back##</a>
*}
                    </td>
                  </tr>
                { else}
                   <tr>
                      <td class='normal'>##edit_ilog_id##</td>
                    </tr>
                    <tr>
                      <td class="normal">
                        <form action="ilogi.php" method="post" name="f1" onsubmit="return verform1()">
                           <table class="normal" cellpadding="3" cellspacing="0" border="0">
                                <tr>
                                   <td>##Login username##</td>
                                    <td>
                                       {if $smarty.session.master}
                                               <select name="suser" class="normal">
                                                       {html_options options="$suser_list"}
                                               </select>
                                       { else}
                                               {$mydata.suser }
                                               <input type="hidden" name="suser" />
                                       {/if}
                                     </td>
                                 </tr>
                                 <tr>
                                      <td>##Login password##</td>
                                      <td><input type="text" name="spass" class="normal" /></td>
                                 </tr>
                                 <tr>
                                         <td>##Individual ID##</td>
                                         <td> <select name="sID">
                                                         {html_options options="$sname_list"}
                                                 </select>
                                         </td>
                                 </tr>
                                 <tr>
                                         <td>##Login Type##</td>
                                         <td> <select name="stp" class="normal">
                                             <option value="2" >##Read Only##</option>
                                             <option value="0" >##General Edit##</option>
                                             <option value="1" >##Edit individual ID##</option>
                                             <option value="3" >##Administrative##</option>
                                                 </select >
                                         </td>
                                 </tr>
                                 <tr>
                                         <td colspan="2"><input type="submit" value="##Add##" />
                                         </td>
                                 </tr>
                        </table>
                               <input type="hidden" name="add" value="1" />
                              <input type="hidden" name="adik" value="1" />
                   </form>
                  </td>
                </tr>
                {/if}
        {/if}
{ else}
    <tr>
            <td class="normal">##You should re-login as an administrator to perform this action.##</b>.
            </td>
    </tr>
{/if}
{if $mydata.del_list}  
    <tr>
<td class="normal">
<table class="normal" cellpadding="3" cellspacing="0" border="0" width="50%">
<tr><td colspan="3" align="left"><h3>##Existing logins##</h3></td></tr>
<tr>
<td width="2%" align="right"></td>
<td width="40%" align="center"><b>##Password##</b></td>
<td width="58%" align="left"><b>##Login type##</b></td>
</tr>
{foreach from=$mydata.del_list item=k}
<tr>
<td align="right"><a href="ilogi.php?off=on&p={$k.pass}&t={$k.tp}">x</a></td>
<td align="center">{$k.pass}</td>
<td align="left">{$k.tp1}</td>
</tr>
{/foreach}
</table>
</td>
{/if}            
</tr>
</table>
<script type="text/javascript">
        var inmenu=4;
</script>

{* Do NOT uncomment these lines *}
{*  This is text passed by PHP, included here for the language editor

##The username $suser with password $spass already exists##
##The login has been added successfully.##
##Login add problem##
##Please select Individual ID from the drop down box##
##Individual with ID $sID not found##

*}