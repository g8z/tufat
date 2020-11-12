<?php
ob_start();
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
 * This is modified to inforporate Smarty Template Processor
 * Vijay Nair                                 10/Apr/2004
 */

require_once 'config.php';

$recperpage = 25;
global $a;

$mydata['addok'] = $addok;
$mydata['loadok'] = $loadok;
$mydata['delok'] = $delok;
$mydata['ID'] = $ID;
$mydata['editk'] = $edik;
$mydata['edit'] = $edit;
$mydata['fp'] = $fp;
$mydata['mid'] = $mid;
$mydata['add'] = $add;
$mydata['addik'] = $addik;
$mydata['del'] = $del;
$mydata['view'] = $view;
$mydata['tnail'] = $tnail;

if ( $edit == 1 && ( !isset( $_SESSION['read_only']) || ( $_SESSION["my_rec"] == $ID))) 
{
    if ( $edik == 1) 
	{
        $sdescr = check_slash( $sdescr);
        $stitle = check_slash( $stitle);

        $sql = "update ! set title= ?, link = ?, descr = ? where id = ? and tree= ?";
        $r = $db->query( $sql, array( $db->ilinkTable, $stitle, $slink, $sdescr, $mid, $user));

        if ( $r != false)
            $mydata['loadok'] = true;
        else
            $mydata['loadok'] = false;
        header( "location: links.php?ID=$ID&loadok=$loadok");
        exit;
    } 
	else 
	{
        $sql = "select title, link, descr from {$db->ilinkTable} where id = ".$db->dbh->quote($mid)." and tree= ".$db->dbh->quote($user);
		
        $r = $db->getRow($sql);
        if ( $r != false) {
            $mydata['editcnt'] = 1;
            $a = $r;
            // stripslashes() jrpi: removed 01.02.06
            $a["title"] = $a["title"];
            // stripslashes(
            $a["descr"] = $a["descr"];

            $smarty->assign( "a", $a);
        }
    }
} 
elseif ( !isset( $_SESSION["edit_only"]) && $add == 1 && ( !isset( $_SESSION['read_only']) || ( $_SESSION["my_rec"] == $ID))) 
{
    if ( $addik == 1) 
	{
        $stitle = check_slash( $stitle);
        $sdescr = nl2br( check_slash( $sdescr ) );

        $sql = "select max(id) from {$db->ilinkTable}";
        $p = $db->getValue($sql);

        $maxid = ( $p != false) ? $maxid = $p + 1 : 1;

        $sql = "insert into ! (id, tree, link,  title, descr, sid) values (?, ?,  ?, ?, ?, ?)";
        $r = $db->query( $sql, array( $db->ilinkTable, $maxid, $user, $slink, $stitle, $sdescr, $ID));

        if ( $r != false) 
		{
            $mydata['addok'] = true;
        } 
		else 
		{
            $mydata['addok'] = false;
        }
        header( "location: links.php?ID=$ID&addok=$addok");
        exit;
    }
} 
elseif ( !isset( $_SESSION["edit_only"]) && $del == 1 && $mid != '' && ( !isset( $_SESSION['read_only']) || ( $_SESSION["my_rec"] == $ID))) 
{
    $sql = "delete from ! where id = ? and sid= ?";
    $r = $db->query( $sql, array( $db->ilinkTable, $mid, $ID));

    if ( $r != false) 
	{
        $mydata['delok'] = true;
    } else 
	{
        $mydata['delok'] = false;
    }
    header( "location: links.php?ID=$ID&delok=$delok");
    exit;
} 
elseif ( ( $view == 1 || !isset( $add) || !isset( $tnail) || !isset( $del)) && $tnail != 1) 
{
    $sql = "select id,link,title,descr from ! where tree= ? and sid = ? order by id";
    $r = $db->query( $sql, array( $db->ilinkTable, $user, $ID));
	
    if ( $r != false) 
	{
        $n = $db->rowsInResult( $r);
        $mydata['recscnt'] = $n;
		
        if ( $n > 0) 
		{
            for ( $i = 1; $i <= $n; $i++) 
			{
                $a = $db->mfa( $r);
				
                if ( strstr( $a["link"], "http://") == false && preg_match( "/^www(.)+/", $a["link"]) != false) 
				{
                    $a["link"] = 'http://' . $a['link'];
                }
                else 
				if ( stristr( $a['link'], '@' ) ) 
				{
					$a["link"] = 'mailto:' . $a['link'];
                }

                $linksList[$i]->link = $a["link"];
                $linksList[$i]->descr = $a["descr"];
                $linksList[$i]->title = $a["title"];
                $linksList[$i]->id = $a["id"];
            }
        }
		
        $smarty->assign( "linksList", $linksList);
    }
}

//@ redirect on view page after an action
$mydata['view'] = 1;

$smarty->assign( "mydata", $mydata);
$smarty->assign( "loadphp", true);

// # Get the page we want to display
$smarty->assign( 'rendered_page', $smarty->fetch( 'links.tpl'));

// # Display the page.
$smarty->display( 'index.tpl');

?>