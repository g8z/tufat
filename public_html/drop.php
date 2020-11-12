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
 * This program is modified to incorporate Smarty Template
 * Vijay Nair                                    3/Apr/2004
 */
require 'config.php';
if (isset($_SESSION['read_only'])) {
  header("Location: load.php");
  exit;
}

// process tree deletion
if ($deleteTree && isset($_SESSION['admin'])) {
  $db->removeTree($user, true);
  // destroy the session, this logs out the user and removes any
  // cached tree information.
  session_unregister("user");
  unset($_SESSION['user']);
  $user = false;
  session_unregister("admin");
  unset($_SESSION['admin']);
  session_unregister("read_only");
  unset($_SESSION['read_only']);
  session_unregister("master");
  unset($_SESSION['master']);
  session_unregister("edit_only");
  unset($_SESSION['edit_only']);
  session_unregister("my_rec");
  unset($_SESSION['my_rec']);
  session_unregister("mylang");
  unset($_SESSION['mylang']);
  header("Location: index.php");
  exit;
}

$smarty->assign('rendered_page', $smarty->fetch('drop.tpl'));
$smarty->display('index.tpl');
?>