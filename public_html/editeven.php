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
 * Modified to incorporate Smarty Template Processor
 * Vijay Nair                                          4/Apr/2004
 */ 
// import_request_variables( 'gp' );
function procEven( $xtag, $ct, $ID, $desc, $pc, $dt, $etyp = '', $mr = 0)
{
    global $db, $user;
    $mrtyp = ( $mr) ? "F" : "I"; 
    // Remove the current event if it exists
    $sql = "DELETE FROM ! WHERE tree=? AND id=? AND type=? AND tag='EVEN' AND hid=?";
    $db->query( $sql, array( $db->gedcomTable, $user, $ID, $mrtyp, $ct));

    $sql = "DELETE FROM ! WHERE tree=? AND id=? AND type='E' AND hid=?";
    $db->query( $sql, array( $db->gedcomTable, $user, $ID, $ct));

    if ( $ct && $desc) {
        // Now rebuild
        $sql = "INSERT INTO ! ( id, tree, type, tag, level, data, hid ) VALUES ( ?, ?, ?, ?, ?, ?, ?)"; 
        // event reference
        $db->query( $sql, array( $db->gedcomTable, $ID, $user, $mrtyp, 'EVEN', '0', $xtag, $ct)); 
        // event entry
        $db->query( $sql, array( $db->gedcomTable, $ID, $user, 'E', $xtag, '1', addslashes( $desc), $ct));

        if ( $xtag == "EVEN") {
            // Custom event type
            if ( $etyp) {
                $db->query( $sql, array( $db->gedcomTable, $ID, $user, 'E', 'TYPE', '1', $etyp, $ct));
            } 
        } 

        if ( $pc) {
            // Event place
            $db->query( $sql, array( $db->gedcomTable, $ID, $user, 'E', 'PLAC', '1', $pc, $ct));
        } 

        if ( $dt) {
            $db->query( $sql, array( $db->gedcomTable, $ID, $user, 'E', 'DATE', '1', $dt, $ct));
        } 
    } 
} 

function showEven( $xtag, $ct, $ID, $en = '', $mr = 0)
{
    global $tags, $db, $user, $smarty, $err;

    $mrtyp = ( $mr) ? "F" : "I";
    $sql = "SELECT data FROM ! WHERE tree=? AND id=? AND type=? AND hid=? AND tag='EVEN'";
    $r = $db->query( $sql, array( $db->gedcomTable, $user, $ID, $mrtyp, $ct));
    $ir = $db->mfa( $r);

    if ( $ir['data']) {
        $sql = "SELECT * FROM ! WHERE tree=? AND id=? AND hid=? AND type='E'";
        $r = $db->query( $sql, array( $db->gedcomTable, $user, $ID, $ct));

        $even['hid'] = $ct;
        while ( $ir = $db->mfa( $r)) {
            $even[$ir['tag']] = $ir['data'];
        } 
    } 

    $ftags = array_flip( $tags);
    $smarty->assign( "ftags", $ftags);
	
    $mydata = array( 
					tp => $even['TYPE'], 
					tphdr => $even['TYPE'], 
					xtag => $xtag, 
					xtagdisp => $ftags[$xtag], 
					ct => $even['hid'], 
					en => $en, 
					ds => htmlentities( stripslashes( $even[$xtag]), ENT_QUOTES), 
					pc => htmlentities( $even['PLAC'], ENT_QUOTES), 
					dt => $db->dateField( $xtag . "-" . $even['hid'] . "_dt", $even['DATE']), 
					ID => $ID, 
					mr => $mr, 
					err => $err,
					);
	
    $smarty->assign( "mydata", $mydata);
} 

?>