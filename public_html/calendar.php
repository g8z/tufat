<?php
/**
 * TUFaT, The Ultimate Family Tree Creator
 * Copyright 1999 - 2007, Darren G. Gates, All Rights Reserved
 * http://www.tufat.com
 * info@tufat.com
 * Selling the code for this script without prior written consent of
 * Darren G. Gates is expressly forbidden. The license of TUFaT
 * permits you to use install this script on one domain or one
 * physical server. Taking credit for any part of this software is a
 * violation of the copyright. TUFaT comes with no guarantees
 * for reliability or accuracy – in other words, you use this script
 * at your own risk! By using this software, you accept these risks,
 * and agree to indemnify Darren G. Gates for any liability that
 * might arise from its use. You must obtain permission from Darren
 * G. Gates before redistributing TUFaT in any form, over the
 * Internet or any other medium. In ALL cases this copyright notice,
 * as well as the (c) tufat.com notice on the actual TUFaT pages,
 * must remain intact. Removing or modifying this copyright notice
 * is a violation of the license agreement and may subject you to
 * legal proceedings.
 */

function calendar( $year, $month, $startDay, $user, $ev = 0) {
    $prev_month_end = mktime( 0, 0, 0, $month, 0, $year);
    $next_month_begin = mktime( 0, 0, 0, $month + 1, 1, $year);
    $curr_month_begin = mktime( 0, 0, 0, $month, 1, $year);
    $days_in_month = date( "t", $curr_month_begin);
    $month_name = strftime( '%B', $curr_month_begin);
    $prev_month_name = strftime( '%b', $prev_month_end);
    $next_month_name = strftime( '%b', $next_month_begin);
    $calendar = array( );
    $calendar["year"] = $year;
    $calendar["mon"] = $month;
    $calendar["month"] = substr( '0' . strval( $month), -2);
    $calendar["month_name"] = "##" . $month_name . "##";
    $calendar["prev_month_name"] = "##" . $prev_month_name . "##";
    $calendar["next_month_name"] = "##" . $next_month_name . "##";
    $calendar["prev_month_year"] = date( "Y", $prev_month_end);
    $calendar["prev_month_month"] = date( "m", $prev_month_end);
    $calendar["next_month_year"] = date( "Y", $next_month_begin);
    $calendar["next_month_month"] = date( "m", $next_month_begin);
    // Get the weekday for the first of the month
    $curr_month_start_day = date( "w", $curr_month_begin);
    if ( $startDay == "1") {
        $curr_month_start_day = $curr_month_start_day - 1;
        if ( $curr_month_start_day < 0)
            $curr_month_start_day = 6;
    }
    // Fill in the days of the selected month.
    $i = 1;
    // Line Count
    $y = 0;

    /* First let us fill in the first line. Month day may start in between */
    $line = array( );

    /* Put spaces for the days which doesn't have a date in the first line */

    for ( $x = 0; $x < $curr_month_start_day; $x++) {
        $line[$y][$x] = "";
    }
    for ( $x = $curr_month_start_day; $x < 7; $x++) {
        $line[$y][$x] = $i;
        $i++;
    }
    $y++;
    $x = 0;

    /* Now process for the other dates to fill in other dates  */
    while ( $i <= $days_in_month) {
        if ( $x < 7) {
            /* Week day dates accumulation */
            $line[$y][$x] = $i;
            $i++;
            $x++;
        } else {
            $x = 0;
            $y++;
        }
    }

    /* Now fill in the blank days with null for last line */
    while ( $x < 7) {
        $line[$y][$x] = "";
        $x++;
    }

    /* Now set up the array for week names */

    if ( $startDay == '1') {
        $days = array( "##Mon##", "##Tue##", "##Wed##", "##Thu##", "##Fri##", "##Sat##", "##Sun##");
    } else {
        $days = array( "##Sun##", "##Mon##", "##Tue##", "##Wed##", "##Thu##", "##Fri##", "##Sat##");
    }

    $calendar["days"] = $days;
    $calendar["lines"] = $line;
    // Add Events For the Big Calendar
    if ( $ev == 1) {
        $db = new FamilyDatabase( );
        // find repeating holidays / events that might fall in this month
        $rep_sql = "SELECT * FROM ! WHERE `repeat` = '1' and `user` = ?";
        $rep_evs = $db->query( $rep_sql, array( $db->eventsTable, $user));

        while ( $rep_event = $db->mfa($rep_evs)) {
            // strip the time, we just need the date
            $ts = strtotime( date( "Y-m-d", strtotime($rep_event['date'])));
            // convert duration (days to seconds)
            $ends = strtotime( $rep_event['date']) + ( $rep_event['duration'] * 86400);
            $mo_start = strtotime( $calendar['year'] . "-" . $calendar['month'] . "-00");
            $mo_end = strtotime( $calendar['year'] . "-" . $calendar['month'] . "-" . date( 't', $calendar["month"]));
            while ( $ts <= $ends && $rep_event['repeat_time']) {
                if (($ts >= $mo_start) && ($ts <= $mo_end)) {
                    $repeating_events[date( 'Y-m-d', $ts)][] = $rep_event;
                }
                $ts = strtotime( "+" . $rep_event['repeat_time'] . " " . $rep_event['repeat_unit'], $ts);
            }
        }

        $edate_arr = array();
        // now for unique events, and lets add our repeating events to the array
        for ( $i = 0; $i < count( $calendar["lines"]); $i++) {
            for ( $j = 0; $j < count( $calendar["lines"][$i]); $j++) {
                if ( !( empty( $calendar["lines"][$i][$j]))) {
                    // don't bother if the box doesn't have an associated date
                    $edate = $calendar["year"] . '-' . $calendar["month"] . '-' . substr( '0' . strval( $calendar["lines"][$i][$j]), -2);
                    // $non=0;
                    $edate_array[] = ' `date` LIKE \''.$edate.'\' ';

                    if ( $repeating_events[$edate]) {
                        foreach ( $repeating_events[$edate] as $key => $rpevdata) {
                            $calendar['events'][$i][$j][] = $rpevdata['title'];
                            $calendar['ev_id'][$i][$j][] = $rpevdata['id'];
                        }
                    }

                }
            }
        }
      $sql = 'select * from ! where ('.implode(' OR ', $edate_array).') AND `user` = ? AND NOT `repeat` = \'1\'';
      $r = $db->query( $sql, array($db->eventsTable, $user));
      if ($r) {
        while ( $a = $db->mfa( $r)) {
          $calendar["events"][$i][$j][] = $a["title"];
          $calendar["ev_id"][$i][$j][] = $a["id"];
        }
      };
      unset( $db);
    }
                
    return $calendar;
}

?>