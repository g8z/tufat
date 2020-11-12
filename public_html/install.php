<?php
// install.php
// package installer bootstrap

// set package root directory as needed by installer
define('PACKAGE_ROOT', dirname(__FILE__) . '/');

// set install files directory
define('INSTALL_DIR', PACKAGE_ROOT . 'install/');

// check for presence of install files directory, where all the
// installation logic is found.
if (!is_dir(INSTALL_DIR)) {
  echo 'The installation files have been removed from this package.';
  die();
}

// start the installer
require(INSTALL_DIR . 'install_main.php');

