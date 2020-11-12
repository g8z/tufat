{if $smarty.session.user}
<!--  $collapsibleMenu -->
{if $collapsibleMenu}
  {php}
    if ( basename($_SERVER['REQUEST_URI'])) $_SERVER['REQUEST_URI'] = 'index.php?';
    if ($_REQUEST['mitem']) 
	    $_SESSION['m_item'] = $_REQUEST['mitem'];
		
    $q = parse_url($_SERVER['REQUEST_URI']);
    $bname = basename($_SERVER['REQUEST_URI']);
    $bname = ($_SERVER['REQUEST_URI'] != "/".$bname."/")?$bname:"/".$bname."/";
    $_REQUEST['bsname'] = preg_replace("/\&mitem\=.*/","",$bname) . str_replace('&','&amp;',$q['query']);

    if (empty($_REQUEST['bsname'])) $_REQUEST['bsname'] = 'index.php?';
    $_REQUEST['bsname'] .= '&mitem=';
  {/php}
{/if}

<form name="quicksearch" method="post" action="qsearch.php">
	<span class="menuItem_nh">
	##Quick Search##:<br />
	<input type="text" name="qsearch"
			style="width: 120px;" 
			value="{$qsearch|default:'##name or ID##'}"
			{if !$qsearch}onfocus="this.value='';"{/if} 
			onchange="this.submit();" />
	<br />
	</span>
</form>
<br />

<table border="0" cellpadding="5" cellspacing="0">
  <tr>
    <td>
       <a href="{if $collapsibleMenu}{$smarty.request.bsname}view{if $ID}&amp;ID={$ID}{/if}{else}#{/if}" class="menuMain">##View##</a><br />
       {if !$collapsibleMenu or ($collapsibleMenu and $smarty.session.m_item eq "view")}
           <div style="padding-left:10px;">
             {if $ID}
                <a href="load.php?ID={$ID}" class="menuItem">##Current Record##</a><br />
             {/if}
             <a href="whatsnew.php{if $ID}?ID={$ID}{/if}" class="menuItem">##What's New##</a><br />
             <a href="index.php{if $ID}?ID={$ID}{/if}" class="menuItem">##Tree Index##</a><br />
             {if $ID}
                <a href="generations.php?ID={$ID}" class="menuItem">##Generations##</a><br />
                <a href="flow.php?ID={$ID}" class="menuItem">##Ancestors##</a><br />
                <a href="cshow.php?ID={$ID}" class="menuItem">##Lateral View##</a>
             {/if}
           </div>
       {/if}
       <a href="{if $collapsibleMenu}{$smarty.request.bsname}tools{if $ID}&amp;ID={$ID}{/if}{else}#{/if}" class="menuMain">##Tools##</a><br />
       {if !$collapsibleMenu or ($collapsibleMenu and $smarty.session.m_item eq "tools")}
          <div style="padding-left:10px;">
            <a href="relfind.php{if $ID}?ID={$ID}{/if}" class="menuItem">##Find Relationships##</a><br />
            <a href="chtemp.php{if $ID}?ID={$ID}{/if}" class="menuItem">##Change Template##</a>
          </div>
       {/if}
    {if $smarty.session.admin}
       <a href="{if $collapsibleMenu}{$smarty.request.bsname}backup{if $ID}&amp;ID={$ID}{/if}{else}#{/if}" class="menuMain">##Backup##</a><br />
       {if !$collapsibleMenu or ($collapsibleMenu and $smarty.session.m_item eq "backup")}
          <div style="padding-left:10px;">
            {if $smarty.session.master}<a href="backup.php?{if $ID}&amp;ID={$ID}{/if}" class="menuItem">##To SQL##</a><br />{/if}
            <a href="export.php{if $ID}?ID={$ID}{/if}" class="menuItem">##Export GEDCOM##</a><br />
            <a href="impexpp.php?{if $ID}ID={$ID}{/if}&amp;ex=1" class="menuItem">##Export Images/Files##</a>
          </div>
       {/if}
       <a href="{if $collapsibleMenu}{$smarty.request.bsname}restore{if $ID}&amp;ID={$ID}{/if}{else}#{/if}" class="menuMain">##Restore##</a><br />
       {if !$collapsibleMenu or ($collapsibleMenu and $smarty.session.m_item eq "restore")}
          <div style="padding-left:10px;">
            <a href="import.php{if $ID}?ID={$ID}{/if}" class="menuItem">##Import GEDCOM##</a><br />
            <a href="impexpp.php?{if $ID}ID={$ID}{/if}&amp;ex=0" class="menuItem">##Import Images/Files##</a>
          </div>
       {/if}
       <a href="{if $collapsibleMenu}{$smarty.request.bsname}admin{if $ID}&amp;ID={$ID}{/if}{else}#{/if}" class="menuMain">##Administration##</a><br />
       {if !$collapsibleMenu or ($collapsibleMenu and $smarty.session.m_item eq "admin")}
          <div style="padding-left:10px;">
            {if $smarty.session.master}<a href="ilogi.php{if $ID}?ID={$ID}{/if}" class="menuItem">##Add New Login##</a><br />
            <a href="editlang.php{if $ID}?ID={$ID}{/if}" class="menuItem">##Language Options##</a><br />{/if}
            <a href="chliv.php{if $ID}?ID={$ID}{/if}" class="menuItem">##Hide Information##</a><br />
            <a href="editpass.php{if $ID}?ID={$ID}{/if}" class="menuItem">##Edit Passwords##</a><br />
            <a href="drop.php" class="menuItem">##Delete Tree##</a><br />
            <a href="normalize.php" class="menuItem">##Normalize Tree##</a><br />
            <a href="maintain_templates.php"  class="menuItem">Maintain templates</a>
          </div>
       {/if}
    {/if}
       <a href="search.php{if $ID}?ID={$ID}{/if}" class="menuMain">##Search##</a><br />
       {* <a href="help.php{if $ID}?ID={$ID}{/if}" class="menuMain">##Help##</a><br /> *}
       
       <a href="http://www.tufat.com/docs/tufat/" target="_blank" class="menuMain">##Help##</a><br />
       <a href="logout.php" class="menuMain">##Log-Out##</a>

    </td>
  </tr>
</table>

<table class="calendar" border="0" cellpadding="1" cellspacing="1">
  <tr>
    <th class="month" colspan="7">
      {$calendar.month_name}&nbsp;{$calendar.year}
    </th>
  </tr>
  <tr>
    <td class="prev-month" colspan="3">
      <a class="menuItem" href="eventscal.php?mon={$calendar.mon-1}">
        {$calendar.prev_month_name}
      </a>
    </td>
    <td></td>
    <td class="next-month" colspan="3">
      <a class="menuItem" href="eventscal.php?mon={$calendar.mon+1}">
        {$calendar.next_month_name}
      </a>
    </td>
  </tr>
  <tr>
  {section name="day_of_week" loop=$calendar.days}
    <th class="day-of-week">{$calendar.days[day_of_week]}</th>
  {/section}
  </tr>
  {section name="row" loop=$calendar.lines}
    <tr>
      {section name="col" loop=$calendar.lines[row]}
        {assign var="date" value=$calendar.lines[row][col]}
        {if $date == $selected_date}
          <td class="selected-day">{if $date}{$date|date_format:"%e"}{/if}</td>
        {elseif $date|date_format:"%m" == $month}
          <td class="day">
            <a href="{$date|date_format:$url_format}">
              {$date|date_format:"%e"}
            </a>
          </td>
        {else}
          <td class="{if $calendar.events[row][col][0]}day_event{else}day{/if}"><a href="events.php?edate={$calendar.year}-{$calendar.month}-{$date|string_format:"%02d"}">{$date}</a></td>
        {/if}
      {/section}
    </tr>
  {/section}
  <tr>
    <td class="today" colspan="7">
       <a href="eventscal.php">##Events Calendar##</a>
    </td>
  </tr>
</table>
{else}<table><tr><td>&nbsp;</td></tr></table>{/if}

{if $birthdays_len > 0 and $smarty.session.user }
<table style="width:130px;">
<tr><td>&nbsp;</td></tr>
<tr>
	<td class="menuItem_nh" colspan="3" style="font-size:70%;font-weight:bold;" align="middle" valign="middle">##Birthdays this week##:</td>
</tr>
{foreach from=$birthdays key=key item=person}
{if $person.rest_days > 0}
	<tr>
		<td style="width:90px;">
		<a href="load.php?ID={$key}" class="menuItem" style="font-size:80%;">{$person.name} {$person.surname}</a>
		</td>
		<td></td>
		<td style="width:40px;"><span class="menuItem_nh" style="font-size:80%;">{$person.rest_days} days</span></td>
	</tr>
{else}{*if birthday is today, then highlight it*}
	<tr>
		<td style="width:90px;">
		<a href="load.php?ID={$key}" class="menuItem" style="font-size:80%;">{$person.name} {$person.surname}</a></td>
		<td>&nbsp;</td>
		<td><span class="menuItem_nh" style="font-size:80%;color:#FF7F00">##today##</span></td>
	</tr>
{/if}

{/foreach}
</table>
{/if}

	{*added 2006/05/20 Pat K. <cicada@edencomputing.com>*}
	{if $allowMultipleLanguages}
	<br />
	<form name="langSwitch" method="post" action="">
	<span class="menuItem_nh">
		##Language:##<br />
	   <select name="slang" onchange="submit();" style="width: 120px;">
	      {html_options options="$languages" selected="$slang"}
	   </select>
	</span>
	</form>
	{/if}
	
