<table  cellspacing="0" cellpadding="5" width='100%' style="height: 100%;">
{if $smarty.session.master}
     <tr>
         <td><font class='title'>##Import/Export languages##</font></td>
     </tr>
     <tr>
         <td class="normal"><a href='editalll.php'>##Edit language##</a> |
        <a href='editalll.php?add=1'>##Add language##</a> |
        <a href='editalll.php?del=1'>##Delete language##</a> |
        <a href='editalll.php?chlt=1'>##Change encoding##</a> |
        <a href='impexpl.php'>##Import/Export##</a></td>
     </tr>

{* Back button removed
     <tr>
         <td class="normal">
        {if $mydata.imp != 1 && $mydata.exp != 1}
                <a href='editalll.php'>##Back##</a>
        { else}

                <a href='impexpl.php'>##Back##</a>
        {/if}
        </td>
     </tr>
*}
        {if $mydata.imp != 1 && $mydata.exp != 1}
             <tr>
               <td class="normal">##Import a Language File##</td>
             </tr>
             <tr>
               <td class="normal">
                   <form action='impexpl.php' method="post" enctype='multipart/form-data'>
                        <table class="normal" cellpadding="3" cellspacing="0" border="0">
                                <tr>
                                        <td>##File Name##</td>
                                        <td><input class="normal" type="file" name="sfile" />
                                        </td>
                                </tr>
                                <tr>
                                        <td colspan="2"><input type="submit" value='##Import##' />
                                        </td>
                                </tr>
                        </table>
                                <input type="hidden" name="imp" value="1" />
                  </form>
               </td>
             </tr>
             <tr>
               <td>&nbsp;</td>
             </tr>
             <tr>
               <td class="normal">##Export a Language File##</td>
             </tr>
             <tr>
               <td class="normal">
                   <form action='impexpl.php' method="post">
                        <table cellpadding="3" cellspacing="0" border="0">
                                <tr>
                                        <td><font class='normal'>##Language Name##</font>
                                        </td>
                                        <td>
                                                <select name="sla">
                                                {foreach from=$langList key=key item=disp}
                                                        <option value='{$disp.l}_111_{$disp.enc}'>{$disp.l} ({$disp.enc})
                                                        </option>
                                                {/foreach}
                                                </select>
                                        </td>
                                </tr>
                                <tr>
                                        <td colspan="2">
                                                <input type="submit" value='##Export Language##' />

                                        </td>
                                </tr>
                        </table>
                     <input type="hidden" name="exporting" value='1' />
                     <input type="hidden" name="exp" value="1" />
                </form>
              </td>
            </tr>
        {/if}
        {if $imperr}
           <tr>
            <td class="normal">{$imperr}</td>
          </tr>
        {/if}
{ else}
    <tr>
            <td class="normal">##You should re-login using the master password to perform this action.##</td>
    </tr>
{/if}
</table>
<script type="text/javascript">
        var inmenu=4;
</script>

{* Do NOT uncomment these lines *}
{*  This is text passed by PHP, included here for the language editor

##The language $sla with encoding $senc already exists. Please delete it before importing.##
##$j phrases successfully added.##
##The language save file appears to be invalid.##

*}