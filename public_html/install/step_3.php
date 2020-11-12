<?php

// step_3.php
// set document root

// when posted, update config file
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 	// set the application config file variables
 	$_POST['shgen'] = (strtolower($_POST['shgen']) == 'false')? false:true;
	update_config_file(array(
		'MASTERPASSWORD' => $_POST['smpass'],
		'SUPERVISNAME' => $_POST['suname'],
		'SUPERVISEMAIL' => $_POST['suemail'],
		'TREENAME' => $_POST['stn'],
		'ALLOWCROSSTREESEARCH' => $_POST['sacts'],
		'ALLOWCREATE' => $_POST['sacr'],
		'SHOWALLTREES' => (isset($_POST['salltree']) ? $_POST['salltree'] : 0),
		'ANIMALPEDIGREE' => $_POST['shgen'],
		'DEFAULTTEMPLATE' => $_POST['stplid'],
		'USE_SMTP_CLASS' => $_POST['stmail']
	));


  header("Location: install.php?step=4");
  exit;
} else {
	$inst = 1;
	require_once(PACKAGE_ROOT.PACKAGE_CONFIG_FILE);
	$_POST['smpass'] = MASTERPASSWORD;
	$_POST['suname'] = SUPERVISNAME;
	$_POST['suemail'] = SUPERVISEMAIL;
	$_POST['stn'] = TREENAME;
	$_POST['sacts'] = ALLOWCROSSTREESEARCH;
	$_POST['sacr'] = ALLOWCREATE;
	$_POST['salltree'] = SHOWALLTREES;
	$_POST['shgen'] = ANIMALPEDIGREE;
	$_POST['stplid'] = DEFAULTTEMPLATE;
	$_POST['stmail'] = USE_SMTP_CLASS;
}

// otherwise, show input form
show_form();
