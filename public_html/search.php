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
 * Modified to incorporate SMarty Template Processor
 * Vijay Nair                             10/Apr/2004
 */
$_REQUEST['mitem'] = 'search';

require_once 'config.php';
$llg = 1;

$mydata['animalPedigree'] = ANIMALPEDIGREE;
$mydata['allowCrossTreeSearch'] = ALLOWCROSSTREESEARCH;
$mydata['birt_date_start'] = $db->dateField( "birt_date_start", "", 1);
$mydata['birt_date_end'] = $db->dateField( "birt_date_end", "", 1);
$mydata['deat_date_start'] = $db->dateField( "deat_date_start", "", 1);
$mydata['deat_date_end'] = $db->dateField( "deat_date_end", "", 1);
$mydata['born_on_day'] = $db->dateField( "born_on_day");
$mydata['born_during_month'] = $db->dateField( "born_during_month");
$mydata['ID'] = $ID;

$smarty->assign( "mydata", $mydata);

if ( $rmsearch) {
    $sql = "DELETE FROM ! WHERE ID = ? AND searchuser = ? AND searchid = ? ";
    $result = &$db->query($sql, array( $db->searchesTable, $ID, $_SESSION['user'], $rmsearch));
}

$sql = "SELECT searchname, searchid,ID  FROM ! WHERE ID=? AND searchuser = ? ORDER BY searchname ASC";
$result = &$db->query($sql, array( $db->searchesTable, $ID, $_SESSION['user']));

while ($a = $db->mfa($result)) {
    $saved_searches[] = $a;
}

$smarty->assign("saved_searches", $saved_searches);
// # Get the page we want to display
$smarty->assign('rendered_page', $smarty->fetch( 'search.tpl'));
// # Display the page.
$smarty->display('index.tpl');

?>
