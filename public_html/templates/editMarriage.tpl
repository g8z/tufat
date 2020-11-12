<form method="post" name="theForm" action="editMarriage.php">
        <table width="600" border="0" cellspacing="0" cellpadding="3">
{*                <tr>
                        <td colspan="2">
                                <input name="mySubmit2"  onclick="javascript:validateMarriage(document.theForm);" type="button" class="normal" value="##Save Information##" />
                                <input name="cancel2" type="button" class="normal" id="cancel2" onclick="javascript:window.location='load.php?ID={$mydata.sp1}';" value="##Cancel##" />
                        </td>
                </tr>
                <tr>
                        <td colspan="2">&nbsp;</td>
                </tr> *}
                <tr>
                        <td colspan="2" class="title">
                                {if !$smarty.session.animalPedigree}
                                        ##Edit Marriage or Partnership Information##
                                { else}
                                        ##Edit Partnership Information##
                                {/if}
                        </td>
                </tr>
                <tr>
                        <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                        <td class="normal" width="200">
                                {if $mydata.sp1sex  == "f"}
                                        ##Wife or Female Partner##
                                        <input type="hidden" name="wife" value="{$mydata.sp1}" />
                                { else}
                                        ##Husband or Male Partner##
                                        <input type="hidden" name="husb" value="{$mydata.sp1}" />
                                {/if}
                        </td>
                        <td width="400" class="normal">{$mydata.sp1name}</td>
                </tr>
                <tr>
                        <td class="normal">

                                {if $mydata.sp2sex  == "f"}

                                        ##Wife or Female Partner##
                                        <input type="hidden" name="wife" value="{$mydata.sp2}" />

                                { else}

                                        ##Husband or Male Partner##
                                        <input type="hidden" name="husb" value="{$mydata.sp2}" />

                                {/if}

                        </td>
                        <td class="normal">{$mydata.sp2name}</td>
                </tr>
                <tr>
                        <td  class="normal">##Type of Union##</td>
                        <td  class="normal">
                                <input name="marr_type" class="normal" size="25" maxlength="74" type="text" id="marr_type" value="{$vars.marr_type}" />
                                {$mydata.eg1}
                        </td>
                </tr>
                <tr>
                        <td  class="normal" colspan="2">&nbsp;</td>
                </tr>
                <tr>
                        <td colspan="2" class="subtitle">##Marriage Information##</td>
                </tr>
                <tr>
                        <td  class="normal">##Marriage Date##</td>
                        <td  class="normal">{$mydata.marr_date}</td>
                </tr>
                <tr>
                        <td  class="normal">##Place of Marriage##</td>
                        <td  class="normal">
                                <input name="marr_plac" class="normal" size="25" maxlength="74" type="text" id="marr_plac" value="{$vars.marr_plac}" />
                                ##(e.g. San Francisco, CA)##
                        </td>
                </tr>
                <tr>
                        <td  class="normal">##Marriage Agency##</td>
                        <td  class="normal">
                                <input name="marr_agnc" class="normal" size="25" maxlength="230" type="text" id="marr_agnc" value="{$vars.marr_agnc}" />
                                ##(e.g. Catholic Church)##
                        </td>
                </tr>
                <tr>
                        <td class="normal">##Marriage Notes and Citations## </td>
                        <td class="normal">
                                <input type="button" onclick='launchCentered("shownotes.php?mr=1&amp;xtag=MARR&amp;ID={$mydata.ID}", "400", "300", "resizable=1, scrollbars=1")' value="##Notes##" />
                                <input type="button" onclick='launchCentered("showsour.php?mr=1&amp;xtag=MARR&amp;ID={$mydata.ID}", "480", "400", "resizable=1, scrollbars=1")' value="##Citations##" />
                        </td>
                </tr>
                <tr>
                        <td class="normal">##Divorced?## </td>
                        <td  class="normal"><input type="checkbox" {$mydata.divorcedOK} name="sdivorced" /></td>
                </tr>
                <tr>
                        <td colspan="2">&nbsp;</td>
                </tr>

        {if !$smarty.session.animalPedigree}

                <tr>
                        <td colspan="2" class="subtitle">##Marriage events##
                        </td>
                </tr>
                <tr>
                        <td class="normal">##Divorce, Engagement, and additional Marriage Information##</td>
                        <td class="normal">
                           <input type="button" onclick='launchCentered("tageven.php?mr=1&amp;ID={$mydata.ID}", "600", "450","scrollbars=1,resizable=1")' value="##Add/Edit Events##" />
                       </td>
                </tr>
                <tr>
                        <td colspan="2">&nbsp;</td>
                </tr>

        {/if}

                <tr>
                        <td colspan="2">
                                <input name="mySubmit" onclick="javascript:validateMarriage(document.theForm);" type="button" class="normal"
                                value="##Save Information## " />
                                <input name="cancel" type="button" class="normal" id="cancel" onclick="javascript:window.location='load.php?ID={$mydata.sp1}';" value="##Cancel##" />
                        </td>
                </tr>
        </table>
        <input type="hidden" name="sp1" value="{$mydata.sp1}" />
        <input type="hidden" name="org" value="{$mydata.sp1}" />
        <input type="hidden" name="sp2" value="{$mydata.sp2}" />
        <input type="hidden" name="ID" value="{$mydata.ID}" />
        <input type="hidden" name="submitForm" value="" />
</form>
<script type="text/javascript">
        var inmenu="0";
</script>