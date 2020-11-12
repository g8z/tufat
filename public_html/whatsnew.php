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

// Instantiate smarty
$smarty->assign( "animalPedigree", ANIMALPEDIGREE); 

// First load 10 newest individuals into the array to show in the .tpl file
$t = time( );
$x = $t - ( 30 * 24 * 60 * 60);

$sql = "select id,name,surn,ta from ! where tree= ? and hide <> 'Yes' or hide is NULL order by ta desc limit 10";
$r = $db->query( $sql, array( $db->indexTable, $user));

if ( $r != false) 
{
    $smarty->assign( "new10ind", $db->rowsInResult( $r));
	
    for ( $i = 0; $i < $db->rowsInResult( $r); $i++) 
	{
        $a = $db->mfa( $r);
        $mname = stripslashes( $a["name"]);
        $mname = $db->removeFam( $mname);

        $rec[$i]->ID = $a["id"];
        $rec[$i]->mname = "$mname";
        $rec[$i]->surn = stripslashes( $a["surn"]);
        $rec[$i]->ta = strftime( "%I:%M %p " . SHORTDATEFORMAT, $a["ta"]);
    } 
	
    $smarty->assign( "newest10individuals", $rec);
}

// Now get data for 10 newest individual images
$sql = "select id,type,ta,data from ! where tree= ? and type='P' group by id order by ta desc limit 10";
$r = $db->query( $sql, array( $db->blobsTable, $user));

if ( $r != false) 
{
    $smarty->assign( "new10imgs", $db->rowsInResult( $r));
	
    for ( $i = 0; $i < $db->rowsInResult( $r); $i++) 
	{
        $a = $db->mfa( $r); 
        // $nm = $db->changeBrack2( stripslashes( $db->getItem( "name", $a["id"] ) ) );
        $name_srname = explode( "/", $db->getItem( "name", $a["id"]));

        $rec1[$i]->ID = $a["id"];
        $rec1[$i]->name = $name_srname[0];
        $rec1[$i]->surname = $name_srname[1];
        $rec1[$i]->ta = strftime( "%I:%M %p " . SHORTDATEFORMAT, $a["ta"]);
    } 

    $smarty->assign( "new10indimgs", $rec1);
}

// Now get 10 new tree images
$sql = "select id,title,descr,ta from ! where tree= ? and kd='1' order by ta desc limit 10";
$r = $db->query( $sql, array( $db->famgalTable, $user));

if ( $r != false) 
{
    $smarty->assign( "newest10treeimgs", $db->rowsInResult( $r));

    for ( $i = 0; $i < $db->rowsInResult( $r); $i++)
	{
        $a = $db->mfa( $r);
        $rec2[$i]->ID = $a["id"];
        $rec2[$i]->title = stripslashes( $a["title"]);
        $rec2[$i]->ta = strftime( "%I:%M %p " . SHORTDATEFORMAT, $a["ta"]);
    } 
	
    $smarty->assign( "new10treeimgrecs", $rec2);
} 

// Now display the page
// # Get the page we want to display
$smarty->assign( 'rendered_page', $smarty->fetch( 'whatsnew.tpl')); 

// # Display the page.
$smarty->display( 'index.tpl'); 
// #require 'templates/'.$templateID.'/tpl_footer.php';
?>