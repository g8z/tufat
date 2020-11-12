<table>
<tr>
<td><font class="title" color='#0000AA'><b>{$mydata.name}</b></font></td>
<td width="240" align="right"><font class="title" color='#000000'><b>##Family Chart##</b></font></td>
</tr>
</table>
<br />
<form action='flow.php' method="post" name="flow01">
        <font class="normal">##Generations##
        <select name="gen">{html_options options=$mydata.genList selected=$mydata.gen}</select>
        <select name="type">{html_options options=$mydata.typeArray selected=$mydata.type}</select>
        <input type="submit" value="##Go##" />&nbsp;
{* Back button removed
        <input type="button" onclick="location.href='load.php?ID={$mydata.ID}'" value='##Back##' />
*}
        </font>
        <input type="hidden" name="ID" value='{$mydata.ID}' />
</form>
<br />
<font class="normal">##scroll_flow##</font><br />

{* set the table size based on generations to be shown *}

{if $mydata.gen > 5}

        <table  cellspacing="0" cellpadding="0" width='100%' style="height: 100%;" border="0">

{ else}

        <table  cellspacing="0" cellpadding="0" width='100%' style="height: 100%;" border="0">

{/if}

{* Now print each cell for each person in the grid *}

{foreach from=$printList item=printList1}

        <tr>

        {foreach from=$printList1 item=cell}
                <td align="left" {if $cell.model == 'G' && $mydata.type != 3}width="32" valign="bottom" { elseif $cell.model == 'DG' && $mydata.type != 3}width="32" valign="top"{ elseif $cell.model == '+' && $mydata.type != 3}width="32" style="background-image: url('images/{$smarty.session.templateID}/vline.gif');background-repeat : repeat-y;"{ elseif $cell.model == '|' && $mydata.type != 3}width="32" style="background-image: url('images/{$smarty.session.templateID}/vline.gif');background-repeat : repeat-y;"{/if}>
                {if $cell.model != ""}
                        {if $cell.model == 'O'}
                            {* Print the cell information  *}

                            {if $mydata.type == 3}
			                            <table bgcolor="#{$cell.cell.bgc}" width='100%' style="height: 100%;" border="0" cellspacing="0" cellpadding="0">
         		                            <tr>
                                               <td>
                                                   <table bgcolor="#{$cell.cell.bgc}" width='100%' style="height: 100%;">

                            { else}

                                     <table border="1" bgcolor="#{$cell.cell.bgc}" width='100%' style="height: 100%;">
                                           <tr>
                                               <td>
                                                   <table bgcolor="#{$cell.cell.bgc}" width='100%' style="height: 100%;">

                            {/if}
                            {if $cell.cell.unknown}

			                                               <tr>
         		                                               <td width='100%' align="center">
               		      	                                   <font class='normal'>##Unknown##</font>
                     		                                   </td>
                              			                  </tr>

                            { else}
                            		{if $cell.cell.type == 2 or $cell.cell.type == 1}

                                                        <tr>
                                                             <td width='100%' height='100%' align="left">
                                                                {if $cell.cell.spic && $cell.cell.type == 1}

                                                                        <img alt="" border="0" width="40" src="{$cell.cell.portraitfile}" />

                                                                {/if}
                                                                <font class="normal">Name: </font>
                                                                <a class="{$cell.cell.link}" href='load.php?ID={$cell.cell.ID}'>{$cell.cell.name} (ID #{$cell.cell.ID})</a>

                                                                {if $cell.cell.birt_date != ""}

                                                                        <br />
                                                                        <font class="normal">
                                                                        ##Birth Date##:
                                                                        {$cell.cell.birt_date}
                                                                        </font>

                                                                {/if}
                                                                {if $cell.cell.deat_date != ""}

                                                                        <br />
                                                                        <font class="normal">
                                                                        ##Death Date##:
                                                                        {$cell.cell.deat_date}
                                                                        </font>

                                                                {/if}
                                                                {if $cell.cell.spcnt > 0}

                                                                        <br />
                                                                        <font size='-2' class="normal">##Spouse:##

                                                                        {foreach name=sp from=$cell.cell.spouseList key=spid item=sp}

                                                                                <a class="{$sp->link}" href='load.php?ID={$spid}'>{$sp->name} (ID #{$spid})</a>

                                                                                {if $smarty.foreach.sp.iteration < $cell.cell.spcnt}
                                                                                        ,
                                                                                {/if}
                                                                        {/foreach}

                                                                        </font>

                                                                {/if}
                                                                {if $cell.cell.occu != ""}

                                                                        <br />
                                                                        <font class="normal">
                                                                        ##Occupation##:
                                                                        {$cell.cell.occu}
                                                                        </font>

                                                                {/if}
                                                           </td>
                                                        </tr>

                                 { else}

                                                        <tr>
                                                                <td width='100%' align="center">
                                                                <a class='{$cell.cell.link}' href='load.php?ID={$cell.cell.ID}'>{$cell.cell.name} (ID #{$cell.cell.ID})</a>
                                                                </td>
                                                        </tr>

                                 {/if}
                            {/if}

                                                 </table>
                                             </td>
                                         </tr>
                                     </table>
                              </td>

                        { elseif $cell.model == 'G' && $mydata.type != 3}
                           <img  alt="" src="images/{$smarty.session.templateID}/corner.gif"  width="32" height="32" /></td>
                        {elseif $cell.model == 'DG' && $mydata.type != 3}
                            <img  alt="" src="images/{$smarty.session.templateID}/dcorner.gif"  width="32" height="32" /></td>
                        { elseif $cell.model == '+' && $mydata.type != 3}
                            <img alt="" src="images/{$smarty.session.templateID}/t.gif"  width="32" height="32" /></td>
                       { elseif $cell.model == '|' && $mydata.type != 3}
                            <img alt="" src="images/{$smarty.session.templateID}/spacer.gif"  width="32" height="32" /></td>
                       { else}
                       		</td>
                       {/if}
                {/if}
        {/foreach}
        </tr>

{/foreach}

</table>
<script type="text/javascript">
        var inmenu=0;
</script>

{* Do NOT uncomment these lines *}
{*  This text is passed by PHP, included here for the language editor

##With Thumbnail##
##Without Thumbnail##
##Minimal/Text Only##

*}