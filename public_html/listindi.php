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
 * This program is modified to incorporate Smarty
 * Vijay Nair                                1-Apr-2004
 */

require_once 'config.php';
// if the $user session variable is not listed in the users table, default to the login screen
// check _GET & _POST to thwart hackers
$flow = 1;

$sn = $_GET["surn"];
$mydata['sn'] = $sn;
$lists = array( );
$ids = array( );

if ( strlen( $sn) > 0) {
    $sql = "select distinct id from {$db->gedcomTable} where tree= ".$db->dbh->quote($user)." and  ((tag='surn' and data= ".$db->dbh->quote($sn).") or (tag='name' and locate(".$db->dbh->quote("/{$sn}/").",data) > 0))";
} else {
    $sql = "select distinct id from {$db->gedcomTable} where tree= ".$db->dbh->quote($user)." and tag='surn' and data not like ''";
}

$r = $db->getCol($sql);
if ( count( $r) > 0) {
    foreach ( $r as $id) {
        if ( !in_array( $id, $ids)) {
            $nm = $db->getItem( "Name", $id);
            if ( $nm == '' || strpos( $nm, "Unknown") > 0)
                $nm = $db->mytrans( "##(no name)##");

            if ( array_key_exists( $nm, $ids)) {
                $names[$nm]++;
                $ids[$nm . " {" . $names[$nm] . "}"] = $id;
            } else
                $ids[$nm] = $id;
        }
    }
}

ksort( $ids);

foreach ( $ids as $nm => $id) {
    if ( ANIMALPEDIGREE)
        $gg = $db->changeBrack( $nm);
    else
        $gg = $db->removeFam( $nm);
    $hid = $db->getItem( "HIDE", $id);
    if ( $hid == "Yes")
        $gg = $db->obstr( $gg, 1);

    $lists[$gg] = $id;
}

$smarty->assign( "mydata", $mydata);

$smarty->assign( "lists", $lists);

$smarty->display( "listindi.tpl");

?>
