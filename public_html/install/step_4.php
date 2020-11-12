<?php

// step_4.php
// installation complete. make final preparations and launch application.

// clear smarty compiled templates and cache before
// launching application
$inst = 1;
require_once(PACKAGE_ROOT.PACKAGE_CONFIG_FILE);
$smarty->clear_all_cache();
@set_time_limit(300);
$dh = opendir(PACKAGE_ROOT.SMARTY_COMPILE_DIR);
while (($file = readdir($dh)) !== false) {
  $path = PACKAGE_ROOT.SMARTY_COMPILE_DIR.$file;
  if (is_file($path) && preg_match('/\.tpl\.php$/', $path)) {
    @unlink($path);
  }
}
closedir($dh);

// show final message
show_header();
?>
<h2 class="success">Installation Complete</h2>
<p><?php echo htmlentities(PACKAGE_NAME) ?> is now ready to be used.</p>
<?php
$del_string = '';
if (is_file(PACKAGE_ROOT.'install.php')) {
	$del_string = "You should now delete the <tt>install.php</tt>";
}
if (is_dir(INSTALL_DIR)) {
	if ($del_string == '') {
		$del_string .= "You should now delete";
	} else {
		$del_string .= " and ";
	}
	$del_string .= "the <tt>".htmlentities(INSTALL_DIR)."</tt> folder.";
}
if ($del_string != '') {
	echo $del_string;
	echo "<br /><br /><a href=\"{$_SERVER['PHP_SELF']}\">Re-check install files</a>";
} else {
	echo '<p class="success">Install files have been successfully removed!</p>';
}

?>

<p>You may also wish to adjust the permissions of the
<tt><?php echo htmlentities(SMARTY_TEMPLATE_DIR) ?></tt>,
<tt><?php echo htmlentities(SMARTY_COMPILE_DIR) ?></tt>,
<tt><?php echo htmlentities(TEMP_DIR) ?></tt>, and
<tt><?php echo htmlentities(SMARTY_COMPILE_DIR) ?></tt> folders to the
minimum required file-writing permissions for your server, and adjust the
permissions of the <tt>config.php</tt> file to the minimum required
file-reading permissions for your server. If you do not know how to do that,
or if you do not know what the minimum required permissions are, please
contact your website host.</p>

<p><a href="index.php">Go to <?php echo htmlspecialchars(PACKAGE_NAME) ?> Now</a></p>
<?php
show_footer()
?>