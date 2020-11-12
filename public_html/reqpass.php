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
 * Vijay Nair                              14/Apr/2004
 */

$noAuth = true;
require_once 'config.php';

$sql = "select * from ! where username= ?";
$r = $db->query( $sql, array( TBL_USER, $tname));

if ( $r != false && $db->rowsInResult( $r) > 0) {
    $mydata['dataok'] = true;
    $a = $db->mfa( $r);
    $em = new Email;
    $em->html = false;
    $em->to = $a["email"];
    $em->from = $a["email"];
    $em->subject = "Family Tree Password Request";
    $em->message = "Family Tree Password Request\n" . "A request has been made for access to your family tree.\n" . "Person requesting access: $tyn\n" . "How to contact this person: $tcinf\n" . "Tree Name: $tname\n" . "\nThe following passwords are available to access your family tree:\n" . "Read-Only password: " . $a["read_only_password"] . "\n" . "General-use password: " . $a["password"] . "\n" . "Administrator password: " . $a["admin_password"];
    $mydata['mailmsg'] = "";
    if ( $em->send( )) {
        $aemail = $a["email"];
        $mydata['mailmsg'] = $db->mytrans( '##Your request has been sent to the $tname administrator. If you do not hear from the administrator soon, you may want to contact him or her directly at $aemail##');
    } else {
        global $emi;
        $emi = $a["email"];
        $mydata['emimsg'] = $db->mytrans( '##There was an error sending the e-mail to $emi##');
        $mydata['supervisoremail'] = SUPERVISEMAIL;
    } 
} else {
    $mydata['errmsg'] = $db->mytrans( '##Tree Name $tname not found##');
} 

$smarty->assign( "mydata", $mydata);
$smarty->display( "reqpass.tpl");

?>