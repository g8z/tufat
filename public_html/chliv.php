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
 * This is modified to incorporate Smarty and Formsess Template Processors
 * Vijay Nair                              30/3/2004
 */

require_once 'config.php';

if ( isset( $_SESSION["admin"]) || isset( $_SESSION["master"])) {
    $line = file ( 'config.php');
    $s = '';

    foreach ( $line as $k => $v) {
        $l = strtolower( $v);

        if ( !( strpos ( $l , "hideliving") === false)) {
            preg_match ( "/\,\'(.*)\'/", $l, $m);
            $mliv = $m[1];

            if ( $installik == 1) {
                $s .= "define ('HIDELIVING','$sliv');\n";
            } else
                $s .= $v;
        } else
            $s .= $v;
    } 
    if ( $installik == 1) {
        $of = fopen( 'config.php', "w");
        if ( $of != false) {
            fwrite( $of, $s);
        } 
        fclose( $of);

        if ( $shtyp != '')
            $db->setHideType( $shtyp);

        header( "Location: chliv.php");
    } 

    $mydata['hidetype'] = $db->getHideType( );
} 

$smarty->assign( "mydata", $mydata); 
// # Get the page we want to display
$smarty->assign( 'rendered_page', $smarty->fetch( 'chliv.tpl')); 
// # Display the page.
$smarty->display( 'index.tpl');

$inst = 0;
// #require 'templates/'.$templateID.'/tpl_footer.php';
?>
