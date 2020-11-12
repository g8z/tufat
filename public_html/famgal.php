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
 * Modified to incorporate Smarty template Processor
 * Vijay Nair                              5/Apr/2004
 */

require_once 'config.php';

$recperpage = 25;
global $a;

$fp = $db->lgetFamilyP( $ID);

if ( $fp == false) 
{
    $fp = $db->lgetFamilyC( $ID);
}

$maxid = 1;
$sql = "select max(id) from {$db->famgalTable}";
$p = $db->getValue($sql);

if ( $p != false) 
{
    $maxid = $p + 1;
}

if ( $hid > 1 && ( !isset( $_SESSION['read_only']) || ( $_SESSION["my_rec"] == $ID)))
{
    $sql = "select max(id) from {$db->famgalTable} where id < ".$db->dbh->quote($hid)." and tree=".$db->dbh->quote($user);
	
    $p = $db->getValue($sql);
	
    if ( $p != false) 
	{
        $oid = $p;
        // echo $oid;
        $rx = "999999999";

        $sql = "update ! set id= ? where id= ?";
        $db->query( $sql, array( $db->famgalTable, $rx, $oid));
        $sql = "update ! set id = ? where id = ?";
        $db->query( $sql, array( $db->famgalTable, $oid, $hid));
        $sql = "update ! set id = ? where id = ?";
        $db->query( $sql, array( $db->famgalTable, $hid, $rx));
    }
}

$mydata['ID'] = $ID;
$mydata['edit'] = $edit;
$mydata['editk'] = $editk;
$mydata['fp'] = $fp;
$mydata['mid'] = $mid;
$mydata['add'] = $add;
$mydata['del'] = $del;
$mydata['view'] = $view;
$mydata['tnail'] = $tnail;

$sfile  = $_FILES["sfile"];

if ( $edit == 1 && ( !isset( $_SESSION['read_only']) || ( $_SESSION["my_rec"] == $ID))) 
{
    if ( $edik == 1) 
	{

        if ( ( $tname = $sfile["tmp_name"]) != '') {
            $rname = $sfile["name"];
            $data = file_get_contents($tname);
            $data = addslashes($data);

            $mtype = $db->getMime( $rname, $xx);
            $s = ", data = '$data', name='$rname', type=$mtype";
        }  else
            $s = '';

        $sdescr = addslashes( $sdescr);
        $stitle = addslashes( $stitle);
        $r = $db->query( "update $db->famgalTable set title='$stitle', descr = '$sdescr' $s where id = '$mid' and tree='$user'");
    }

    $sql = "select title, descr, kd from ! where id = ? and tree= ?";
    $r = $db->query( $sql, array( $db->famgalTable, $mid, $user));

    if ( $r != false && $db->rowsInResult( $r) > 0) 
	{
        $a = $db->mfa( $r);

        $a["title"] = stripslashes( $a["title"]);
        $a["descr"] = stripslashes( $a["descr"]);
        $smarty->assign( "a", $a);
        $mydata['edit_recs'] = true;
    }
} 
else
	if ( !isset( $_SESSION["edit_only"]) && $add == 1 && 
		( !isset( $_SESSION['read_only']) || ( $_SESSION["my_rec"] == $ID))) 
	{		
    	$mydata['addik'] = $addik;
		
    	if ( $addik == 1) 
		{			
        	if ( ( $tname = $sfile["tmp_name"]) != '') 
			{
				$rname = strtolower( $sfile["name"]);
				$data = fread( fopen( $tname, "rb"), filesize( $tname));
				$data = addslashes( $data);
		
				$mtype = $db->getMime( $rname, $xx);
	
				$stitle = addslashes( $stitle);
				$sdescr = addslashes( $sdescr);
				
				if ( $sindi == 0) 
				{
					$msid = 0;
					$kd = 0;
				} 
				else if ( $sindi == 1) 
				{
					$fp = 0;
					$msid = $ID;
					$kd = 0;
				} 
				else 
				{
					$fp = 0;
					$msid = $ID;
					$kd = 1;
				}
				
				$t = time( );

           	$sql = "insert into ! (kd, id, ta, tree, fid, data, type, name, title, descr, indi, sid) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $r = $db->query( $sql, array( $db->famgalTable, $kd, $maxid, $t, $user, $fp, $data, $mtype, $rname, $stitle, $sdescr, $sindi, $msid));

            if ( $r != false) 
			{
            	$mydata['addmsg'] = $db->mytrans( "##The image has been successfully added to the gallery##");
            } 
			else 
			{
             	$mydata['addmsg'] = $db->mytrans( "##There was some upload problem. Please contact your Adminitrator.##");
            }
        }
    }
} 
else 
   if ( !isset( $_SESSION["edit_only"]) && $del == 1 && $mid != '' && ( !isset( $_SESSION['read_only']) || ( $_SESSION["my_rec"] == $ID))) 
   {
    $sql = "select fid from {$db->famgalTable} where id=".$db->dbh->quote($mid);
    $tqr = $db->getValue($sql);
	
    if ( $tqr != false) 
	{
        $fidik = $tqr;
    }

    if ( $fp != false && $kd != 1 && $fidik != 0) 
	{
        $sql = "delete from ! where id = ? and fid= ?";
        $r = $db->query( $sql, array( $db->famgalTable, $mid, $fp));
    } 
	else if ( $kd == 1) 
	{
        $sql = "delete from ! where id = ? and kd = '1'";
        $r = $db->query( $sql, array( $db->famgalTable, $mid));
    } 
	else 
	{
        $sql = "delete from ! where id = ? and sid= ?";
        $r = $db->query( $sql, array( $db->famgalTable, $mid, $ID));
    }
    
	//print "<br />";
	
    if ( $r != false) 
	{
        $mydata['delmsg'] = $db->mytrans( "##The deletion has been made.##");
    } 
	else 
	{
        $mydata['delmsg'] = $db->mytrans( "##Deletion problem##");
    }
} 
else
if ( ( $view == 1 || !isset( $add) || !isset( $tnail) || !isset( $del)) && $tnail != 1) 
{
    $sql = "select id,data,name,title,descr,type from ! where tree= ? and indi = '1' and sid = ? order by id";
    $r = $db->query( $sql, array( $db->famgalTable, $user, $ID));
	
    if ( $r != false) 
	{
        $n = $db->rowsInResult( $r);
        $mydata['archcnt'] = $n;
		
        if ( $n >= 0) 
		{
            $mydata['showgallery'] = true;
            $test = 1;
			
            if ( require 'classes/archive.php') 
			{
                $mydata['askarchive'] = true;
                $tarok = 1;
            }

            $sql = "select id from {$db->famgalTable} where indi ='1' and sid=".$db->dbh->quote($ID)." and tree=".$db->dbh->quote($user)." order by id limit 1";
            $p = $db->getValue($sql);
			
            if ( $p != false) 
			{
                $cid = $p;
                $mydata['cid'] = $cid;
            }
        }
		
        if ( $n > 0) 
		{
            $mydata['archcntmsg'] = $n . " " . $db->mytrans( '##items found##');

            for ( $i = 1; $i <= $n; $i++) 
			{
                $a = $db->mfa( $r);

                $archlist[$i]->name = $a["name"];
                $archlist[$i]->data = "( " . round( strlen( $a["data"]) / 1024, 2) . " KB )";
                $archlist[$i]->title = stripslashes( $a["title"]);
                $archlist[$i]->mid = $a["id"];
                $archlist[$i]->type = $a["type"];
            }
            $smarty->assign( "archlist", $archlist);
        }
    }

    if ( $fp != false) 
	{
        $sql = "select id,data,name,title,descr,type from ! where tree= ? and fid = ? order by id";
        $r = $db->query( $sql, array( $db->famgalTable, $user, $fp));

        if ( $r != false) 
		{
            $n = $db->rowsInResult( $r);
			
            if ( $n >= 0) 
			{
                $mydata['famgalcnt'] = $n;
                $test = 1;
				
                if ( $tarok != 1) 
				{
                    if ( require 'classes/archive.php') 
					{
                        $tarok = 1;
                    }
                }
				
                $mydata['tarok'] = $tarok;

                $sql = "select id from {$db->famgalTable} where fid=".$db->dbh->quote($fp)." and tree=".$db->dbh->quote($user)." order by id limit 1";
                $p = $db->getValue($sql);
				
                if ( $p != false) 
				{
                    $cid = $p;
                    $mydata['famcid'] = $cid;
                }
				
                $mydata['famgalfoundmsg'] = $n . " " . $db->mytrans( '##items found##');

                for ( $i = 1; $i <= $n; $i++) 
				{
                    $a = $db->mfa( $r);
                    $famgallist[$i]->name = $a["name"];
                    $famgallist[$i]->data = "( " . round( strlen( $a["data"]) / 1024, 2) . " KB )";
                    $famgallist[$i]->title = stripslashes( $a["title"]);
                    $famgallist[$i]->mid = $a["id"];
                    $famgallist[$i]->type = $a["type"];
                }
				
                $smarty->assign( "famgallist", $famgallist);
            }
        }
    }
	
    $mydata['user'] = $user;

    $sql = "select id,data,name,title,descr,type from ! where tree= ? and kd='1' order by id";
    $r = $db->query( $sql, array( $db->famgalTable, $user));
	
    if ( $r != false) 
	{
        $n = $db->rowsInResult( $r);
		
        if ( $n >= 0) 
		{
            $mydata['treecnt'] = $n;
            $test = 1;
			
            if ( $tarok != 1) 
			{
                if ( require 'classes/archive.php') 
				{
                    $tarok = 1;
                }
            }
			
            $mydata['tarok'] = $tarok;

            $sql = "select id from {$db->famgalTable} where kd='1' and tree=".$db->dbh->quote($user)." order by id limit 1";
            $p = $db->getValue($sql);

            if ( $p != false) 
			{
                $cid = $p;
                $mydata['treecid'] = $cid;
            }
			
            $mydata['treecntmsg'] = $n . " " . $db->mytrans( '##items found##');

            for ( $i = 1; $i <= $n; $i++) 
			{
                $a = $db->mfa( $r);
                $treegallist[$i]->name = $a["name"];
                $treegallist[$i]->data = "( " . round( strlen( $a["data"]) / 1024, 2) . " KB )";
                $treegallist[$i]->title = stripslashes( $a["title"]);
                $treegallist[$i]->mid = $a["id"];
                $treegallist[$i]->type = $a["type"];
            }
			
            $smarty->assign( "treegallist", $treegallist);
        }
    }
} 
else if ( $tnail == 1) 
	{
    if ( require 'classes/archive.php') 
	{
        $mydata['askarchive'] = true;
        $mydata['tarok'] = 1;
    }

    $sql = "select id,data,name,title,descr from ! where indi = '1' and sid = ? and type='0' and tree= ?";
    $r = $db->query( $sql, array( $db->famgalTable, $ID, $user));

    if ( $r != false) 
	{
        $n = $db->rowsInResult( $r);
        $mydata['indgalcnt'] = $n;
		
        if ( $n > 0) 
		{
            $mydata['tnail_indgalmsg'] = $n . " " . $db->mytrans( '##items found##');

            if ( $from1 == '')
                $from1 = 1;
				
            $to1 = $recperpage;
            $mydata['recperpage'] = $recperpage;
            $mydata['from1'] = $from1;
            $mydata['to1'] = $to1;
            $mydata['urlencsrch'] = urlencode( $srch);
            $mydata['sla'] = $sla;

            for ( $u = 1, $w = 1; $u <= $n; $u += $recperpage, $w++) 
			{
                if ( $from1 != $u)
                    $pagelists[$w] = $u;
                else
                    $pagelists[$w] = 0;
            }
			
            $smarty->assign( "pagelists", $pagelists);

            for ( $i = 1;$i < $from1 && $i <= $n; $i++)
            	$a = $db->mfa( $r);

            $k = 0;
			
            for ( $i = $from1;( $i < ( $from1 + $recperpage)) && ( $i <= $n); $i++) 
			{
                $a = $db->mfa( $r);

                $mtit = stripslashes( $a["title"]);
				
                if ( strlen( $mtit) > 15) 
				{
                    $mtit = substr( $mtit, 0, 12) . "...";
                }
				
                $tnail_ind[$k]->title = $mtit;
                $tnail_ind[$k]->mid = $a["id"];

                $k++;
            }
            $smarty->assign( "tnail_ind", $tnail_ind);
        }
    }
	
    if ( $fp != false) 
	{
        $sql = "select id,data,name,title,descr from ! where fid = ? and type='0' and tree= ?";
        $r = $db->query( $sql, array( $db->famgalTable, $fp, $user));

        if ( $r != false) 
		{
            $n = $db->rowsInResult( $r);
            $mydata['tnailfamgalcnt'] = $n;
			
            if ( $n > 0) 
			{
                $mydata['tnail_famgalcntmsg'] = $n . " " . $db->mytrans( '##items found##');

                if ( $from == '')
                    $from = 1;
					
                $to = $recperpage;
                $mydata['from'] = $from;
                $mydata['to'] = $to;
                $mydata['recperpage'] = $recperpage;
                $mydata['urlencsrch'] = urlencode( $srch);
                $mydata['sla'] = $sla;

                for ( $u = 1, $w = 1; $u <= $n; $u += $recperpage, $w++) 
				{
                    if ( $from1 != $u)
                        $pagelists1[$w] = $u;
                    else
                        $pagelists1[$w] = 0;
                }
				
                $smarty->assign( "pagelists1", $pagelists1);

                for ( $i = 1;$i < $from && $i <= $n; $i++)
                	$a = $db->mfa( $r);

                $k = 0;
                for ( $i = $from;( $i < ( $from + $recperpage)) && ( $i <= $n); $i++) 
				{
                    $a = $db->mfa( $r);

                    $mtit = stripslashes( $a["title"]);
					
                    if ( strlen( $mtit) > 15) 
					{
                        $mtit = substr( $mtit, 0, 12) . "...";
                    }
					
                    $tnail_fam[$k]->title = $mtit;
                    $tnail_fam[$k]->mid = $a["id"];
                    $k++;
                }
				
                $smarty->assign( "tnail_fam", $tnail_fam);
            }
        }
    }

    $sql = "select id,data,name,title,descr from ! where kd='1' and type='0' and tree= ?";
    $r = $db->query( $sql, array( $db->famgalTable, $user));

    if ( $r != false) 
	{
        $n = $db->rowsInResult( $r);
        $mydata['tnailtreecnt'] = $n;
		
        if ( $n > 0) 
		{
            $mydata['tnailtreecntmsg'] = $n . " " . $db->mytrans( '##items found##');

            if ( $from2 == '')
                $from2 = 1;
				
            $to = $recperpage;
            $mydata['from2'] = $from2;
            $mydata['to'] = $to;
            $mydata['recperpage'] = $recperpage;
            $mydata['urlencsrch'] = urlencode( $srch);
            $mydata['sla'] = $sla;

            for ( $u = 1, $w = 1; $u <= $n; $u += $recperpage, $w++) 
			{
                if ( $from2 != $u)
                    $pagelists2[$w] = $u;
                else
                    $pagelists2[$w] = 0;
            }
			
            $smarty->assign( "pagelists2", $pagelists2);

            for ( $i = 1;$i < $from2 && $i <= $n; $i++)
            	$a = $db->mfa( $r);

            $k = 0;
			
            for ( $i = $from2;( $i < ( $from2 + $recperpage)) && ( $i <= $n); $i++) 
			{
                $a = $db->mfa( $r);

                $mtit = stripslashes( $a["title"]);
                if ( strlen( $mtit) > 15) {
                    $mtit = substr( $mtit, 0, 12) . "...";
                }
                $tnail_tree[$k]->title = $mtit;
                $tnail_tree[$k]->mid = $a["id"];

                $k++;
            }
			
            $smarty->assign( "tnail_tree", $tnail_tree);
        }
    }
}

if ( $_SESSION['read_only'] == 1) 
	$smarty->assign( "read_only", 1);
	
	
if( $mydata['addik'] == 1 or $mydata['del'] == 1 )
{
    if( $mydata['del'] == 1 )
		$img = 'del';
		
    if( $mydata['addik'] == 1)
		$img = 'add';
	
    header( "Location: famgal.php?ID=$ID&view=1&img=$img&err=".(($r!=false)?0:1) );
    exit;
}

if( $img == 'del' && isset($err) )
{
    if ( !$err ) 
        $mydata['imgmsg'] = $db->mytrans( "##The deletion has been made.##");
	else 
        $mydata['imgmsg'] = $db->mytrans( "##Deletion problem##");
}	

if( $img == 'add' && isset($err) )
{
	if ( !$err ) 
		$mydata['imgmsg'] = $db->mytrans( "##The image has been successfully added to the gallery##");
	else 
		$mydata['imgmsg'] = $db->mytrans( "##There was some upload problem. Please contact your Adminitrator.##");
}	

$smarty->assign( "mydata", $mydata);
$smarty->assign( "loadphp", true);

// # Get the page we want to display
$smarty->assign( 'rendered_page', $smarty->fetch( 'famgal.tpl'));

// # Display the page.
$smarty->display( 'index.tpl');

?>
