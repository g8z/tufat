{* This is the search results display template *}

<font class="title"><b>##Search Results##</b></font>

<table width="100%" border="0" cellpadding="5" cellspacing="0">
        <tr>
                <td width="50%">
                <span class="normal">##Total number of records:##</span>
                </td>
                <td><span class="normalBold">{$mydata.numTotal}</span>
                </td>
        </tr>
        <tr>
                <td><span class="normal">##Number of records in found:##</span>
                </td>
                <td><span class="normalBold">{if $mydata.numFound ==''}0{else}{$mydata.numFound}{/if}</span>
                </td>
        </tr>
        <tr>
                <td>
                        <span class="normal">##Match percentage:## </span>
                </td>
                <td><span class="normalBold">{$mydata.percentMatch}%</span>
                </td>
        </tr>

        {if $mydata.numFound > 0}

                <tr>
                        <td colspan="2">
                                <span class="normal">
                                		{if $animalPedigree}
                                		##Click a name to load the animal's record, or click on a column header to re-sort the search results.##
                                		{else}
	                                	##Click a name to load the person's record, or click on a column header to re-sort the search results.##
	                                	{/if}
	                              </span>
                        </td>
                </tr>

        {/if}

</table>
<br />

{if $mydata.result2Cnt > 0}

        <table border="0" cellpadding="2" cellspacing="0" width='100%'>
                <tr>

            {if $mydata.animalPedigree}

                        <td class="normalBold" valign="bottom">
                                <a href='searchResults.php?sortby=name&amp;{$mydata.getstr}'>##Registered Name##</a>
                        </td>
                        <td class="normalBold" valign="bottom">
                                <a href='searchResults.php?sortby=surn&amp;{$mydata.getstr}'>##Callname##</a>
                        </td>

            { else}

                    <td class="normalBold" valign="bottom">
                            <a href='searchResults.php?sortby=name&amp;{$mydata.getstr}'>##Name##</a>
                    </td>
                        <td class="normalBold" valign="bottom">
                                <a href='searchResults.php?sortby=surn&amp;{$mydata.getstr}'>##Surname##</a>
                        </td>

                {/if}

                        <td class="normalBold" valign="bottom">
                                <a href='searchResults.php?sortby=sex&amp;{$mydata.getstr}'>##cap_Gender##</a>
                        </td>

                {if !$mydata.animalPedigree}

                        <td class="normalBold" valign="bottom">
                                <a href='searchResults.php?sortby=occu&amp;{$mydata.getstr}'>##Occupation##</a>
                        </td>

                { else}

                        <td class="normalBold" valign="bottom">
                                <a href='searchResults.php?sortby=bred&amp;{$mydata.getstr}'>##Breed##</a>
                        </td>

                {/if}

                        <td class="normalBold" valign="bottom">
                                <a href='searchResults.php?sortby=bdate&amp;{$mydata.getstr}'>##Date of Birth##</a>
                        </td>
                        <td class="normalBold" valign="bottom">
                                <a href='searchResults.php?sortby=ddate&amp;{$mydata.getstr}'>##Date of Death##</a>
                        </td>

                {if !$mydata.animalPedigree}

                <td class="normalBold" valign="bottom">
                        <a href='searchResults.php?sortby=bplace&amp;{$mydata.getstr}'>##Burial Site##</a>
                </td>

                {else}

                        <td class="normalBold" valign="bottom">
                                <a href='searchResults.php?sortby=cdea&amp;{$mydata.getstr}'>##cause of death##</a>
                        </td>

                {/if}

                </tr>

                {foreach from=$result2List key=ID item=disp}

                <tr>
                        <td class="normal">
                                <a href="load.php?ID={$ID}">{$disp->name}</a>
                        </td>
                        <td class="normal">{$disp->surn}</td>
                        <td class="normal">{$disp->sex}</td>

                        {if !$mydata.animalPedigree}

                                <td class="normal">{$disp->occu}</td>

                        {else}

                                <td class="normal">{$disp->bred}</td>

                        {/if}

                        <td class="normal">{$disp->birt_date}</td>
                        <td class="normal">{$disp->deat_date}</td>

                        {if !$mydata.animalPedigree}

                                <td class="normal">{$disp->buri_plac}</td>

                {else}

                                <td class="normal">{$disp->cdea}</td>

                        {/if}

                </tr>

                {/foreach}

        </table>

{else}

        <span class="normal">##No records were returned by your search request.##</span>

{/if}

<br /><a href="search.php?ID={$mydata.ID}">##Search Again##</a>


{* Now show results for CrossTree Search *}

{if $mydata.alltrees && $mydata.allowCrossTreeSearch}

        {if $mydata.resultcnt > 0}

                <br /><br />
                <table border="0" cellpadding="2" cellspacing="0" width='100%'>
                        <tr>
                                <td colspan=6>
                                        <span class="normalBold">##Search results for trees other than the ## {$mydata.treeName}&nbsp;## tree:##
                                        </span>
                                </td>
                        </tr>
                        <tr>
                                <td colspan=6>
                                        <span class="normal">##Clicking on the name of an individual will give you a form by which you can request access to the tree by the tree administrator. For security reasons, you may not access trees outside your own family without permission of the tree owner, and only initials and years are provided in lieu of full names and dates. Highlighted rows indicate a likely match to the currently loaded tree.##
                                        </span>
                                </td>
                        </tr>
                        <tr>
                                <td colspan=6>
                                        <span class="normal">&nbsp;</span>
                                </td>
                        </tr>
                        <tr>
                                <td class="normalBold">
                                        <a href='searchResults.php?sortby2=name&amp;{$mydata.getstr}'>##Initials##</a>
                                </td>
                                <td class="normalBold">
                                        <a href='searchResults.php?sortby2=sex&amp;{$mydata.getstr}'>##cap_Gender##</a>
                                </td>
                                <td class="normalBold" valign="bottom">

                                {if !$mydata.animalPedigree}

                                        <a href='searchResults.php?sortby2=occu&amp;{$mydata.getstr}'>##Occupation##</a>

                        { else}

                                        <a href='searchResults.php?sortby2=bred&amp;{$mydata.getstr}'>##Breed##</a>

                                {/if}

                                </td>
                                <td class="normalBold">
                                        <a href='searchResults.php?sortby2=bdate&amp;{$mydata.getstr}'>##Date of Birth##</a>
                                </td>
                                <td class="normalBold">
                                        <a href='searchResults.php?sortby2=ddate&amp;{$mydata.getstr}'>##Date of Death##</a>
                                </td>
                                <td class="normalBold" valign="bottom">

                                {if !$mydata.animalPedigree}

                                        <a href='searchResults.php?sortby2=bplace&amp;{$mydata.getstr}'>##Burial Site##</a>

                                { else}

                                        <a href='searchResults.php?sortby2=cdea&amp;{$mydata.getstr}'>##cause of death##</a>

                                {/if}

                                </td>
                        </tr>

                        {foreach from=$intreesList key=key item=disp}

                        <tr>
                                <td class="normal">
                                        <a href="javascript:launch('getAdminInfo.php?tree={$disp->tree}&amp;ID={$disp->ID}&amp;user={$disp->user}','width=600,height=450,scrollbars,resizable');">{$disp->initials}</a>
                                </td>
                                <td class="normal">{$disp->sex}</td>

                            {if !$mydata.animalPedigree}

                                <td class="normal">{$disp->occu}</td>

                                { else}

                                <td class="normal">{$disp->bred}</td>

                                {/if}

                                <td class="normal">{$disp->birt_date}</td>
                                <td class="normal">{$disp->deat_date}</td>

                                {if !$mydata.animalPedigree}

                                <td class="normal">{$disp->buri_plac}</td>

                                { else}

                                <td class="normal">{$disp->cdea}</td>

                                {/if}

                        </tr>

                        {/foreach}

                </table>
                <br /><a href=search.php?ID={$mydata.ID}>##Search Again##</a>

        { else}

                 <br /><br />
                 <span class="normal">##No records in any other trees were returned by your search request.##
                 </span>

        {/if}

{/if}
<script type="text/javascript">
        var inmenu={if $smarty.session.admin}6{else}3{/if};
</script>
