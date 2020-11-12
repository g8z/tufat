<script language='JavaScript' type="text/javascript">
function ShowWindow(event, u)
{ldelim}
       document.getElementById(u).style.display="";
{rdelim}


function HideWindow(u)
{ldelim}
      document.getElementById(u).style.display="none";
{rdelim}

</script>

<font class="title">##Lateral View##</font><br /><br />
<table width='100%' cellpadding="0" cellspacing="0" border="0">
        <tr>
                <td>
                        <font class="normal">##cshow_h##</font>
                </td>
        </tr>
</table>
<br />
<form action='cshow.php?ID={$mydata.ID}' method="post">
        <font class="normal">##Degree##</font>
        <select name="level" class="normal">
                { html_options options=$levelsArray selected=$mydata.level}
        </select>
        <font class="normal">##View by##
        </font>
        <select class="normal" name="type">
                <option {if $mydata.type=='F'}selected="selected"{/if} value="F">
                        ##Paternal Ancestry##
                </option>
                <option {if $mydata.type=='M'}selected="selected"{/if} value="M">
                        ##Maternal Ancestry##
                </option>
        </select>
        <input type="submit" value='##Go##' />
{* Back button removed
<input type="button" onclick='location.href="load.php?ID={$mydata.ID}"' value='##Back##' /> *}
        {*<input type="hidden" name="ID" value="{$mydata.ID}" />*}
</form>

{* Now show the information of family tree *}
{* dispList is the array of contents *}

<table width='100%' cellpadding="0" cellspacing="0" border="0">
{foreach from=$dispList key=key item=disprec}

        <tr>

        {foreach from=$disprec->toptdcnt item=tdrec}
                {* Print the blank columns for the levels *}
                <td>&nbsp;</td>
        {/foreach}

        {* for each level list information *}

        {foreach from=$disprec->levelList key=key item=cellArray}

                {if $cellArray.blankrec}

                        <td>&nbsp;</td>

                {elseif $cellArray.cnt > 0}  {* display cell information *}

                        <td>
                                <table border="0" width='100' onmouseout="HideWindow('cell{$cellArray.xx}')" onmouseover="ShowWindow(event, 'cell{$cellArray.xx}')" bgcolor='{$cellArray.color}'>
                                <tr>
                                        <td align="center">

                                        {if $cellArray.recs}

                                        <div id='cell{$cellArray.xx}' style='position:absolute;display:none'>
                                                <table width="240" border="0" cellpadding="2" bgcolor='#eeee00' cellspacing="0"  onmouseover="ShowWindow(event, 'cell{$cellArray.xx}')">
                                                        <tr>
                                                                <td valign="top">
                                                                        <font class="normalSmall">
                                                                        {$cellArray.relink}:</font><br />
                                                                        {* Show information of everyone in the level *}
                                                                        {if $cellArray.listArrayCnt == 0}
                                                                                <font class="normalSmall">##None Found##</font>
                                                                        { else}
                                                                                {* Ladies First  *}
                                                                                {foreach from=$cellArray.listArray item=listArray}
                                                                                        {if $listArray->IC == 'pinkLink' &&  $listArray->v != $mydata.ID}
                                                                                        <a class={$listArray->IC} href='cshow.php?ID={$listArray->v}&amp;type={$mydata.type}&amp;level=2'><font class="normalSmall">{$listArray->istr}
                                                                                        &nbsp;(ID #{$listArray->v})</font></a><br />

                                                                                                {if $listArray->spcnt > 0}
                                                                                                        {if !$mydata.animalPedigree}
                                                                                                        <font class="normalSmall">##spouses##:
                                                                                                        </font><br />
                                                                                                        { else}
                                                                                                        <font class="normalSmall">##mates##:
                                                                                                        </font><br />
                                                                                                        {/if}

                                                                                                        {foreach from=$listArray->spouseList key=spid item=sprec}                                                                                                        <a class={$sprec->lC} href='cshow.php?ID={$spid}&amp;type={$mydata.type}&amp;level=2'><font class="normalSmall">{$sprec->istr}&nbsp;
                                                                                                        (ID #{$spid})</font></a><br />
                                                                                                        {/foreach}
                                                                                                {/if}
                                                                                        {/if}
                                                                                {/foreach}
                                                                                {* Now gents  *}
                                                                                {foreach from=$cellArray.listArray item=listArray}
                                                                                        {if $listArray->IC != 'pinkLink' &&  $listArray->v != $mydata.ID}
                                                                                        <a class={$listArray->IC} href='cshow.php?ID={$listArray->v}&amp;type={$mydata.type}&amp;level=2'><font class="normalSmall">{$listArray->istr}
                                                                                        &nbsp;(ID #{$listArray->v})</font></a><br />

                                                                                                {if $listArray->spcnt > 0}
                                                                                                        {if !$mydata.animalPedigree}
                                                                                                        <font class="normalSmall">##spouses##:
                                                                                                        </font><br />
                                                                                                        { else}
                                                                                                        <font class="normalSmall" >##mates##:
                                                                                                        </font><br />
                                                                                                        {/if}

                                                                                                        {foreach from=$listArray->spouseList key=spid item=sprec}
                                                                                                        <a class={$sprec->lC} href='cshow.php?ID={$spid}&amp;type={$mydata.type}&amp;level=2'><font class="normalSmall">{$sprec->istr}&nbsp;
                                                                                                        (ID #{$spid})</font></a><br />
                                                                                                        {/foreach}
                                                                                                {/if}
                                                                                        {/if}
                                                                                {/foreach}
                                                                        {/if}

                                                                </td>
                                                        </tr>
                                                </table>
                                        </div>

                                        { else}

                                        <div id='cell{$cellArray.xx}' style='display:none'>
                                        </div>

                                        {/if}
                                        {foreach from=$cellArray.listArray2 key=key item=listArray2}

                                                {if $listArray2->blankrec} {* blank column *}

                                                        &nbsp;

                                                { else}
							
                                                        <font class="normal" face='Arial' size="1"><b>
                                                        {$listArray2->istr}</b></font><br />

                                                        {if $listArray2->v == $mydata.ID}

                                                                <a class="blueLink" href='load.php?ID={$mydata.ID}'>##Load Record##</a>

                                                        {/if}
                                                {/if}
                                        {/foreach}
                                        </td>
                                </tr>
                                </table>
                        </td>

                { else}

                        <td>&nbsp;</td>

                {/if}

        {/foreach}

        </tr>
        <tr>

        {foreach from=$disprec->downtdcnt item=tdrec}
                {* Print the blank columns for the levels *}
                <td>&nbsp;</td>
        {/foreach}
        </tr>
{/foreach}
</table>
<script type="text/javascript" >
        var inmenu=0;
</script>

{* Do NOT Uncomment this section *}
{* This text is passed by php, added here for the language editor

##level##  ##removed##  ##once##  ##twice## 
##1st## ##2nd## ##3rd## ##4th## ##5th## ##6th## ##7th## ##8th## ##9th## ##10th##
##great## ##grand## ##parents##
##aunt## ##uncle## ##aunts and uncles##
##niece## ##nephew## ##cousin##
##nieces and nephews##

*}