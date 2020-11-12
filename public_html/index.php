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
 */

ob_start();
/**
 * This file is modified to incorporate Smarty
 *
 * Vijay Nair                              19-Mar-2004
 */
// if the $user session variable is not listed in the users table, default to the login screen
// check _GET & _POST to thwart hackers
require_once 'config.php';

if ( $db->check_tables( ) == false || !INCLUDES ) {
    $no_tables = 1;
    die(header('Location: install.php'));
}

$sql = "select count(ID) from ".TBL_USER;
$usercnt = $db->getValue($sql);

if ( $usercnt > 0) {
    $sql = "SELECT ID,dname FROM ".TBL_USER." WHERE username = ".$db->dbh->quote($_SESSION['user']);
    $x = $db->getRow($sql);
	
    if ( !$x['id']) {
        $_SESSION['user'] = '';
        $_SESSION['admin'] = '';
        header( 'Location: login.php');
        exit;
    } else {
        $_SESSION['treeName'] = $x['dname'];
        $treeName = $x['dname'];
    }
} else {
    header( 'Location: cnewtree.php');
    exit;
}

$_SESSION['animalPedigree'] = ANIMALPEDIGREE;
$inst = 0;

/**
 * * $familyTree must be of this form:
 *
 * $familyTree = array (
 * "Jackson" => array ("1"=>"Michael", "2"=>"Samuel L.", "5"=>"LaToya"),
 * "Gates" => array ( "3"=>"Darren G.", "12"=>"David Gregory"),
 * "Clinton" => array ("18"=>"William Jefferson")
 * );
 */

$members_count = $db->getIndiCount( );

// No records to display
$individuals = 0;
$individuals = $members_count;

if ( $members_count > 300 && $norm != 1 && !ANIMALPEDIGREE) {
    $l = 1;
} else {
    $familyTree = $db->getTree( $user);
		
    // an array of strings representing family surnames
    $keys = array_keys( $familyTree);
	
    // an array of arrays with ID/name pairs	
    $vals = array_values( $familyTree);
    $family_count = count( $familyTree);
		
    // the total number of lines
    $size = 0;
    $columns = ( MULTICOLUMNDISPLAY > 5) ? 5 : MULTICOLUMNDISPLAY;
	
    // the number of columns that we are splitting our view into
    // loop through all the $vals to see exactly how large this tree is
    foreach ( $vals as $family) 
	{
        $size += sizeof( $family);
    }
	
    // Increment size to account for additional lines created when displaying the headers.
    $size += sizeof( $familyTree ) * 3;
    $i = 0;
    $line = 0;

    $sql = "select count(distinct id) from {$db->gedcomTable} where type='F' and tree=".$db->dbh->quote($user);
    $r = $db->getValue($sql);

    $em = $familyTree;
    $l = '';

    foreach ( $em as $k => $v) 
	{
        $sn = $db->getSurn( $k, $user);
				
        if ( $sn == '')
		{
            $l[$k] = $v;
		}
        else 
		{
            $familyTree[$sn][$k] = $v;
        }
    }

    $familyTree[''] = $l;

    if ( $r > 0) 
	{
        $nkeys = $keys;
        sort( $nkeys);
		
        foreach ( $nkeys as $k => $v) 
		{
            $l = $familyTree[$v];
            $m = array( );
			
            foreach ( $l as $k2 => $v2) 
			{
                if ( @array_key_exists( $v2, $m)) 
				{
                    $names[$v2]++;
                    $m[$v2 . ' {' . $names[$v2] . '}'] = $k2;
                } 
				else
				{
                    @$m[$v2] = $k2;
				}
            }
			
            $jiko = @array_keys( $m);
            sort( $jiko);
			
            foreach ( $jiko as $k2 => $v2) 
			{
                $nfamilyTree[$v][$v2] = $m[$v2];
            }
        }

        $families = @array_keys( $nfamilyTree);
    }

    $col = 0;
	
    if ( !is_array( $families))
        $families = array( );

    foreach ( $families as $key) 
	{
        $family = $nfamilyTree[$key];
        $ids = array_keys( $family);
		
        if ( empty( $key)) 
		{
            if ( !ANIMALPEDIGREE)
			{
                $key = $db->mytrans( '##(no surname given)##');
			}
            else
			{
                $key = $db->mytrans( '##(no name given)##');
			}
        }
		
        if ( $line > $size / $columns && $line != 0) 
		{
            $line = 0;
            $col++;
        }

        $family_array = array( );
		
        foreach ( $ids as $id)
		{
            if ( ANIMALPEDIGREE)
			{
                $gg = $db->changeBrack( $id);
			}
            else
			{
                $gg = $db->removeFam($id);
			}
			
            $hid = $db->getItem( 'HIDE', $family[$id]);
				
            if ( $hid == 'Yes')
			{
                $gg = $db->obstr( $gg, 1);
			}
						
            $line++;
            $family_array[ $family[$id] ] = $gg;
        }
				
        $nfamilies[$col][$key] = $family_array;
        $line = $line + 3;
    }

    $list = $db->getNotFam( );
}

$mydata['list_count'] = count( $list);

if ( $l != 1 && $i == 0 && count( $list) < 1 && !animalPedigree) 
{
    // there are no individuals in this tree...
    $new = true;
} 
else 
{
    // there are people in this tree
    $mydata['sby1'] = $sby;

    if ( !isset( $sby)) 
	{
        $sby = 'name';
    }

    if ( count( $list) > 0 || ANIMALPEDIGREE) 
	{
        if ( ANIMALPEDIGREE) 
		{
            $mydata['sby_list']['name'] = $db->mytrans( '##Animal Name##');
            $mydata['sby_list']['ownr'] = $db->mytrans( '##Owner##');
            $mydata['sby_list']['bred'] = $db->mytrans( '##Breed##');

            $fl = $db->getAllFieldList( $sby);
            $i = 1;
            $colnumbers = 4;
			
            foreach ( $fl as $k1 => $v1) 
			{
                if ( $k1 != $db->mytrans( '##(no owner specified)##') &&
				     $k1 != $db->mytrans( '##(no breed specified)##')) 
				{
                    if ( $sby != 'name') 
					{
                        $list = $db->getAllBy( $sby, $k1);
						
                        foreach ( $list as $k2 => $v2) 
						{
                            $x = $db->changeBrack( $db->getItem( 'name', $v2));
                            $newlist[$k1][$v2] = $x;
                        }
                    } 
					else 
					{
                        $newlist[$k1][$v1] = $db->changeBrack( $k1);
                    }
                } 
				else 
				{
                    foreach ( $fl[$k1] as $k2 => $v2) 
					{
                        $newlist[$k1][$v2] = $db->changeBrack( $db->getItem( 'name', $v2));
                    }
                }
            }
        }
		else 
		{
            foreach ( $list as $k => $v) 
			{
                $k = str_replace( '/', '', $k);
                $hid = $db->getItem( 'HIDE', $v);
								
                if ( $hid == 'Yes')
                    $k = $db->obstr( $k, 1);
					
                $x = $db->changeBrack2( $db->getItem( 'name', $v));
                $newlist[$v] = $x;
            }
        }
    }
	
    $lastLogin = $db->getLoginValue( 'lastlogin');
    $dateCreated = $db->getLoginValue( 'created');
}

$mydata['individuals'] = $individuals;
$mydata['animalPedigree'] = ANIMALPEDIGREE;
$mydata['treeName'] = $treeName;
$mydata['norm'] = $norm;
$mydata['sby'] = $sby;
$mydata['members_count'] = $members_count;
$mydata['family_count'] = $family_count;
$mydata['columns'] = $columns;
$smarty->assign( 'lastLogin', $lastLogin);
$smarty->assign( 'dateCreated', $dateCreated);

if ( trim( $newlist['-1'] ) != false ) 
{

}

$smarty->assign( 'newlist', $newlist);

$smarty->assign( 'mydata', $mydata);
$smarty->assign( 'mytrans', $mytrans);
$smarty->assign( 'families', $nfamilies);

// Check for a Month Change submission
if ( $submit) 
{
    // Subtract one from the month for previous, add one for next
    if ( $submit == "Prev") 
	{
        $month_now--;
    } 
	else 
	{
        $month_now++;
    }

    $date = getdate( mktime( 0, 0, 0, $month_now, 1, $year_now));
} 
else 
{
    $date = getdate( );
}

$month_num = $date["mon"];
$month_name = $date["month"];
$year = $date["year"];
$date_today = getdate( mktime( 0, 0, 0, $month_num, 1, $year));
$first_week_day = $date_today["wday"];
$cont = true;
$today = 27;

while (($today <= 32) && ($cont)) {
  $date_today = getdate( mktime( 0, 0, 0, $month_num, $today, $year));

  if ( $date_today["mon"] != $month_num) {
        $lastday = $today - 1;
        $cont = false;
  }

  $today++;
}

// # Get the page we want to display
$smarty->assign( 'rendered_page', $smarty->fetch( 'index_page.tpl'));

ob_flush();
// # Display the page.
$smarty->display( 'index.tpl');

?>
