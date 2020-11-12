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
 * This is modified to incorporate Smarty Template Processor
 * Vijay Nair                           5/Apr/2004
 * Commented by aTufa Mar/2005
 */

$flow = 1;

require_once 'config.php';
// Percentage height,width for <TABLE>
$percy = '100%';
$percx = '100%';

$mydata['percy'] = $percy;
$mydata['percx'] = $percx;
$mydata['ID'] = $ID;
$mydata['treeName'] = TREENAME;

if ( $ID > 0) {
    $db = new FamilyDatabase( );

    if ( !isset( $type))
        $type = 2;
    if ( !isset( $gen))
        $gen = 4;
    $mydata['type'] = $type;
    $mydata['gen'] = $gen;

    $name = $db->getItem( 'name', $ID);
    if ( ANIMALPEDIGREE)
        $name = str_replace( '/', "'", $name);
    else
        $name = str_replace( '/', '', $name);

    $hid = $db->getItem( 'hide', $ID);
    if ( $hid == 'Yes')
        $name = $db->obstr( $name, 1);

    $mydata['name'] = $name;
    $mydata['gen'] = $gen;
    // Create array for generation selection
    for ( $i = 1; $i <= 8; $i++) {
        $genList[$i] = $i;
    }
    $mydata['genList'] = $genList;

    $typeArray['1'] = $db->mytrans( '##With Thumbnail##');
    $typeArray['2'] = $db->mytrans( '##Without Thumbnail##');
    $typeArray['3'] = $db->mytrans( '##Minimal/Text Only##');
    $mydata['type'] = $type;
    $mydata['typeArray'] = $typeArray;

    function getNext( $in, &$out)
    {
        global $db;
        $x = array( );
        if ( count( $in) > 0) {
            foreach ( $in as $k => $v) {
                $m = $db->getMother( $v);
                $f = $db->getFather( $v);
                if ( $m != false)
                    $x[] = $m;
                else
                    $x[] = -1;

                if ( $f != false)
                    $x[] = $f;
                else
                    $x[] = -1;
            }
        }
        $out = $x;
    }

    function pow2( $n)
    {
        $s = 1;
        for ( $i = 0; $i < $n; $i++)
        $s = 2 * $s;
        return $s;
    }

    function celltext( $ID, $type)
    {
        global $db, $percx, $percy, $lang;
        $cellArray = array( );
        if ( $ID > -1)
            $bgc = 'FCEABD';
        else
            $bgc = 'c3c3c3';

        $cellArray['bgc'] = "$bgc";
        $cellArray['ID'] = $ID;

        if ( $ID == -1)
            $cellArray['unknown'] = true;
        else {
            $cellArray['unknown'] = false;
            list( $name, $sex, $hidden, $bd, $de, $o) = $db->getItems( array( 'name', 'sex', 'HIDE', 'birt_date', 'deat_date', 'occu'), $ID);

            $name = str_replace( '/', '', $name);
            $cellArray['name'] = $name;

            if ( $hidden == 'Yes') {
                $cellArray['name'] = $name = $db->obstr( $name, 1);
                $type = 1;
                $cellArray['type'] = $type;
            }

            if ( $type == 2 || $type == 1) {
                $cellArray['type'] = $type;
                $spic = false;

                if ( $type == 1 && $db->hasPortrait( $ID)) {
                    $spic = true;
                    $portrait = ( $hidden != 'Yes') ? $db->getPortrait( $ID) : '';

                    $portrait = stripslashes( $portrait );

                    if ( trim( $portrait) != '') {
                        $filePath = 'temp/' . $ID . '_portrait.png';
                        if ( file_exists( $filePath))
                            unlink( $filePath);

                        File::writefile( $filePath, $portrait);
                    }
                    $cellArray['portraitfile'] = $filePath;
                }

                $cellArray['spic'] = $spic;

                if ( $bd != '') {
                    $bd = $db->dateFormat( $bd, 3);
                    $cellArray['birt_date'] = $bd;
                }
                if ( $de != '') {
                    $de = $db->dateFormat( $de, 3);
                    $cellArray['deat_date'] = $de;
                }
                $sp = $db->getSpouses( $ID);
                $cellArray['spcnt'] = count( $sp);
                if ( count( $sp) > 0) {
                    $i = 0;
                    $spouseList = array( );
                    foreach ( $sp as $k => $v) {
                        $i++;
                        list( $n, $spsex) = $db->getItems( array( 'name', 'sex'), $v);
                        $n = str_replace( '/', '', $n);
                        $spouseList[$v]->name = $n;
                        $spouseList[$v]->link = ( $spsex == 'M') ? 'blueLinkNormal' : 'pinkLinkNormal';
                    }
                    $cellArray['spouseList'] = $spouseList;
                }
                $cellArray['link'] = ( $sex == 'M') ? 'blueLinkNormal' : 'pinkLinkNormal';
                if ( $o != '') {
                    $cellArray['occu'] = $o;
                }
            }
            $cellArray['link'] = ( $sex == 'M') ? 'blueLinkNormal' : 'pinkLinkNormal';
        }
        // $s;
        return $cellArray;
    }
    // start processing
    if ( $gen > 8)
        $gen = 4;

    $net = 1;

    for ( $i = 0; $i < $gen; $i++) {
        $net = 2 * $net;
    }

    $ml = $net - 1;

    $totend = $ml + $net;

    $col = 1;
    for ( $i = 0; $i < $gen; $i++) {
        $col = 2 + $col;
    }

    for ( $i = 1; $i <= $totend; $i++) {
        for ( $j = 1; $j <= $col; $j++) {
            $model[$i][$j] = "E";
        }
    }

    $med = ( ( $totend + 1) / 2);
    $i = 1;
    while ( $i <= $totend) {
        if ( ( $i % 2) == 1) {
            $model[$i][$col] = 'O';
        } else {
            if ( $model[$i - 2][$col] != 'J')
                $model[$i][$col] = 'J';
            elseif ( $i != $med)
                $model[$i][$col] = 'Y';
        }

        $i++;
    }

    $j = $col - 1;
    $bk = $net;

    while ( $j >= 1) {
        if ( $j % 2 == 1) {
            $inO = 1;
        } else
            $inO = 0;

        $i = 1;
        while ( $i <= $totend) {
            if ( $inO == 0) {
                if ( $model[$i][$j + 1] == 'O') {
                    $x = 0;
                    $p = $i;
                    while ( $p >= 1) {
                        if ( $model[$p][$j] == '+')
                            $x = 1;
                        if ( $model[$p][$j] == 'DG')
                            break;
                        $p--;
                    }

                    if ( $x == 1)
                        $model[$i][$j] = 'DG';
                    elseif ( $i + 1 == $totend) {
                        $model[$i][$j] = 'DG';
                    } else
                        $model[$i][$j] = 'G';

                    if ( $model[$i][$j] == 'DG') {
                        $p = $i - 1;
                        while ( $p >= 1) {
                            if ( $model[$p][$j] == '+')
                                break;
                            if ( $model[$p][$j + 1] == 'J')
                                $model[$p][$j] = '+';
                            else
                                $model[$p][$j] = '|';

                            $p--;
                        }
                        $p--;
                        while ( $p >= 1) {
                            if ( $model[$p][$j] == 'G')
                                break;
                            if ( $model[$p][$j + 1] == 'J')
                                $model[$p][$j] = '+';
                            else
                                $model[$p][$j] = '|';
                            $p--;
                        }
                    }
                }

                $x = ( int)( $j / 2);
                $x = ( int)( ( $col + 1) / 2) - $x;
                $mn = pow2( $x) - 1;

                $next = $mn + 1 + $mn;

                $broika = ( int)( ( $i - $mn) / $next);
                $broika++;
                $z = ( $i - $mn - $broika);

                $l = $mn;
                while ( $l <= $totend) {
                    $l++;

                    $model[$l][$j] = '+';
                    $l += $next;
                }

                if ( $i == $med &$j == 2)
                    $model[$i][$j] = '+';
            } else {
                if ( $model[$i][$j + 1] == '+') {
                    $model[$i][$j] = 'O';
                }

                if ( $model[$i][$j + 2] == 'Y')
                    $model[$i][$j] = 'J';
            }

            $i++;
        }

        $j--;
    }

    $s = 0;
    for ( $i = 1; $i <= $totend; $i++) {
        if ( $model[$i][$col] == 'O')
            $s++;
    }
    // Get information to be shown in each cell and keep in the aray format
    $cell[$med][1] = celltext( $ID, $type);

    $out = array( $ID);

    for ( $j = 3; $j <= $col; $j += 2) {
        $in = $out;
        getNext( $in, $out);
        $k = 0;
        for ( $i = 1; $i <= $totend; $i++) {
            if ( $model[$i][$j] == 'O') {
                $cell[$i][$j] = celltext( $out[$k], $type);
                $k++;
            }
        }
    }
    for ( $i = 1; $i <= $totend; $i++) {
        for ( $j = 1; $j <= $col; $j++) {
            if ( $model[$i][$j] != '') {
                $printList[$i][$j]['model'] = $model[$i][$j];
                $printList[$i][$j]['cell'] = $cell[$i][$j];
            }
        }
    }

    $smarty->assign( 'mydata', $mydata);
    $smarty->assign( 'printList', $printList);
    // # Get the page we want to display
    $smarty->assign( 'rendered_page', $smarty->fetch( 'flow.tpl'));
    // # Display the page.
    $smarty->display( 'index.tpl');
} else {
    header( 'Location: index.php');
}

?>