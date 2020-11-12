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
 */
$flow = 1;
require_once 'config.php';

@ini_set( 'max_execution_time', '300');

$mydata['sfile'] = $sfile;
$mydata['ID'] = $ID;

if ( $sfile != '') {
    $fn = $sfile["tmp_name"];
    $x = require 'classes/archive.php';

    if ( $x == true) {
        srand( make_seed( ));
        $tmpid = rand( 1, 5500);
        /*                $dn = "temp/" . "e_" . $tmpid;
                mkdir($dn,0777);*/
        $dn = "temp";
        $sz = filesize( $_FILES['sfile']['tmp_name']) * 2;

        $gf = gzopen( $_FILES['sfile']['tmp_name'], "rb");
        $data = gzread( $gf, $sz);
        gzclose( $gf);

        $f = fopen( $dn . '/x.tar', "wb");

		fwrite( $f, $data);
        fclose( $f);
        if ( !( $ID > 0))
            $ID = 0;

        $tar = new tarfile( $f);
		$fl = $tar->extract( $data);

		$i = 0;
        foreach( $fl as $k => $v) {
            $fname = $v["filename"];
            $abspath = $dn . "/" . $fname;
            $fname = basename( $fname);
            $fdata = addslashes( $v["data"]);
            $filesList[$i]->fname = $fname;
            $t = time( );

            if ( $indi == 1 && $ID != '') {
                $sql = "insert into ! (ta, tree, sid, fid, type, kd, data, name, title,indi) values (?, ?, ?, '0', '0', '0', ?, ?, ?,'1')";
                $params = array ( $db->famgalTable, $t, $user, $ID, $fdata, $fname, $fname);
            } else if ( $fid != '') {
                $sql = "insert into ! (ta,tree, sid, fid, type, kd, data, name, title) values (?, ?, ?, ?, '0', '0', ?, ?, ?)";
                $params = array ( $db->famgalTable, $t, $user, $ID, $fid, $fdata, $fname, $fname);
            } else if ( $kd == 1) {
                $sql = "insert into ! (ta, tree, sid, fid, type, kd, data, name, title,indi) values ( ?, ?, ?, '0', '0', '1', ?, ?, ?,'2')";
                $params = array ( $db->famgalTable, $t, $user, $ID, $fdata, $fname, $fname);
            } 

            $r = $db->query( $sql, $params);

            if ( $r != false) {
                $filesList[$i]->vol = round( strlen( $fdata) / 1024 , 2) ;
            } else
                $filesList[$i]->failed = true;
            if ( file_exists( $abspath)) unlink( $abspath);
            $i++;
        } 

        unlink( $dn . "/x.tar"); 
        // rmdir( $dn );
    } 
    $smarty->assign( "mydata", $mydata);
    $smarty->assign( "filesList", $filesList);
} else {
    if ( $indi == 1)
        $g = 'Individual';
    else if ( $kd == 1)
        $g = 'Tree';
    else if ( $fid > 0)
        $g = 'Family';

    $mydata['kd'] = $kd;
    $mydata['indi'] = $indi;
    $mydata['fid'] = $fid;
    $mydata['ID'] = $ID;
    $smarty->assign( "mydata", $mydata);
} 
// # Get the page we want to display
$smarty->assign( 'rendered_page', $smarty->fetch( 'impgal.tpl')); 
// # Display the page.
$smarty->display( 'index.tpl');

?>
