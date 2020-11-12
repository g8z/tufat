<?php
ob_start();
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
 * This is modified to inorporate Smarty and Formsess template processors
 * VIjay Nair                                      26/3/2004
 */
/* Modified to incorporate data validation routines  15/Jul/2004  Vijay */
/* Bugfix ID-1 by aTufa May/2005
Please run also fix_id-1.php that remove ID-1 records.

Sibling 'male' bufix by aTufa June/2005
*/
require_once ('./config.php');
/*
flow chart of the logic behind editing/creating family and individual records

variables passed into this script:
$famc -> if this new individual is a child of an existing person, then $famc denotes the family record ID
$fams -> if this new individual is a spouse of an existing person, then $fams denotes the family record ID
*/
if (isset($_SESSION['admin']) || !$_SESSION["admin"]) {
  if ((isset($_SESSION['read_only']) && $_SESSION['read_only'])) {
    if (!isset($_SESSION['my_rec']) || $_SESSION["my_rec"] != $ID) {
      header("Location: load.php?ID=$ID");
      exit;
    }
  }
}
// #$db = new FamilyDatabase(); // connect to the database
// check to see if this record is locked. If it is, then redirect to page with unlock form
$locked = $db->getLock($ID);
// get the admin password from the $user session variable
list($password, $readOnlyPassword, $adminPassword) = $db->getPasswords($user);
// do not prompt for a password if this is a form submission
if (($lock_password == $adminPassword || $lock_password == MASTERPASSWORD)) $locked = '';
$sql = "select user from ! where user= ? and pass= ? and tp = '3'";
$r = $db->query($sql, array($db->ilogiTable, $user, $lock_password));
if ($r != false && $db->rowsInResult($r) > 0) {
  $locked = '';
}
if (strlen($locked) > 0 && isset($lock_password) && $lock_password == '') {
  $locked = '';
}
if (($locked != '' && $locked != stripslashes($lock_password)) && !isset($isex)) {
  header("Location: unlock.php?ID=$ID&chparent=$chparent&personID=$personID&personType=$personType&isex=$isex");
  exit;
}
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : null;
switch ($action) {
  case 'dropChildren':
    $db->dropChild($_REQUEST['parentID'], $_REQUEST['childID']);
    die(header('Location: load.php?ID=' . (int)$_REQUEST['returnID']));
  break;
  case 'dropSibling':
    $db->dropSibling($_REQUEST['parentID'], $_REQUEST['siblingID']);
    die(header('Location: load.php?ID=' . (int)$_REQUEST['returnID']));
  break;
}
$_POST["hide"] = ($_POST["hide"] == "on") ? "Yes" : "No";
if ($_POST['delete']) {
  // ->
  // remove the record from  $individuals table
  $nextOfKin = $db->removeRecord($ID);
  // load the person that we were at BEFORE the deleted record
  if ($nextOfKin) {
    $ID = $nextOfKin;
    header("Location: load.php?ID=$ID");
  } else {
    // path specified in config.php
    header("Location: " . VIEWSCRIPT . "?ID=$ID");
    // load the entire family tree, since no next-of-kin could be identified
    
  }
  exit;
}
if ($chparent == 2 && $ID != '' && $snid != '') {
  // ->
  $db->changeFamily($ID, $snid, $isex);
  header("Location: load.php?ID=$ID");
  exit;
}
if ($hidsta != '' && isset($_SESSION["admin"])) {
  // ->  $hidsta = false
  $dt = ($hide == "Yes") ? "Yes" : "No";
  $sql = "UPDATE ! SET data= ? WHERE type='I' AND tag='HIDE' AND id = ?";
  $db->query($sql, array($db->gedcomTable, $dt, $ID));
}
$err = '';
if ($submitForm) {
  /* Validate data  */
  foreach($_POST as $fld => $val) {
    if ($fld != 'email') {
      $err.= $db->checkTags($fld, $val);
    } elseif (!empty($val)) {
      $email = ($db->validEmail($_POST['email'])) ? $_POST['email'] : '';
    }
    if ($fld == 'birt_date') {
      $bdt = $val;
    }
    if ($fld == 'deat_date') {
      $ddt = $val;
    }
    if ($fld == 'dead') $dead = $val;
    $vars[$fld] = $val;
  }
  $err = trim($err);
  
  if ($err == '') {
    // here is valid data
    // if the "notify" checkbox was checked, then send the person an email
    if ($ntfy && USEMAIL && !empty($email) && $db->validEmail($email) && $ID != '') {
      $sql = "SELECT email FROM {$db->userTable} WHERE username =" . $db->dbh->quote($user);
      $mailfrom = $db->getValue($sql);
      $today = date("F j, Y \a\\t\t g:i a");
      $subject = $db->mytrans("##An update has been made to your record in the family tree!##");
      $message = $db->mytrans("##This is an automatic notification that an update was made to your family tree record on $today.##") . $db->mytrans("##To view your record click##") . " <a href='http://" . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'] . "?ID=" . $ID . "'>" . $db->mytrans("##here##") . "</a>.\n\n" . $db->mytrans("##You will need your family tree login ID and password to access or edit the record.##");
      if (USE_SMTP_CLASS) {
        // use smtp class for mail sending # jrpi:02.02.06
        require 'smtp.class.php';
        $r_e = send_mail($mailfrom, $mailfrom, $email, $subject, $message);
      } else {
        $xheaders = "From: $mailfrom\r\n";
        mail($email, $subject, $message, $xheader);
      }
    }

    if (!$ID or $new == 1 or $ID == '') {
      // get a new record ID if we are adding a new record (not updating existing)
      $ID = $db->lgetNewID('I');
      $db->lputValue($ID, 'I', 'INDI', 0, '@I' . $ID . '@');
    }
    if ($personType == "mother" || $personType == "father") {
      // so that we don't insert 2 instances of fams later on
      $famsSaved = $_POST['fams'];
      $_POST['fams'] = "";
    }
    //hack for person note problem
    if (isset($_POST['note'])) {
      $_POST['note'] = str_replace("\n", '<br />', $_POST['note']);
      $db->query('DELETE FROM ! WHERE type=\'I\' AND ID=? AND tag in (\'NOTE\', \'CONT\')', array($db->gedcomTable, $ID));
    }

    $person_gedcom = array('name' => '', 'surn' => '', 'sex' => '', 'birt_date_1' => '', 'birt_date_2' => '', 'birt_date_3' => '', 'birt_date' => '', 'deat_date_1' => '', 'deat_date_2' => '', 'deat_date_3' => '', 'deat_date' => '', 'birt_plac' => '', 'deat_plac' => '', 'emai' => '', 'URL' => '', 'addr' => '', 'addr_city' => '', 'addr_stae' => '', 'addr_post' => '', 'addr_ctry' => '', 'phon' => '', 'npfx' => '', 'spfx' => '', 'nick' => '', 'nsfx' => '', 'birt_type' => '', 'birt_addr' => '', 'deat_caus' => '', 'deat_addr' => '', 'note' => '', 'chan_date' => '', 'fams' => '', 'famc' => '',);
    // routine to add all the form data to the database...
    //$gedcom = $db->buildGEDCOM($_POST, $person_gedcom);
    $gedcom = $db->buildGEDCOM($_POST);
    $txt = $db->getIndiGeds($ID);
    $gedcom = $db->procGedcom($gedcom, $txt);
    // add the variables to the database fields. this function has no effect if not using mysql
    if (strlen($_POST["lock_password"]) >= 0) {
      $sql = "SELECT * FROM ! WHERE id = ?";
      $r = $db->query($sql, array($db->locksTable, $ID));
      if ($r != false && $db->rowsInResult($r) < 1) $sql = "INSERT INTO ! (id, lock_password, tree) VALUES ( ?, ?, ?)";
      $r = $db->query($sql, array($db->locksTable, $ID, $_POST["lock_password"], $db->lgetUser()));
      $sql = "UPDATE ! SET lock_password= ? WHERE id = ?";
      $r = $db->query($sql, array($db->locksTable, $_POST["lock_password"], $ID));
    }
    // save GEDCOM data
    if ($mimik != 1) {
      $db->updateGEDCOM($gedcom, $ID, 'I');
    }

    // restore $fams variable from  $famsSaved
    if ($famsSaved) {
      $fams = $famsSaved;
      $_POST['fams'] = $famsSaved;
    }
    if ($personType == "partner") {
      $mfam = $db->lgetFamilyP($ID);
      if (!$mfam) {
        $familyID = $db->lgetNewID('F');
        $db->lputValue($familyID, 'F', 'FAM', 0, '@F' . $familyID . '@');
        // update the husband and wife individual records
        // / ID-1 2nd fix
        if ($personID > 0) $db->addSpouse($familyID, $personID);
      } else $familyID = $mfam;
      // / ID-1 2nd fix
      if ($ID > 0) $db->addSpouse($familyID, $ID);
    }
    // update the spouse info, since this is a new mother or father of someone. The addSpouse function determines
    // whether to add a wife or husb record by looking up the sex of the $ID person
    if ($personType == "mother" || $personType == "father") {
      // determine if $personID has an entry for $famc. if not, then create a new family record
      $famc = $db->lgetFamilyC($personID);
      if ($famc == '') {
        $familyID = $db->lgetNewID('F');
        $db->lputValue($familyID, 'F', 'FAM', 0, '@F' . $familyID . '@');
        $famc = $familyID;
        // now add the child ($personID) to this family record that has the new father/mother
        
      }
      $db->addChild($famc, $personID);
      // add this new parent to the family record
      // / ID-1 2nd fix
      if ($ID > 0) $db->addSpouse($famc, $ID);
    }
    if ($personType == "sibling") {
      if ($sex == "M") $personType = "son";
      else $personType = "daughter";
    }

    if ($personType == "son" || $personType == "daughter") {
      // determine the sex of the parent with ID = $personID
      $parentGender = $db->getItem("sex", $personID);
      $famc = $db->lgetFamilyP($personID);
      // if we are adding a new son or new daughter, and famc is not specified,
      // then it means that we have to add a new parent record
      if (!$famc) {
        // create a new family record for this new family
        $familyID = $db->lgetNewID('F');
        $db->lputValue($familyID, 'F', 'FAM', 0, '@F' . $familyID . '@');
        $famc = $familyID;
        // add the original parent as a spouse in this new family record
        // / ID-1 2nd fix
        if ($personID > 0) $db->addSpouse($famc, $personID);
      }
      // now add this person as a CHILD of the family with ID = $famc
      // does NOT add a famc reference to the individuals table, as ->addChild(..) function does!
      $db->addChild($famc, $ID);
    }
    if ($personID > 0) $db->updateIndex($personID);
    $db->updateIndex($ID);
    // load this individuals record or record from which this record was added
    header('Location: load.php?ID=' . (int)(isset($_REQUEST['personID']) ? $_REQUEST['personID'] : $ID));
    exit;
  }
  // if $new variable is set, then this is the very first person in the entire family tree
  
} elseif (!$personType && !$new) {
  // we are editing a person's record, so we need to populate the $vars[] array with the data
  $edit = true;
  // get the raw GEDCOM string for this person's record, given the person's ID
  $gedcom = $db->getGEDCOM('I', $ID);
  // extract GEDCOM info into variables that we can use to populate our form fields
  $vars = $db->extractGEDCOM($gedcom);
  // remove the /SURNAME/ part of the $name string
  $nameParts = explode("/", $vars['name']);
  $vars['name'] = $nameParts[0];
  if (!$vars['surn']) $vars['surn'] = $nameParts[1];
  // $vars['name'], $vars['surn'] or $vars['sex'] is empty, then we might have a case where the
  // database table data is correct but the GEDCOM information is invalid (e.g. bad import?), so we correct
  // function getItem( $gedcomKey, $ID, $table = 1 )
  // now loop through all $vars and replace " with ' (so that we do not truncate data in form field)
  $varsKeys = array_keys($vars);
  foreach($varsKeys as $varKey) {
    if (strstr($varKey, "note") == false && strstr($varKey, "ctit") == false && strstr($varKey, "hinf") == false) {
      $vars[$varKey] = str_replace('"', "'", $vars[$varKey]);
    }
  }
  $vars["hide"] = $db->getItem("hide", $ID);
  $vars["dead"] = $db->getItem("dead", $ID);
  $vars["birt_note"] = $db->getItem("birt_note", $ID);
  $vars["addr_ctry"] = $db->getItem("addr_ctry", $ID);
}
if ($chparent == 1) {
  $mydata['personType'] = $personType;
  if (ANIMALPEDIGREE) {
    if ($personType == "mother") $persontype_text = $db->mytrans("##Dam##");
    else $persontype_text = $db->mytrans("##Sire##");
  } else $persontype_text = $db->mytrans("##$personType##");
  $persontype_text.= " ##of## ";
  if (ANIMALPEDIGREE) {
    $mydata['person_name'] = $db->changeBrack(($db->getItem("name", $personID)));
  } else {
    $mydata['person_name'] = (str_replace("/", "", $db->getItem("name", $personID)));
  }
  if ($personType == "father") {
    $isex = "M";
    $pid = $db->getFather($personID);
  } else {
    $isex = "F";
    $pid = $db->getMother($personID);
  }
  $mydata['persontype_text'] = $persontype_text;
  $mydata['isex'] = $isex;
  $mydata['ID'] = $personID;
  $mydata['pid'] = $pid;
  $list = $db->getIndi($isex);
  asort($list);
  if ($isex == "M") {
    if (!ANIMALPEDIGREE) $snids['-1'] = $db->mytrans("##No father##");
    else $snids['-1'] = $db->mytrans("##No sire##");
  } else {
    if (!ANIMALPEDIGREE) $snids['-1'] = $db->mytrans("##No mother##");
    else $snids['-1'] = $db->mytrans("##No dam##");
  }
  if (!is_array($list)) $list = array();
  foreach($list as $k => $v) {
    if ($personID != $v) {
      $k = str_replace("/", "", $k);
      $k = $k . " (ID# $v)";
      if (strlen($k) > 60) $k = substr($k, 0, 60) . "...";
      $snids[$v] = $k;
    }
  }
  $smarty->assign("mydata", $mydata);
  $smarty->assign("mytrans", $mytrans);
  $smarty->assign("snids", $snids);
  $smarty->assign("ID", $personID);
  // now display the form
  $smarty->assign('rendered_page', $smarty->fetch('edit_chparent01.tpl'));
  // # Display the page.
  $smarty->display('index.tpl');
  exit;
}
$mydata['new'] = $new;
$mydata['edit'] = $edit;
$mydata['hide'] = $vars["hide"];
if (!$new) {
  $prevID = (!$personID) ? $ID : $personID;
  // get the name from the $personID variable
  if ($edit) $personID = $ID;
  $name = $db->getItem("name", $personID);
  $name = (ANIMALPEDIGREE) ? $db->changeBrack($name) : str_replace("/", "", $name);
  $hid = $db->getItem("hide", $ID);
  if ($hid == "Yes" && (1 != 1)) $name = $db->obstr($name, 1);
  if ($edit) {
    $name = $db->changeBrack($name);
  }
} else {
  if (!ANIMALPEDIGREE) {
    $mydata['createtree_text'] = ($db->getIndiCount() > 0) ? $db->mytrans("##Create a new person in your tree##") : $db->mytrans("##Create the first person in your tree##");
  } else {
    $mydata['createtree_text'] = $db->mytrans("##Create a new member in the tree##");
  }
}
$mydata['personType'] = $personType;
$mydata['personID'] = $personID;
$mydata['name'] = $vars['name'];
$mydata['surn'] = $vars['surn'];
$mydata['sp1'] = $sp1;
$mydata['animalPedigree'] = ANIMALPEDIGREE;
$mydata['err'] = $err;
if ($personType == "partner" || $personType == "son" || $personType == "daughter" or $personType == "father" or $personType == "mother" or $personType == "sibling") {
  // give the user the option of copying data from another record
  $selectList["0"] = "--- ##Select## ---";
  // assumes $sp1 variable has been passed in via GET string
  $spouseGender = $db->getItem("sex", $sp1);
  $spouseGender = $db->reverseGender($spouseGender);
  // get a list of the current spouses of person $sp1... do not include that person in the spouse list
  if ($personType == "partner") {
    if (PARNER_ANY_SEX) {
      $spouseList = $db->getIndi();
    } else {
      $spouseList = $db->getIndi($spouseGender);
    }
    $l2 = $db->getSpouses($personID);
    foreach($spouseList as $k => $v) {
      foreach($l2 as $k2 => $v2) {
        if ($v2 == $v) {
          $spouseList[$k] = - 1;
        }
      }
    }
  } elseif ($personType == 'son' || $personType == 'daughter' || $personType == 'mother' || $personType == 'father' || $personType == 'sibling') {
    $sx = '';
    if ($personType == 'son' || $personType == 'father') {
      $sx = 'M';
    } elseif ($personType == 'daughter' || $personType == 'mother') {
      $sx = 'F';
    }
    $spouseList = $db->getIndi($sx);
  }
  foreach($spouseList as $spouseName => $spouseNumOnly) {
    if ($spouseNumOnly > 0) {
      // it was >=0; ID-1 bug fix aTufa
      $spouseName = str_replace('/', '', $spouseName);
      $spouseNameAndNum = $db->sG($spouseNumOnly);
      if (strlen($spouseNameAndNum) > 60) $spouseNameAndNum = substr($spouseNameAndNum, 0, 60) . '...';
      if ($spouseNumOnly != $personID) $selectList[$spouseNumOnly] = stripslashes($spouseNameAndNum);
    }
  }
  $smarty->assign('spouse_selectlist', $selectList);
}
if ($personType == 'mother' || $personType == 'father') {
  // we need to know if $personID has already had the second parent specified. If not, give the chance for the user to
  // give the second parent a name
  $childName = $db->getItem('name', $personID);
  $otherParentType = $db->reversePersonType($personType);
}
if ($personType == 'son' || $personType == 'daughter') {
  // get a list of all possible families that this new child could be in, and put into $famsList array
  // $personID is the *parent* ID of this
  $famsList = $db->getFamsList($personID);
  // determine who the possible mothers or fathers are of this person
  $parentGender = strtolower($db->getItem('sex', $personID));
  if ($parentGender == 'm') {
    $genderString = 'her';
    // this parent is "m", so the OTHER parentType is "mother" (opposite of "m", which is male)
    $parentType = 'mother';
  } else {
    $genderString = 'his';
    $parentType = 'father';
  }
}
if ($new == 1 || $vars['hide'] != 'Yes' || isset($_SESSION['admin'])) {
  if (ANIMALPEDIGREE) {
    $mytrans['name_eg'] = '';
  } else {
    if (strtolower($vars['sex']) == 'f' || $spouseGender == 'F' || $personType == 'mother' || strtolower($sex) == 'f') {
      $mytrans['name_eg'] = '##(e.g. Betsy Sue)##';
    } else {
      $mytrans['name_eg'] = '##(e.g. George William)##';
    }
  }
  if ($vars['sex'] == 'm' || $vars['sex'] == 'M' || $personType == 'father' || $personType == 'son') $sex = 'M';
  $sexchecked = '';
  $c = $db->getChildren($ID);
  $nc = (count($c) > 0) ? false : true;
  if ($vars['sex'] == 'M' || $vars['sex'] == 'm' || ($personID && $spouseGender == 'M' && !PARNER_ANY_SEX) || $personType == 'son' || $personType == 'father' || $personType == 'brother') {
    $mydata['sm'] = ' checked ';
    $mydata['sf'] = '';
    $sex = 'M';
  }
  if (($personID && $spouseGender == 'F' && !PARNER_ANY_SEX) || $personType == 'daughter' || $personType == 'mother' || $vars['sex'] == 'F' || $vars['sex'] == 'f') {
    $mydata['sm'] = '';
    $mydata['sf'] = ' checked ';
    $sex = 'F';
  }
  if (USEMAIL && MYSQL) {
    $notify = $db->getItem('ntfy', $ID);
    $mydata['emailnotify'] = true;
    if ($notify == 1) $mydata['notifyChecked'] = ' checked ';
  }
  $mydata['ID'] = $ID;
  $mydata['nc'] = $nc;
  $mydata['sex'] = $sex;
  // sibling bufix aTufa
  if ($personType == 'sibling') unset($mydata['sex']);
  $mydata['spouseGender'] = $spouseGender;
  $mydata['birth_date_data_field'] = $db->dateField('birt_date', $vars['birt_date']);
  $mydata['death_date_data_field'] = $db->dateField('deat_date', $vars['deat_date']);
  list($mydata['birt_type'], $mydata['birt_addr'], $mydata['deat_caus'], $mydata['deat_addr']) = $db->getItems(array('birt_type', 'birt_addr', 'deat_caus', 'deat_addr'), (int)$ID);
  // $mytrans['BurialSite'] = $db->mytrans( 'Burial Site' );
  $mydata['lockPassword'] = $db->getLock($ID);
  $mydata['today'] = date("j M Y", time());
  $vars['hinf'] = stripslashes($vars['hinf']);
  $vars['ctit'] = stripslashes($vars['ctit']);
  $vars['note'] = stripslashes($vars['note']);
}
if ($ID) {
  // get the children of this individual. If there are any children at all, then the individual cannot be deleted because it could create floating family nodes!
  $sons = $db->getSons($ID, 'm');
  $daughters = $db->getDaughters($ID, 'f');
  // concatenate the sons and daughters array
  $childArray = array_unique(array_merge($sons, $daughters));
  foreach($childArray as $childID) {
    $childIDList.= 'ID #' . $childID . ', ';
  }
  $childIDList.= ",";
  // can be replaced with regular expression
  $childIDList = str_replace(", ,", "", $childIDList);
  $mydata['childIDList'] = $childIDList;
  $mydata['children_count'] = sizeof($childArray);
}
// print the hidden variables! These variables are just the numeric or |num|num|num| value - no @F ..@ in it
// thus, we don't want to use $vars[] variables, since those variables are extracted from the raw GEDCOM
if (!$new) {
  list($fams, $famc) = $db->getItems(array("fams", "famc"), $personID);
  $mytrans['famc'] = $famc;
  $mytrans['fams'] = $fams;
}
//handle note for textarea
if (isset($_POST['note'])) {
  $_POST['note'] = str_replace('<br />', "\n", $_POST['note']);
}
$smarty->assign('myvars', $vars);
$smarty->assign('mydata', $mydata);
$smarty->assign('mytrans', $mytrans);
$smarty->assign('loadphp', true);
// # Get the page we want to display
$smarty->assign('rendered_page', $smarty->fetch('edit.tpl'));
// # Display the page.
$smarty->display('index.tpl');
?>
