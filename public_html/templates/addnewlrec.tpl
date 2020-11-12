{if $allowed}
        <font class='normal'>
        <a href='addnewlrec.php?senc={$mydata.senc}&amp;sla={$mydata.sla}'>
        ##Add new record##</a> |
        <a href='editlang.php?senc={$mydata.senc}&amp;sla={$mydata.sla}'>
        ##All records##</a> |
        <a href='editlang.php?senc={$mydata.senc}&amp;sla={$mydata.sla}&amp;s=1'>##Search record##</a>
        <br /><br />
        </font>
        <font class='title'>##Add new language record test##</font>
        <br /><br />
        <font class='normal'>

        {if $mytrans.afterpost}

                <br />{$mytrans.addmsg}<br /><br />
                <a href="addnewlrec.php?sla={$mydata.sla}&amp;senc={$mydata.senc}">##Add New##</a><br />

        { else}
        </font>
        <form action="addnewlrec.php" name="addnewlrec_form" method="post">
                <table class="normal">
                        <tr>
                                <td valign="top" align="right">##Key##</td>
                                <td>
                                        <textarea name="sneww" cols="70" rows="3" /></textarea>
                                </td>
                        </tr>
                        <tr>
                                <td valign="top" align="right">##Value##</td>
                                <td align="left">
                                        <textarea name="snewm" cols="70" rows="7" /></textarea>
                                </td>
                        </tr><!--<br />-->
                <tr>
                <td colspan="2" align="center">
                <input type="hidden" name="sla"  value="{$mydata.sla}" />
                <input type="hidden" name="senc" value="{$mydata.senc}" />
                <input type="hidden" name="addik"  value="1" />
                <input type="submit" value="##Add##" onclick="javascript: if ( !sneww.value.trim() ||  !snewm.value.trim() ){ldelim} alert ('##Please check your data##');return false{rdelim};
                "/>
                </td>
                </tr>
                </table>
{*        Back button removed
<input type="button" onclick='location.href="editlang.php?sla={$mydata[sla]}&amp;senc={$mydata[senc]}"' value='##Back##' />
*}
        </form >

        {/if}
{ else}

        <font class="normal"><br /><br />
        ##You should re-login using your master password to perform this action.##
        </font>
        <br /><br />

{/if}


{* Do NOT Uncomment This Section *}
{* This text is passed by php -
##Added OK##
##Add Problem##
##This text is already defined##
*}