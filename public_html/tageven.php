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

$flow = 1;
require_once 'config.php';

if ( $mr != 1)
    $mr = 0;

$mrtyp = ( $mr) ? "F" : "I";

$mydata['mr'] = $mr;
$mydata['ID'] = $ID;
$err = '';
require 'taglist.php';

require 'editeven.php';

if ( $mr == 1) {
    $tags = $famtags;
} 

$ftags = array_flip( $tags);

if ( $ID != '') {
	$nme = $db->getItem( "name", $ID);
	$nm = $db->changeBrack2( $nme);
	$smarty->assign( "tagslist", $ftags);
	$mydata['name'] = $nm;

	foreach ( $tags as $k => $v) {
		$ct[$v] = 0;
		$ct2[$v] = 0;
	} 
	$IDx = $ID;

	if ( $del == 1) {
		$mrtyp = ( $mr) ? "F" : "I";
		if ( $ctl && $ID && $user) {
			$sql = "DELETE FROM ! WHERE tree=? AND id=? AND type=? AND tag='EVEN' and hid=?";
			$r1 = $db->query( $sql, array( $db->gedcomTable, $user, $ID, $mrtyp, $ctl));

			$sql = "DELETE FROM ! WHERE tree=? AND id=? AND hid=? AND type='E'";
			$r2 = $db->query( $sql, array( $db->gedcomTable, $user, $ID, $ctl));

			if ( $r1 && $r2) {
				$delmsg = "##Event successfully deleted.##";
			} 
		} 
	} 

	if ( $editik == 1) {
			foreach ( $_POST as $k => $v) {
					if ( strstr( $k, "_pc") != false) {
							$t = split( "_", $k);
							$yy = split( "-", $t[0]);
							$xtag2 = $yy[0]; 
							// check for html tags
							$err .= $db->checkTags( 'even', $_POST[$xtag2 . "-" . $yy[1] . "_ds"]);
							$err .= $db->checkTags( 'even', $_POST[$xtag2 . "-" . $yy[1] . "_tp"]);
							$err .= $db->checkTags( 'even', $_POST[$xtag2 . "-" . $yy[1] . "_pc"]);
							$dt_m = $_POST[$xtag2 . "-" . $yy[1] . "_dt_1"];
							$dt_d = $_POST[$xtag2 . "-" . $yy[1] . "_dt_2"];
							$dt_y = $_POST[$xtag2 . "-" . $yy[1] . "_dt_3"];

							$dt_st = ( $_POST[$xtag2 . "-" . $yy[1] . "_dt"]) ? $_POST[$xtag2 . "-" . $yy[1] . "_dt"] : "$dt_y-$dt_m-$dt_d";
              $dt_st = ($dt_st == '-00-00' ? '' : $dt_st);
              
							procEven( $xtag2, $yy[1], $ID, $_POST[$xtag2 . "-" . $yy[1] . "_ds"], $_POST[$xtag2 . "-" . $yy[1] . "_pc"], $dt_st, $_POST[$xtag2 . "-" . $yy[1] . "_tp"], $mr);
					} 
			} 
	} 

	if ( $err != '') {
		$smarty->display( "tageven01.tpl");
		exit;
	} 
    
  // Count our events to get the next ID
  $sql = "SELECT hid, data FROM ! WHERE tree=? AND id=? AND type=? AND tag='EVEN' ORDER BY data, hid";
  $res = $db->query( $sql, array( $db->gedcomTable, $user, $ID, $mrtyp));
  $nid = 0;
  while($ir = $db->mfa($res)) {
    $ct2[$ir['data']] += 1;
    $evenl[] = array( 'hid' => $ir['hid'], 'tag' => $ir['data'], 'en' => $ct2[$ir['data']]);
    $nid = ( $ir['hid'] > $nid) ? $ir['hid'] : $nid;
  } 
  
  if ( $addik == 1) {
    $mydata['xtag'] = $xtag;
    $mydata['xtagdisp'] = $ftags[$xtag];
    $mydata['ct'] = $nid + 1;
    $mydata['en'] = $ct2[$xtag] + 1;
    $mydata['dt'] = $db->dateField( $xtag . "-" . $mydata['ct'] . "_dt", $dt);
    $mydata['addneweven'] = true;

    $smarty->assign( "mydata", $mydata);

    if ( !$hdrshown) {
      $smarty->display( 'tageven.tpl');
      $hdrshown = true;
    } 

    $smarty->display( 'editeven.tpl');
    $smarty->display( 'tageven01.tpl');
    exit;
  } 

  $hdrshown = false;
  if (is_array($evenl)) {
    $smarty->assign( "mydata", $mydata); 
    $smarty->display( "tageven.tpl");
    foreach ( $evenl as $k => $event) {
      showEven( $event['tag'], $event['hid'], $ID, $event['en'], $mr); 
      // show each event item
      $smarty->display( "editeven.tpl");
      $shown = true;
    } 
  } 
} 

$mydata['err'] = $err;

if ( $delmsg)
    $mydata['err'] .= "<br />" . $delmsg;
if ( !$shown) {
    $smarty->assign( "mydata", $mydata);
    if ( !$hdrshown) {
        $smarty->display( "tageven.tpl");
        $hdrshown = true;
    } 
} 
$smarty->display( "tageven01.tpl");

?>
