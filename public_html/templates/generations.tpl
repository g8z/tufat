<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr class="normalSmall">
    <td align="center" valign="bottom">

    {if $mydata.maternalGreatGrandmother1name != $mydata.undefined}
       <a class="pinkLink" href="generations.php?ID={$mydata.maternalGreatGrandmother1}">{$mydata.maternalGreatGrandmother1name}</a>

    {else}

      <span class="normalSmall">{$mydata.undefined}</span>

    {/if}

    </td>
    <td align="center" valign="bottom">

    {if $mydata.maternalGreatGrandfather1name != $mydata.undefined}
       <a class="blueLink" href="generations.php?ID={$mydata.maternalGreatGrandfather1}">{$mydata.maternalGreatGrandfather1name}</a>

    {else}

      <span class="normalSmall">{$mydata.undefined}</span>

    {/if}

    </td>
    <td align="center" valign="bottom">

    {if $mydata.maternalGreatGrandmother2name != $mydata.undefined}
       <a class="pinkLink" href="generations.php?ID={$mydata.maternalGreatGrandmother2}">{$mydata.maternalGreatGrandmother2name}</a>

    {else}

      <span class="normalSmall">{$mydata.undefined}</span>

    {/if}

    </td>
    <td align="center" valign="bottom">

    {if $mydata.maternalGreatGrandfather2name != $mydata.undefined}
       <a class="blueLink" href="generations.php?ID={$mydata.maternalGreatGrandfather2}">{$mydata.maternalGreatGrandfather2name}</a>

    {else}

      <span class="normalSmall">{$mydata.undefined}</span>

    {/if}

    </td>
    <td align="center" valign="bottom">

    {if $mydata.paternalGreatGrandmother1name != $mydata.undefined}

       <a class="pinkLink" href="generations.php?ID={$mydata.paternalGreatGrandmother1}">{$mydata.paternalGreatGrandmother1name}</a>

    {else}

      <span class="normalSmall">{$mydata.undefined}</span>

    {/if}

    </td>
    <td align="center" valign="bottom">

    {if $mydata.paternalGreatGrandfather1name != $mydata.undefined}

       <a class="blueLink" href="generations.php?ID={$mydata.paternalGreatGrandfather1}">{$mydata.paternalGreatGrandfather1name}</a>

    {else}

      <span class="normalSmall">{$mydata.undefined}</span>

    {/if}

    </td>
    <td align="center" valign="bottom">

    {if $mydata.paternalGreatGrandmother2name != $mydata.undefined}

       <a class="pinkLink" href="generations.php?ID={$mydata.paternalGreatGrandmother2}">{$mydata.paternalGreatGrandmother2name}</a>

    {else}

      <span class="normalSmall">{$mydata.undefined}</span>

    {/if}

    </td>
    <td align="center" valign="bottom">

    {if $mydata.paternalGreatGrandfather2name != $mydata.undefined}

       <a class="blueLink" href="generations.php?ID={$mydata.paternalGreatGrandfather2}">{$mydata.paternalGreatGrandfather2name}</a>

    {else}

      <span class="normalSmall">{$mydata.undefined}</span>

    {/if}

    </td>
  </tr>
  <tr class="normal">
    <td colspan="2" align="center"><img  alt=""  src="images/{$smarty.session.templateID}/generationsVine.gif" width="80" height="17" /></td>
    <td colspan="2" align="center"><img  alt=""  src="images/{$smarty.session.templateID}/generationsVine.gif" width="80" height="17" /></td>
    <td colspan="2" align="center"><img  alt=""  src="images/{$smarty.session.templateID}/generationsVine.gif" width="80" height="17" /></td>
    <td colspan="2" align="center"><img  alt=""  src="images/{$smarty.session.templateID}/generationsVine.gif" width="80" height="17" /></td>
  </tr>
  <tr class="normal">
    <td colspan="2" align="center" valign="bottom">

    {if $mydata.maternalGrandmothername != $mydata.undefined}

    <a class="pinkLink" href="generations.php?ID={$mydata.maternalGrandmother}">{$mydata.maternalGrandmothername}</a>

    { else}

            <span class="normalSmall">{$mydata.undefined}</span>

    {/if}

    </td>
    <td colspan="2" align="center" valign="bottom">

    {if $mydata.maternalGrandfathername != $mydata.undefined}

    <a class="blueLink" href="generations.php?ID={$mydata.maternalGrandfather}">{$mydata.maternalGrandfathername}</a>

    { else}

            <span class="normalSmall">{$mydata.undefined}</span>

    {/if}

    </td>
    <td colspan="2" align="center" valign="bottom">

    {if $mydata.paternalGrandmothername != $mydata.undefined}

    <a class="pinkLink"
    href="generations.php?ID={$mydata.paternalGrandmother}">{$mydata.paternalGrandmothername}</a>

    { else}

            <span class="normalSmall">{$mydata.undefined}</span>

    {/if}

    </td>
    <td colspan="2" align="center" valign="bottom">

    {if $mydata.paternalGrandfathername != $mydata.undefined}

    <a class="blueLink" href="generations.php?ID={$mydata.paternalGrandfather}">{$mydata.paternalGrandfathername}</a>

    { else}

            <span class="normalSmall">{$mydata.undefined}</span>

    {/if}

    </td>
  </tr>
  <tr class="normal">
    <td colspan="4" align="center"><img  alt=""  src="images/{$smarty.session.templateID}/generationsVine.gif" width="170" height="25" /></td>
    <td colspan="4" align="center"><img  alt=""  src="images/{$smarty.session.templateID}/generationsVine.gif" width="170" height="25" /></td>
  </tr>
  <tr class="normal">
    <td colspan="4" align="center">

    {if $mydata.mothername != $mydata.undefined}

    <a class="pinkLink" href="generations.php?ID={$mydata.mother}">{$mydata.mothername}</a>

    {else}

    <span class="normalSmall">{$mydata.undefined}</span>

    {/if}

    </td>
    <td colspan="4" align="center">

    {if $mydata.fathername != $mydata.undefined}

    <a class="blueLink" href="generations.php?ID={$mydata.father}">{$mydata.fathername}</a>

    { else}

            <span class="normalSmall">{$mydata.undefined}</span>

    {/if}

    </td>
  </tr>
  <tr class="normal">
    <td colspan="8" align="center"><img  alt=""  src="images/{$smarty.session.templateID}/generationsVine.gif" /></td>
  </tr>
  <tr>
    <td colspan="8" align="center">
      <a class="{$mydata.link}" href="load.php?ID={$mydata.ID}">
        <font style="font-size:18px; font-weight:bold;" >{$mydata.name}</font>
      </a>
    </td>
  </tr>
  <tr>
    <td colspan="8" align="left" class="normalSmall">##click_on_a_name##
    </td>
  </tr>
  <tr class="normalSmall">
    <td height="32" valign="bottom">##Siblings:##
        </td>
    <td valign="bottom">##Spouses:##</td>
    <td valign="bottom">##Children:##</td>
    <td valign="bottom">##Grandchildren:##</td>
    <td valign="bottom">##Nieces &amp; Nephews:##</td>
    <td valign="bottom">##Aunts &amp; Uncles:##</td>
    <td valign="bottom">##1st Cousins:##</td>
    <td valign="bottom">##Great, great grandparents:##</td>
  </tr>
  <tr class="normalSmall">
    <td valign="top"> {* Sibling list  *}

            {if $mydata.siblingcnt > 0}
                    {foreach from=$siblingsList key=key item=disp1}
                            {foreach from=$disp1 key=ID item=disp}

                            <a class="{$disp->linkclass}" href="generations.php?ID={$ID}">{$disp->name|trim}</a>{if $disp->halfstring != ''}<span class="normalSmall">&frac12;</span>{/if}<br />

                            {/foreach}
                    {/foreach}
            { else}

                    {$mydata.none}

            {/if}

    </td>
    <td valign="top"> {* Spouse List *}

            {if $mydata.spousecnt > 0}
                    {foreach from=$spouseList key=ID item=disp}

                            <a class="{$disp->linkclass}" href="generations.php?ID={$ID}">{$disp->name|trim}</a><br />

                    {/foreach}
            { else}

                    {$mydata.none}

            {/if}

    </td>
    <td valign="top"> {* Children list *}

            {if $mydata.childrencnt > 0}
                    {foreach from=$childrenList key=key item=disp1}
                            {foreach from=$disp1 key=ID item=disp}

                            <a class="{$disp->linkclass}" href="generations.php?ID={$ID}">{$disp->name|trim}</a><br />

                            {/foreach}
                    {/foreach}
            { else}

                    {$mydata.none}

            {/if}

    </td>
    <td valign="top"> {* Grand Children list  *}

            {if $mydata.grandchildrenExist}
                    {foreach from=$grandchildrenList key=key item=disp1}
                            {foreach from=$disp1 key=ID item=disp}

                            <a class="{$disp->linkclass}" href="generations.php?ID={$ID}">{$disp->name|trim}</a><br />

                            {/foreach}
                    {/foreach}
            { else}

                    {$mydata.none}

            {/if}

    </td>
    <td valign="top"> {* Nieces and Nephews list *}

            {if $mydata.niecesnephewscnt > 0}
                    {foreach from=$niecesnephewsList key=key item=disp1}
                            {foreach from=$disp1 key=ID item=disp}

                            <a class="{$disp->linkclass}" href="generations.php?ID={$ID}">{$disp->name|trim}</a><br />

                            {/foreach}
                    {/foreach}
            { else}

                    {$mydata.none}

            {/if}

    </td>
    <td valign="top"> {* Uncles and Aunts list  *}

            {if $mydata.unclesauntscnt > 0}
                    {foreach from=$unclesauntsList key=key item=disp1}
                            {foreach from=$disp1 key=ID item=disp}

                            <a class="{$disp->linkclass}" href="generations.php?ID={$ID}">{$disp->name|trim}</a><br />

                            {/foreach}
                    {/foreach}
            { else}

                    {$mydata.none}

            {/if}

    </td>
    <td valign="top">  {* Cousins List  *}

            {if $mydata.cousinscnt > 0}
                    {foreach from=$cousinsList key=key item=disp1}
                            {foreach from=$disp1 key=ID item=disp}

                            <a class="{$disp->linkclass}" href="generations.php?ID={$ID}">{$disp->name|trim}</a><br />

                            {/foreach}
                    {/foreach}
            { else}

                    {$mydata.none}

            {/if}

    </td>
    <td valign="top">  {* Great Great grand parents List *}

            {if $mydata.greatgreatgpExists}
                    {foreach from=$greatgreatgpList key=key item=disp1}
                            {foreach from=$disp1 key=ID item=disp}

                            <a class="{$disp->linkclass}" href="generations.php?ID={$ID}">{$disp->name|trim}</a><br />

                            {/foreach}
                    {/foreach}
            { else}

                    {$mydata.none}

            {/if}

    </td>
  </tr>
</table>
<p>&nbsp;</p>
<script type="text/javascript">
        var inmenu=0;
</script>