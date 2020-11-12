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

$flow = 1;
require 'config.php';

require 'langconfig.php';

set_time_limit( 60);

$x = require 'classes/archive.php';

if ( $x == true && $test != 1) {
    if ( $indi == 1 && $ID != '') {
        $sql = "select id,data,name,title,descr,type from ! where tree= ? and indi = '1' and sid= ?";
        $r = $db->query( $sql, array( $db->famgalTable, $user, $ID));

        $snm = 'IndividualGallery-' . $db->getGN( $db->getIndexItem( "Name", $ID));
    } else if ( $fid != '') {
        $sql = "select id,data,name,title,descr,type from ! where tree= ? and fid = ?";
        $r = $db->query( $sql, array( $db->famgalTable, $user, $fid));
        $snm = 'FamilyGallery';
    } else if ( $kd == 1) {
        $sql = "select id,data,name,title,descr,type from ! where tree= ? and kd='1'";
        $r = $db->query( $sql, array( $db->famgalTable, $user));

        $snm = 'TreeGallery';
    }

    $n = $db->rowsInResult( $r);

    srand( make_seed( ));
    $tmpid = rand( 1, 5500);
    $fn = array( );

    mkdir( "temp/" . $tmpid);
    if ( $zip == 1)
        $tar = new zipfile( "temp/$tmpid/");
    else
        $tar = new tarfile( "temp/$tmpid/");

    if ( $n > 0) {
        for ( $i = 0;$i < $n;$i++) {
            $a = $db->mfa( $r);
            $tar->addfile( $a["data"], $a["name"]);
        }
    }
    if ( $zip == 1)
        $tar->filewrite( "temp/" . $tmpid . "/down.zip");
    else
        $tar->filewrite( "temp/" . $tmpid . "/down.tar");

    if ( $zip != 1) {
        $t = fopen( "temp/$tmpid/down.tar", "rb");
        $dt = fread( $t, filesize( "temp/$tmpid/down.tar"));
        fclose( $dt);

        $g = gzopen( "temp/$tmpid/down.tar.gz", "wb4");
        gzwrite( $g, $dt);
        fclose( $g);

        $gz = fopen( "temp/$tmpid/down.tar.gz", "rb");
        $data = fread( $gz, filesize( "temp/$tmpid/down.tar.gz"));
        fclose( $gz);
    } else {
        $gz = fopen( "temp/$tmpid/down.zip", "rb");
        $data = fread( $gz, filesize( "temp/$tmpid/down.zip"));
        fclose( $gz);
    }

    foreach ( $fn as $k => $v) {
        unlink( $v);
    }

    if ( $zip == 1) {
        header( "Content-Disposition: inline; filename=" . $snm . ".zip");
        header( "Content-Title: " . $snm . ".zip");
        header( "Content-Type: application/special");
        echo $data;
    } else {
        header( "Content-Disposition: inline; filename=" . $snm . ".tar.gz");
        header( "Content-Title: " . $snm . ".tar.gz");
        header( "Content-Type: application/special");
        echo $data;
    }

    unlink( "temp/$tmpid/down.tar.gz");
    unlink( "temp/$tmpid/down.tar");
    rmdir( "temp/$tmpid");
}

?>