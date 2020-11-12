{if $add != 1 and $ed != 1}
  <span class="normal">
  <font class="title">##Family Events##</font><br />
  <br />
  {if $read_only!=1}   <a href="events.php?add=1&amp;edate={$edate}">##Add New Event##</a> <br />
    <br />
  {/if}
  {if $non==1}
  	 {if $fedate != ''}
	    ##There are no events scheduled at this time for## {$fedate}. <br />
	 {else}
	 	 ##There are no events scheduled.##
	 {/if}
		 
  </span>
  {else}
    ##The following Family Events have been recorded for ## {$fedate}
    <br />
    <br />

  </span>
    <table border="0" cellpadding="2" cellspacing="2">
    <tr>
    <td class="normalBold">##Date and Time##</td>
    <td class="normalBold">##Description##</td>
    <td class="normalBold">{if $read_only!=1}##Edit##{/if}</td>
    </tr>
    {foreach from=$elist key=key item=item}
       <tr>
        <td class="normal" valign="top"> <a href="eventscal.php">{$item->date}</a> </td>
        <td class="normal"> {$item->event} </td>
        {if $read_only!=1}  <td class="normal" valign="top"> &nbsp; <a href="events.php?ed=1&amp;id={$item->id}&amp;edate={$edate}">##edit##</a> </td>
        {else}<td class="normal" valign="top">&nbsp;</td>
        {/if}
       </tr>
    {/foreach}
    </table>
  {/if}


{else}
    <p>
    <font class="title">##Family Events##</font></p>
<form method="post" action="events.php">
<span class="normal">
    ##Do you have a special event that you would like to invite members of your family to?##
    ##The members of your family who have saved e-mail addresses are listed below.##
    ##An e-mail notification of your event invitation will be sent to the selected family members.##
     <span class="normalBold"> ##Event for ##{$fedate}: <br /><br /> </span>
     {$fhour}
        Hour:
        <select name="hour">
        <option value="1" {if $xlist->hour=='01'} selected="selected" {/if} >1 ##AM##  </option>
        <option value="2" {if $xlist->hour=='02'} selected="selected" {/if} >2 ##AM## </option>
        <option value="3" {if $xlist->hour=='03'} selected="selected" {/if} >3 ##AM## </option>
        <option value="4" {if $xlist->hour=='04'} selected="selected" {/if} >4 ##AM## </option>
        <option value="5" {if $xlist->hour=='05'} selected="selected" {/if} >5 ##AM## </option>
        <option value="6" {if $xlist->hour=='06'} selected="selected" {/if} >6 ##AM## </option>
        <option value="7" {if $xlist->hour=='07'} selected="selected" {/if} >7 ##AM## </option>
        <option value="8" {if $xlist->hour=='08'} selected="selected" {/if} >8 ##AM## </option>
        <option value="9" {if $xlist->hour=='09'} selected="selected" {/if} >9 ##AM## </option>
        <option value="10" {if $xlist->hour=='10'} selected="selected" {/if} >10 ##AM## </option>
        <option value="11" {if $xlist->hour=='11'} selected="selected" {/if} >11 ##AM## </option>
        <option value="12" {if $xlist->hour=='12'} selected="selected" {/if} >12 ##PM## </option>
        <option value="13" {if $xlist->hour=='13'} selected="selected" {/if} >1 ##PM## </option>
        <option value="14" {if $xlist->hour=='14'} selected="selected" {/if} >2 ##PM## </option>
        <option value="15" {if $xlist->hour=='15'} selected="selected" {/if} >3 ##PM## </option>
        <option value="16" {if $xlist->hour=='16'} selected="selected" {/if} >4 ##PM## </option>
        <option value="17" {if $xlist->hour=='17'} selected="selected" {/if} >5 ##PM## </option>
        <option value="18" {if $xlist->hour=='18'} selected="selected" {/if} >6 ##PM## </option>
        <option value="19" {if $xlist->hour=='19'} selected="selected" {/if} >7 ##PM## </option>
        <option value="20" {if $xlist->hour=='20'} selected="selected" {/if} >8 ##PM## </option>
        <option value="21" {if $xlist->hour=='21'} selected="selected" {/if} >9 ##PM## </option>
        <option value="22" {if $xlist->hour=='22'} selected="selected" {/if} >10 ##PM## </option>
        <option value="23" {if $xlist->hour=='23'} selected="selected" {/if} >11 ##PM## </option>
        <option value="24" {if $xlist->hour=='24'} selected="selected" {/if} >12 ##AM## </option>
        </select>
        <br /><br />

		{*  no longer used
         <input type="hidden" name="duration" value="" />
         <input type="hidden" name="duration_unit" value="" />
		*}
		
         <span class="normalBold">##Event Repetition:##</span> <br />
         <input type="radio" value="0" {if $xlist->repeat==0} checked="checked" {/if} name="repeat" /> this event does not repeat. <br />
         <input type="radio" value="1" {if $xlist->repeat==1} checked="checked" {/if} name="repeat" /> this event repeats every &nbsp;
         <input type="text" name="repeat_time" value="{$xlist->repeat_time}" size="2" />
        <select name="repeat_unit">
          <option {if $xlist->repeat_unit=='days'} selected="selected" {/if} value="days" >##days##</option>
          <option {if $xlist->repeat_unit=='weeks'} selected="selected" {/if} value="weeks" >##weeks##</option>
          <option {if $xlist->repeat_unit=='months'} selected="selected" {/if} value="months" >##months##</option>
          <option {if $xlist->repeat_unit=='years'} selected="selected" {/if} value="years" >##years##</option>
        </select> ##until## {html_select_date prefix="until_" start_year="-0" end_year="+10"}<!--
         <input type="text" name="until_day" value="{$xlist->until_day}" size="2" maxlength="2"/> day
						<input type="text" name="until_month" value="{$xlist->until_month}" size="2" maxlength="2" /> month
						<input type="text" name="until_year" value="{$xlist->until_year}" size="4" maxlength="4" /> year
						-->
						<br /><br />
         <input type="hidden" name="location" value="{$xlist->location}" />


         <span class="normalBold">##Family members with e-mail addresses:## <br /></span>
         <select name='emails[]' size="4" multiple="multiple">
            <!--<option>&nbsp;</option>-->
           {foreach from=$aemail key=key item=item}
             <option value="{$item.email}"> {$item.name}</option>
           {/foreach}
         </select> <br /> <i>##(CTRL + Left Click for selection)##</i>
         <br /><br />

        <span class="normalBold">##Event Title:##</span><br />
        ##This will form the subject of any automatic emails that are sent for this event.##
        ##The event will be displayed in the Events Calendar using this title.##<br />
        <input type="text" name="title" value="{$xlist->title}" size="58" /> <br /><br />

        <span class="normalBold">##Event Description:##</span><br />
        ##This description will form the body of the e-mail to your family members.##
        <textarea name="event" cols="80" rows="5">{$xlist->event}</textarea><br />
        <br />
        {if $add == 1}
                <input type="hidden" name="edate" value="{$edate}" />
                <input type="submit" name="added" value="##Add Event##" />
        {/if}
        {if $ed == 1}
                <input type="hidden" name="id" value="{$id}" />
                <input type="hidden" name="edate" value="{$edate}" />
                <input type="submit" name="edited" value="##Update##" />&nbsp;
                <input type="submit" name="deleted" value="##Delete##" />
        {/if}
     </span>
      </form>
    <br />

{/if}