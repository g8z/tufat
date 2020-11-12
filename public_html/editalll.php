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
 * Modified to incorporate Smarty and Formsess template processors
 * VIjay Nair                                      31/3/2004
 */

require_once( 'config.php');

$s['Unicode (UTF-8)'] = 'UTF-8';
$s['Unicode (UTF-16)'] = 'UTF-16';
$s['Unicode (UTF-32)'] = 'UTF-32';
$s['Cyrillic (WINDOWS-1251)'] = 'windows-1251';
$s['Cyrillic (KOI8-R)'] = 'KOI8-R';
$s['Cyrillic (ISO-8859-5)'] = 'ISO-8859-5';
$s['Cyrillic (KOI8-U)'] = "KOI8-U";
$s['Central European (ISO-8859-2)'] = "ISO-8859-2";
$s['Central European (ISO-8859-16)'] = "ISO-8859-16";
$s['Central European (windows-1250)'] = "windows-1250";
$s['Southern European (ISO-8859-3)'] = "ISO-8859-3";
$s['Baltic (ISO-8859-13)'] = "ISO-8859-13";
$s['Baltic (ISO-8859-4)'] = "ISO-8859-4";
$s['Baltic (windows-1257)'] = "windows-1257";
$s['Japanese (euc-jp)'] = 'euc-jp';
$s['Japanese (Shift-JIS)'] = 'Shift-JIS';
$s['Japanese (ISO-2022-JP)'] = 'ISO-2022-JP';
$s['Chinese (big5)'] = "big5";
$s['Chinese (big5-HKSCS)'] = "big5-HKSCS";
$s['Chinese (ISO-2022-CN)'] = "ISO-2022-CN";
$s['Thai (iso-8859-11)'] = "iso-8859-11";
$s['Arabic (windows-1256)'] = "windows-1256";
$s['Arabic (ISO-8859-6)'] = "ISO-8859-6";
$s['Esperanto/South European (ISO-8859-3)'] = "ISO-8859-3";
$s['Western/Estonian/Latvian (ISO-8859-1)'] = "ISO-8859-1";
$s['Western (ISO-8859-15)'] = "ISO-8859-15";
$s['Western (windows-1252)'] = "windows-1252";
$s['Greek (ISO-8859-7)'] = "ISO-8859-7";
$s['Greek (windows-1253)'] = "windows-1253";
$s['Turkish (ISO-8859-9)'] = "ISO-8859-9";
$s['Turkish (windows-1254)'] = "windows-1254";
$s['Vietnamese (windows-1258)'] = "windows-1258";
$s['Nordic (ISO-8859-10)'] = "ISO-8859-10";
$s['Nordic (windows-sami-2)'] = "windows-sami-2";
$s['Korean (EUC-KR)'] = "EUC-KR";
$s['French/German (windows-850)'] = "windows-850";
$s['Hebrew (windows-1255)'] = "windows-1255";
$s['Hebrew (ISO-8859-8)'] = "ISO-8859-8";
$s['Celtic (ISO-8859-14)'] = "ISO-8859-14";
$s['Malay/Danish/Finnish/Norwegian/Dutch (windows-1252)'] = "windows-1252";

ksort( $s);
$smarty->assign( "langsList", $s);
$mydata['slname'] = strtolower( $slname);
$mydata['slenc'] = strtolower( $slenc);
$mydata['add'] = $add;
$mydata['del'] = $del;
$mydata['chlt'] = $chlt;

if ( isset( $_SESSION['master'])) {
    if ( $add == 1) {
        if ( $slname != '' && $slenc != '') {
            $slname = strtolower( $slname);
            $slenc = strtolower( $slenc);

            $sql = "select enc from ! where lcase(enc) = ? and lcase(l) = ?";
            $r = $db->query( $sql, array( $db->langTable, $slenc, $slname));

            if ( $r != false) {
                $mydata['langsok'] = 1;
                if ( $db->rowsInResult( $r) > 0 || strstr( $slname, "_lll_")) {
                    $mydata['add_error'] = "1";
                } else {
                    $sql = "select w,m from ! where  lcase(l) = 'english' and lcase(enc) = 'utf-8'";
                    $p = $db->query( $sql, array( $db->langTable));

                    if ( $p != false) {
                        $n = ( $db->rowsInResult( $p));
                        $mydata['reccnt'] = $n;
                        $mydata['er'] = false;

                        for ( $i = 0; $i < $n; $i++) {
                            $b = $db->mfa( $p);
                            $mw = $b["w"];
                            $mm = $b["m"];

                            $sql = "insert into ! (w,m,l,enc) values ( ?, ?, ?, ?)";
                            $rr = $db->query( $sql, array( $db->langTable, $mw, $mm, $slname, $slenc));

                            if ( !$rr)
                                $mydata['er'] = true;
                        } 
                    } 
                } 
            } 
        } 
    } elseif ( $del == 1) {
        $mydata['delmsg'] = "";
        if ( $sla != '') {
            $x = split( "_lll_", $sla);
            if ( count( $x) == 2) {
                $sla = $x[0];
                $senc = $x[1];
            } 

            if ( strtolower( $sla) == 'english' && strtolower( $senc) == 'utf-8') {
                $mydata['delmsg'] = "English (UTF-8) is the default language. ";
            } else {
                $sql = "delete from ! where  l =  ? and enc= ?";
                $r = $db->query( $sql, array( $db->langTable, $sla, $senc));

                if ( $r != false) {
                    $mydata['delmsg'] = "Language deleted OK";
                } else {
                    $mydata['delmsg'] = "Language delete problem";
                } 
            } 
        } else {
            $sql = "select l,enc from ! group by l,enc order by l";
            $r = $db->query( $sql, array( $db->langTable));

            $slaList = array( );
            if ( $r != false) {
                for ( $i = 0; $i < $db->rowsInResult( $r); $i++) {
                    $a = $db->mfa( $r);
                    $a["enc"] = strtoupper( $a["enc"]);
                    $slaList[$a["l"]] = $a["enc"];
                } 
            } 
        } 
    } elseif ( $chlt == 1) {
        $mydata['chgmsg'] = "";
        if ( $sla != '' && $slenc != '') {
            $x = split( "_lll_", $sla);
            if ( count( $x) == 2) {
                $slname = $x[0];
                $senc = $x[1];
            } 
            $y = split( "_lll_", $slenc);
            if ( count( $y) == 2) {
                $newname = trim( str_replace( "($y[1])", "", $y[0]));
                $newenc = $y[1];
            } 

            $sql = "update ! set enc=lower(?), l=lower(?) where lower(l) = lower(?) and lower(enc)=lower(?)";
            $r = $db->query( $sql, array( $db->langTable, $newenc, $newname, $slname, $senc));

            if ( $r != false) {
                $mydata['chgmsg'] = "Language encoding updated OK";
            } else {
                $mydata['chgmsg'] = "Language update problem";
            } 
        } else {
            $sql = "select lower(l) as l,lower(enc) as enc from ! group by lower(l),lower(enc) order by l";
            $r = $db->query( $sql, array( $db->langTable));

            $slaList = array( );
            if ( $r != false) {
                for ( $i = 0; $i < $db->rowsInResult( $r); $i++) {
                    $a = $db->mfa( $r);
                    $a["enc"] = strtoupper( $a["enc"]);
                    $slaList[$a["l"]] = $a["enc"];
                } 
            } 
        } 
    } else {
        $sql = "select lower(l) as l,lower(enc) as enc from ! group by lower(l),lower(enc) order by l";
        $r = $db->query( $sql, array( $db->langTable));

        $slaList = array( );
        if ( $r != false) {
            for ( $i = 0; $i < $db->rowsInResult( $r); $i++) {
                $a = $db->mfa( $r);
                $a["enc"] = strtoupper( $a["enc"]);
                $slaList[$a["l"]] = $a["enc"];
            } 
        } 
    } 
} 

$smarty->assign( "mydata", $mydata);
$smarty->assign( "slaList", $slaList); 
// # Get the page we want to display
$smarty->assign( 'rendered_page', $smarty->fetch( 'editalll.tpl')); 
// # Display the page.
$smarty->display( 'index.tpl');

?>