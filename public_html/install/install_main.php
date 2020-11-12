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
 * This program is modified to incorporate Smarty Template Processor
 * Vijay Nair                                     23/Apr/2004
 * Added Session check by aTufa
 */

// intialize environment
set_magic_quotes_runtime(0);

// package-specific installation settings
require_once('install_config.php');

// support functions
require_once(INSTALL_DIR . 'install_funcs.php');

// strip slashes
if (get_magic_quotes_gpc()) {
  clean_array($_GET);
  clean_array($_POST);
  clean_array($_REQUEST);
  clean_array($_SERVER);
}

// trim surrounding whitespace on all request parameters
trim_array($_GET);
trim_array($_POST);
trim_array($_REQUEST);

// check for required PHP version
if (!have_required_php_version()) {
  show_header();
  echo '<p class="subtitle">Incorrect PHP version!</p>';
  echo '<p>This application requires PHP version ', PACKAGE_MIN_PHP_VERSION,
    ' or higher. You have version ' . phpversion() . '</p>';
  echo '<p>The installation cannot proceed.</p>';
  show_footer();
  exit();
}

// check for critcial required directories and files
$missing = array();
foreach ($PACKAGE_REQUIRED_DIRS as $k => $dir) {
  if (!is_dir(PACKAGE_ROOT . $dir)) {
    $missing[] = "Directory ".PACKAGE_ROOT.$dir;
  }
}
foreach ($PACKAGE_REQUIRED_FILES as $k => $file) {
  if (!is_file(PACKAGE_ROOT . $file)) {
    $missing[] = "File $file";
  }
}
if (!empty($missing)) {
  show_header();
  echo '<p class="subtitle">One or more critical application files is missing!</p>';
  echo '<p>Your installation is incomplete or corrupted. The following required item(s) are missing:</p>';
  echo '<ul>';
  foreach ($missing as $k => $entry) {
    echo '<li>', htmlspecialchars($entry), '</li>';
  }
  echo '</ul>';
  echo '<p>Please correct these error(s) and restart the installation.</p>';
  show_footer();
  exit();
}
// set $step to the proper step number
$step = (isset($_REQUEST['step']))?(int)$_REQUEST['step']:1;

$fname = INSTALL_DIR . "step_{$step}.php";
if (!file_exists($fname)) {
  show_error('Invalid "step" ' . $step);
}

require($fname);