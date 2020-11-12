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
 * Modified to incorporate Smarty Template Processor
 * Vijay Nair                           10/APr/2004
 */

require_once 'config.php';
$flow = 1;

require 'taglist.php';

$mydata['xtag'] = $xtag;
$mydata['ID'] = $ID;
$mydata['del'] = $del;
$mydata['add'] = $add;
$mydata['mid'] = $mid;

if ( $xtag != '' && $ID != '') {
    $ftags = array_flip( $tags);
    $mydata['taghdr01'] = $ftags[$xtag];

    if ( $del == 1 && $mid > 0) {
        if ( !isset( $_SESSION['read_only']) || $_SESSION["my_rec"] == $ID) {
            $sql = "delete from ! where tree= ? and tag='SREF' and hid = ?";
            $r1 = $db->query( $sql, array( $db->gedcomTable, $user, $mid));

            if ( $r1 != false) {
                $mydata['delmsg'] = $db->mytrans( '##Source # $mid has been deleted successfully.##');
            } else {
                $mydata['delmsg'] = $db->mytrans( "##Sources delete problem.##");
            } 
        } 
    } elseif ( $add == 1) {
        if ( !isset( $_SESSION['read_only']) || $_SESSION["my_rec"] == $ID) {
            $maxid = $db->getMaxSourID( ) + 1;

            $sql = "insert into ! (id, type,tree, tag, level, data) values( ?, 'S', ?,  'SOUR', '0', ?)";
            $db->query( $sql, array( $db->gedcomTable, $maxid, $user, "@S" . $maxid . "@"));

            if ( $mr == 1)
                $txt = $db->getFamGeds( $ID);
            else
                $txt = $db->getIndiGeds( $ID);
            if ( strstr( $txt, "1 " . strtoupper( $xtag)) == false) {
                $txt .= CRLF . "1 " . strtoupper( $xtag) . CRLF;
            } 

            $l = split( CRLF, $txt);
            $ima = 0;
            $cline = 0;
            for ( $i = 0; $i < count( $l); $i++) {
                if ( ( substr( $l[$i], 0, 6) == "1 " . strtoupper( $xtag))) {
                    $j = $i + 1;
                    while ( $j < count( $l) && substr( $l[$j], 0, 2) != '0 ' && substr( $l[$j], 0, 2) != '1 ') {
                        if ( substr( $l[$j], 0, 6) == '2 EVEN') {
                            $ima = 1;
                            $cline = $j;
                        } 
                        $j++;
                    } 
                } 
            } 

            if ( $ima == 0) {
                $txt = preg_replace( "/1 " . strtoupper( $xtag) . "/", "1 " . strtoupper( $xtag) . CRLF . "2 EVEN" . CRLF . "3 SREF @S" . $maxid . "@", $txt, 1);
            } else {
                $l2 = split( CRLF, $txt);
                $m = "";
                for ( $i = 0; $i < count( $l2); $i++) {
                    if ( $i == $cline) {
                        $m .= $l2[$i] . CRLF . "3 SREF @S" . $maxid . "@" . CRLF;
                    } else
                        $m .= $l2[$i] . CRLF;
                } 

                $txt = $m;
            } 

            if ( $mr == 1)
                $db->putFamGeds( $ID, $txt);
            else
                $db->putIndiGeds( $ID, $txt);
        } 
    } 

    if ( $mr == 1) {
        $sql = "select inum from ! where tree= ? and type = 'F' and id= ? and tag = ? order by inum";
        $r = $db->query( $sql, array( $db->gedcomTable, $user, $ID, strtoupper( $xtag)));
    } else {
        $sql = "select inum from ! where tree= ? and type = 'I' and id= ? and tag = ? order by inum";
        $r = $db->query( $sql, array( $db->gedcomTable, $user, $ID, strtoupper( $xtag)));
    } 
    if ( $r != false && $db->rowsInResult( $r) > 0) {
        $a = $db->mfa( $r);
        $nid = $a["inum"];
    } 
    if ( $mr == 1) {
        $sql = "select * from ! where tree= ? and type = 'F' and inum > ? order by inum";
        $r = $db->query( $sql, array( $db->gedcomTable, $user, $nid));
    } else {
        $sql = "select * from ! where tree= ? and type = 'I' and inum > ? order by inum";
        $r = $db->query( $sql, array( $db->gedcomTable, $user, $nid));
    } 
    if ( $r != false) {
        $n = $db->rowsInResult( $r);
        $mydata['recscnt'] = $n;
        $recsList = array( );
        $inEven = false;

        for ( $i = 0; $i < $n; $i++) {
            $a = $db->mfa( $r);

            if ( $a["level"] < 2 && $a["tag"] != "EVEN")
                break;

            if ( $a["level"] <= 2 && $a["tag"] != "EVEN")
                continue;
            if ( $a["tag"] == 'EVEN')
                $inEven = true;
            if ( $a["tag"] == "SREF" && $inEven) {
                $recsList[$i]->hid = $a["hid"];

                $titl = addslashes( $db->getSourItem( "TITL", $a["hid"]));
                if ( strlen( $titl) > 0)
                    $recsList[$i]->titl = $titl;
                else
                    $recsList[$i]->titl = "Source #" . $a["hid"];

                $mydata['nf'] = 1;
            } 
        } 
    } 
} 

$smarty->assign( "recsList", $recsList);
$smarty->assign( "mydata", $mydata); 
// # Get the page we want to display
$smarty->assign( 'rendered_page', $smarty->fetch( 'showeven.tpl')); 
// # Display the page.
$smarty->display( 'index.tpl');

?>