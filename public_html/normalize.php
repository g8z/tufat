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
require_once ('./config.php');

if (isset($_SESSION['admin']) && !$_SESSION["admin"]) {
  header("Location: index.php");
}

if (isset($_REQUEST['run'])) {

  $sql = "SELECT t.*, MAX(t.inum) as max_inum, count(*) as count FROM {$db->gedcomTable} t WHERE tree= ".$db->dbh->quote($user).' GROUP BY id, tree, type, tag, hid HAVING count>1 ';
  $records = $db->query($sql);

  while ( $record = $db->mfa( $records)) {
    $where = array();
    $where[] = 'id='.$db->dbh->quote($record['id']); 
    $where[] = 'tree='.$db->dbh->quote($record['tree']); 
    $where[] = 'type='.$db->dbh->quote($record['type']); 
    $where[] = 'tag='.$db->dbh->quote($record['tag']); 
    $where[] = 'hid='.$db->dbh->quote($record['hid']); 
    $sql = 'DELETE FROM '.$db->gedcomTable.' WHERE '.implode(' AND ', $where).' AND inum <> '.$record['max_inum'];
    $db->dbh->exec($sql);
  }
} 

$sql = "SELECT {$db->gedcomTable}.*, count(*) as count FROM {$db->gedcomTable} WHERE tree= ".$db->dbh->quote($user).' GROUP BY id, tree, type, tag, hid HAVING count>1 LIMIT 0, 1';
$count = $db->getValue($sql);

$smarty->assign('normalization', $count);
$smarty->assign('rendered_page', $smarty->fetch('normalize.tpl'));
$smarty->display('index.tpl', 'normalize');
?>