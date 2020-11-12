<font class="title">##Find Relation##</font>
<br /><br />
{if $mydata.skip}
<span class="normal">

        {$mydata.showmenumsg}
    <br /><br />
</span>

{* --Graphical part start*}
<table border="0" cellspacing="0" cellpadding="1">

{foreach from=$atree item=atree1}
        <tr>
    {foreach from=$atree1 item=cell}
      <td class="normal" align="center" {if $cell.model == 'O'} bgcolor="#C3C3C3" {/if}>
       {if $cell.model == 'O'}
       {if $cell.spic} <a href="load.php?ID={$cell.ID}"><img alt="" border="0" width="75"  src="{$cell.portraitfile}" /></a> <br />{/if}
                  <a href="load.php?ID={$cell.ID}">{$cell.name} (ID #{$cell.ID})</a> &nbsp;
       {/if}
       {if $cell.model == '-'} -- {/if}
       {if $cell.model == '|'} | {/if}
       {if $cell.model == ' '} &nbsp;&nbsp;&nbsp;  {/if}
       {if $cell.model == 'bk'}  ##Tree is broken here## {/if}
      </td>
    {/foreach}
    </tr>
{/foreach}

</table>

{* --Graphical part end*}

        <br />
        <a href='relfind.php'>##Find another relation in your family tree##</a>
        <br /><br />

        <a href='load.php?ID={$mydata.si1}'>{$mydata.si1sG}</a><br />

        {if $mydata.si1 != $mydata.si2}

                <a href='load.php?ID={$mydata.si2}'>{$mydata.si2sG}</a><br />

        {/if}
{ else}
<table border="0" cellspacing="0" cellpadding="0">
 {if $optList|@count gt 0}
 <tr>
    <td class="normal">
         <form name="xx" action="" method="post">
                ##Sort by##&nbsp;
                <select onchange='location.href="relfind.php?ID={$mydata.ID}&amp;ssort="+this.value' name="ssort" class="normal">
                        {if $smarty.session.animalPedigree}
                                <option {$mydata.nameselected} value='name'>##Registered Name##
                        { else}
                                <option {$mydata.nameselected} value='name'>##Name##
                        {/if}
                        </option>
                        {if $smarty.session.animalPedigree}
                                <option {$mydata.surnselected} value='surn'>##Call Name##
                        { else}
                                <option {$mydata.surnselected} value='surn'>##Surname##
                        {/if}
                        </option>
                        <option {$mydata.idselected} value='id'>##GEDCOM ID##</option>
                </select>
        </form>
    </td>
 </tr>
<tr>
   <td>&nbsp;</td>
 </tr>
 <tr>
    <td>
       <form action='relfind.php' method="post">
                <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td class="normal" align="left">##Find relation between## <br /><br /></td>
                </tr>
                <tr><td>
                <select class="normal" name="si1">

                        {foreach from=$optList key=key item=disp}

                                <option value='{$disp->val}' >{$disp->desc}
                                </option>

                        {/foreach}

                </select>
                <font class="normal"> ## and ##
                </font>
                <select class="normal" name="si2">

                        {foreach from=$optList key=key item=disp}

                                <option value='{$disp->val}'>{$disp->desc}
                                </option>

                        {/foreach}

                </select>
                </td></tr></table>
        <br />
        <input type="submit" value='##Find Relation##' />
        <input type="button" onclick="location.href='index.php'" value='##Cancel##' />
    </form>
   </td>
 </tr>
 {else}
 
 <tr><td>##The system could not find any records for use with the Find Relation feature.##</td></tr>
 {/if}
</table>

{/if}

<script type="text/javascript">
        var inmenu=1;
</script>


{* Do NOT uncomment these lines *}
{*  This text is passed by PHP, added here for the language editor

##is the## ##in law## ##is## ##the## ##of## ##removed##
##cousin## ##father## ##mother## ##uncle## ##aunt## ##great## ##grand## ##parent## ##brother## ##sister##
##first##  ##second## ##third## ##fourth## ##fifth## ##sixth## ##seventh## ##eighth## ##ninth## ##tenth## ##level##
##once## ##twice## ##times##
##A relationship between $person1 and $person2 could not be found##
##The person is the same##
##The animal is the same##
##$person1 is the spouse of $person2##
##$person1 is the $step brother of $person2##
##$person1 is the $step sister of $person2##

*}