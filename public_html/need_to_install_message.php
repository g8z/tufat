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
if ( !isset( $stplid)) {
    if ( isset( $_COOKIE['templateID'])) {
        $templateID = $_COOKIE['templateID'];
        if ( strlen( $templateID) < 1)
            $templateID = DEFAULTTEMPLATE;
    } else
        $templateID = DEFAULTTEMPLATE;
} 
if ( $jp16 > 1)
    $step = 3;
if ( $stplid != '') {
    $templateID = $stplid;
    $_SESSION['templateID'] = $stplid;

    $_SESSION['templateID'] = $stplid;
    $templateID = $stplid;
    setcookie( 'templateID', $stplid, time( ) + 24 * 60 * 60 * 30);
} 

if ( !isset( $stplid))
    $stplid = DEFAULTTEMPLATE;

/* Display the Database Mising message and request the user to install database  */

$smarty->assign( 'rendered_page', $smarty->fetch( 'need_to_install_message.tpl'));
$smarty->assign( 'allowMultipleLanguages', 'FALSE'); 
// #include_once('navMenu.php');
$smarty->assign( 'navMenu', "");
$smarty->display( 'index.tpl');

?>