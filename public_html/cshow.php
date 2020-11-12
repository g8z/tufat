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
 *
 * This program is modified to incorporate Smarty and Formsess Template Processors
 * Vijay Nair                                             30/3/2004
 */

/* following functions are used in this program only  */

function show_word( $i)
{
    global $lang, $db;
    $i = abs( $i);

    if ( $i < 11) {
        switch ( $i) {
            case 1:
                return $db->mytrans( "##1st##");
            case 2:
                return $db->mytrans( '##2nd##');
            case 3:
                return $db->mytrans( '##3rd##');
            case 4:
                return $db->mytrans( '##4th##');
            case 5:
                return $db->mytrans( '##5th##');
            case 6:
                return $db->mytrans( '##6th##');
            case 7:
                return $db->mytrans( '##7th##');
            case 8:
                return $db->mytrans( '##8th##');
            case 9:
                return $db->mytrans( '##9th##');
            case 10:
                return $db->mytrans( '##10th##');
        }
    } else
        return $db->mytrans( '##level##') . " (" . $i . ")";
}

function show_word2( $i)
{
    global $lang, $db;
    $i = abs( $i);

    switch ( $i) {
        case 1:
            return $db->mytrans( "##once##");
        case 2:
            return $db->mytrans( "##twice##");
        default:
            return $i . "x";
    }
}

function printRel( $m, $p, $i, $l)
{
    global $type, $level, $db, $tree;

    $ret = "";
    if ( $l == 0 && $m != $p) {
        $tl = $level + 1 - $i;
        while ( --$tl > 1) {
            $ret = $db->mytrans( "##great##") . "-" . $ret;
        }
        if ( $tl == 1)
            $ret = $ret . " " . $db->mytrans( "##grand##");

        $ret = $ret . $db->mytrans( "##parents##");

        return $ret;
    }

    $cm[0] = $tree[$level + 1];

    $a1 = $m;
    $a2 = $p;

    $lf1 = $level + 1;
    $lf2 = $i;

    $tl = abs( $lf1 - $lf2);

    if ( $l > 1) {
        $n = $l - 1;

        $ret .= show_word( $n);
        $ret .= " " . $db->mytrans( "##cousins##");

        if ( $tl > 0) {
            $ret .= "<br><font class=\"normalSmall\">" . show_word2( $tl) . " " . $db->mytrans( "##removed##") . "</font>";
        }
        return $ret;
    }

    if ( $l != 1 || $i != $level + 1) {
        $tl--;
        $ret = "";
        while ( $tl > 1) {
            $ret .= $db->mytrans( "##great##") . "-";
            $tl--;
        }

        if ( $tl == 1)
            $ret .= $db->mytrans( "##grand##") . " ";

        if ( $lf1 > $lf2) {
            $ret .= $db->mytrans( "##aunts and uncles##");
        } else {
            $ret .= $db->mytrans( "##nieces and nephews##");
        }
        return $ret;
    }

    return $ret;
}

function getColor( $x)
{
    global $db;

    if ( strstr( $x, $db->mytrans( "##parents##")) != false)
        return "#ff0000";
    if ( strstr( $x, $db->mytrans( "##aunt##")) != false || strstr( $x, $db->mytrans( "##uncle##")) != false)
        return "#ffff00";
    if ( strstr( $x, $db->mytrans( "##niece##")) != false || strstr( $x, $db->mytrans( "##nephew##")) != false)
        return "#dd00dd";
    if ( strstr( $x, $db->mytrans( "##1st##")) != false && strstr( $x, $db->mytrans( "##cousin##")) != false)
        return "#00ff00";
    if ( strstr( $x, $db->mytrans( "##2nd##")) != false && strstr( $x, $db->mytrans( "##cousin##")) != false)
        return "#3333ff";
    if ( strstr( $x, $db->mytrans( "##3rd##")) != false && strstr( $x, $db->mytrans( "##cousin##")) != false)
        return "#00eeee";
    if ( strstr( $x, $db->mytrans( "##4th##")) != false && strstr( $x, $db->mytrans( "##cousin##")) != false)
        return "#ccccee";
}

function printCell( $l, $i, $ypos, $xx)
{
    global $ID, $db, $level, $type;
    $cellArray = array( );

    if ( !is_array( $l)) {
        $l = array( $l);
    }
    $cellArray['blankrec'] = false;
    if ( $i > $level + 1 && $ypos == 0 || $i == $level + 1 && $ypos == 1) {
        $cellArray['blankrec'] = true;
        return $cellArray;
    }

    $cellArray['cnt'] = 0;
    if ( count( $l) > 0) {
        $cellArray['cnt'] = 1;
        $relik = printRel( $ID, $l[0], $i, $ypos);
        if ( $ypos != 0 || $i != $level + 1)
            $color = getColor( $relik);
        else
            $color = '#eeeeee';

        $cellArray['relink'] = $relik;
        $cellArray['color'] = $color;
        $cellArray['xx'] = $xx;
        $cellArray['recs'] = false;
        if ( $ypos != 0 || $i != $level + 1) {
            $cellArray['recs'] = true;

            $x1 = 0;
            $listArray = array( );
            $cellArray['listArrayCnt'] = 0;

            foreach ( $l as $k => $v) {
                $listArray[$x1]->v = $v;
                if ( $v > 0) {
                    $cellArray['listArrayCnt'] = 1;
                    if ( $v != $ID) {
                        $istr = $db->getItem( 'name', $v);

                        if ( ANIMALPEDIGREE )
                            $istr = $db->changeBrack( $istr);
                        else
                            $istr = $db->changeBrack2( $istr);
                        // get the hide type
                        if ( $db->getItem( 'hide', $v) == 'Yes') {
                            $istr = $db->obstr( $istr, $v);
                        }

                        if ( $db->getItem( 'sex', $v) == "M") {
                            $lC = "blueLink";
                        } else {
                            $lC = "pinkLink";
                        }
                        $listArray[$x1]->IC = $lC;
                        $listArray[$x1]->istr = $istr;

                        $sp = $db->getSpouses( $v);
                        $listArray[$x1]->spcnt = count( $sp);

                        $spouseList = array( );
                        foreach ( $sp as $k5 => $v5) {
                            $istr = $db->getItem( 'name', $v5);
                            if ( ANIMALPEDIGREE)
                                $istr = $db->changeBrack( $istr);
                            else
                                $istr = $db->changeBrack2( $istr);
                            if ( $db->getItem( 'sex', $v5) == "M") {
                                $lC = "blueLink";
                            } else {
                                $lC = "pinkLink";
                            }

                            if ( $db->getItem( 'hide', $v5) == 'Yes') {
                                $istr = $db->obstr( $istr, $v5);
                            }

                            $spouseList[$v5]->lC = $lC;
                            $spouseList[$v5]->istr = $istr;
                        }
                        $listArray[$x1]->spouseList = $spouseList;
                    }
                }
                $x1++;
            }
        }

        $cellArray['listArray'] = $listArray;
        $listArray2 = array( );
        $x2 = 0;

        foreach ( $l as $k => $v) {
            if ( $ypos == 0 && $i > $level + 1) {
                $listArray2[$x2]->blankrec = true;
            } else {
                $listArray2[$x2]->blankrec = false;
                $listArray2[$x2]->v = $v;

                if ( $v == $ID) {
                    $istr = $db->getItem( 'name', $ID);

                    if ( ANIMALPEDIGREE)
                        $istr = $db->changeBrack( $istr);
                    else
                        $istr = $db->changeBrack2( $istr);
                    // this is the "base level" person
                    if ( $db->getItem( 'hide', $ID) == 'Yes') {
                        $istr = $db->obstr( $istr, $ID);
                    }
                } else {
                    $f = 1;
                    $istr = $relik;
                }

                $listArray2[$x2]->istr = $istr;
            }

            if ( $f == 1)
                break;
            $x2++;
        }
        $cellArray['listArray2'] = $listArray2;
    }
    return $cellArray;
}

/* end of function definitions  */
@ini_set( "max_execution_time", "180");

require_once 'config.php';
$flow = 1;

if ( !isset( $level))
    $level = 3;

if ( !isset( $type))
    $type = 'F';

$cid = $ID;

$tree = array( );

$tree[] = $cid;

for ( $i = 0; $i <= $level; $i++) {
    $m = $db->getMother( $cid);
    $f = $db->getFather( $cid);

    if ( $type == 'F' && $f > 0) {
        $tree[] = $f;
        $cid = $f;
    } elseif ( $type == 'M' && $m > 0) {
        $tree[] = $m;
        $cid = $m;
    } else {
        break;
    }
}

for ( $i = 2; $i <= 6; $i++) {
    $levelsArray[$i] = "$i";
}

$mydata['level'] = $level;
$mydata['type'] = $type;
$mydata['ID'] = $ID;
$mydata['animalPedigree'] = ANIMALPEDIGREE;

$smarty->assign( "levelsArray", $levelsArray);

$vals = array( );
$vals[0][0] = array( $tree[$level + 1]);
$i = 1;

for ( $j = $level; $j >= -2; $j--) {
    $vals[$i][0] = $tree[$j];

    for ( $k = 1; $k <= $i; $k++) {
        if ( $j >= 0) {
            $vals[$i][$k] = array_diff( $db->getLChildren( $vals[$i - 1][$k - 1]), array( $tree[$j]));
        } else {
            $vals[$i][$k] = array_diff( $db->getLChildren( $vals[$i - 1][$k]), array( $tree[$j]));
        }
    }
    $i++;
}

$xx = 0;

for ( $i = 0; $i <= $level + 3; $i++) {
    // Top <td> count for column indenting
    $toptdcnt = array( );
    for ( $j = 0; $j < $level + 1 - $i; $j++)
    $toptdcnt[$j] = " ";

    $dispList[$i]->toptdcnt = $toptdcnt;

    for ( $l = 0; $l < count( $vals[$i]); $l++) {
        $v = $vals[$i][$l];
        $cellArray = printCell( $v, $i, $l, $xx);
        $levelList[$l] = $cellArray;
        $xx++;
    }

    $dispList[$i]->levelList = $levelList;
    $downtdcnt = array( );
    for ( $l = 0; $l < 2 * ( $level - 1) + 1; $l++)
    $downtdcnt[$l] = " ";
    // down <td> count for column indenting
    $dispList[$i]->downtdcnt = $downtdcnt;
}
// print_r( $dispList );
/*
   // determine hide status for all names

   $hid = $db->getItem( "hide", $ID );

   if ( $hid == "Yes" && ( 1 != 1 ) )
   $name = $db->obstr( $name, 1 );
   */

$smarty->assign( "mydata", $mydata);
$smarty->assign( "dispList", $dispList);
// # Get the page we want to display
$smarty->assign( 'rendered_page', $smarty->fetch( 'cshow.tpl'));
// # Display the page.
$smarty->display( 'index.tpl');

?>