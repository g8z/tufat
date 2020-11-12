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
 * Vijay Nair                           12/Apr/2004
 */

require_once('config.php');
require_once('taglist.php');
$flow = 1;

if ($mr == 1) {
  $tags = $famtags;
}

$mydata['xtag'] = $xtag;
$mydata['ID'] = $ID;
$mydata['del'] = $del;
$mydata['add'] = $add;
$mydata['mid'] = $mid;
$mydata['ct'] = $ct;
$mydata['mr'] = $mr;
$recsList = array();

if ($xtag != '' && $ID != '') {
  if ($xtag == "MARR" || $xtag == "DIV") {
    $mr = 1;
  }
  
  switch ($xtag) {
    case "BIRT":
      $mydata['tagmsg'] = $db->mytrans("##Birth Notes##");
    break;
    case "DEAT":
      $mydata['tagmsg'] = $db->mytrans("##Death Notes##");
    break;
    case "ADOP":
      $mydata['tagmsg'] = $db->mytrans("##Adoption Notes##");
    break;
    case "BAPM":
      $mydata['tagmsg'] = $db->mytrans("##Baptism Notes##");
    break;
    case "BARM":
      $mydata['tagmsg'] = $db->mytrans("##Bar/Mitzvah Notes##");
    break;
    case "GRAD":
      $mydata['tagmsg'] = $db->mytrans("##Graduation Notes##");
    break;
    case "IMMI":
      $mydata['tagmsg'] = $db->mytrans("##Immigration Notes##");
    break;
    case "CONF":
      $mydata['tagmsg'] = $db->mytrans("##Confirmation Notes##");
    break;
    case "MARR":
      $mydata['tagmsg'] = $db->mytrans("##Marriage Notes##");
    break;
    case "DIV":
      $mydata['tagmsg'] = $db->mytrans("##Divorce Notes##");
    break;
    default:
      $ftags = array_flip($tags);
      if ($ftags[$xtag] != '') {
        $mydata['tagmsg'] = $ftags[$xtag] . " ";
      }
      $mydata['tagmsg'].= $db->mytrans("##Notes##");
  }
  
  if ($del == 1 && $mid > 0) {
    $mrtyp = ($mr) ? "F" : "I";
    if (!isset($_SESSION['read_only']) || $_SESSION["my_rec"] == $ID) {
      $sql = "delete from ! where tree=? and hid=? and id=? and tag='NREF' and type=?";
      $r = $db->query($sql, array($db->gedcomTable, $user, $mid, $ID, $mrtyp));
      $sql = "delete from ! where tree= ? and hid = ? and id= ? and type='N'";
      $r1 = $db->query($sql, array($db->gedcomTable, $user, $mid, $ID));
      if ($r1 != false) {
        $mydata['delmsg'] = $db->mytrans('##Note # $mid has been deleted successfully.##');
      } else {
        $mydata['delmsg'] = $db->mytrans("##Notes delete problem.##");
      }
    }
    header("Location: shownotes.php?ct=$ct&ID=$ID&xtag=$xtag&mr=$mr");
  } elseif ($add == 1) {
    if ($mr) {
      $maxnote = $db->getMaxMarrNoteID($ID) + 1;
    } else {
      $maxnote = $db->getMaxNoteID($ID) + 1;
    };
    header("Location: editnote.php?sid=$maxnote&ID=$ID&xtag=$xtag&mr=$mr&ct=$ct");
  }

  if ($mr == 1) {
    //@ query arguments mismatch - fixed by Andrew
    $sql = "select hid from ! where tree= ? and type = 'N' and id= ? and tag = 'NOTE' and data = 'MARR' order by inum";
    $r = $db->query($sql, array($db->gedcomTable, $user, $ID));
  } else {
    $sql = "select hid from ! where tree= ? and type = 'N' and tag = 'NOTE' AND data=? and id= ? order by hid";
    $r = $db->query($sql, array($db->gedcomTable, $user, strtoupper($xtag), $ID));
  }

  if ($r != false && $db->rowsInResult($r) > 0) {
    $i = 0;
    while ($a = $db->mfa($r)) {
      $i++;
      $recsList[$i]->hid = $a['hid'];
      $recsList[$i]->note = $db->getNote($a['hid'], $ID);
      $recsList[$i]->note['str'] = nl2br($recsList[$i]->note['str']);
    }
  }
  $smarty->assign('recsList', $recsList);
}

$mydata['recscnt'] = count($recsList);
$smarty->assign("mydata", $mydata);
$smarty->display('shownotes.tpl');
?>