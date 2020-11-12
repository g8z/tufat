{include file=popup_header.tpl}

     <table width="100%" border="0" cellspacing="0" cellpadding="2">
       <tr>
          <td class="subtitle" align="left">{if $event.type}{$event.type}{else}{$event.name}{/if}</td>
       </tr>
       <tr><td>&nbsp;</td></tr>
       <tr>
          <td class="normal">
             <b>##Place:##</b> {if $event.plac}{$event.plac}<br /><br />{/if}
             <b>##Description:##</b> {$event.desc}<br /><br />
             <b>##Date:##</b> {$event.date.month}/{$event.date.day}/{$event.date.year}
          </td>
       </tr>
     </table>

{include file=popup_footer.tpl}