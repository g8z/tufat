<table width="100%" border="0" cellspacing="0" cellpadding="5">
       <tr>
           <td class="title">##Search your family tree!##</td>
       </tr>
        <tr>
                <td><span class="normal">

                        {if $mydata.animalPedigree}
									 {assign var=forthebest value="##for_the_best##"|replace:"people":"animals"}
                            {$forthebest}

                        { else}

                                ##for_the_best##

                        {/if}

                        </span>
                </td>
        </tr>
</table>
<form name="form1" method="get" action="searchResults.php">
        <table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
             <td class="normal">&nbsp;</td>
          </tr>
        {if $mydata.allowCrossTreeSearch}

                <tr class="normal">
                        <td>##search all trees##</td>
            <td><input name="alltrees" type="checkbox" id="alltrees" value="1" />
            </td>
                </tr>

        {/if}

                <tr class="normal">

                        {if $mydata.animalPedigree}

                        <td width="20%">##regname fragment##
                        </td>

                        { else}

                        <td width="20%">##name fragment##
                        </td>

                        {/if}

            <td width='10%'>
                    <select class="normal" name="f1bool">
                            <option value='AND'>##AND##</option>
                            <option value='OR'>##OR##</option>
                    </select>
            </td>
            <td width="434"><input name="name" type="text" class="normal" id="name" size="30" maxlength="255" />
                        </td>
                </tr>
                <tr class="normal">

                {if  $mydata.animalPedigree}

                        <td width="20%">##callname fragment##
                        </td>

                { else}

                        <td width="20%">##surname fragment##
                        </td>

                {/if}

                        <td width='10%'>
                                <select class="normal" name="f2bool">
                                        <option value='AND'>##AND##</option>
                                        <option value='OR'>##OR##</option>
                                </select>
                        </td>
            <td><input name="surn" type="text" class="normal" id="surn" size="30" maxlength="255"  />
                        </td>
                </tr>
                <tr class="normal">
                        <td>##born between##</td>
                        <td width='10%'>
                                <select class="normal" name="f3bool">
                                        <option value='AND'>##AND##</option>
                                        <option value='OR'>##OR##</option>
                                </select>
                        </td>
            <td>{$mydata.birt_date_start} ##and## {$mydata.birt_date_end}
                        </td>
                </tr>
                <tr class="normal">
                        <td>##died between##</td>
                        <td width='10%'>
                                <select class="normal" name="f4bool">
                                        <option value='AND'>##AND##</option>
                                        <option value='OR'>##OR##</option>
                                </select>
                        </td>
            <td>{$mydata.deat_date_start} ##and## {$mydata.deat_date_end}
                        </td>
                </tr>
                <tr class="normal">
                        <td>##born on this day##
                        </td>
                        <td width='10%'>
                                <select class="normal" name="f5bool">
                                        <option value='AND'>##AND##</option>
                                        <option value='OR'>##OR##</option>
                                </select>
                        </td>
            <td>{$mydata.born_on_day}</td>
                </tr>
                <tr class="normal">
                        <td>##born during this month##</td>
                        <td width='10%'>
                                <select class="normal" name="f6bool">
                                        <option value='AND'>##AND##</option>
                                        <option value='OR'>##OR##</option>
                                </select>
                        </td>
            <td>{$mydata.born_during_month}</td>
                </tr>
                <tr class="normal">

                {if !$mydata.animalPedigree}

                        <td>##occupation fragment##</td>
                        <td width='10%'>
                                <select class="normal" name="f7bool">
                                        <option value='AND'>##AND##</option>
                                        <option value='OR'>##OR##</option>
                                </select>
                        </td>
            <td><input name="occu" type="text" class="normal" id="occu" size="30"  maxlength="255" />
              (e.g. medic)</td>

                { else}

                        <td>##breed fragment##</td>
                        <td width='10%'>
                                <select class="normal" name="f8bool">
                                        <option value='AND'>##AND##</option>
                                        <option value='OR'>##OR##</option>
                                </select>
                        </td>
            <td><input name="bred" type="text" class="normal" id="bred" size="30"  maxlength="255" />
                        </td>

                {/if}


                </tr>
                <tr class="normal">
                        <td>##gender##</td>
                        <td width='10%'>
                                <select class="normal" name="f9bool">
                                        <option value='AND'>##AND##</option>
                                        <option value='OR'>##OR##</option>
                                </select>
                        </td>
           		 <td><select name="sex" class="normal" id="sex">
                                        <option value="" selected="selected">--</option>
                                        <option value="M">##Male##</option>
                                        <option value="F">##Female##</option>
                                </select>
                        </td>
                </tr>
                <tr class="normal">

                {if !$mydata.animalPedigree}

                        <td>##burial place fragment##</td>
                        <td width='10%'>
                                <select class="normal" name="f10bool">
                                        <option value='AND'>##AND##</option>
                                        <option value='OR'>##OR##</option>
                                </select>
                        </td>
                        <td><input name="buri_plac" type="text" class="normal" id="buri_plac" size="30"  maxlength="255"  />
                        </td>

                { else}

                        <td>##cause of death##</td>
                        <td width='10%'>
                                <select class="normal" name="f11bool">
                                        <option value='AND'>##AND##</option>
                                        <option value='OR'>##OR##</option>
                                </select>
                        </td>
                        <td><input name="cdea" type="text" class="normal" id="cdea" size="30" maxlength="255" />
                        </td>

                {/if}

                </tr>
                <tr class="normal">
                        <td>##only those with portraits##</td>
                        <td width='10%'>
                                <select class="normal" name="f12bool">
                                        <option value='AND'>##AND##</option>
                                        <option value='OR'>##OR##</option>
                                </select>
                        </td>
            <td><input name="portrait" type="checkbox" id="portrait" value="1" />
            </td>
                </tr>
          <tr class="normal">
                        <td>##only those with biographies##</td>
                        <td width='10%'>
                                <select class="normal" name="f13bool">
                                        <option value='AND'>##AND##</option>
                                        <option value='OR'>##OR##</option>
                                </select>
                        </td>
            <td><input name="bio" type="checkbox" id="bio" value="1" /></td>
                </tr>
                <tr class="normal">
                        <td>##number of results to return##
                        </td>
            <td><select name="limit" class="normal" id="limit">
                        <option value="9999999">--</option>
                        <option value="10">10</option>
                        <option value="100" selected="selected">100</option>
                        <option value="500">500</option>
                        <option value="1000">1000</option>
                        <option value="5000">5000</option>
                      </select>
             </td>
           </tr>
          <tr class="normal">
                        <td>&nbsp;</td><td></td>
            <td><input type="submit" name="Submit" value="##Search##" class="normal" />
            <input name="cancel" type="button" class="normal" id="cancel" onclick="javascript:window.location='load.php?ID={$mydata.ID}';" value="##Cancel##" />
            </td>
         </tr>
          <tr>
             <td class="normal">&nbsp;</td>
          </tr>
          <tr class="normal">
                        <td>##Save this search as:##
                        </td>
             <td colspan="2"><input name="searchname" type="text" class="normal" id="searchname" size="30" maxlength="255" />&nbsp;&nbsp;
                             <input type="submit" name="savesearch" value="##Save##" class="normal" />
             </td>
          </tr>
{if $saved_searches}
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td class="normal" colspan="3"><b>##Saved Searches:##</b></td>
          </tr>
          {foreach from=$saved_searches item=value}
          <tr>
            <td class="normal" colspan="3"><a href="searchResults.php?searchid={$value.searchid}&amp;ID={$value.ID}">{$value.searchname}</a>  | <a href="search.php?rmsearch={$value.searchid}&amp;ID={$value.ID}">##Remove##</a></td>
          </tr>
          {/foreach}
{/if}
        </table>

        <input type="hidden" name="ID" value="{$mydata.ID}" />
</form>
<script type="text/javascript">
        var inmenu={if $smarty.session.admin}5{else}2{/if};
</script>