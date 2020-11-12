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

require_once 'config.php';
$flow = 1;
$err = '';

if ($sid != '') {
  if ($postik == 1) {
    $err = $db->checkTags('note', $snote);
    $snote = trim($snote);

    if ($err == '' && $snote != '') {
      $db->setNote($snote, $ID, $xtag, $mr, $sid);
    }

    if ($snote == '') {
      $postik = '';
      $err = $db->mytrans('##No Data Updated##.');
    }

    $mydata['postik'] = $postik;
    $mydata['hdr'] = $db->mytrans('##Note##') . " # $sid";
    $mydata['note'] = $snote;
    $mydata['sid'] = $sid;
    $mydata['err'] = $err;
    $mydata['mr'] = $mr;
    $mydata['xtag'] = $xtag;
    $mydata['ct'] = $ct;
  } else {
    $mydata['postik'] = $postik;
    $mydata['hdr'] = $db->mytrans('##Note##') . " # $sid";
    $thisnote = $db->getNote($sid, $ID);
    $mydata['note'] = stripslashes($thisnote['str']);
    $mydata['sid'] = $sid;
    $mydata['err'] = $err;
    $mydata['mr'] = $mr;
    $mydata['xtag'] = $xtag;
    $mydata['ct'] = $ct;
  }
  $mydata['ID'] = $ID;
  $smarty->assign("mydata", $mydata);
  $smarty->display("editnote.tpl");
}
?>