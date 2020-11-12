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

function show_menu($i = 0) {
  global $si1, $si2, $db, $loginPage, $ID, $mydata;
  global $_SESSION, $lang;
  $layerWidth = 140;
  $layerHeight = 250;
  if ($i == 0) {
    $mydata['si1sG'] = $db->sG($si1);
    $mydata['si1'] = $si1;
    $mydata['si2sG'] = $db->sG($si2);
    $mydata['si2'] = $si2;
  }
}

function show_word($i) {
  global $lang, $db;
  if ($i < 11) {
    switch ($i) {
      case 1:
        return $db->mytrans("##first##");
      case 2:
        return $db->mytrans('##second##');
      case 3:
        return $db->mytrans('##third##');
      case 4:
        return $db->mytrans('##fourth##');
      case 5:
        return $db->mytrans('##fifth##');
      case 6:
        return $db->mytrans('##sixth##');
      case 7:
        return $db->mytrans('##seventh##');
      case 8:
        return $db->mytrans('##eighth##');
      case 9:
        return $db->mytrans('##ninth##');
      case 10:
        return $db->mytrans('##tenth##');
    }
  } else return $db->mytrans('##level##') . " (" . $i . ")";
}

function show_word2($i) {
  global $db;
  switch ($i) {
    case 1:
      return $db->mytrans("##once##");
    case 2:
      return $db->mytrans("##twice##");
    default:
      return $i . " " . $db->mytrans("##times##");
  }
}

function celltext($ID) {
  global $db;
  $cellArray = array();
  $cellArray['ID'] = $ID;
  $cellArray['model'] = 'O';
  if ($ID == '-') $cellArray['model'] = '-';
  if ($ID == '|') $cellArray['model'] = '|';
  if ($ID == ' ') $cellArray['model'] = ' ';
  if ($ID == 'bk') $cellArray['model'] = 'bk';
  if ($cellArray['model'] <> 'O') return $cellArray;
  list($name) = $db->getItems(array('name'), $ID);
  $name = str_replace('/', '', $name);
  if ($db->getItem('hide', $ID) == 'Yes') {
    $name = $db->obstr($name, $ID);
  }
  // if a hide type is being applied for this indiviual, encrypt the name here
  $cellArray['name'] = $name;
  $spic = false;
  if ($db->hasPortrait($ID)) {
    $spic = true;
    $portrait = ($hidden != 'Yes') ? $db->getPortrait($ID) : '';
    $portrait = stripslashes($portrait);
    if (trim($portrait) != '') {
      $filePath = 'temp/' . $ID . '_portrait.png';
      if (file_exists($filePath)) unlink($filePath);
      File::writefile($filePath, $portrait);
    }
    $cellArray['portraitfile'] = $filePath;
  }
  $cellArray['spic'] = $spic;
  return $cellArray;
}

/*  Function get subtree from  $arel tree with $tid on top
*  Very sensitive code - Do Not Modify !!
*  by aTufa May/2005
*/
function getRelTree($arel, $tid) {
  $broken = false;
  $stree = array();
  $max_iteration = 1000;

  while ($tid && !$broken && $max_iteration) {
    $stree[] = $tid;
    $max_iteration--;
    
    for ($i = 0;$i < count($arel['id']);$i++) {
      if ($arel['id'][$i] == $tid) {
        $tid = $arel['par'][$i];
        break;
      }

      if ($i == (count($arel['id']) - 1)) {
        $broken = true;
        $stree[] = 'bk';
        break;
      }
    }
  }

  return $stree;
}
