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
 * Modified to incorporate Smarty Template Processor.
 * Vijay Nair                                 22/3/2004
 */

require_once 'config.php';

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

$smarty->assign( "s", $s);
$smarty->assign( "mydata", array( 'imp' => $imp, 'exp' => $exp));

if ( isset( $_SESSION['master'])) {
    if ( $imp != 1 && $exp != 1) {
        $sql = "select l,enc from ! group by enc order by l";
        $r = $db->query( $sql, array( $db->langTable));
        if ( $r != false) {
            for ( $i = 0; $i < $db->rowsInResult( $r); $i++) {
                $a = $db->mfa( $r);
                $langList[$i]["enc"] = strtoupper( $a["enc"]);
                $langList[$i]["l"] = $a["l"];
            }
        }
        $smarty->assign( "langList", $langList);
    }

    if ( $imp == 1) {
        $fn = $_FILES["sfile"]["tmp_name"];
        if ( $fn != '') {
            $fi = file( $fn);
            $x = split( ":", $fi[0]);
            $y = split( ":", $fi[1]);
            if ( strlen( $x[1]) > 0 && strlen( $y[1]) > 0 && $x[0] == "Language" && $y[0] == "Encoding") {
                $sla = $x[1];
                $senc = $y[1];

                $sql = "select lower(l) as l, lower(enc) as enc from ! where lower(l) = lower(?) and lower(enc) = lower(?)";
                $r = $db->query( $sql, array( $db->langTable, $sla, $senc));

                if ( $r != false) {
                    if ( $db->rowsInResult( $r) > 0) {
                        $imperr = $db->mytrans( "##The language $sla with encoding $senc already exists. Please delete it before importing.##");
                    } else {
                        $j = 0;
                        $imperr = "Importing: " . $x[1] . " (" . $y[1] . ") <br>";
                        for ( $i = 2; $i < count( $fi); $i++) {
                            $line = split( "---", $fi[$i]);

                            $sql = "insert into ! (w,m,l,enc) values ( ?, ?, ?, ?)";
                            $r = $db->query( $sql, array( $db->langTable, addslashes( base64_decode( $line[0])), addslashes( base64_decode( $line[1])), $sla, $senc));
                            if ( $r != false)
                                $j++;
                        }
                        $imperr .= $db->mytrans( "##$j phrases successfully added.##") . "<br>";
                    }
                }
            } else {
                $imperr = $db->mytrans( "##The language save file appears to be invalid.##");
            }
        }
    } elseif ( $exp == 1) {
        if ( $sla != '') {
            header( "Location:explang.php?exporting=1&sla=$sla");
        }
    }
} else {
    print "<font class=\"normal\">##You should re-login using the master password to perform this action.##</font>";
}
$smarty->assign( "imperr", $imperr);
// # Get the page we want to display
$smarty->assign( 'rendered_page', $smarty->fetch( 'impexpl.tpl'));
// # Display the page.
$smarty->display( 'index.tpl');

?>