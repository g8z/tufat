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
 * for reliability or accuracy � in other words, you use this script
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

$fp = $db->lgetFamilyP( $ID);
if ( $fp == false) {
    $fp = $db->lgetFamilyC( $ID);
} 

$sql = "select id,data,name from ! where  id = ?";
$r = $db->query( $sql, array( $db->famgalTable, $mid));

if ( $r != false && $db->rowsInResult( $r) > 0) {
    $a = $db->mfa( $r);
    $pic = $a["data"];
    $db->getMime( $a["name"], $tp);
} 
header( "Content-Disposition: inline; filename=" . $a["name"]);
header( "Content-Title: " . $a["name"]);
header( "Content-Type: $tp");
echo $pic;

?>