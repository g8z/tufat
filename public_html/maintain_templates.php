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

if (count($_POST)){
  if (isset($_POST['cache'])) {
    $smarty->clear_all_cache();
    $smarty->assign('message', "##Cache successfully cleared.##");
    $smarty->assign('rendered_page', $smarty->fetch('message.tpl'));
  }
  
  if (isset($_POST['compiled_templates'])) {
    @set_time_limit(300);
    $dh = opendir(TEMPLATE_C_DIR);
    while (($file = readdir($dh)) !== false) {
      $path = TEMPLATE_C_DIR . $file;
      if (is_file($path) && preg_match('/\.tpl\.php$/', $path))
        @unlink($path);
    }
    closedir($dh);
    $smarty->assign('message', '##Compiled templates successfully cleared.##');
    $smarty->assign('rendered_page', $smarty->fetch('message.tpl'));
  }
} else {
  $smarty->assign('rendered_page', $smarty->fetch('maintain_templates.tpl'));
}

$smarty->display('index.tpl', 'maintain_templates');
