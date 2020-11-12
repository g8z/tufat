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
 * Vijay Nair                             8/Apr/2004
 */

require_once 'config.php';
$flow = 1;
require 'taglist.php';

$mydata['del'] = $del;
$mydata['ID'] = $ID;
$mydata['xtag'] = $xtag;

if ( $del == 1) {
  if ( !isset( $_SESSION["read_only"]) || $_SESSION["my_rec"] != $ID) {

    // remove source
    $sql = "delete from ! where tree= ? and type='S' and id= ?";
    $r = $db->query( $sql, array( $db->gedcomTable, $user, $sid));

    // remove source reference
    $sql = "delete from ! where tree=? and id=? and tag='SOUR'";
    $db->query( $sql, array( $db->gedcomTable, $user, $sid));

    // find associated citations
    $sql = "select * from ! where tree=? AND id=? AND tag='SREF'";
    $cr = $db->query( $sql, array( $db->gedcomTable, $user, $ID));

    // remove associated citations
    $sql = "DELETE FROM ! WHERE tree=? AND id=? AND type='C' AND hid=? AND data=?";

    while ( $delcit = $db->mfa( $cr)) {
        $db->query( $sql, array( $db->gedcomTable, $user, $ID, $delcit['hid'], '@S' . $delcit['hid'] . '@'));
    }

    if ( $r != false) {
        $smarty->assign( 'sid', $sid);
        $mydata['delmsg'] = $db->mytrans( '##Source##') . " #$sid " . $db->mytrans( "##has been deleted successfully.##");
    } else {
        $mydata['delmsg'] = $db->mytrans( "##Source delete problem.##");
    }
  }
}

$sql = "SELECT id FROM ! WHERE type='S' AND tag = 'SOUR' AND tree= ? ORDER BY id";
$r = $db->query( $sql, array( $db->gedcomTable, $user));

if ( $r != false) {
  for ( $i = 0; $i < $db->rowsInResult($r); $i++) {
    $a = $db->mfa($r);

    $sourcesList[$i]->titl = $db->getSourItem( "TITL", $a["id"]);
    $sourcesList[$i]->id = $a["id"];
  }

  $mydata['recscnt'] = $db->rowsInResult( $r);
  $smarty->assign( "sourcesList", $sourcesList);
}
$smarty->assign( "mydata", $mydata);
$smarty->display( "listsources.tpl");

?>