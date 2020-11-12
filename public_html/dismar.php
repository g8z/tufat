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

require 'config.php';

if ( $s1 != '' && $s2 != '') {
    $flist1 = $db->lgetFamiliesP( $s1);
    $flist2 = $db->lgetFamiliesP( $s2);

    foreach ( $flist1 as $k1 => $f1) {
        foreach ( $flist2 as $k2 => $f2) {
            if ( $f1 == $f2) {
                $params = array( $db->gedcomTable, $user, $f1);
                $sql = "select id from ! where tree= ? and type='F' and tag='WIFE' and id = ?";
                $c1 = $db->query( $sql, $params);

                $sql = "select id from ! where tree= ? and type='F' and tag='HUSB' and id = ?";
                $c2 = $db->query( $sql, $params);

                $sql = "select id from ! where tree= ? and type='F' and tag='CHIL' and id = ?";
                $c3 = $db->query( $sql, $params); 
                // Changed by Swaroop
                // if ( $db->rowsInResult( $c1 ) == 1 && $db->rowsInResult( $c2 ) == 1 && $db->rowsInResult( $c3 ) == 0 )
                // {
                $sql = "delete from ! where tree= ? and type='F' and id = ?";
                $db->query( $sql, array( $db->gedcomTable, $user, $f1));

                $sql = "delete from ! where tree= ? and type='I' and tag = 'FAMS' and hid = ? and id = ?";
                $db->query( $sql, array( $db->gedcomTable, $user, $f1, $s1));

                $sql = "delete from ! where tree= ? and type='I' and tag = 'FAMS' and hid = ? and id = ?";
                $db->query( $sql, array( $db->gedcomTable, $user, $f1, $s2));

                $db->updateIndex( $s1);
                $db->updateIndex( $s2); 
                // }
            } 
        } 
    } 
} 

header( "Location: load.php?ID=$ID");

?>