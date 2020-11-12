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
 * 
 * This is modified to incorporate Smarty Template Processor
 * Vijay Nair                              12/Apr/2004
 */

require_once 'config.php'; 

// put personal data into $fams array
// get GEDCOM string for this person's record, given the person's ID
$gedcom = $db->getGEDCOM( 'I', $ID); 

// extract GEDCOM info into variables that we can use to populate table
$vars = $db->extractGEDCOM( $gedcom); 

// remove the /SURNAME/ part of the $name string
$nameParts = explode( "/", $vars['name']);

$vars['name'] = $nameParts[0];

if ( !$vars['surn']) 
{
    $vars['surn'] = $nameParts[1];
} 

if ( $vars['note']) 
{
    $vars['note'] = nl2br( $vars['note']);
} 

// this is an array of the fields (identified by GEDCOM name) that we should display,
// along with the field labels
$fields = array(	"chan_date" => "##Date of Last Change##", 
					"name" => "##Given Name##",
					"surn" => "##Surname##", 
					"email" => "##E-Mail Address##", 
					"url" => "##World Wide Web Address##", 
					"phon" => "##Phone Number##", 
					"addr" => "##Street Address##", 
					"addr_city" => "##City##", 
					"addr_stae" => "##State##", 
					"addr_post" => "##Postal Code##", 
					"addr_ctry" => "##Country##", 
					"npfx" => "##Name Prefix##", 
					"spfx" => "##Surname Prefix##", 
					"nick" => "##Nickname##", 
					"nsfx" => "##Name Suffix##", 
					"birt_type" => "##Type of Birth##", 
					"birt_addr" => "##Address of Birth Site##", 
					"birt_note" => "##Notes About the Birth##", 
					"deat_caus" => "##Cause of Death##", 
					"deat_addr" => "##Address of Death Site##", 
					"deat_note" => "##Notes About the Death##", 
					"note" => "##Notes##");

if ( ANIMALPEDIGREE) 
{
    $fields["name"] = "##Registered Name##";
    $fields["surn"] = "##Call Name##";
    $fields["bred"] = "##Breed##";
    $fields["brdr"] = "##Breeder##";
    $fields["birt_date"] = "##Birth Date##";
    $fields["deat_date"] = "##Death Date##";
    $fields["caus"] = "##Cause of Death##";
    $fields["sex"] = "##Gender##";
    $fields["rega_numb"] = "##Registration" . " #1##";
    $fields["rega_plac"] = "##Location##";
    $fields["regb_numb"] = "##Registration" . " #2##";
    $fields["regb_plac"] = "##Location##";
    $fields["regc_numb"] = "##Registration" . " #3##";
    $fields["regc_plac"] = "##Location##";

    foreach ( $fields as $k => $v) 
	{
        $vars[$k] = $db->getItem( $k, $ID);
    } 
	
    $vars["name"] = $db->changeBrack( $vars["name"]);
    $vars["name"] = preg_replace( "/\"(.+)\"/", "", $vars["name"]);
} 

$tname = $vars["name"];

$hid = $db->getItem( "hide", $ID);

if ( $hid == "Yes") 
{
    $vars["name"] = $db->obstr( $vars["name"], 1);
    $vars["surn"] = $db->obstr( $vars["surn"], 2);
} 

$hidden = $db->getItem( "HIDE", $ID);

foreach ( $fields as $key => $value) {
  if ( !empty( $vars[$key])) {
    // check to see if $key is a date field, if it is, add to temp table
    if ( stristr( $key, "_date")) {
      $vars[$key] = ucwords( strtolower( $vars[$key]));
      $vars[$key] = $db->dateFormat( $vars[$key], 3);
    } 

        if ( stristr( $key, "sex")) 
		{
            if ( $vars[$key] == "M") 
			{
                $vars[$key] = "Male";
            } 
			else 
			{
                $vars[$key] = "Female";
            } 
        } 
        if ( $vars[$key] == "Y") 
		{
            $vars[$key] = "Yes";
        } 

        if ( $vars[$key] == "N") 
		{
            $vars[$key] = "No";
        } 

        if ( $hidden == "Yes" && $key != 'name' && $key != 'surn') 
		{
            $vars[$key] = $db->obstr( $vars[$key]);
        } 
		
        if ( $key == "url") 
		{
            if ( strstr( $vars[$key], "http://") == false) 
			{
                $vars[$key] = "http://" . $vars[$key];
            } 
        } 
    } 
} 

$spouses = $db->getSpouses( $ID);
$spouseList = array( );

foreach ( $spouses as $spouse) 
{
    list( $spouseID, $spouseName, $spouseSurname) = $db->getItems( array( "ID", "name", "surn"), $spouse);
    $marriagePlace = "";
    $marriageDate = "";

    $marriage = $db->getMarriageInfo( $ID, $spouseID);

    $spouseName = $db->removeFam( $spouseName);
    $hid = $db->getItem( "HIDE", $spouseID);
	
    if ( $hid == "Yes") 
	{
        $spouseName = $db->obstr( $spouseName, 1);
    } 
	
    $spouseList["spouseID"] = $spouseID;
    $spouseList["spouseName"] = $spouseName;
    $spouseList["spouseSurname"] = $spouseSurname;
} 

$smarty->assign( "spouseList", $spouseList);

if ( $marriage[0])
{
    $marriagePlace = $marriage[0];
} 

if ( $marriagePlace || $marriage[1]) 
{
    $marriageDate = $db->dateFormat( $marriage[1], 3);
} 

$vars["ID"] = $ID;
$vars['animalPedigree'] = ANIMALPEDIGREE;
$vars["marriagePlace"] = $marriagePlace;
$vars["marriageDate"] = $marriageDate;

/*
   include_once 'calendar.php';
   $today = getdate();

   $month=$mon;
   if (!isset($mon)) $month = $today['mon'];

   if (!isset($year)) $year = $today['year'];

   $startDay="0";


   $newcal = calendar($year,$month, $startDay, $user, 1 );
   $smarty->assign("calendar",$newcal);*/

require 'taglist.php';
$ftags = array_flip( $tags);

$sql = "SELECT hid FROM ! WHERE id=? AND tree=? AND type=? AND tag = 'EVEN' AND level = '0' ORDER BY hid";
$result = $db->query( $sql, array( $db->gedcomTable, $ID, $user, 'I'));

while ( $row = $db->mfa( $result)) 
{
    $isql = "SELECT * FROM ! WHERE id=? AND tree=? AND type=? AND hid =? AND level > 0 ORDER BY level, inum";
    $rh = $db->query( $isql, array( $db->gedcomTable, $ID, $user, 'E', $row['hid']));

  while ( $a = $db->mfa( $rh)) {
    if ( array_key_exists( $a['tag'], $ftags)) {
            $i++;
            $events[$i]['name'] = $ftags[$a['tag']];
            $events[$i]['desc'] = $a['data'];
            $events[$i]['hid'] = $a['hid'];
            $event = true;
    } 
		
    if ( ( $a['tag'] == "TYPE" or $a['tag'] == "PLAC") and $event) {
      $events[$i]['formated_date'] = str_replace('%', '', date(SHORTDATEFORMAT, strtotime($a['data'])));
    } elseif ( $a['tag'] == "DATE" and $event) {
      $date = explode( "-", $a['data']);
      $events[$i]['formated_date'] = str_replace('%', '', date(SHORTDATEFORMAT, strtotime($a['data'])));
      $events[$i]['date']['year'] = $date[0];
      $events[$i]['date']['month'] = $date[1];
      $events[$i]['date']['day'] = $date[2];
    } 
  } 
}

if ( !$summary && $events) {
  foreach ( $events as $key => $value) {
    if ( $value['date']['month'] == $today['mon'] and $value['date']['day'] == $today['mday']) {
      $today_events[] = $value;
    }
  } 

  $smarty->assign( "today_events", $today_events);
} else {
  $smarty->assign( "today_events", $events);
} 

//@ checking if events exists(added by Andrew)
$smarty->assign( "summary", $summary );

// $user_events = $db->get_user_events ($ID);
$smarty->assign( "vars", $vars);
$smarty->assign( "fields", $fields);
$smarty->assign( "loadphp", true); 

// # Get the page we want to display
$smarty->assign( 'rendered_page', $smarty->fetch( 'moreinfo.tpl')); 

// # Display the page.
$smarty->display( 'index.tpl');

?>
