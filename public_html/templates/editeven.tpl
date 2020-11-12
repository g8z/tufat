{if $mydata.tphdr != ''}

        <font class="subtitle"> EV # {$mydata.en} - {$mydata.tphdr}</font><br />

{ elseif $mydata.xtag == 'DIV'}

        <font class="subtitle">{$mydata.xtagdisp}</font><br />

{ else}

        <font class="subtitle">{$mydata.xtagdisp} # {$mydata.en}</font>
        <br />

{/if}
{if $mydata.err}
        <br /><font color=red>{$mydata.err}</font)<br />
{/if}

<table width="99%">

{if $mydata.xtag == "EVEN"}

        <tr>
                <td class="normal" width='200'>{$mydata.xtagdisp} ##Event Type##
                </td>
                <td><input name="{$mydata.xtag}-{$mydata.ct}_tp" class="normal" size="35" maxlength="74" type="text" id="{$mydata.xtag}_tp" value="{$mydata.tp|stripslashes}" />
                </td>
        </tr>

{/if}

{if $mydata.xtag == 'DIV' && $mydata.ds == 'Y'}

        <input type="hidden" name="{$mydata.xtag}-{$mydata.ct}_ds" class="normal" size="35" maxlength="74" type="text" id="{$mydata.xtag}_ds" value="{$mydata.ds|stripslashes}" />


{ elseif $mydata.xtag != 'DIV'}

        <tr>
                <td class="normal" width='200'>
                        {$mydata.xtagdisp} ##Description##
                </td>
                <td><input name="{$mydata.xtag}-{$mydata.ct}_ds" class="normal" size="35" maxlength="74" type="text" id="{$mydata.xtag}_ds" value="{$mydata.ds|stripslashes}" />
                </td>
        </tr>


{/if}

        <tr>
                <td class="normal" width='200'>
                        {$mydata.xtagdisp} ##Place##
                </td>
                <td><input name="{$mydata.xtag}-{$mydata.ct}_pc" class="normal" size="35" maxlength="74" type="text" id="{$mydata.xtag}_pc" value="{$mydata.pc|stripslashes}" />
                </td>
        </tr>
        <tr>
            <td class="normal">{$mydata.xtagdisp} ##Date##
            </td>
            <td>
                    {$mydata.dt}
            </td>
        </tr>
     {if !$mydata.addneweven}
        <tr class="normal">
                <td>{$mydta.xtagdisp} ##Notes and Citations##
            </td>
                <td>
                        <input type="button" onclick='launchCentered("shownotes.php?xtag={$mydata.xtag}&amp;ID={$mydata.ID}&amp;mr={$mydata.mr}", "400", "300","resizable=1,scrollbars=1")' value="##Notes##" />

                        <input type="button" onclick='launchCentered("showsour.php?xtag={$mydata.xtag}&amp;ID={$mydata.ID}&amp;mr={$mydata.mr}", "480", "360","resizable=1,scrollbars=1")' value="##Citations##" />

                </td>
       </tr>
     {/if}
{if !isset( $smarty.session.read_only) && $smarty.session.my_rec != $mydata.ID}
        <tr>
                <td>
                        <input type='submit' value='Submit' />
                        <input type="button" onclick='if ( confirm("##Are you sure?##")) location.href="tageven.php?ID={$mydata.ID}&amp;ctl={$mydata.ct}&amp;xtag={$mydata.xtag}&amp;del=1&amp;mr={$mydata.mr}"' value="##Remove##" />

                </td>
        </tr>
{/if}

</table>
        <input type="hidden" name="ID" value="{$mydata.ID}" />
        <input type="hidden" name="mr" value="{$mydata.mr}" />
        <input type="hidden" name="en" value="{$mydata.en}" />
        <input type="hidden" name="editik" value="1" />
<br />