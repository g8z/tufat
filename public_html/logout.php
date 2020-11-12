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
$llg = 1; 
// #require 'templates/'.$templateID.'/tpl_header.php';
// unset/unregister session variables
session_unregister( "user");
unset( $_SESSION['user']);
setcookie('keep-alive', '', 0);

$user = false;

session_unregister( "admin");
unset( $_SESSION['admin']);

session_unregister( "read_only");
unset( $_SESSION['read_only']);

session_unregister( "master");
unset( $_SESSION['master']);

session_unregister( "edit_only");
unset( $_SESSION['edit_only']);

session_unregister( "my_rec");
unset( $_SESSION['my_rec']);

session_unregister( "mylang");
unset( $_SESSION['mylang']);

session_unregister( "ID");
unset( $_SESSION['ID']);

// # Keep these values until changed
// session_unregister( "encType" );
// unset( $_SESSION['encType'] );
// session_unregister( "slang" );
// unset( $_SESSION['slang'] );
$smarty->assign( 'rendered_page', $smarty->fetch( "logout.tpl")); 
// # Re-include the navMenu so we don't give logged-in options to logged-out users
require( 'navMenu.php');
$smarty->assign( 'navMenu', $smarty->fetch( 'navMenu.tpl')); 
// # Display the page.
$smarty->display( 'index.tpl');

?>
