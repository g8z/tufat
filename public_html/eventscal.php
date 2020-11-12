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
 * Created by aTufa June/2005
 */

require_once 'config.php';

require_once 'calendar.php';

$today = getdate( );
$month = $mon;
if ( !isset( $mon))
    $month = $today['mon'];

if ( !isset( $year))
    $year = $today['year'];

if ( isset( $_REQUEST['year']))
    $_SESSION['year'] = $_REQUEST['year'];
if ( isset( $_SESSION['year']))
    $year = $_SESSION['year'];

$startDay = "0";
$newcal = calendar( $year, $month, $startDay, $user, 1);
if ( $_SESSION['read_only'] == 1)
    $smarty->assign( "read_only", 1);

$smarty->assign( "calendar", $newcal); 
// # Get the page we want to display
$smarty->assign( 'rendered_page', $smarty->fetch( 'eventscal.tpl')); 
// # Display the page.
$smarty->display( 'index.tpl');

?>