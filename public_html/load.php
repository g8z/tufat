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
 * violation of the copyright. TUFaT comes with no guarantees
 * for reliability or accuracy - in other words, you use this script
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

 // access check
//@ navMenu fix for load.php page
$_REQUEST['mitem'] = 'view';
require_once 'config.php';


$ID = (int)$_REQUEST['ID'];
$submitForm = $_POST['submitForm'];

// if no $ID present, load entire tree, unless its the form submission of person #1 in the tree
if (!$ID && !$submitForm) {
  // check to see if this is a form submission for person #1
  header("Location: " . VIEWSCRIPT);
  exit;
}

if (empty($user)) {
  $user = $db->getValue("SELECT tree FROM {$db->gedcomTable} WHERE id=" . (int)$ID . ' LIMIT 0, 1');
}

if (DEFAULTVIEW == 1 && !$_SESSION['admin']) {
  $_SESSION['read_only'] = 1;
}

$sql = "SELECT id FROM {$db->gedcomTable} WHERE tree= " . $db->dbh->quote($user) . " AND type='I' AND id = " . $db->dbh->quote($ID);
$r = $db->getValue($sql);
if ($r < 1 || $r == false) {
  header("Location: nf.php?OLDID=$ID");
  exit;
}


list($name, $family, $gender, $hidden) = $db->getItems(array("name", "surn", "sex", "hide"), $ID);

// get the mother information
// get the familyID that this person is a *child* of
$familyID = $db->lgetFamilyC($ID);
$motherID = $db->getMother($ID);
$fatherID = $db->getFather($ID);

if ($motherID) {
  // get the mother name from the ID
  $motherName = $db->getItem('name', $motherID);
  $hid1 = $db->getItem('HIDE', $motherID);
  if ($motherName == '' || $motherName == false) $motherName = $db->mytrans('##Unknown##');
  $motherName = $db->removeFam($motherName);
  if ($hid1 == 'Yes') {
    $motherName = $db->obstr($motherName, 1);
  }
}

if ($fatherID) {
  // get the father name from the ID
  $fatherName = $db->getItem('name', $fatherID);
  $hid2 = $db->getItem('HIDE', $fatherID);
  $fatherName = $db->removeFam($fatherName);
  if ($hid2 == 'Yes') $fatherName = $db->obstr($fatherName, 1);
  if (!$fatherName) $fatherName = $db->mytrans('##Unknown##');
}

if (strtolower($gender) == 'm') {
  // strtolower may not be needed here
  $genderString = $db->mytrans('##Male##');
} else {
  $genderString = $db->mytrans('##Female##');
}

if ($hidden == 'Yes') {
  $genderString = '*';
}

if (!ANIMALPEDIGREE) {
  $name = $db->removeFam($name);
  $family = $db->getItem('surn', $ID);
  if ($hidden == 'Yes') {
    $name = $db->obstr($name, 1);
    $family = $db->obstr($family, 2);
  }
} else {
  $name = $db->changeBrack($name);
  $family = "";
}

// get the birth and death dates here
list($birt_date, $mydeat_date, $mybirt_plac, $myburi_site, $myoccu) = $db->getItems(array('birt_date', 'deat_date', 'birt_plac', 'buri_plac', 'occu'), $ID);
if ($mydeat_date == '0000') {
  $mydeat_date = null;
}

$birt_date = $db->dateFormat($birt_date, 3);
$mybirt_date = ($birt_date != '') ? $db->mytrans("##b. $birt_date##") : '';
$mydeat_date = $db->dateFormat($mydeat_date, 3);

if ($hidden == 'Yes') {
  $mybirt_date = $db->obstr($mybirt_date);
  $mydeat_date = $db->obstr($mydeat_birt);
}

// get the birth place
if ($hidden == 'Yes') {
  $myburi_plac = $db->obstr($myburi_site);
  $myburi_site = $db->obstr($myburi_site);
  $myoccu = $db->obstr($myoccu);
  $mybirt_plac = $db->obstr($mybirt_plac);
}

if (ANIMALPEDIGREE) {
  $bred = $db->getIndexItem('bred', $ID);
  $cdea = $db->getIndexItem('cdea', $ID);
} else {
  $bred = '';
  $cdea = '';
}

$bio = $db->getBio($ID);
$fp = $db->lgetFamilyP($ID);
if ($fp == false) {
  $fp = $db->lgetFamilyC($ID);
}

if ($fp != false) {
  $sql = "select count(id) from {$db->famgalTable} where tree=" . $db->dbh->quote($user) . " and  fid=" . $db->dbh->quote($fp);
  $r = $db->getValue($sql);
  $fp_x = ($r != false) ? $r : 0;
}

$sql = "SELECT COUNT(id) FROM {$db->famgalTable} WHERE tree=" . $db->dbh->quote($user) . " AND ((indi='1' AND sid =" . $db->dbh->quote($ID) . ") OR kd = '1')";
$r = $db->getValue($sql);
$x = ($r != false) ? $r : 0;
$xfpx = $fp_x + $x;
$ct1 = " ($xfpx)";
$sql = "SELECT COUNT(id) FROM {$db->ilinkTable} WHERE tree=" . $db->dbh->quote($user) . " AND sid =" . $db->dbh->quote($ID);
$r = $db->getValue($sql);
$y = ($r != false) ? $r : 0;
$ct2 = " ($y)";

/* Now work on getting the Portrait and show it..  */
$hidden = $db->getIndexItem('HIDE', $ID);
// write portrait to temp file
if ($db->hasPortrait($ID)) {
  $portrait = ($hidden != 'Yes') ? $db->getPortrait($ID) : '';
  $tmp_image = $portrait;

  if (trim($portrait)) {
    $filePath = TEMP_DIR. $ID . '_portrait.png';
    if (file_exists($filePath)) { 
      @unlink($filePath);
    }
    
    File::writefile($filePath, $portrait);
    // set src propertie for HTML IMG tag
    $portrait = str_replace(getcwd().'/', '', $filePath);
    
    // get the dimensions
    list($img_w, $img_h, $img_type) = getimagesize($filePath);
    if (!empty($img_h) && !empty($img_w) && ($img_h / $img_w > MAXPORTRAITHEIGHT / MAXPORTRAITWIDTH)) {
      $new_h = MAXPORTRAITHEIGHT;
      $new_w = (int)(MAXPORTRAITHEIGHT / $img_h * $img_w);
    } else {
      $new_h = (empty($img_w) && empty($img_h) ? MAXPORTRAITWIDTH : (int)(MAXPORTRAITWIDTH / $img_w * $img_h));
      $new_w = MAXPORTRAITWIDTH;
    }
    
    if ($img_h < $new_h) $new_h = $img_h;
    if ($img_w < $new_w) $new_w = $img_w;
    $heightLimit = "height=" . '"{$new_w}"';
    $widthLimit = "width=" . '"{$new_h}"';

    //@ detect image type: png,jpeg,gif and resample image for lighweight
    switch ($img_type) {
      case 1: //GIF
        $src_img = imagecreatefromstring($tmp_image);
      break;
      case 2: //JPEG
        $src_img = imagecreatefromstring($tmp_image);
      break;
      case 3: //PNG
        $src_img = imagecreatefromstring($tmp_image);
      break;
      default:
        $src_img = false;
      break;
    }
    
    $tmp_image = null;
    if ($src_img) {
      $dst_img = imageCreateTrueColor($new_w, $new_h);
      imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $new_w, $new_h, $img_w, $img_h);
      if (file_exists($filePath)) @unlink($filePath);
      imagejpeg($dst_img, $filePath);
    }
  } else {
    @unlink($filePath);
  }
}
/* Now work on spouse's informations   */
$spouse_recs = array();
$spouses = $db->getSpouses($ID);
$i = 0;
foreach($spouses as $spouse) {
  list($spouseID, $spouseName, $spouseSurname, $spouseSex) = $db->getItems(array('ID', 'name', 'surn', 'sex'), $spouse);
  // reset marriage variables for each new spouse
  $marriagePlace = '';
  $marriageDate = '';
  // get the marriage data for this couple
  $marriage = $db->getMarriageInfo($ID, $spouseID);
  if (trim($marriage[0]) != '') $marriagePlace = trim($marriage[0]);
  $spouseName = $db->removeFam($spouseName);
  $hid = $db->getItem('HIDE', $spouseID);
  if ($hid == 'Yes') $spouseName = $db->obstr($spouseName, 1);
  if ($marriagePlace != '' || $marriage[1]) {
    $marriageDate = $db->dateFormat($marriage[1], 3);
  }
  $testD = $db->areDivorced($ID, $spouseID);
  $flist1 = $db->lgetFamiliesP($ID);
  $flist2 = $db->lgetFamiliesP($spouseID);
  // turned back vijay.
  $dis = false;
  // Changed by Swaroop
  foreach($flist1 as $k => $f1) {
    if ($flist2) foreach($flist2 as $k2 => $f2) {
      if ($f1 == $f2) {
        $sql = "select id from {$db->gedcomTable} where tree= " . $db->dbh->quote($user) . " and type='F' and tag='WIFE' and id = " . $db->dbh->quote($fl);
        $c1 = $db->getCol($sql);
        $sql = "select id from {$db->gedcomTable} where tree= " . $db->dbh->quote($user) . " and type='F' and tag='HUSB' and id = " . $db->dbh->quote($fl);
        $c2 = $db->getCol($sql);
        $sql = "select id from {$db->gedcomTable} where tree= " . $db->dbh->quote($user) . " and type='F' and tag='CHIL' and id = " . $db->dbh->quote($fl);
        $c3 = $db->getCol($sql);
        // Changed by Swaroop - changed back Vijay
        if (count($c1) == 1 && count($c2) == 1 && count($c3) == 0) {
          $dis = true;
        }
      }
    }
  }
  $spouse_recs[$i]->spouseID = $spouseID;
  $spouse_recs[$i]->spouseName = ($spouseName != '') ? $spouseName : 'Missing Name';
  $spouse_recs[$i]->spouseSurname = $spouseSurname;
  $spouse_recs[$i]->dis = $dis;
  $spouse_recs[$i]->testD = $testD;
  $spouse_recs[$i]->marriagePlace = $marriagePlace;
  $spouse_recs[$i]->marriageDate = $marriageDate;
  $spouse_recs[$i]->link = ($spouseSex == 'F') ? 'pinkNormal' : 'blueNormal';
  $i++;
}
$spousecnt = $i;
/* Data for siblings   */
$siblingcnt = 0;
$p1id = $db->getFather($ID);
$p2id = $db->getMother($ID);
if ($p1id != false) {
  $siblingcnt = $p1id;
} elseif ($p2id != false) {
  $siblingcnt = $p2id;
}
/* Collect data about Male Offsprings   */
$sons = $db->getSons($ID);
$i = 0;
$sons_recs = array();
foreach($sons as $son) {
  list($sonID, $sonName, $birt_date, $birt_plac) = $db->getItems(array('ID', 'name', 'BIRT_DATE', 'birt_plac'), $son);
  $sonName = $db->removeFam($sonName);
  $hid = $db->getItem('HIDE', $sonID);
  if ($hid == 'Yes') $sonName = $db->obstr($sonName, 1);
  if ($birt_date && $hid != 'Yes') {
    $birt_date = $db->dateFormat($birt_date, 3);
    $birt_date = $db->mytrans("##b. Date: $birt_date##");
  } else {
    $birt_date = '';
  }
  $birt_plac = ($birt_plac && $hid != 'Yes') ? $db->mytrans("##b. Place: $birt_plac##") : '';
  $sons_recs[$i]->sonID = $sonID;
  $sons_recs[$i]->sonName = $sonName;
  $sons_recs[$i]->birt_date = $birt_date;
  $sons_recs[$i]->birt_plac = $birt_plac;
  $i++;
}
$sons_cnt = $i;
$i = 0;
/* Now get information about female offsprings  */
$daughters = $db->getDaughters($ID);
foreach($daughters as $daughter) {
  list($daughterID, $daughterName, $birt_date, $birt_plac) = $db->getItems(array('ID', 'name', 'birt_date', 'birt_plac'), $daughter);
  $daughterName = $db->removeFam($daughterName);
  $hid = $db->getItem('HIDE', $daughterID);
  if ($hid == 'Yes') $daughterName = $db->obstr($daughterName, 1);
  if ($birt_date && $hid != 'Yes') {
    $birt_date = $db->dateFormat($birt_date, 3);
    $birt_date = $db->mytrans("##b. Date: $birt_date##");
  } else {
    $birt_date = '';
  }
  $birt_plac = ($birt_plac && $hid != 'Yes') ? $db->mytrans("##b. Place: $birt_plac##") : '';
  $daughters_recs[$i]->daughterID = $daughterID;
  $daughters_recs[$i]->daughterName = $daughterName;
  $daughters_recs[$i]->birt_date = $birt_date;
  $daughters_recs[$i]->birt_plac = $birt_plac;
  $i++;
}
$daughters_cnt = $i;
/* Now collect information about siblings    */
$brothers = $db->getBrothers($ID);
$sisters = $db->getSisters($ID);
$siblings = array_merge($brothers, $sisters);
$siblings_recs = array();
$listed = array();
$i = 0;
/* Get father and mother of siblings  */
foreach($siblings as $sibling) {
  list($siblingID, $siblingName, $birt_date, $birt_plac, $sex) = $db->getItems(array('ID', 'name', 'birt_date', 'birt_plac', 'sex'), $sibling);
  $siblingName = ($siblingName == '') ? $siblingID : $db->removeFam($siblingName);
  $hid = $db->getItem('HIDE', $siblingID);
  if ($hid == 'Yes') $siblingName = $db->obstr($siblingName, 1);
  $siblingClassString = ($sex == 'M') ? 'class=blueLinkNormal' : 'class=pinkLinkNormal';
  // check for 1/2 brothers and sisters
  $siblingFatherID = $db->getFather($sibling);
  $siblingMotherID = $db->getMother($sibling);
  $halfString = '';
  if ($fatherID != $siblingFatherID || $motherID != $siblingMotherID) $halfString = " &frac12";
  if ($listed[$siblingID] != 1) {
    $listed[$siblingID] = 1;
    if ($birt_date && $hid != 'Yes') {
      $birt_date = $db->dateFormat($birt_date, 3);
      $birt_date = $db->mytrans("##b. Date: $birt_date##");
    } else {
      $birt_date = '';
    }
    $birt_plac = ($birt_plac && $hid != 'Yes') ? $db->mytrans("##b. Place: $birt_plac##") : '';
    $siblings_recs[$i]->siblingID = $siblingID;
    $siblings_recs[$i]->siblingClassString = $siblingClassString;
    $siblings_recs[$i]->siblingName = $siblingName;
    $siblings_recs[$i]->halfString = $halfString;
    $siblings_recs[$i]->birt_date = $birt_date;
    $siblings_recs[$i]->birt_plac = $birt_plac;
  }
  $i++;
}
$siblings_cnt = $i;
$smarty->assign('mydata', array(familyID => $db->lgetFamilyC($ID), motherID => $motherID, motherName => trim($motherName), fatherID => $fatherID, fatherName => trim($fatherName), myname => trim($name), myfamily => $family, mygender => $gender, myhidden => $hidden, animalPedigree => ANIMALPEDIGREE, ID => $ID, read_only => $_SESSION['read_only'], edit_only => $_SESSION['edit_only'], my_rec => $_SESSION['my_rec'], deat_date => $mydeat_date, birt_date => $mybirt_date, birt_plac => $mybirt_plac, buri_site => $myburi_site, occu => $myoccu, bred => $bred, cdea => $cdea, mybio => trim($bio), genderString => $genderString, gallerycnt1 => $ct1, gallerycnt2 => $ct2, portrait => $portrait, filetime => time(), widthLimit => $widthLimit, heightLimit => $heightLimit, filePath => $filePath, spouses_count => $spousecnt, siblingscnt => $siblingscnt, sons_cnt => $sons_cnt, daughters_cnt => $daughters_cnt, siblings_cnt => $siblings_cnt, img_h => IMG_H));
$smarty->assign('templateID', $templateID);
$smarty->assign('spouse_recs', $spouse_recs);
$smarty->assign('sons_recs', $sons_recs);
$smarty->assign('daughters_recs', $daughters_recs);
$smarty->assign('siblings_recs', $siblings_recs);
$smarty->assign('loadphp', true);
// # Get the page we want to display
$smarty->assign('rendered_page', $smarty->fetch('load.tpl'));
// # Display the page.
$smarty->display('index.tpl');
?>
