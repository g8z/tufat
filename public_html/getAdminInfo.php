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
 * This is modified to incorporate Smarty Template Processor
 * Vijay Nair                           9/Apr/2004
 */

require_once 'config.php';
// send the email and close the window
if ( $recipient && $sender) {
    $m = new Email( );
    $m->recipient = $recipient;
    $m->from = $sender;
    $m->cc = $sender;
    $m->subject = $subject;
    $m->message = $message;
    $m->send( );
    // now close the window
    print "<script language=Javascript>\n";
    print "close();\n";
    print "</script>";
    exit;
}

$matchTree = $tree;
$mydata['matchTree'] = $matchTree;

$sql = "SELECT email FROM ".TBL_USER." WHERE username =".$db->dbh->quote($matchTree)." LIMIT 1";
$mydata['adminmail'] = $db->getValue($sql);
$sname = $_SERVER['SERVER_NAME'];

$mydata['msg01'] = $db->mytrans( '##Dear $matchTree tree administrator, A search of the family tree database on $sname suggests that we have a common link in our family trees. The record in your tree with ID# $ID may match a record in the $user tree may match a record in the family tree. I am interested in pursuing this possibility to find common ancestors and relations between our families. Thanks!##');
$smarty->assign( "mydata", $mydata);
$smarty->display( "getAdminInfo.tpl");

?>