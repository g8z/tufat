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
 * This is modified to incorporate Smarty Template Processor
 * Vijay Nair                             12/Apr/2004
 */

require_once 'config.php';

$flow = 1;

require 'taglist.php';

$mydata['xtag'] = $xtag;
$mydata['ID'] = $ID;
$mydata['del'] = $del;
$mydata['add'] = $add;
$mydata['mid'] = $mid;
$mydata['ct'] = $ct;
$mydata['mr'] = $mr;

$recsList = array( );
if ( $mr == 1) {
    $tags = $famtags;
}

$mydata['tagmsg'] = "";
$err = '';

if ( $xtag != '' && $ID != '') {
    if ( $xtag == "MARR" || $xtag == "DIV")
        $mr = 1;

    switch ( $xtag) {
        case "BIRT":
            $mydata['tagmsg'] = $db->mytrans( "##Birth Citations##");
            break;
        case "DEAT":
            $mydata['tagmsg'] = $db->mytrans( "##Death Citations##");
            break;
        case "ADOP":
            $mydata['tagmsg'] = $db->mytrans( "##Adoption Citations##");
            break;
        case "BAPM":
            $mydata['tagmsg'] = $db->mytrans( "##Baptism Citations##");
            break;
        case "BARM":
            $mydata['tagmsg'] = $db->mytrans( "##Bar/Mitzvah Citations##");
            break;
        case "GRAD":
            $mydata['tagmsg'] = $db->mytrans( "##Graduation Citations##");
            break;
        case "IMMI":
            $mydata['tagmsg'] = $db->mytrans( "##Immigration Citations##");
            break;
        case "CONF":
            $mydata['tagmsg'] = $db->mytrans( "##Confirmation Citations##");
            break;
        case "MARR":
            $mydata['tagmsg'] = $db->mytrans( "##Marriage Citations##");
            break;
        case "DIV":
            $mydata['tagmsg'] = $db->mytrans( "##Divorce Citations##");
            break;
        default:
            $ftags = array_flip( $tags);
            if ( $ftags[$xtag] != '') {
                $mydata['tagmsg'] = $ftags[$xtag] . " ";
            }
            $mydata['tagmsg'] .= $db->mytrans( "##Citations##");
    }

    if ( $changik == 1) 
	{
        $mrtyp = ( $mr) ? 'F' : 'I';
		
        foreach ( $_POST['cit'] as $tcid => $rec) 
		{
            if ( $rec['hid'] && !( $rec['spage'] == '' && $rec['sdate'] == '' && $rec['snote'] == '')) 
			{
                $err = '';
                $err .= $db->checkTags( 'note', $rec['spage']);
                $err .= $db->checkTags( 'note', $rec['sdate']);
                $err .= $db->checkTags( 'note', $rec['snote']);

                $sql = "SELECT hid FROM ! WHERE tree=? AND id=? AND type=? AND tag='SOUR'";
                $r = $db->query( $sql, array( $db->gedcomTable, $user, $rec['smt'], $mrtyp));
				
                if ( $r == false)
                    $err .= CRLF . "##Invalid Source specified## (" . $rec['smt'] . ")" . CRLF;

                if ( !$err) 
				{
                    $sql = "DELETE FROM ! WHERE tree=? AND id=? AND hid=? and type=? AND tag='SREF'";
                    $db->query( $sql, array( $db->gedcomTable, $user, $ID, $rec['hid'], $mrtyp));

                    $db->setCitItem( $mr, $ID, 'TYPE', $rec['hid'], $xtag);
                    $db->setCitItem( $mr, $ID, 'SREF', $rec['hid'], $rec['smt']);
                    $db->setCitItem( $mr, $ID, 'PAGE', $rec['hid'], $rec['spage']);
                    $db->setCitItem( $mr, $ID, 'DATE', $rec['hid'], $rec['sdate']);
                    $db->setCitItem( $mr, $ID, 'NOTE', $rec['hid'], $rec['snote']);
                }
            }
        }
    } 
	elseif ( $del == 1 && $sid > 0) 
	{
        if ( !isset( $_SESSION['read_only']) || $_SESSION["my_rec"] == $ID) 
		{
            $sql = "delete from ! where tree= ? and id=? and tag='SREF' and hid = ?";
            $r1 = $db->query( $sql, array( $db->gedcomTable, $user, $ID, $sid));

            $sql = "delete from ! where tree=? and id=? and type='C' and hid=?";
            $r2 = $db->query( $sql, array( $db->gedcomTable, $user, $ID, $sid));

            if ( $r1 != false && $r2 != false) 
			{
                $mydata['delmsg'] = $db->mytrans( '##Citation has been deleted successfully.##');
            } 
			else 
			{
                $mydata['delmsg'] = $db->mytrans( "##Citation delete problem.##");
            }
        }
    } 
	elseif ( $add == 1 && $ref != 1 && $err == '') 
	{
        if ( !isset( $_SESSION['read_only']) || $_SESSION["my_rec"] == $ID) 
		{
            header( "Location: showsour.php?ID=$ID&xtag=$xtag&mr=$mr&new=1");
        } 
		else 
		{
            header( "Location: showsour.php?$ID=$ID&xtag=$xtag&mr=$mr");
        }
    }

    $sql = "select id from ! where tree= ? and type='S' and tag='SOUR' order by id";
    $p = $db->query( $sql, array( $db->gedcomTable, $user));
    $optsList = array( );
	
    if ( $p != false) 
	{
        while ( $c = $db->mfa( $p)) 
		{
            $optsList[$c['id']]->id = $c['id'];
            $optsList[$c['id']]->titl = stripslashes( $db->getSourItem( "TITL", $c["id"]));
        }

        $mydata['optsList'] = $optsList;
        $mydata['optsListcnt'] = count( $optsList);
    }

    if ( $err == '') 
	{
        $i = 0;
        $mrtyp = ( $mr) ? "F" : "I";
        $sql = "SELECT hid FROM ! WHERE tree=? AND id=? AND type='C' AND tag='TYPE' and data=? ORDER BY hid";
        $r = $db->query( $sql, array( $db->gedcomTable, $user, $ID, $xtag));
		
        while ( $a = $db->mfa( $r)) 
		{
                $recsList[$i]->smt = $db->getCitItem( $ID, 'SREF', $a['hid']);
                $recsList[$i]->source = stripslashes( $optsList[$recsList[$i]->smt]->titl);
                $recsList[$i]->hid = $a['hid'];
                $recsList[$i]->spage = $db->getCitItem( $ID, 'PAGE', $a['hid']);
                $recsList[$i]->sdate = $db->getCitItem( $ID, 'DATE', $a["hid"]);
                $recsList[$i]->snote = $db->getCitItem( $ID, 'NOTE', $a["hid"]);
                $recsList[$i]->mr = $mr;
                $i++;
        }
    } 
	else 
	{
        $i = 0;
		
        foreach ( $_POST['cit'] as $tcid => $rec) 
		{
            $recsList[$i]->source = stripslashes( $optsList[$rec['smt']]->titl);
            $recsList[$i]->hid = $rec['hid'];
            $recsList[$i]->spage = $rec['spage'];
            $recsList[$i]->sdate = $rec['sdate'];
            $recsList[$i]->snote = $rec['snote'];
            $recsList[$i]->smt = $rec['smt'];
            $recsList[$i]->mr = $mr;
            $i++;
        }
        $mydata['nf'] = 1;
    }
}

if ( $new) 
{
    // Add a new record - in this case we just add a blank set to the citations
    $type = ( $mr) ? 'F' : 'I';
    $sql = "select max(hid) from {$db->gedcomTable} WHERE id=".$db->dbh->quote($ID)." AND type = ".$db->dbh->quote($type)." AND tag='SREF'";
    $ncid = $db->getValue($sql);
    $recc = count( $recsList) + 1;
    $recsList[$recc]->hid = $ncid["hid"] + 1;
    $recsList[$recc]->mr = $_POST['mr'];
    $recsList[$recc]->noshow = true;
}

$mydata['xtag'] = $xtag;
$mydata['ID'] = $ID;
$mydata['cnt'] = count( $recsList);
$mydata['err'] = $err;
$smarty->assign( "recsList", $recsList);
$smarty->assign( "mydata", $mydata);

$smarty->display( "showsour.tpl");

?>