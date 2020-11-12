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
 * This is modified to incorporate Smarty and Formses template Managers
 * Vijay Nair                                       25/3/2004
 */ 
// # Modified Pat King <cicada@edencomputing.com> for new language parser
require_once 'config.php';

$afterpost = false;
$smarty->assign( "allowed", false);

if ( isset( $_SESSION['master']) && $sla != '' && $senc != '') {
    $smarty->assign( "allowed", true);
    if ( $addik == 1 && strlen( $sneww) > 0) {
        $afterpost = true;

        $sql = "select enc from ! where l = ? and enc = ? group by l,enc";
        $r = $db->query( $sql, array( $db->langTable, $sla, $senc));

        if ( $r != false && $db->rowsInResult( $r) > 0) {
            $a = $db->mfa( $r);
            $newenc = $a["enc"];
        } else
            $newenc = "utf-8";
        $sql = "select enc from ! where l = ? and enc = ? and  w = ?";
        $r = $db->query( $sql, array( $db->langTable, $sla, $senc, addslashes( $sneww)));

        if ( $r != false && $db->rowsInResult( $r) < 1) {
            $sql = "insert into  ! (w,m,l,enc) values( ?, ?, ?, ?)";
            $r = $db->query( $sql, array( $db->langTable, addslashes( $sneww), addslashes( $snewm), $sla, $newenc));
            if ( $r != false)
                $addmsg = "##Added OK##";
            else
                $addmsg = "##Add Problem##";
        } else
            $addmsg = "##This text is already defined##.";
    } 
} 

$smarty->assign( "mytrans", array( afterpost => $afterpost, addmsg => $addmsg));
$smarty->assign( "mydata", array( sla => $sla, senc => $senc)); 
// # Display the page.
$smarty->display( 'addnewlrec.tpl'); 
// #require 'templates/'.$templateID.'/tpl_footer.php';
?>