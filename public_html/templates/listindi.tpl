{include file=popup_header.tpl}

     <table width="100%" border="0" cellspacing="0" cellpadding="2">
     {if $mydata.sn != ''}
             <tr>
                     <td><b>{$mydata.sn}</b></td>
             </tr>
     {/if}
     {foreach from=$lists key=key item=member}
             <tr>
                     <td><a href='listindi.php' onclick='parent.location.href="load.php?ID={$member}"'>{$key}</a></td>
             </tr>
     {/foreach}
     </table>
     
{* ##(no name)## *}

{include file=popup_footer.tpl}
