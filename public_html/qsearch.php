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

require_once('config.php');

if ( !$_REQUEST['qsearch']) {
    header( "Location: " . VIEWSCRIPT);
    exit;
}

$qsearch = trim($_REQUEST['qsearch']);
$_SESSION['qsearch'] = $qsearch;
$smarty->assign('qsearch', $qsearch);

if (is_numeric($qsearch) && (int)$qsearch == $qsearch && $db->gedcom_id_exists($qsearch)) {
  // we got a simple ID search, send it to the loader
  header( "Location: load.php?ID=$qsearch");
  exit;
}

// # If we got this far, we've probably got a search string, which we process in this order
// #  Feed searchResults all the info it's looking for:

$search_array = array(
  'f1bool' => 'AND',
  'name' => $qsearch,
  'f2bool'=> 'OR',
  'surn' => $qsearch,
  'f10bool' => 'OR',
  'buri_plac' => $qsearch,
  'f3bool' => 'AND', 
  'birt_date_start_1' => '00',
  'birt_date_start_2' => '00',
  'birt_date_start_3' => '',
  'birt_date_end_1' => '00',
  'birt_date_end_2' => '00',
  'birt_date_end_3' => '', 
  'f4bool' => 'AND',
  'deat_date_start_1' => '00',
  'deat_date_start_2' => '00', 
  'deat_date_start_3' => '', 
  'deat_date_end_1' => '00',
  'deat_date_end_2' => '00',
  'deat_date_end_3' => '',
  'f5bool' => 'AND',
  'born_on_day_1' => '00',
  'born_on_day_2' => '00',
  'f6bool' => 'AND',
  'born_during_month_1' => '00',
  'f9bool' => 'AND',
  'sex' => '',
  'f7bool' => 'AND', 
  'occu' => '',
  'f12bool' => 'AND',
  'f13bool' => 'AND',
  'limit' => '100',
  'Submit' => 'Search',
  'searchname' => '',
  'ID' => $ID);
$params = array();
foreach ($search_array as $key => $value)
  $params[] = "$key=$value";

$getstr = implode('&', array_map('htmlentities', $params));

$alltrees = ALLOWCROSSTREESEARCH;

die(header( "Location: searchResults.php?$getstr"));

?>
