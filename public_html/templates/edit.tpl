{* This file is the main edit.tpl file *}

<script type="text/php">
function checkHtml($d)
{ldelim}
        if (strip_tags($d) != $d) {ldelim}
                alert("##HTML tags are not allowed##");
                return false;
        {rdelim}
        return true;
{rdelim}
</script>
<table width="100%" border="0" cellspacing="0" cellpadding="5">
        <tr>
                <td colspan="2" class="title">
                    	{if !$mydata.new}
                    		{if $mydata.edit}
                    			{if $animalPedigree}##Edit Data for## {$mydata.name}{else}##Edit Personal Data for## {$mydata.name} {$mydata.surn}{/if} 
                    		{else}
                             {if $mydata.personType == "partner"}
                                 {if $animalPedigree}##Add new Mate##{else}##Add new Partner##{/if}
                             {elseif $mydata.personType == "son"}
                             		{if $animalPedigree}##Add new Male offspring##{else}##Add new Son##{/if}
                             {elseif $mydata.personType == "daughter"}
                             		{if $animalPedigree}##Add new Female offspring##{else}##Add new Daughter##{/if}
                             {elseif $mydata.personType == "sibling"}
                             		##Add new Sibling##
                             {/if}
                        {/if}
                     { else}
                             {$mydata.createtree_text}
                             <span class="subtitle"> <br />
                             {if $mydata.personType == "mother"}
	                             {if $animalPedigree}##Add new Dam##{else}##Add new Mother##{/if}
	                          {elseif $mydata.personType == "father"}
		                          {if $animalPedigree}##Add new Sire##{else}##Add new Father##{/if}
		                        {/if} 
                             </span>
                     {/if}
                     {if $mydata.err}
                             <br /><font color=red size="1">{$mydata.err}</font><br />
                     {/if}
                </td>
        </tr>
        <tr>
                <td colspan="2" class="normalSmall" >
                        ##it_is_strongly##
                </td>
        </tr>
        {if $mydata.personType == "partner" || $mydata.personType == "son" || $mydata.personType == "daughter" || $mydata.personType == "mother" || $mydata.personType == "father" || $mydata.personType == "sibling"}
                <tr>
                        <td colspan="2" class="normal">&nbsp;
                                <br />
                                <span class="normalBold">##You may choose either:##</span><br />
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                ##Option A:##&nbsp;##choose an existing##
					               {if $mydata.personType == "partner"}
					                	{if $animalPedigree}##animal as the mate##{else}##person as the partner##{/if},
					               {elseif $mydata.personType == "father"}
					                	{if $animalPedigree}##animal as the Sire, or##{else}##person as Father##{/if},
					               {elseif $mydata.personType == "mother"}
					                	{if $animalPedigree}##animal as the Dam, or##{else}##person as Mother##{/if},
					               {elseif $mydata.personType == "son"}
					                	{if $animalPedigree}##animal as the male offspring##{else}##person as the son##{/if},
					               {elseif $mydata.personType == "daughter"}
					               	{if $animalPedigree}##animal as the female offspring##{else}##person as the daughter##{/if},
					               {elseif $mydata.personType == "sibling"}
					               	{if $animalPedigree}##animal as the sibling##{else}##person as the sibling##{/if},
					               {/if}&nbsp;##or##<br />
					               
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                ##Option B:##&nbsp;##create a new##
					               {if $mydata.personType == "partner"}
					                	{if $animalPedigree}##animal as the mate##{else}##individual as the partner##{/if}
					               {elseif $mydata.personType == "father"}
					                	{if $animalPedigree}##animal as the Sire, or##{else}##individual as Father##{/if}
					               {elseif $mydata.personType == "mother"}
					                	{if $animalPedigree}##animal as the Dam, or##{else}##individual as Mother##{/if}
					               {elseif $mydata.personType == "son"}
					                	{if $animalPedigree}##animal as the male offspring##{else}##individual as the son##{/if}
					               {elseif $mydata.personType == "daughter"}
					               	{if $animalPedigree}##animal as the female offspring##{else}##individual as the daughter##{/if}
					               {elseif $mydata.personType == "sibling"}
					               	{if $animalPedigree}##animal as the sibling##{else}##individual as the sibling##{/if}
					               {/if}<br />
                                <br />
                                <form action="edit.php" method="post" name="partnerSelectForm">
                                        <table border="0" cellpadding="2" cellspacing="0" class='optionBack' >
                                        <tr>
                                                <td colspan="2" class="subtitle">
                                                        ##Option A##...
                                                </td>
                                        </tr>
                                        <tr>
                                                <td colspan="2">
                                                   <span class="normal">
	                                                  ##If this##
	                                                  {if $mydata.personType == "partner"}
		                                                   {if $animalPedigree}##mate##{else}##partner##{/if}
		                                               {elseif $mydata.personType == "father"}
			                                                {if $animalPedigree}##sire##{else}##father##{/if}
		                                               {elseif $mydata.personType == "mother"}
		                                               		{if $animalPedigree}##dam##{else}##mother##{/if}
		                                               {elseif $mydata.personType == "son"}
		                                               		{if $animalPedigree}##male offspring##{else}##son##{/if}
		                                               {elseif $mydata.personType == "daughter"}
		                                               		{if $animalPedigree}##female offspring##{else}##daughter##{/if}
		                                               {elseif $mydata.personType == "sibling"}
		                                               		##sibling##
		                                               {/if}
								##is already present in the family database, then please select the##
									{if $animalPedigree}##individual##{else}##person##{/if}
								##from this list##:
                                                   </span>
                                                </td>
                                        </tr>
                                        <tr>
                                                <td colspan="2">
                                                        <select name="ID" class="normal">
                                                                { html_options options="$spouse_selectlist"}
                                                        </select><input type="button" name="submitButton" class="normal" value="##OK##" onclick="javascript:validateSpouseSelection(document.partnerSelectForm);" />
                                                </td>
                                        </tr>
                                        </table>
                                        <input type="hidden" name="submitForm" value="" />
                                        <input type="hidden" name="personType" value="{$mydata.personType}" />
                                        <input type="hidden" name="mimik" value="1" />
                                        <input type="hidden" name="personID" value="{$mydata.personID}" />
                                        <input type="hidden" name="sp1" value="{$mydata.sp1}" />
                                </form>
                        </td>
                </tr>
{*                <tr>
                        <td colspan="2">
                                <img  alt="" src="images/{$smarty.session.templateID}/spacer.gif" height="5" />
                        </td>
                </tr>  *}
                <tr>
                        <td colspan="2" class="subtitle"><br />
                                ##Option B##...
                        </td>
                </tr>
        {/if}
</table>
<form method="post" name="theForm" action="edit.php">
<table width="100%" border="0" cellspacing="0" cellpadding="3">

        {if $mydata.new != 1 && $mydata.hide == "Yes" && !isset($smarty.session.admin)}

                <tr>
                        <td width="30%" colspan="2"  class="normal">
                                <font size="4" class="normal"><b>
                                ##hid_indi##</b>
                                </font>
                        </td>
                </tr>

        {/if}

        {  if $mydata.new ==1 || $mydata.hide != "Yes" || isset($smarty.session.admin)}
                <tr>
                        <td colspan="2">
                                <input name="mySubmit" type="button" class="normal" onclick="javascript:validate(document.theForm);" value="##Save Information##" />
                                <input name="cancel" type="button" class="normal" onclick="javascript:window.location='load.php?ID={$mydata.ID}';" value="##Cancel##" />
                        </td>
                </tr>
                <tr>
                   <td colspan="2">&nbsp; {if $bd_str}<font color="Red">{$bd_str}</font>{/if}</td>
                </tr>
                <tr>
                        <td colspan="2" class="subtitle">
                                ##Essential Information##
                        </td>
                </tr>
                <tr>
                        <td width="30%"  class="normal">{if $animalPedigree}##Registered Name##{else}##Given names (first &amp; middle)##{/if}</td>
                        <td width="433"  class="normal">
                                <input name="name" class="normal" size="25" maxlength="100" type="text" value="{$mydata.name}" />
                                &nbsp;{$gname_eg}
                        </td>
                </tr>
                <tr>
                        <td  class="normal">{if $animalPedigree}##Call Name##{else}##Surname or Family Name##{/if}</td>
                        <td  class="normal">
                                <input name="surn" class="normal" size="25" maxlength="50" type="text" value="{$mydata.surn}" />
                        </td>
                </tr>
                <tr>
                        <td  class="normal">##Gender##</td>
                        <td  class="normal">
                                {if $mydata.sex}
                                        {if $mydata.sex == 'M' || $mydata.sex == 'm'}
                                                <input type="hidden" checked="checked" name="sex" value="M" />##Male##
                                        { else}
                                                <input type="hidden" checked="checked" name="sex" value="F" />##Female##
                                        {/if}
                                {else}
                                        <input type="radio" name="sex" {$mydata.sm} value="M" /> ##Male## &nbsp;
                                        <input type="radio" name="sex" {$mydata.sf} value="F" /> ##Female##
                                {/if}

                        </td>
                </tr>
                <tr>
                        <td  class="normal">##Date of Birth##</td>
                        <td  class="normal">{$mydata.birth_date_data_field}</td>
                </tr>
                <tr>
                        <td  class="normal">##Date of Death##</td>
                        <td  class="normal">{$mydata.death_date_data_field}</td>
                </tr>

                {if $mydata.animalPedigree } {* animal check for birth etc. details  *}

                        <tr>
                                <td  class="normal">##cause of death##</td>
                                <td  class="normal">
                                        <input name="cdea" class="normal" size="25" maxlength="74" type="text" value="{$myvars.cdea}" />
                                </td>
                        </tr>
                        <tr>
                                <td  class="normal">##Owner##</td>
                                <td  class="normal">
                                        <input name="ownr" class="normal" size="25" maxlength="74" type="text"  value="{$myvars.ownr}" />
                                </td>
                        </tr>
                        <tr>
                                <td  class="normal">##Registration## #1</td>
                                <td  class="normal">
                                        <input name="rega_numb" class="normal" size="25" maxlength="230" type="text" value="{$myvars.rega_numb}" />
                                        ##Location##
                                        <input name="rega_plac" class="normal" size="25" maxlength="230" type="text" value="{$myvars.rega_plac}" />
                                </td>
                        </tr>
                        <tr>
                                <td class="normal">##Registration## #2</td>
                                <td class="normal">
                                        <input name="regb_numb" class="normal" size="25" maxlength="230" type="text" value="{$myvars.regb_numb}" />
                                        ##Location##
                                        <input name="regb_plac" class="normal" size="25" maxlength="230" type="text" value="{$myvars.regb_plac}" />
                                </td>
                        </tr>
                        <tr>
                                <td  class="normal">##Registration## #3</td>
                                <td  class="normal">
                                        <input name="regc_numb" class="normal" size="25" maxlength="230" type="text" value="{$myvars.regc_numb}" />
                                        ##Location##
                                        <input name="regc_plac" class="normal" size="25" maxlength="230" type="text" value="{$myvars.regc_plac}" />
                                </td>
                        </tr>
                        <tr>
                                <td  class="normal">##Breeder##</td>
                                <td  class="normal">
                                        <input name="brdr" class="normal" size="25" maxlength="74" type="text" id="brdr" value="{$myvars.brdr}" />
                                </td>
                        </tr>
                        <tr>
                                <td  class="normal">##Breed##</td>
                                <td  class="normal">
                                        <input name="bred" class="normal" size="25" maxlength="74" type="text" id="bred" value="{$myvars.bred}" />
                                </td>
                        </tr>

                { else} {* human checking for birth/death  start *}

                        <tr>
                                <td  class="normal">##Place of Birth## </td>
                                <td  class="normal">
                                        <input name="birt_plac" class="normal" size="25" maxlength="74" type="text" value="{$myvars.birt_plac}" />
                                        ##(e.g. Knoxville, TN)##
                                </td>
                        </tr>
                        <tr>
                                <td  class="normal">##Place of Death##</td>
                                <td  class="normal">
                                        <input name="deat_plac" class="normal" size="25" maxlength="74" type="text" value="{$myvars.deat_plac}" />
                                        ##(e.g. at Home in Oakland, CA)##
                                </td>
                        </tr>

                        {if isset($smarty.session.admin)}

                                <tr>
                                        <td width="30%"  class="normal">
                                                ##Hide Individual##
                                        </td>
                                        <td width="433"  class="normal">
                                                <input type="checkbox" {if $myvars.hide == 'Yes'} checked="checked" {/if} name="hide" class="normal" />
                                        </td>
                                </tr>

                        {/if}
                        {* if !isset($smarty.session.read_only)}

                                <tr>
                                        <td width="30%"  class="normal">##Death Status (if unknown)##</td>
                                        <td width="433"  class="normal">
                                                <input type="radio"  {if $myvars.dead == "No"} checked="checked"  {/if} name="dead" value='No' />
                                                ##Alive##&nbsp;&nbsp;
                                                <input type="radio"  {if $myvars.dead == "Yes"} checked="checked" {/if} name="dead" value='Yes' />
                                                ##Dead##
                                        </td>
                                </tr>

                        { /if *}

                {/if} {* human checking for birth/death  End *}

                <tr>
                        <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                        <td colspan="2" class="subtitle">
                        	{if $animalPedigree}
	                        	##Owner information (last known)##
	                        {else}
		                        ##Contact information (last known)##
		                     {/if}
                        </td>
                </tr>
                <tr>
                        <td  class="normal">##E-Mail##</td>
                        <td  class="normal">
                                <input name="emai" class="normal" size="25" maxlength="74" type="text" value="{$myvars.emai}" />
                        </td>
                </tr>

                {if $mydata.emailnotify}

                        <tr>
                                <td valign="top" align="right"  class="normal">
                                                <input name="ntfy" type="checkbox" id="notify" value="1" {$mydata.notifyChecked} />
                                </td>
                                <td  class="normal">##E-mail this person any time this record is updated##.</td>
                        </tr>

                { /if }

                <tr>
                        <td  class="normal">##WWW Address##</td>
                        <td  class="normal">
                                <input name="URL" class="normal" size="25" maxlength="74" type="text" id="url" value="{$myvars.url}" />
                        </td>
                </tr>
                <tr>
                        <td  class="normal">##Street Address##</td>
                        <td  class="normal">
                                <input name="addr" class="normal" size="25" maxlength="74" type="text" id="phon22" value="{$myvars.addr}" />
                        </td>
                </tr>
                <tr>
                        <td  class="normal">##City##</td>
                        <td  class="normal">
                                <input name="addr_city" class="normal" size="25" maxlength="74" type="text" id="addr" value="{$myvars.addr_city}" />
                        </td>
                </tr>
                <tr>
                        <td  class="normal">##State##</td>
                        <td  class="normal">
                                <input name="addr_stae" class="normal" size="25" maxlength="74" type="text" id="addr2" value="{$myvars.addr_stae}" />
                        </td>
                </tr>
                <tr>
                        <td class="normal">##Postal Code##</td>
                        <td class="normal">
                                <input name="addr_post" class="normal" size="25" maxlength="74" type="text" id="addr_stae" value="{$myvars.addr_post}" />
                        </td>
                </tr>
                <tr>
                        <td  class="normal">##Country##</td>
                        <td  class="normal">
                                <input name="addr_ctry" class="normal" size="25" maxlength="74" type="text" id="addr_ctry" value="{$myvars.addr_ctry}" />
                        </td>
                </tr>
                <tr>
                        <td  class="normal">##Phone##</td>
                        <td  class="normal">
                                <input name="phon" class="normal" size="25" maxlength="74" type="text" id="phon" value="{$myvars.phon}" />
                        </td>
                </tr>
                <tr>
                        <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                        <td colspan="2">
                                <input name="mySubmit" type="button" class="normal" onclick="javascript:validate(document.theForm);" value="##Save Information##" />
                                <input name="cancel" type="button" class="normal" onclick="javascript:window.location='load.php?ID={$mydata.ID}';" value="##Cancel##" />
                        </td>
                </tr>
                <tr>
                        <td colspan="2">&nbsp;</td>
                </tr>

                {if !$mydata.animalPedigree} {* Human information 03  *}

                        <tr>
                                <td colspan="2" class="subtitle">
                                        ##Additional Name Information##
                                </td>
                        </tr>
                        <tr>
                                <td class="normal">##Name Prefix##</td>
                                <td class="normal">
                                        <input name="npfx" class="normal" size="25" maxlength="230" type="text" id="npfx" value="{$myvars.npfx}" />
                                        ##(e.g. Dr. or Prof.)##
                                </td>
                        </tr>
                        <tr>
                                <td  class="normal">##Surname Prefix##</td>
                                <td  class="normal">
                                        <input name="spfx" class="normal" size="25" maxlength="230" type="text" id="spfx" value="{$myvars.spfx}" /> ##(e.g. Le)##
                                </td>
                        </tr>
                        <tr>
                                <td class="normal">##Nickname##</td>
                                <td class="normal">
                                        <input name="nick" class="normal" size="25" maxlength="230" type="text" id="nick" value="{$myvars.nick}" /> ##(e.g. Bill)##
                                </td>
                        </tr>
                        <tr>
                                <td  class="normal">##Name Suffix##</td>
                                <td  class="normal">
                                        <input name="nsfx" class="normal" size="25" maxlength="230" type="text" id="nsfx" value="{$myvars.nsfx}" /> ##(e.g. III, Jr.)##
                                        
                                </td>
                        </tr>
                        <tr>
                                <td colspan="2">&nbsp;</td>
                        </tr>
                        <tr>
                                <td colspan="2" class="subtitle">
                                        ##Additional Birth and Death Information##
                                </td>
                        </tr>
                        <tr>
                                <td class="normal">##Type of Birth##</td>
                                <td class="normal">
                                        <input name="birt_type" class="normal" size="25" maxlength="74" type="text" id="birt_type" value="{$myvars.birt_type}" />
                                        ##(e.g. Normal, Ceasarian)##
                                </td>
                        </tr>
                        <tr>
                                <td  class="normal">##Address of Birthplace##</td>
                                <td  class="normal">
                                        <input name="birt_addr" class="normal" size="25" maxlength="74" type="text" id="birt_addr" value="{$myvars.birt_addr}" />
                                </td>
                        </tr>
                        <tr>
                                <td  class="normal">##Birth Notes and Citations##
                                </td>
                                <td  class="normal">

                                        {if $mydata.ID > 0 }

                                                <input type="button" onclick='launchCentered("shownotes.php?xtag=BIRT&amp;ID={$mydata.ID}", "400", "300", "resizable=1, scrollbars=1")' value="##Notes##" />
                                                <input type="button" onclick='launchCentered("showsour.php?xtag=BIRT&amp;ID={$mydata.ID}", "480", "400", "resizable=1, scrollbars=1")' value="##Citations##" />

                                        { else}

                                                <input type="button" onclick='alert("##new_sinfo##")' value="##Notes##" />
                                                <input type="button" onclick='alert("##new_sinfo##")' value="##Citations##" />

                                        {/if}

                                </td>
                        </tr>
                        <tr>
                                <td  class="normal">##Cause of Death##</td>
                                <td  class="normal">
                                        <input name="deat_caus" class="normal" size="25" maxlength="74" type="text" id="deat_caus" value="{$myvars.deat_caus}" /> ##(e.g. Cancer)##
                                </td>
                        </tr>
                        <tr>
                                <td  class="normal">##Address of Death Site##</td>
                                <td  class="normal">
                                        <input name="deat_addr" class="normal" size="25" maxlength="74" type="text" id="deat_addr" value="{$myvars.deat_addr}" /> ##(e.g. at Home)##
                                </td>
                        </tr>
                        <tr>
                                <td  class="normal">##Death Notes and Citations##
                                </td>
                                <td  class="normal">

                                        {if $mydata.ID > 0 }

                                                <input type="button" onclick='launchCentered("shownotes.php?xtag=DEAT&amp;ID={$mydata.ID}", "400", "300", "resizable=1, scrollbars=1")' value="##Notes##" />
                                                <input type="button" onclick='launchCentered("showsour.php?xtag=DEAT&amp;ID={$mydata.ID}", "480", "400", "resizable=1, scrollbars=1")' value="##Citations##" />


                                        { else}

                                                <input type="button" onclick='alert("##new_sinfo##")' value="##Notes##" />
                                                <input type="button" onclick='alert("##new_sinfo##")' value="##Citations##" />

                                        {/if}

                                </td>
                        </tr>
                        <tr>
                                <td colspan="2">&nbsp;</td>
                        </tr>
                        <tr>
                                <td height="21" colspan="2" class="subtitle">
                                        ##Life events##
                                </td>
                        </tr>
                        <tr>
                                <td class="normal">
                                        ##Graduation, baptism, military service, retirement, and other life events.##</td>
                                <td>

                                        {if $mydata.ID > 0 }

                                                <input type="button" onclick='launchCentered("tageven.php?ID={$mydata.ID}", "600", "450","scrollbars=1,resizable=1")' value="##Add/Edit Events##" />

                                        { else}

                                                <input type="button" onclick='alert("##new_sinfo_2##")' value="##Add/Edit Events##" />

                                        { /if }

                                </td>
                        </tr>

                { else}  {* Human information 03 End
                                         AnimalPedigree 03 start *}

                        <tr>
                                <td height="21" colspan="2" class="subtitle">
                                        ##Health Information##
                                </td>
                        </tr>
                        <tr>
                                <td height="80" colspan="2" class="subtitle">
                                        <textarea name="hinf" cols="70" rows="5" class="normal" id="hinf">{$myvars.hinf}</textarea>
                                </td>
                        </tr>
                        <tr>
                                <td height="21" colspan="2" class="subtitle">
                                        ##Championship Titles##
                                </td>
                        </tr>
                        <tr>
                                <td height="80" colspan="2" class="subtitle">
                                        <textarea name="ctit" cols="70" rows="5" class="normal" id="ctit">{$myvars.ctit}</textarea>
                                </td>
                        </tr>

                { /if } {* Animal pedigree end *}

                <tr>
                        <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                        <td height="21" colspan="2" class="subtitle">##Notes##</td>
                </tr>
                <tr>
                        <td height="80" colspan="2" class="subtitle">
                                <textarea name="note" cols="70" rows="5" class="normal" id="note">{$myvars.note}</textarea>
                                <input type="hidden" name="chan_date" value="{$mydata.today}" />
                        </td>
                </tr>
                <tr>
                        <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                        <td height="30" colspan="2" class="subtitle">
                                <table width="100%" border="0" cellspacing="0" cellpadding="1">
                                        <tr>
                                                <td width="51%" height="28">
                                                <p><span class="normalSmall">##to_lock##</span></p>
                                                </td>
                                                <td width="59%" valign="top">
                                                        <input name="lock_password" type="text" class="normalSmall" id="lock_password" value="{$mydata.lockPassword}" size="15" maxlength="20" />
                                                        <span class="normalSmall"> ##(20 char max.)##</span>
                                                </td>
                                        </tr>
                                </table>
                        </td>
                </tr>

        {/if}

        <tr>
                <td colspan="2">

                        {if $myvars.hide != "Yes" || isset($smarty.session.admin) }

                                <input
                                {if $myvars.hide != "Yes"}
                                name="mySubmit" onclick="javascript:validate(document.theForm);" type="button"
                                { else}
                                type="submit"
                                {/if}
                                class="normal" value="##Save Information##" />

                        { /if }
                        <input name="cancel" type="button" class="normal" onclick="javascript:window.location='load.php?ID={$mydata.ID}';" value="##Cancel##" />
                        <input name="reset" type="reset" class="normal" id="reset" value="##Reset##" />

                        {if $myvars.hide == "Yes"}

                                <input type="hidden" name="hidsta" value="1" />

                        {/if}

                        {if $mydata.children_count  > 0}

                                <br /><br />
                                <table border="0" cellpadding="0" cellspacing="0">
                                        <tr>
                                                <td>
                                                        <span class="normalSmall">##This record cannot be deleted because the system has found one or more children associated with this record. Its deletion could create a logical inconsistency in the family tree. To delete it, first remove the associated children##
                                                        {$mydata.childIDList}
                                                        </span>
                                                </td>
                                        </tr>
                                </table>

                        { else}

                                <input type="submit" name="delete" class="normal" value="##Delete##" />
                                <br /><br />
                                <table border="0" cellpadding="0" cellspacing="0">
                                        <tr>
                                                <td>
                                                        <span class="normalSmall">##NOTE: Deleting a record will remove ALL references to this person, as well as the biography and photo for the person, if present. After deletion, the next-of-kin for the deleted individual will be loaded. If no next-of-kin is present, the entire family tree list will be loaded.##
                                                        </span>
                                                </td>
                                        </tr>
                                </table>

                        {/if}

{*                        {if !$mydata.new} *}

                                {if $mydata.personType != "son" && $mydata.personType != "daughter" && $mydata.personType != "partner" }

                                        <input type="hidden" name="fams" value="{$mydata.fams}" />
                                        <input type="hidden" name="famc" value="{$mydata.famc}" />

                                { /if }

                                <input type="hidden" name="personType" value="{$mydata.personType}" />
                                <input type="hidden" name="personID" value="{$mydata.personID}" />

                                {if $mydata.ID }

                                        <input type="hidden" name="ID" value="{$mydata.ID}" />

                                {/if}
{*                        {/if}  *}
                </td>
        </tr>
        <tr><td colspan="2">&nbsp;</td></tr>
</table>
      <input type="hidden" name="submitForm" value="" />
      <input type="hidden" name="new" value="{$mydata.new}" />
      <input type="hidden" name="edit" value="{$mydata.edit}" />
    </form>
<script type="text/javascript">
        var inmenu=0;
</script>

{* Do NOT Uncomment This Section *}
{* This text is passed by PHP - added here to aid the language editor
##Invalid email address##
##Select##
##No father##
##No mother##
##No sire##
##No dam##
##Create a new person in your tree##
##Create the first person in your tree##
##Create a new member in the tree##
##(e.g. Betsy Sue)##
##(e.g. George William)##
##An update has been made to your record in the family tree!##
##This is an automatic notification that an update was made to your family tree record on $today.##
##You will need your family tree login ID and password to access or edit the record.##
##To view your record click##
##here##                
*}

