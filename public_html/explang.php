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
// /  This file is obsolete, included here for historic purposes
require_once 'config.php';
$flow = 1;
if ( $exporting == 1 && $sla != '') {
    $x = split( "####", str_replace( "_111_", "####", $sla));
    $sl = $x[0];
    $senc = $x[1];

    $sql = "select l,w,m,enc from ! where lower(l) = lower(?) and lower(enc)= lower(?)";
    $r = $db->query( $sql, array( $db->langTable, $sl, $senc));

    if ( $r != false && ( $n = $db->rowsInResult( $r)) > 0) {
        $data = "Language:" . $sla . "\n";
        $data .= "Encoding:" . $senc . "\n";
        for ( $i = 0; $i < $n; $i++) {
            $a = $db->mfa( $r);

            $data .= base64_encode( $a["w"]) . "---" . base64_encode( $a["m"]) . "\n";
        } 

        header( "Content-Disposition: attachment; filename=$sla $senc.txt");
        header( "Content-type: application/octetstream");
        header( "Pragma: no-cache");
        header( "Expires: 0");

        print $data;
    } 
} 

?>