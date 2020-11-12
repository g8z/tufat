<p class="subtitle">##Additional Information about##&nbsp;{$vars.name}</p>

<table border="0" cellpadding="2" cellspacing="0" width="400">

{foreach from=$fields key=key item=value}
        {if $vars.$key != ''}
                {if $key == "url"}

                        <tr>
                                <td nowrap="nowrap" valign="top">
                                        <span class="normal"><b>{$value}</b></span>
                                </td>
                                <td valign="top">
                                        <span class="normal"><a href='{$vars.$key}' target='_blank'>{$vars.$key}</a>
                                        </span>
                                </td>
                        </tr>

                {else}

                        <tr>
                                <td nowrap="nowrap" valign="top">
                                        <span class="normal"><b>{$value}</b></span>
                                </td>
                                <td valign="top">
                                        <span class="normal">{$vars.$key}</span>
                                </td>
                        </tr>

                {/if}
        {/if}
{/foreach}

{if !$vars.animalPedigree}
        <tr>
                <td class="normal"><b>##Life events##</b>
                </td>
                <td>

                        {if $vars.ID > 0}

                                <a href='#' onclick='launchCentered("tageven.php?ID={$vars.ID}", "600", "450", "scrollbars=1,resizable=1")'>##View life events##</a> |
                                <a href='moreinfo.php?summary=1&amp;ID={$vars.ID}' >##Summary##</a>

                        {/if}

                </td>
        </tr>
{/if}
{foreach from=$spouseList key=key item=spouse}
        {if !$vars.animalPedigree}
                {if $spouse->spouseID != ''}

                        <tr>
                                <td>
                                        <font class="normal"><b>##Spouse##</b></font>
                                </td>
                                <td valign="top">
                                        <a href="load.php?ID={$spouse->spouseID}">{$spouse->spouseName} {$spouse->spouseSurname}</a>
                                </td>
                        </tr>

                {/if}
        { else}
                {if $spouseID != ''}

                         <tr>
                                 <td>
                                         <font class="normal"><b>##Spouse##</b></font>
                                 </td>
                                 <td valign="top">
                                         <a href="load.php?ID={$spouseID}">{$spouse.spouseName} "{$spouse->spouseSurname}"</a>
                                 </td>
                         </tr>

                {/if}
        {/if}
{/foreach}

        <tr class="normal">
                <td>
                        <font class="normal"><b>##Birth Notes and Sources##</b>
                        </font>
                </td>
                <td>
                        <a href='#' onclick='launchCentered("shownotes.php?xtag=BIRT&amp;ID={$vars.ID}", "500", "300", "resizable=1,scrollbars=1")'>##Notes##</a> | <a href="#" onclick='launchCentered("showsour.php?xtag=BIRT&amp;ID={$vars.ID}", "500","400","resizable=1,scrollbars=1")'>##Sources##</a>
                </td>
        </tr>

{if $vars.marriagePlace || $vars.marriageDate}
        {if $vars.marriagePlace}

                <tr>
                        <td>
                                <font class="normal"><b>##Marriage Place##</b>
                                </font>
                        </td>
                        <td><font class="normal">{$vars.marriagePlace}</font>
                </td>
        </tr>

        {/if}
        {if $vars.marriageDate}

                <tr>
                        <td><font class="normal"><b>##Marriage Date##</b></font>
                        </td>
                        <td><font class="normal">{$vars.marriageDate}</font>
                        </td>
                </tr>

        {/if}
{/if}

</table>
<br />
<span class="normal">##No other additional information has been entered for## {$vars.name}</span>
<p><a href="load.php?ID={$vars.ID}">##Re-load##&nbsp;{$vars.name}##'s record##</a>
</p>
<script type="text/javascript">
        var inmenu=0;
</script>
{if $today_events}
     <table class="calendar" border="0" cellpadding="5" cellspacing="0">
       <tr>
         <td class="today" colspan="7">{if $smarty.request.summary}##Summary##{else}##Today##{/if}</td>
       </tr>
             {foreach from=$today_events item=value}
             <tr>
                <td class="normal" align="left">
				 <a href="#" onclick='launchCentered("tageven.php?ID={$vars.ID}", "600", "450", "scrollbars=1,resizable=1")'>
				 {if $value.type}{$value.type}{else}{$value.name}{/if}</a> - {if ($value.date.month + $value.date.month + $value.date.year)>1} {$value.formated_date}{else}##N/A##{/if}
				 {*
                 <a href="#"  onclick='launchCentered("summaryeven.php?ID={$vars.ID}&amp;hid={$value.hid}", "300", "200", "scrollbars=1,resizable=0")'>{if $value.type}{$value.type}{else}{$value.name}{/if}</a> - {if ($value.date.month + $value.date.month + $value.date.year)>1} {$value.date.month}/{$value.date.day}/{$value.date.year}{else}##N/A##{/if}
				 *}
                </td>
             </tr>
             {/foreach}
     </table>
{else}
  {if $summary}##There are no life event information available yet.##{/if}
{/if}

{* DO NOT UNCOMMENT THESE LINES
##Date of Last Change##
##Given Name##
##Surname##
##E-Mail Address##
##World Wide Web Address##
##Phone Number##
##Street Address##
##City##
##State##
##Postal Code##
##Country##
##Name Prefix##
##Surname Prefix##
##Nickname##
##Name Suffix##
##Type of Birth##
##Address of Birth Site##
##Notes About the Birth##
##Cause of Death##
##Address of Death Site##
##Notes About the Death##
##Notes##
##Registered Name##
##Call Name##
##Breed##
##Breeder##
##Birth Date##
##Death Date##
##Cause of Death##
##Gender##
##Registration #1##
##Registration #2##
##Registration #3##
##Location##
*}
