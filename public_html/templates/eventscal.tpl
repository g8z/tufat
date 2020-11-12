
<table class="calendar" border="0" cellpadding="1" cellspacing="1">
  <tr>

    <th class="month" colspan="7">
      {$calendar.month_name}&nbsp;{$calendar.year}
    </th>

  </tr>
  <tr>
    <td class="prev-month" colspan="3">

      <a href="eventscal.php?mon={$calendar.mon}&amp;year={$calendar.year-1}">
        &lt;&lt; {$calendar.year-1}
      </a> &nbsp;&nbsp;
      <a href="eventscal.php?mon={$calendar.mon-1}&amp;year={$calendar.year}">
        &lt; {$calendar.prev_month_name}
      </a>
    </td>
    <td></td>
    <td class="next-month" colspan="3">
      <a href="eventscal.php?mon={$calendar.mon+1}&amp;year={$calendar.year}">
        {$calendar.next_month_name} &gt;
      </a> &nbsp;&nbsp;
      <a href="eventscal.php?mon={$calendar.mon}&amp;year={$calendar.year+1}">
        {$calendar.year+1} &gt;&gt;
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

          <td align="left" valign="top" height="100" width="100" class="day">
            <a href="events.php?edate={$calendar.year}-{$calendar.month}-{$date|string_format:"%02d"}">{$date}</a><br /><br />

            {section name="ev" loop=$calendar.events[row][col]}
                 {assign var="event" value=$calendar.events[row][col][ev]}
                 {assign var="evid" value =$calendar.ev_id[row][col][ev]}
              {if $read_only!=1}<a href="events.php?ed=1&amp;id={$evid}">{$event}</a><br />
              {else}{$event}<br />
              {/if}
            {/section}

          </td>

      {/section}
    </tr>
  {/section}

</table>