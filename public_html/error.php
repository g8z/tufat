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
 * Modified to incorporate Smarty Template Processor
 * Vijay Nair                 10/Apr/2004
 */
require_once 'config.php';

$mydata['msg'] = $msg;
$mydata['viewScript'] = VIEWSCRIPT;

$smarty->assign( "mydata", $mydata); 
// # Get the page we want to display
$smarty->assign( 'rendered_page', $smarty->fetch( 'error.tpl')); 
// # Display the page.
$smarty->display( 'index.tpl'); 
// only show left navigation information is we are NOT on the login page!
// #require 'templates/'.$templateID.'/tpl_footer.php';
?>