<?php
/**
 * TUFaT, The Ultimate Family Tree Creator
 * Copyright 1999 – 2007, Darren G. Gates, All Rights Reserved
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
 *
 * Created by aTufa Feb/2005
 */
// Heavily modified Pat K. <cicada@edencomputing.com> 2006/07/01
require_once 'config.php';

if (!isset($_SESSION['user']) || empty($_SESSION['user']))
  header( 'Location: login.php');

$id = (isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : null);
if ( isset( $added) && $_SESSION['read_only'] != 1) {
  $edatetime = "$edate $hour:00:00";
  $title = htmlentities( $title, ENT_QUOTES);
  $location = htmlentities( $location, ENT_QUOTES);

  if ( $repeat) {
    // if the event repeats, we need to figure out the duration from the until_ values
    $untildate = strtotime( $until_Year . "-" . $until_Month . "-" . $until_Day);
    $edate_date = strtotime( $edate);
    // we need to store this value in # of days to fit a smallint() field
    $duration_unit = 'days';
    $duration = ceil( ( $untildate - $edate_date) / 86400);
  }

    if ( !$repeat_time ) {
    	$repeat_time = '0';
    }

    if ( !$duration ) {
    	$duration = '0';
    }

    if ( !$duration_unit ) {
    	$duration_unit = '0';
    }


	/*
	$sql = "insert into ! set `date`= ?, `event`= ?, title= ?,location= ?, duration= ?,
              `repeat`= ?, repeat_time= ?, repeat_unit= ?, duration_unit= ?, `user` = ?";

	$sql = "insert into ! ( `date`, `event`, `title`, `location`, `duration`, `repeat`, `repeat_time`, `repeat_unit`, `duration_unit`, `user` ) values ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";
	*/

	$temp = "insert into " . $db->eventsTable . " (
		`date`,
		`event`,
		`title`,
		`location`,
		`duration`,
		`repeat`,
		`repeat_time`,
		`repeat_unit`,
		`duration_unit`,
		`user`
		) values (
		'$edatetime',
		'$event',
		'$title',
		'$location',
		'$duration',
		'$repeat',
		'$repeat_time',
		'$repeat_unit',
		'$duration_unit',
		'$user'
	)";

	$db->query( $temp );

    /*
    $db->query( $sql, array( $db->eventsTable, $edatetime, $event, $title, $location, $duration, $repeat, $repeat_time, $repeat_unit, $duration_unit, $user));
	*/

    // remove slashes where 'magic quotes' is being used before sending e-mails

    $title = stripslashes( $title);
    $event = stripslashes( $event);

    for ( $i = 0; $i < count( $emails); $i++) {
        if ( USE_SMTP_CLASS) {
            // use smtp class for mail sending # jrpi: 02 Feb 06
            require_once( 'smtp.class.php');
            @send_mail( "dontreply@email.com", $db->mytrans( "##TUFaT Events System##"), $emails[$i], $title, $event);
        } else {
            @mail( $emails[$i], $title, $event, $db->mytrans( "##From: TUFaT Events System##"));
        }
    }
}

if ( isset( $edited) && $_SESSION['read_only'] != 1) {
  $i = 0;
  $edatetime = "$edate $hour:00:00";

  $title = htmlentities( $title, ENT_QUOTES);
  $location = htmlentities( $location, ENT_QUOTES);

  // if the event repeats, we need to figure out the duration from the until_ values
  $untildate = strtotime( $until_Year . "-" . $until_Month . "-" . $until_Day);
  $edate_date = strtotime( $edate);

  // we need to store this value in # of days to fit a smallint() field
  $duration_unit = 'days';
  $duration = ceil( ( $untildate - $edate_date) / 86400);

  $sql = "update ! SET `date`= ?, `event`= ?, title= ?, location= ?, duration= ?,
             `repeat`= ?, repeat_time= ?, repeat_unit= ?, duration_unit= ? where id = ?";
  $db->query( $sql, array( $db->eventsTable, $edatetime, $event, $title, $location, $duration, $repeat, $repeat_time, $repeat_unit, $duration_unit, $id));

    for ( $i = 0; $i < count( $emails); $i++) {
        if ( USE_SMTP_CLASS) {
            // use smtp class for mail sending # jrpi:02 feb 06
            require_once( 'smtp.class.php');
            @send_mail( "dontreply@email.com", $db->mytrans( "##TUFaT Events System##"), $emails[$i], $title, $event);
        } else {
            @mail( $emails[$i], $title, $event, $db->mytrans( "##From: TUFaT Events System##"));
        }
    }
}

if ( isset( $deleted) && $_SESSION['read_only'] != 1) {
    $sql = "delete from ! WHERE id= ?";
    $db->query( $sql, array( $db->eventsTable, $id));
}

if ( $ed == 1) {
    // edit event

    $sql = "SELECT * FROM {$db->eventsTable} WHERE id= ".$db->dbh->quote($id);
    $xlist = (object)$db->getRow($sql);
    // -> jrpi: 5 feb 2006
    $edate = $x->date;

    $_SESSION['event_name'] = $x["event"];
    $_SESSION['title_name'] = $x["title"];
}
$non = 0;

// get the unique (one shot) events
$sql = "select * from ! where `date` like ? AND `user`= ? AND NOT `repeat` = '1'";
$r = $db->query( $sql, array( $db->eventsTable, $edate . "%", $user));

if ( $r != false) {
  $n = $db->rowsInResult( $r);
  if ( $n > 0) {
    for ( $i = 1; $i <= $n; $i++) {
      $a = $db->mfa( $r);
      $elist[$i]->id = $a["id"];
      $elist[$i]->date = strftime( "%d %b %Y %I%p", strtotime( $a["date"]));
      $elist[$i]->event = nl2br( $a["event"]);
      $elist[$i]->title = $a["title"];
      $elist[$i]->location = $a["location"];
      $elist[$i]->duration = $a["duration"];
      $elist[$i]->duration_unit = $a["duration_unit"];
      $elist[$i]->repeat = $a["repeat"];
      $elist[$i]->repeat_unit = $a["repeat_unit"];
      $elist[$i]->repeat_time = $a["repeat_time"];
    }
  } else
    $non = 1;
}
// now get all the repeating events, and filter out the ones we want
$rep_sql = "SELECT * FROM ! WHERE `repeat` = '1' and `user` = ?";
$rep_evs = $db->query( $rep_sql, array( $db->eventsTable, $user));

while ( $rep_event = $db->mfa($rep_evs)) {
  // strip the time, we just need the date
  $ts = strtotime( date( "Y-m-d", strtotime( $rep_event['date'])));
  // convert duration (days to seconds)
  $ends = strtotime($rep_event['date']) + ( $rep_event['duration'] * 86400);
  while ( $ts <= $ends && $rep_event['repeat_time']) {
    if ( $ts == strtotime( date( "Y-m-d", strtotime( $edate)))) {
      $i++;
      $elist[$i]->id = $rep_event["id"];
      $elist[$i]->date = strftime( "%d %b %Y %I%p", strtotime( $edate));
      $elist[$i]->event = nl2br( $rep_event["event"]);
      $elist[$i]->title = $rep_event["title"];
      $elist[$i]->location = $rep_event["location"];
      $elist[$i]->duration = $rep_event["duration"];
      $elist[$i]->duration_unit = $rep_event["duration_unit"];
      $elist[$i]->repeat = $rep_event["repeat"];
      $elist[$i]->repeat_unit = $rep_event["repeat_unit"];
      $elist[$i]->repeat_time = $rep_event["repeat_time"];
      $non = 0;
    }
    $ts = strtotime( "+" . $rep_event['repeat_time'] . " " . $rep_event['repeat_unit'], $ts);
  }
}

if ( $_SESSION['read_only'] == 1)
    $smarty->assign( "read_only", 1);

$smarty->assign( "add", $add);
$smarty->assign( "ed", $ed);
$smarty->assign( "id", $id);

// don't set a from date if we don't really have one
$fedate = ( $edate) ? strftime( "%d %b %Y", strtotime( $edate)) : null;
$smarty->assign( "edate", $edate);
$smarty->assign( "fedate", $fedate);
$smarty->assign( "non", $non);
$aemail = getPersEmail($db, $user);
$smarty->assign( "aemail", $aemail);
$smarty->assign( "elist", $elist);
$smarty->assign( "xlist", $xlist);
$time_today['day'] = date( "d", time());

// # Get the page we want to display
$smarty->assign( 'rendered_page', $smarty->fetch('events.tpl'));

if ( $added || $edited) {
    // # Reset the navMenu
    require( 'navMenu.php');
    $smarty->assign( 'navMenu', $smarty->fetch( 'navMenu.tpl'));
}

// # Display the page.
$smarty->display( 'index.tpl');
?>