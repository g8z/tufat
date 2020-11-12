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
 * This is modified to incorporate smarty and formsess template processor
 * Vijay Nair                                                30/3/2004
 */

require_once 'config.php';

// #require 'templates/'.$templateID.'/tpl_header.php';

if ( isset( $_SESSION["admin"]) || isset( $_SESSION["master"])) 
{
    if ( !isset( $add))
        $add = 1;

    if ( $add == 1) 
	{
        if ( $adik == 1) 
		{
            $sql = "select user,pass from ! where user= ? and pass= ?";
            $r = $db->query( $sql, array( $db->ilogiTable, $suser, $spass));

            if ( $r != false) 
			{
                $n = $db->rowsInResult( $r);
				
                if ( $n > 0) 
				{
                    $user_exists = true;
                    $messg = $db->mytrans( '##The username $suser with password $spass already exists##');
                } 
				else 
				{
                    $ok = 0;

                    if ( $stp == 1) 
					{
                        $sql = "select id from ! where tree= ? and type='I' and id= ?";
                        $p = $db->query( $sql, array( $db->gedcomTable, $user, $sID));

                        if ( $p != false && $db->rowsInResult( $p) > 0) 
						{
                            $ok = 1;
                        }
                    }

                    if ( $stp == 0 || ( $stp == 1 && $ok == 1) || $stp == 2 || $stp == 3) 
					{
                        if ( $stp == 0 || $stp == 3 || $stp == 2)
                            $sID = 0;

                        $sql = "insert into ! (user,pass, ID, tp) values ( ?, ?, ?, ?)";
                        $r = $db->query( $sql, array( $db->ilogiTable, addslashes( $suser), addslashes( $spass), $sID, $stp));

                        if ( $r != false)
                            $messg = $db->mytrans( "##The login has been added successfully.##");
                        else
                            $messg = $db->mytrans( "##Login add problem##");
                    } 
					else 
					{
                        if ( $sID == 0)
                            $messg = $db->mytrans( '##Please select Individual ID from the drop down box##');
                        else
                            $messg = $db->mytrans( '##Individual with ID $sID not found##');
                    }
                }
            }
        } 
		else 
		{
            if ( isset( $_SESSION["master"])) 
			{
                $sql = "select username from !";
                $p = $db->query( $sql, array( $db->userTable));

                if ( $p != false) 
				{
                    for ( $i = 0; $i < $db->rowsInResult( $p); $i++) 
					{
                        $b = $db->mfa( $p);
                        $suser_list->$b["username"] = $b["username"];
                    }
                }
            }
			
            $all = array( );

            $sql = "select id from ! where type='I' and tree= ? group by id";
            $q = $db->query( $sql, array( $db->gedcomTable, $user));

            $n = $db->rowsInResult( $q);
			
            if ( $q != false && $n > 0) 
			{
                for ( $i = 0; $i < $n; $i++) 
				{
                    $a = $db->mfa( $q);
                    $all[] = $a["id"];
                }
            }

            foreach ( $all as $k => $v) 
			{
                $name = $db->getItem( "name", $v);
                $name = str_replace( "/", "", $name);

                if ( $v > -1 ) 
				{
                	$sname_list[$v] = ( $db->sG( $v));
            	}
            }
        }
    }
	
    if ( $_REQUEST['off'] == on) 
	{
        $db->query( "DELETE FROM " . MYSQLPREFIX . "ilogins WHERE pass='" . $_REQUEST['p'] . "' and tp='" . $_REQUEST['t'] . "'");
    }
	
    $sql = "select * from !";
    $ilres = $db->query( $sql, array( TBL_ILOGI));
	
    if ( $ilres != false) 
	{
        while ( $f = $db->mfa( $ilres)) 
		{
            $del_list[$i]['pass'] = $f['pass'];
            $del_list[$i]['tp'] = $f['tp'];
			
            if ( $f['tp'] == 0)
                $del_list[$i]['tp1'] = 'General Edit';
				
            if ( $f['tp'] == 1)
                $del_list[$i]['tp1'] = 'Edit individual ID #' . $f['ID'];
				
            if ( $f['tp'] == 2)
                $del_list[$i]['tp1'] = 'Read Only';
				
            if ( $f['tp'] == 3)
                $del_list[$i]['tp1'] = 'Administrative';
				
            $i++;
        }
    }
}

$mydata['del_list'] = $del_list;
$mydata['add'] = $add;
$mydata['adik'] = $adik;
$mydata['suser'] = $suser;
$mydata['spass'] = $spass;
$mydata['user_exists'] = $user_exists;
$mydata['stp'] = $stp;
$mydata['messg'] = $messg;
$mydata['templateID'] = $templateID;

$smarty->assign( "suser_list", $suser_list);
$smarty->assign( "sname_list", $sname_list);
$smarty->assign( "mydata", $mydata);

// # Get the page we want to display
$smarty->assign( 'rendered_page', $smarty->fetch( 'ilogi.tpl'));

// # Display the page.
$smarty->display( 'index.tpl');

$inst = 0;

?>