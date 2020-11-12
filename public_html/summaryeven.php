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

require 'taglist.php';
$ftags = array_flip( $tags);

$sql = "SELECT * FROM ! WHERE id=? AND tree=? AND type=? AND hid=? AND level > 0 ORDER BY level, inum";
$result = $db->query( $sql, array( $db->gedcomTable, $ID, $user, 'E', $hid));
while ( $a = $db->mfa( $result)) {
    if ( array_key_exists( $a['tag'], $ftags)) {
        $i++;
        if ( $i > 1 and $exists)
            break;

        $events[$i]['name'] = $ftags[$a['tag']];
        $events[$i]['desc'] = $a['data'];
        $events[$i]['inum'] = $a['inum'];
        $event = true;

        if ( $a['inum'] == $inum)
            $exists = true;
    } 
    if ( ( $a['tag'] == "TYPE" or $a['tag'] == "PLAC") and $event) {
        $events[$i][strtolower( $a['tag'])] = $a['data'];
    } elseif ( $a['tag'] == "DATE" and $event) {
        $date = explode( "-", $a['data']);
        $events[$i]['date']['year'] = $date[0];
        $events[$i]['date']['month'] = $date[1];
        $events[$i]['date']['day'] = $date[2];
    } 
} 
end( $events);
$selected_event = $events[key( $events)];
$smarty->assign( "event", $selected_event); 
// # Display the page.
$smarty->display( 'summaryeven.tpl');

?>