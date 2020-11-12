{* DO NOT UNCOMMENT THESE LINES! 
##January##
##February##
##March##
##April##
##May##
##June##
##July##
##August##
##September##
##October##
##November##
##December##
##Jan## ##Feb## ##Mar## ##Apr## ##May## ##Jun## ##Jul## ##Aug## ##Sep## ##Oct## ##Nov## ##Dec##
##Monday## ##Tuesday## ##Wednesday## ##Thursday## ##Friday## ##Saturday## ##Sunday##
##Mon## ##Tue## ##Wed## ##Thu## ##Fri## ##Sat## ##Sun##
*}

<table class="calendar" border="0" cellpadding="1" cellspacing="1">
  <tr>
    <th class="month" colspan="7">
      {$calendar.month_name}&nbsp;{$calendar.year}
    </th>
  </tr>
  <tr>
    <td class="prev-month" colspan="3">
      <a href="{$calendar.prev_month_name}">
        {$calendar.prev_month_name}
      </a>
    </td>
    <td></td>
    <td class="next-month" colspan="3">
      <a href="{$calendar.prev_month_name}">
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
          <td class="selected-day">{$date|date_format:"%e"}</td>
        {elseif $date|date_format:"%m" == $month}
          <td class="day">
            <a href="{$date|date_format:$url_format}">
              {$date|date_format:"%e"}
            </a>
          </td>
        {else}
          <td class="day">{$date}</td>
        {/if}
      {/section}
    </tr>
  {/section}
  <tr>
    <td class="today" colspan="7">
      {if $today_url != ""}
        <a href="{$today_url}">##Today##</a>
      {else}
        ##Today##
      {/if}
    </td>
  </tr>
</table>
