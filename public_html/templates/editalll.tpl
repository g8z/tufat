{if isset( $smarty.session.master )}
        {* only masters allowed this change *}

        <font class='title'>##Manage languages##</font><br />
        <font class="normal"><br />
        <a href='editalll.php'>##Edit language##</a> |
        <a href='editalll.php?add=1'>##Add language##</a> |
        <a href='editalll.php?del=1'>##Delete language##</a> |
        <a href='editalll.php?chlt=1'>##Change encoding##</a> |
        <a href='impexpl.php'>##Import/Export##</a><!-- |
{* Back button removed  *}
<a href='index.php'>##Back##</a> -->
        <br /><br />
      </font>
        {if $mydata.add == 1}
                {if $mydata.slname != '' && $mydata.slenc != ''}
                        {if  $mydata.langsok == 1}

                                {if $mydata.add_error == 1}

                                        ##Language {$mydata.slname} with encoding {$mydata.slenc} already exists##<br />

                                { else}

                                        {if $mydata.reccnt > 0}

                                                ##Building {$mydata.reccnt} records from the default language##<br />

                                        {/if}
                                        {if $mydata.er == false}

                                                ##Language added OK##
                                                <br /><br />
                                               <a href='editlang.php?sla={$mydata.slname}&amp;senc={$mydata.slenc}'>##Click here##</a> ##to edit the phrases of the new language.##

                                        { else}

                                                ##Problem with adding new Language##

                                        {/if}
                                {/if}
                        {/if}
                { else}
                        <form action='editalll.php' method="post" name="editalllo1">
                                <table>
                                        <tr>
                                                <td><font class='normal'>##Language Name##</font></td>
                                                <td><input type="text" name="slname" /></td>
                                        </tr>
                                        <tr>
                                                <td><font class='normal'>##Language Encoding##</font>
                                                </td>
                                                <td>
                                                        <select name="slenc" class="normal">

                                                                {foreach from=$langsList key=key item=lang}

                                                                        <option value="{$lang}">{$key}</option>

                                                                {/foreach}

                                                        </select>
                                                </td>
                                        </tr>
                                        
                                        <tr>
                                                <td colspan="2">
                                                input type="hidden" name="add" value="1" />
                                                <input type="submit" value="##Add##" />
                                                <
                                                </td>
                                        </tr>
                                </table>
                        </form>

                {/if}
        { elseif  $mydata.del == 1}

                {if $mydata.delmsg != ""}

                        {$mydata.delmsg}

                { else}

                        <form action="editalll.php" method="post" name="editallDel">
                                <table>
                                        <tr>
                                                <td><font class='normal'>##Language Name##</font></td><td>
                                                        <select name="sla">

                                                                {foreach from=$slaList key=key item=enc}

                                                                        <option value="{$key}_lll_{$enc}">{$key} ({$enc})
                                                                        </option>

                                                                {/foreach}

                                                        </select>
                                                </td>
                                        </tr>
                                        <input type="hidden" name="del" value="1" />
                                        <tr>
                                                <td colspan="2"><input type="submit" value='##Delete##' />
                                                </td>
                                        </tr>
                                </table>
                        </form>

                {/if}
        { elseif $mydata.chlt == 1}
                {if $mydata.chgmsg != ""}

                        {$mydata.chgmsg}

                { else}

                        <form action="editalll.php" method="post" name="editallDel">
                                <table>
                                        <tr>
                                                <td><font class='normal'>##Language Name##</font></td><td>
                                                        <select name="sla">

                                                                {foreach from=$slaList key=key item=enc}

                                                                        <option value="{$key}_lll_{$enc}">{$key} ({$enc})
                                                                        </option>

                                                                {/foreach}

                                                        </select>
                                                </td>
                                        </tr>
                                        <tr>
                                                <td><font class="normal">##Language Encoding##</font>
                                                </td>
                                                <td>
                                                        <select name="slenc" class="normal">

                                                                {foreach from=$langsList key=key item=lang}

                                                                        <option value="{$key}_lll_{$lang}">{$key}</option>


                                                                {/foreach}

                                                        </select>
                                                </td>
                                        </tr>
                                        <input type="hidden" name="chlt" value="1" />
                                        <tr>
                                                <td colspan="2"><input type="submit" value='##Update##' />
                                                </td>
                                        </tr>
                                </table>
                        </form>

                {/if}
        { else}

                <form action="editlang.php" method="post" name="editalllang">
                        <table>
                                <tr>
                                        <td><font class='normal'>##Language Name##</font></td>
                                        <td>
                                                <select name="sla">

                                                        {foreach from=$slaList key=key item=enc}

                                                                <option value="{$key}_lll_{$enc}">{$key} ({$enc})
                                                                </option>

                                                        {/foreach}

                                                </select>
                                        </td>
                                </tr>
                                <tr>
                                        <td colspan="2"><input type="submit" value='##Edit Language##' />

                                        </td>
                                </tr>
                        </table>
                </form>

        {/if}
{ else}

        <br /><br />
        <font class="normal">
               ##You should re-login using the master password to perform this action.##
        </font>

{/if}

<script type="text/javascript">
        var inmenu='4';
</script>