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
 */
 
require_once 'config.php';
$flow = 1;
$err = '';
$mydata = array();

if (count($_POST)) {
  $gedcom = $db->buildGEDCOM();
  $sid = substr($data, 2, strlen($_REQUEST['SOUR'])-1);
  $db->updateGEDCOM($gedcom, (int)$_REQUEST['ID'], 'S');
  $smarty->assign('message', '##saved##');
}

if ($_REQUEST['ID'] != '') {
  // First validate data for html tags
  $err.= $db->checkTags('SOUR', $stitl);
  $err.= $db->checkTags('SOUR', $sauth);
  $err.= $db->checkTags('SOUR', $snote);
  $err.= $db->checkTags('SOUR', $stext);
  $err.= $db->checkTags('SOUR', $spubl);
  $err.= $db->checkTags('SOUR', $scaln);

  if ($squay >= 0) $err.= $db->checkTags('SOUR', $squay);

  if ($err == '') {
    list($mydata['titl'], $mydata['auth'], $mydata['publ'], $mydata['caln'], $mydata['note'], $mydata['text'], $mydata['quay']) = $db->getItems(array('sour_titl', 'sour_auth', 'sour_publ', 'sour_caln', 'sour_note', 'sour_text', 'sour_quay'), (int)$_REQUEST['ID']);

    if ($err != '' || count($_POST)) {
      $mydata['titl'] = $_REQUEST['sour_titl'];
      $mydata['auth'] = $_REQUEST['sour_auth'];
      $mydata['publ'] = $_REQUEST['sour_publ'];
      $mydata['caln'] = $_REQUEST['sour_caln'];
      $mydata['note'] = $_REQUEST['sour_note'];
      $mydata['text'] = $_REQUEST['sour_text'];
      $mydata['quay'] = $_REQUEST['sour_quay'];
    }
  }
  
  $mydata['err'] = $err;
  $mydata['xtag'] = $xtag;
}

$mydata['sour'] = (isset($_REQUEST['ID']) ? $_REQUEST['ID'] : ($db->getMaxSourID() + 1));

$smarty->assign('quay_values', array('-1' => '', 0, 1, 2, 3));
$smarty->assign("mydata", $mydata);
$smarty->display("editsour.tpl");
?>