{* This file loads information about the family and/or individual *}
{* This is the templa<span style="background : url('images/{$smarty.session.templateID}/portrait/frameTopRepeat.gif');"><img  alt=""  src="images/{$smarty.session.templateID}/portrait/frameTopLeft.gif" width="66" height="21" /></span>te file for load.php       Vijay Nair  25/3/2004 *}

<table width="660" border="0" cellspacing="0" cellpadding="4">
        <tr>
                <td width="58%" height="200" valign="top">
                        <table border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                        <td width="60%" class="normal">
                                                {* First display Mother Information  *}
                                                {if $mydata.motherID != ''}
                                                  <a class='pinkNormal' href="load.php?ID={$mydata.motherID}">
                                                  {if !$mydata.animalPedigree}
                                                          ##Mother##:
                                                  { else}
                                                          ##Dam##:
                                                  {/if}
                                                  {$mydata.motherName|trim}</a>

                                                  {if !$smarty.session.read_only || $smarty.session.my_rec == $mydata.ID}

                                                          (<a class="pinkNormal" href="edit.php?ID={$mydata.ID}&amp;personID={$mydata.ID}&amp;chparent=1&amp;personType=mother">##Change##</a>)
                                                   | 
                                                  {/if}
                                                {else}
                                                        {if !$smarty.session.read_only or $smarty.session.my_rec == $mydata.ID}

                                                                <a class='pinkNormal' href="edit.php?sex=f&amp;new=1&amp;personID={$mydata.ID}&amp;personType=mother ">

                                                                {if !$smarty.session.edit_only}

                                                                        {if !$mydata.animalPedigree}
                                                                                ##Add Mother Information##</a>
                                                                        { else}
                                                                                ##Add Dam Information##</a>
                                                                        {/if}
                                                                {/if}
                                                                | 
                                                        {/if }
                                                {/if}

                                                {* Now father information *}
                                                {  if $mydata.fatherID}

                                                        <a class="blueNormal" href="load.php?ID={$mydata.fatherID} ">

                                                        {if !$mydata.animalPedigree}
                                                                ##Father##:
                                                        { else}
                                                                ##Sire##:
                                                        {/if}

                                                        {$mydata.fatherName|trim}</a>

                                                        {if !$smarty.session.read_only || $smarty.session.my_rec == $mydata.ID}

                                                                (<a class="blueNormal" href="edit.php?ID={$mydata.ID}&amp;personID={$mydata.ID}&amp;chparent=1&amp;personType=father ">##Change##</a>)

                                                        { else}

                                                                &nbsp;

                                                        {/if}
                                                { else}
                                                        {if !$smarty.session.read_only or $smarty.session.my_rec == $mydata.ID}

                                                                <a class="blueNormal" href="edit.php?sex=m&amp;new=1&amp;personID={$mydata.ID}&amp;personType=father ">
                                                                {if !$smarty.session.edit_only}
                                                                        {if !$mydata.animalPedigree}
                                                                                ##{$smarty.session.read_only}Add Father Information##
                                                                        { else}
                                                                                ##Add Sire Information##
                                                                        {/if}
                                                                {/if}
                                                                </a>
                                                        { /if }
                                                {/if}
                                        </td>
                                </tr>
                                <tr>
                                  <td class="normal">&nbsp;</td>
                                </tr>
                        </table>
                        {* end of parent information section  *}
                        {* Now print the Name/Family Name  *}
                        <span class="title">{$mydata.myname} {$mydata.myfamily}</span>

                        <span class="normal"><br />

                        {if !$mydata.deat_date || $mydata.deat_date == "0000-00-00"}
                                {if $mydata.birt_date }
                                        {$mydata.birt_date}<br />
                                {/if}
                        { else}
                                {if $mydata.birt_date }

                                        ({$mydata.birt_date} - {$mydata.deat_date})

                                { else}

                                        (? - {$mydata.deat_date})

                                {/if}

                                <br />

                        {/if}
                        {*  determine famc and fams for this individual so that we can
                        include it in the edit record GET string  *}

                        {if !$smarty.session.read_only || $smarty.session.my_rec == $mydata.ID}

                                <a href="edit.php?ID={$mydata.ID} ">##Edit this Record##</a><br /><br />

                        {/if}

                         {* Now print the Birth Place details *}
                        {if $mydata.birt_plac && !$mydata.animalPedigree}

                                ##Birth Place##: {$mydata.birt_plac} <br />

                        {/if}

                        {if  $mydata.bred}

                                ##Breed##: {$mydata.bred}<br />

                        {/if}

                        {if $mydata.cdea}

                                ##Cause of death##: {$mydata.cdea}<br />

                        {/if}

                        ##Gender##: {$mydata.genderString}<br />
                        <a href="javascript:launch('getRelation.php?ID={$mydata.ID}','width=300,height=300,top=10,left=10');" target="popup"></a>

                        {if $mydata.mybio != ""}

                                <a href="getbio.php?ID={$mydata.ID} ">##Download Biography##</a> | <a href="upload.php?ID={$mydata.ID}">##Edit##</a><br />

                        { else if !$smarty.session.read_only || $smarty.session.my_rec == $mydata.ID}
                                {if !$smarty.session.edit_only && !$smarty.session.read_only}

                                        <a href="upload.php?ID={$mydata.ID} ">##Add a Biography for##&nbsp;{$mydata.myname}</a><br />

                                {/if}
                        {/if}

                        <!-- a href="javascript:launch( 'moreinfo.php?ID={$mydata.ID}', 'width=300,height=400,top=10,left=10');" -->
                        <a href="moreinfo.php?ID={$mydata.ID}">##View More Information About##&nbsp;{$mydata.myname}</a><br />

                        <a href="famgal.php?ID={$mydata.ID}&amp;view=1">##Image Gallery##</a> {$mydata.gallerycnt1}<br />
                        <a href="links.php?ID={$mydata.ID} ">##My Links##</a> {$mydata.gallerycnt2}<br />

                        {* Now show the portrait  *}
                        <br />
                        </span>
                </td>
                <td width="42%" valign="top">
                        <table width="10" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                        <td colspan="3">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                                <td width="38%" valign="top" style="background : url('images/{$smarty.session.templateID}/portrait/frameTopRepeat.gif');" align="left"><img  alt=""  src="images/{$smarty.session.templateID}/portrait/frameTopLeft.gif" width="71" height="21" /></td>
                                                                <td width="62%" valign="top" align="right" style="background : url('images/{$smarty.session.templateID}/portrait/frameTopRepeat.gif');"><img  alt=""  src="images/{$smarty.session.templateID}/portrait/frameTopRight.gif" width="71" height="21" /></td>
                                                        </tr>
                                                </table>
                                        </td>
                                </tr>
                                <tr>
                                        <td width="14%" valign="top" style="background : url('images/{$smarty.session.templateID}/portrait/frameLeftRepeat.gif');"><img  alt=""  src="images/{$smarty.session.templateID}/portrait/frameLeftTop.gif" width="24" height="58" /></td>
										
										{* This section inserts portrait into picture frame *}
											<td width='70%' rowspan='2' align="center" valign="middle">
											{if !$smarty.session.read_only || $smarty.session.my_rec == $mydata.ID}
											<a href="upload.php?ID={$mydata.ID}">
											{/if}
												{if $mydata.portrait != ''}
												<img src="{$mydata.portrait}" alt='' border='0' align="middle" />
												{else}
													{if !$smarty.session.read_only || $smarty.session.my_rec == $mydata.ID}
													<img src="images/{$smarty.session.templateID}/portrait/no_portrait.gif" alt='' border='0' align="middle" />
													{else}
													<img src="images/{$smarty.session.templateID}/portrait/read_only_no_portrait.gif" alt='' border='0' align="middle" />
													{/if}
												{/if}
											{if !$smarty.session.read_only || $smarty.session.my_rec == $mydata.ID}</a>{/if}</td>
										{* End of portrait insert section *}
										                                        <td width="16%" valign="top" style="background : url('images/{$smarty.session.templateID}/portrait/frameRightRepeat.gif');"><img  alt=""  src="images/{$smarty.session.templateID}/portrait/frameRightTop.gif" width="27" height="58" /></td>
                                </tr>
                                <tr>
                                        <td valign="bottom" style="background : url('images/{$smarty.session.templateID}/portrait/frameLeftRepeat.gif');"><img  alt=""  src="images/{$smarty.session.templateID}/portrait/frameLeftBottom.gif" width="24" height="59" /></td>
                                        <td width="16%" valign="bottom" style="background : url('images/{$smarty.session.templateID}/portrait/frameRightRepeat.gif');"><img  alt=""  src="images/{$smarty.session.templateID}/portrait/frameRightBottom.gif" width="27" height="59" /></td>
                                </tr>
                                <tr>
                                        <td colspan="3">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                                <td width="42%" height="24" align="left" valign="top" style="background : url('images/{$smarty.session.templateID}/portrait/frameBottomRepeat.gif');"><img  alt=""  src="images/{$smarty.session.templateID}/portrait/frameBottomLeft.gif" width="66" height="24" /></td>
                                                                <td width="58%" align="right" valign="top"  style="background : url('images/{$smarty.session.templateID}/portrait/frameBottomRepeat.gif');" ><img  alt="" src="images/{$smarty.session.templateID}/portrait/frameBottomRight.gif" width="71" height="24" /></td>
                                                        </tr>
                                                </table>
                                        </td>
                                </tr>
                        </table>
                </td>
        </tr>
        <tr>
                <td colspan="2" valign="top">
                        <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                <tr>
                                        <td width="75%" colspan="2" valign="top">
                                                <span class="spouses">
                                                	{if $animalPedigree}##Mates##{else}##Life Partners##{/if}<br />
                                                </span>

                                                {* check to make sure that we are not in read-only mode  *}
                                                {if !$smarty.session.read_only || $smarty.session.my_rec == $mydata.ID}

                                                        <a href="edit.php?sp1={$mydata.ID}&amp;personID={$mydata.ID}&amp;personType=partner ">

                                                        {if !$smarty.session.edit_only}
																				{if $animalPedigree}##Add New Mate##{else}##Add New Spouse or Life Partner##{/if}
                                                        {/if}
                                                        </a>

                                                {/if}
                                                  {* display the Spouse's information *}
                                            {if $spouse_recs}
                                                <table border="0" cellpadding="4" cellspacing="0" width="100%" >

                                                {foreach from=$spouse_recs key=key item=spouserec}

                                                        {if !$mydata.animalPedigree}

                                                                <tr>
                                                                        <td valign="top" width="35%">
                                                                                <a class="{$spouserec->link}" href="load.php?ID={$spouserec->spouseID} ">
                                                                                {$spouserec->spouseName} {$spouserec->spouseSurname}</a>
                                                                        </td>
                                                        { else}
                                                                <tr>
                                                                        <td valign="top" width="35%">
                                                                                <a class="{$spouserec->link}" href="load.php?ID={$spouserec->spouseID} ">
                                                                                {$spouserec->spouseName} "{$spouserec->spouseSurname}"</a>
                                                                        </td>

                                                        {/if}
                                                                        <td valign="top" width="25%">
                                                                        {if $spouserec->marriagePlace != '' ||  $spouserec->marriageDate != ''}
                                                                                <span class="normal">
                                                                                {if $spouserec->marriageDate != ''}
                                                                                        ##Date##: {$spouserec->marriageDate}<br />
                                                                                {/if}
                                                                                {if $spouserec->marriagePlace != ''}
                                                                                        ##Place##: {$spouserec->marriagePlace}
                                                                                {/if}
                                                                                </span>
                                                                        {/if}
                                                                        {if $spouserec->testD == true}
                                                                                <span class="normal">(##Divorced##)<br /></span>
                                                                        {else}
                                                                         &nbsp;
                                                                        { /if }
                                                                        {if !$smarty.session.read_only || $smarty.session.my_rec == $mydata.ID}
                                                                  </td>
                                                                                <td align="left" width="100%">
                                                                                <a class="{$spouserec->link}" href="editMarriage.php?sp1={$mydata.ID}&amp;sp2={$spouserec->spouseID}&amp;ID={$mydata.ID}">
                                                                                ##Edit##</a>
                                                                        {/if}
                                                                        {if $spouserec->dis != ''}
                                                                                {if !$smarty.session.read_only || $smarty.session.my_rec == $mydata.ID}
                                                                                        <font class="normal"> | </font>
                                                                                        <a class="{$spouserec->link}" href="dismar.php?ID={$mydata.ID}&amp;s1={$mydata.ID}&amp;s2={$spouserec->spouseID} ">##Dissolve##</a>
                                                                                {/if}
                                                                        {/if}
                                                                                </td>
                                                                </tr>
                                                {/foreach}

                                                </table>
                                            {/if}

                                                {if $mydata.spouses_count == 0 && $smarty.session.read_only || $smarty.session.my_rec == $mydata.ID}

                                                        <span class="normal">##No spousal or partner information present##.</span>
                                                { /if }
                                        </td>
                                </tr>
                        </table>
                </td>
        </tr>
        <tr class="normal">
                <td colspan="2">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                        <td width="33%" align="left" valign="top">
                                          <span class="sons">
                                            {if $animalPedigree}
                                                ##Male Offspring##
                                              {else}
                                                ##Sons##
                                            {/if}
                                          </span><br />

                                          {if !$smarty.session.read_only || $smarty.session.my_rec == $mydata.ID}
                                            <a class="blueNormal" href="edit.php?sex=m&amp;personID={$mydata.ID}&amp;personType=son ">
                                              {if !$smarty.session.edit_only}
                                                {if $animalPedigree}##Add New Male Offspring##{else}##Add New Son##{/if}
                                              {/if}
                                            </a>
                                          {/if}
                                        </td>
                                        <td width="33%" align="left" valign="top">
                                          <span class="daughters">
                                            {if $animalPedigree}
                                                ##Female Offspring##
                                              {else}
                                                ##Daughters##
                                            {/if}
                                          </span><br />
                                          {if !$smarty.session.read_only || $smarty.session.my_rec == $mydata.ID}
                                                  <a class="pinkNormal" href="edit.php?sex=f&amp;personID={$mydata.ID}&amp;personType=daughter ">
                                                  {if !$smarty.session.edit_only}
          {if $animalPedigree}##Add New Female Offspring##{else}##Add New Daughter##{/if}
                                                  {/if}
                                                  </a>
                                          { /if }
                                        </td>
                                        <td width="34%" align="left" valign="top">
                                                <span class="sons">##Siblings##</span><br />
                                                {if !$smarty.session.read_only || $smarty.session.my_rec == $mydata.ID}
                                                        {if !$smarty.session.edit_only}
                                                                {if $mydata.siblings_cnt == 0}
                                                                        <a href='#' onclick='launchCentered("addSiblingInfo.php", "200", "200", "resizable=1, scrollbars=1")'>##Add New Sibling##</a>
                                                                { else}
                                                                        <a class="blueNormal" href="edit.php?personID={$mydata.ID}&amp;personType=sibling ">##Add New Sibling##</a>
                                                                {/if}
                                                        { /if }
                                                {/if}
                                        </td>
                                </tr>
                                <tr>
                                      <td width="33%" valign="top">
                                          {if $sons_recs}
                                              <table border="0" cellpadding="2" cellspacing="0">
                                                {foreach from=$sons_recs key=key item=son_rec}
                                                  <tr>
                                                    <td>
                                                      <a class="blueNormal" href="load.php?ID={$son_rec->sonID}">{$son_rec->sonName|trim}</a><br />
                                                      {if $son_rec->birt_date != ""}
                                                        <span class="normalSmall">{$son_rec->birt_date}<br /></span>
                                                      {/if}
                                                      {if $son_rec->birt_plac != ""}
                                                        <span class="normalSmall">{$son_rec->birt_plac}</span>
                                                      {/if}
                                                    </td>
                                                    <td>
                                                      {if !$smarty.session.read_only or $smarty.session.my_rec == $mydata.ID}
                                                        <a class="remove-button" href="edit.php?action=dropChildren&parentID={$mydata.ID}&childID={$son_rec->sonID}&returnID={$mydata.ID}"><img src="images/b_drop.png" alt="remove"></a>
                                                      {/if}
                                                    </td>
                                                  </tr>
                                                {/foreach}
                                              </table>
                                          {/if}

                                              {if $mydata.sons_cnt == 0}
            {if $animalPedigree}
                                                      <span class="normal">{$mydata.myname}&nbsp;##has no known male offspring##.</span>
                                                     {else}
                                                     <span class="normal">{$mydata.myname}&nbsp;##has no known sons##.</span>
                                                    {/if}

                                              {/if}

                                      </td>
                                        <td width="33%" valign="top">
                                            {if $daughters_recs}
                                                <table border="0" cellpadding="2" cellspacing="0">

                                                        {foreach from=$daughters_recs key=key item=daughter_rec}

                                                                <tr>
                                                                        <td><a class="pinkNormal" href="load.php?ID={$daughter_rec->daughterID} ">{$daughter_rec->daughterName|trim}</a><br />
                                                                       
										
										{if $daughter_rec->birt_date != ""}
											<span class="normalSmall">{$daughter_rec->birt_date}</span><br />
										{/if}
										{if $daughter_rec->birt_plac != ""}
											<span class="normalSmall">{$daughter_rec->birt_plac}</span>
										{ /if}
										</span>
                                                                        </td>
                                                                        <td>
                                                                          {if !$smarty.session.read_only or $smarty.session.my_rec == $mydata.ID}
                                                                            <a class="remove-button" href="edit.php?action=dropChildren&parentID={$mydata.ID}&childID={$daughter_rec->daughterID}&returnID={$mydata.ID}"><img src="images/b_drop.png" alt="remove"></a> 
                                                                          {/if}
                                                                        </td>
                                                                </tr>

                                                        {/foreach}
                                                </table>
                                            {/if}

                                                {if $mydata.daughters_cnt == 0}
																		{if $animalPedigree}
                                                        <span class="normal">{$mydata.myname}&nbsp;##has no known female offspring##.</span>
                                                       {else}
                                                       <span class="normal">{$mydata.myname}&nbsp;##has no known daughters##.</span>
                                                      {/if}
                                                {/if}

                                        </td>
                                        <td width="34%" valign="top">
                                         {if $siblings_recs}
                                                <table border="0" cellpadding="2" cellspacing="0">

                                                        {foreach from=$siblings_recs key=key item=sibling}

                                                                <tr>
                                                                        <td><span class="normalSmall">
                                                                                <a {$sibling->siblingClassString} href="load.php?ID={$sibling->siblingID} ">{$sibling->siblingName}</a> {$sibling->halfString}<br />

                                                                                {if $sibling->birt_date != ""}
                                                                                        {$sibling->birt_date}<br />
                                                                                {/if}
                                                                                {if $sibling->birt_plac != ""}
                                                                                        {$sibling->birt_plac}
                                                                                { /if}

                                                                                </span>
                                                                        </td>
                                                                        <td>
                                                                          {if !$smarty.session.read_only or $smarty.session.my_rec == $mydata.ID}
                                                                            <a class="remove-button" href="edit.php?action=dropSibling&parentID={$mydata.ID}&siblingID={$sibling->siblingID}&returnID={$mydata.ID}"><img src="images/b_drop.png" alt="remove"></a>
                                                                          {/if}
                                                                        </td>
                                                                </tr>

                                                        {/foreach}

                                                </table>
                                        {/if}
                                                {if $mydata.siblings_cnt == 0}

                                                        <span class="normal">{$mydata.myname}&nbsp;##has no known siblings##.</span>

                                                {/if}
                                        </td>
                                </tr>
                        </table>
                </td>
        </tr>
</table>
<script type="text/javascript">
        var inmenu=0;
</script>


{* Do NOT Uncomment this section *}
{*  This text is passed by PHP, included here to aid the lang editor
##Unknown##
##Male## ##Female##
##b. $birt_date##
##b. Date: $birt_date##
##b. Place: $birt_plac##
*}