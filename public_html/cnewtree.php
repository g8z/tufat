<?php
ob_start(); 
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
// added to allow creation of tree before logging in the first time
$noAuth = true;
require_once 'config.php';

$db = new FamilyDatabase( );
$llg = 1;

$newTree = trim( $newTree);

if ( $newTree || $login ) {
	
    // check for valid new tree data
    if ( $newTree != '' && $guidelines) {
        if ( !$db->isUser( $newUsername)) {
            // will not be included in _POST if left unchecked
            if ( !$crosstree)
                $crosstree = '0';
            // add the new user
            $db->addUser( $aname, $dname, $newUsername, $newPassword, $readOnlyPassword, $adminPassword, $email, $crosstree);
            $username = $newUsername;
            $password = $newPassword;
            $_SESSION['treeName'] = $dname;
            header( "Location: login.php?login=1&username=$newUsername&password=$adminPassword");
            exit;
        } else {
            $usernameTaken = true;
        }
    }
    // check for new tree error conditions
    if ( $newTree && !$guidelines) {
        $error_title = $db->mytrans( '##Please Read Guidelines##');
        $error = $db->mytrans( '##You must read and consent to the guidelines before creating a family tree.##');
    } elseif ( $newTree && $usernameTaken) {
        $error_title = $db->mytrans( '##Login Error##');
        $error = $db->mytrans( '##The user name that you requested is already in use. Please enter a different one.##');
    } elseif ( $newtree == '') {
        $error_title = $db->mytrans( '##Username Error##');
        $error = $db->mytrans( '##Username should be entered.##');
    }

    $sqlq = "SELECT dname FROM !";
    $res = $db->query( $sqlq, array( TBL_USER));
    while ( $i = $db->mfa( $res)) {
        if ( $dname == $i['dname']) {
            $error_title = $db->mytrans( '##Tree Error##');
            $error = $db->mytrans( '##The tree name that you requested is already in use. Please enter a different one.##');
        }
    }
}

/* Assign displayable variables to Smarty  */
if ( ALLOWCREATE) {
    if ( $guidelines)
        $smarty->assign( 'guidelinesChecked', 'checked');

    if ( ALLOWCROSSTREESEARCH) {
        $smarty->assign( 'allowCrossTreeSearch', "ALLOWCROSSTREESEARCH");
    }
}

$mydata = array( 'newUsername' => "$newUsername", 'newPassword' => "$newPassword", 'readOnlyPassword' => "$readOnlyPassword", 'adminPassword' => "$adminPassword", 'aname' => "$aname", 'dname' => stripslashes( $dname), 'email' => "$email");

$smarty->assign( 'mydata', $mydata);

/* Now display the page  */

/* Display any error situation  */
if ( $error) {
    /* Modified to show generic error message method using Smarty  Vijay  21/3/3004 *
       ************************************************************************/
    $smarty->assign( 'error_message', "$error");
    $smarty->assign( 'error_title', "$error_title");
    $smarty->assign( 'msg', $smarty->fetch( 'display_error_message.tpl'));
}
// # Get the page we want to display
$smarty->assign( 'rendered_page', $smarty->fetch( 'cnewtree.tpl'));
// # Display the page.
$smarty->display( 'index.tpl');
// only show left navigation information is we are NOT on the login page!
// #require 'templates/'.$templateID.'/tpl_footer.php';
?>