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
 * Vijay Nair                                    7/Apr/2004
 * 
 * PAF Compatibility added by aTufa 2005
 * 
 * 
 * // Modified for DB data handling improvements
 * // Pat K.  2006/7/18
 */ 
// / This tags should be ignored for export to PAF otherwise errors
$PAF_ignore = array( 'EDIT', 'HIDE', 'NEW', 'NICK', 'NPFX', 'NSFX', 'SPFX');

if ( isset( $_POST["exportSubmit"])) {
    $flow = 1;
} 

require_once 'config.php';

function invdate( $y)
{
    if ( strlen( $y) == 10) {
        $x = split( "-", $y);
        if ( count( $x) == 3) {
            $z = $x[1] . "/1/2002";

            $ftm = strtotime( $z);
            $nd = $x[2];
            $nd .= strftime( " %b ", $ftm);
            $nd .= $x[0];
            $nd = strtoupper( $nd);

            return $nd;
        } else
            return $x;
    } elseif ( strlen( $x) == 0)
        return $y;
    else
        return $x;
} 

if ( $exportSubmit) {
    // connect to the database
    $db = new FamilyDatabase( ); 
    // add the GEDCOM header...
    $todayDate = date( "j M Y");
    $data = "0 HEAD" . CRLF . "1 SOUR TUFaT Family Tree" . CRLF . "2 VERS " . VERSION . CRLF;
    $data .= "1 DATE $todayDate" . CRLF . "1 CHAR IBMPC" . CRLF . "1 GEDC" . CRLF . "2 VERS 5.5" . CRLF; 
    // add submitter information
    $data .= "0 @S1@ SUBM" . CRLF;

    $data .= ( $name) ? "1 NAME " . $name . CRLF : '';
    $data .= ( $addr_1) ? "1 ADDR " . $addr_1 . CRLF : '';
    $data .= ( $addr_2) ? "2 CONT " . $addr_2 . CRLF : '';
    $data .= ( $addr_3) ? "2 CONT " . $addr_3 . CRLF : '';
    $data .= ( $addr_4) ? "2 CONT " . $addr_4 . CRLF : '';
    $data .= ( $phone) ? "1 PHON " . $phone . CRLF : '';

    $sql = "SELECT * FROM ! WHERE tree=? AND type=? AND level = 0 ORDER BY id, inum"; 
    // individuals, families, sources, events and notes
    $types = array( 'I', 'F', 'S');
	
    foreach ( $types as $k => $typ) 
	{
        $r = $db->query( $sql, array( $db->gedcomTable, $user, $typ));
		
        while ( $a = $db->mfa( $r)) 
		{
            // we want to ignore these tags for PAF compat.
            if ( $PAF) 
			{
                if ( in_array( $a['tag'], $PAF_ignore))
                    continue;
            } 

            switch ( $a['tag']) 
			{
                // notes
                case "NREF":
                    $data .= "0 @N" . $a['hid'] . '@ NOTE' . CRLF;
                    $isql = "SELECT * FROM ! WHERE tree=? AND id=? AND type=? AND hid=? ORDER BY level, inum";
                    $ir = $db->query( $isql, array( $db->gedcomTable, $user, $a['id'], 'N', $a['hid']));

                    while ( $ira = $db->mfa( $ir)) 
					{
                        if ( $ira['tag'] == 'NOTE')
                            $data .= "1 TYPE " . $ira['data'] . CRLF;
                        else
                            $data .= "2 " . $ira['tag'] . " " . stripslashes( $ira['data']) . CRLF;
                    } 
                    $data .= "2 QUAY 2" . CRLF;

                    break;

                case "INDI":
                    $data .= "0 @I" . $a['id'] . '@ INDI' . CRLF;

                    $isql = "SELECT * FROM ! WHERE tree=? AND id=? AND type=? AND level > 0 ORDER BY inum";
                    $ir = $db->query( $isql, array( $db->gedcomTable, $user, $a['id'], 'I'));

                    while ( $ira = $db->mfa( $ir))
                        $data .= $ira['level'] . " " . $ira['tag'] . " " . $ira['data'] . CRLF;

                    break;

                case "SOUR":
                    $data .= "0 @S" . $a['hid'] . "@ SOUR" . CRLF;

                    $isql = "SELECT * FROM ! WHERE tree=? AND id=? AND type=? AND hid=? ORDER BY inum";
                    $ir = $db->query( $isql, array( $db->gedcomTable, $user, $a['id'], 'S', $a['hid']));
					
                    while ( $ira = $db->mfa( $ir))
                        $data .= $ira['level'] . " " . $ira['tag'] . " " . $ira['data'] . CRLF;
						
                    break;

                case "SREF":
                    $data .= "1 SOUR @S" . $a['data'] . "@" . CRLF;

                    $isql = "SELECT * FROM ! WHERE tree=? AND id=? AND type=? AND hid=? ORDER BY inum";
                    $ir = $db->query( $isql, array( $db->gedcomTable, $user, $a['id'], 'C', $a['hid']));

                    while ( $ira = $db->mfa( $ir))
                        $data .= $ira['level'] . " " . $ira['tag'] . " " . $ira['data'] . CRLF;

                    $data .= "2 QUAY 2" . CRLF;
                    break;

                case "EVEN":
                    $data .= "0 @E" . $a['hid'] . "@ EVEN" . CRLF;
                    $isql = "SELECT * FROM ! WHERE tree=? AND id=? AND type=? AND hid=? ORDER BY inum";
                    $ir = $db->query( $isql, array( $db->gedcomTable, $user, $a['id'], 'E', $a['hid']));

                    while ( $ira = $db->mfa( $ir))
                        $data .= $ira['level'] . " " . $ira['tag'] . " " . $ira['data'] . CRLF;

                    $data .= "2 QUAY 2" . CRLF;
                    break;

                case "FAM":
                    $data .= "0 @F" . $a['id'] . "@ FAM" . CRLF;
                    $isql = "SELECT * FROM ! WHERE tree=? AND id=? AND type=? AND level > 0 ORDER BY inum";
                    $ir = $db->query( $isql, array( $db->gedcomTable, $user, $a['id'], 'F'));
					
                    while ( $ira = $db->mfa( $ir))
                        $data .= $ira['level'] . " " . $ira['tag'] . " " . $ira['data'] . CRLF;

                    break;

                default: 
                    // if it's an unrecognized tag, just pass it along
                    $data .= "0 " . $a['data'] . " " . $a['tag'] . CRLF;
                    break;
            } 
        } 
    } 
    // add the GEDCOM footer...
    $data .= "0 TRLR" . CRLF;

    $data = str_replace( CRLF . CRLF, CRLF, $data);
    $data = stripslashes( $data);

    $len = strlen( $data);

    if ( $arctype == "zip" or $arctype == "gz") 
	{
        require 'classes/archive.php';

        if ( $arctype == "zip") 
		{
            $arc = new zipfile( "./temp/", array( "overwrite" => 1, "level" => 9));
            $arc->addfile( $data, $user . ".ged", $flags);
            $arc->filedownload( $user . ".zip");
        } 
		else 
		{
            $arc = new gzfile( "./temp/", array( "overwrite" => 1));
            $arc->addfile( $data, $user . ".ged", $flags);
            $arc->filedownload( $user . ".tgz");
        } 
    } 
	else 
	{
        header( "Content-disposition: filename=" . $user . ".ged");
        header( "Content-type: application/octetstream");
        header( "Expires: 0");
        print $data;
    } 

    exit;
} 
$llg = 1;

$smarty->assign( "mydata", array( ID => $ID)); 
// # Get the page we want to display
$smarty->assign( 'rendered_page', $smarty->fetch( 'export.tpl')); 
// # Display the page.
$smarty->display( 'index.tpl');

?>