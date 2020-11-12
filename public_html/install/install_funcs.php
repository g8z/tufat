<?php

// strip slashes recursively through array
function clean_array(&$arr) {
	foreach ($arr as $k => $v) {
		if (is_array($v))
			clean_array($arr[$k]);
		else
			$arr[$k] = stripslashes($v);
	}
}

// trim whitespace recursively through array
function trim_array(&$arr) {
	foreach ($arr as $k => $v) {
		if (is_array($v))
			trim_array($arr[$k]);
		else
			$arr[$k] = trim($v);
	}
}

// display an unexpected error message
function show_error($msg) {
  show_header();
  echo '<h2 class="error">Internal Error</h2>';
  echo '<p>The following unexpected error occurred:</p>';
  echo '<div class="box"><p class="error">', htmlspecialchars($msg), '</p></div>';
  show_footer();
  exit();
}

// show standard page header
function show_header() {
  require_once(INSTALL_DIR . 'install_header.php');
}

// show standard page footer
function show_footer() {
  require_once(INSTALL_DIR . 'install_footer.php');
}

// update the application config file by replacing define() statements
// with the corresponding statements from $replace.
function update_config_file($replace) {
  $path = PACKAGE_ROOT . PACKAGE_CONFIG_FILE;

  // read the file
  $lines = file($path);
  if ($lines === FALSE) {
    show_error("Unable to read application configuration file {$path}");
  }

  // change lines as appropriate
  $result = '';
  foreach ($lines as $k => $line) {
    preg_replace('/\r*$/', "\r", $line);
    if (preg_match('/^\s*define\s*\(\s*\'(\w+)\'/', $line, $matches)) {
      $name = $matches[1];
      if (isset($replace[$name])) {
        $val = $replace[$name];
        if ($val === true)
          $val = 'true';
        elseif ($val === false)
          $val = 'false';
        else
          $val ="'$val'";
        $line = "define('{$name}', {$val});\r\n";
      }
    }
    $result .= $line;
  }

  // write the file contents back out
  $fp = @fopen($path, 'w');
  if ($fp === FALSE)
    show_error("Unable to update application config file $path");
  fwrite($fp, $result);
  fclose($fp);
}

function have_required_php_version() {
  if (function_exists('version_compare')) {
    $ok = version_compare(phpversion(), PACKAGE_MIN_PHP_VERSION, '>=');
  } else {
    $v1 = explode('.', phpversion());
    $v2 = explode('.', PACKAGE_MIN_PHP_VERSION);
    $ok = true;
    for ($i = 0; $i < count($v1); $i++) {
      if ($v1[$i] < $v2[$i])
        $ok = false;
      if ($v1[$i] > $v2[$i])
        break;
    }
  }
  
  return $ok;
}

// show input form
function show_form($errmsg = '') {
	//get options for the template dropdown
	$template_options = array();
	if (is_dir(SMARTY_TEMPLATE_DIR)) {
		if ($dh = opendir(SMARTY_TEMPLATE_DIR)) {
			while (($file = readdir($dh)) !== false) {
				if (is_dir(SMARTY_TEMPLATE_DIR."/{$file}") && $file != '.' && $file != '..' && strpos($file, '.') !== 0) {
					$template_options[$file] = ucwords($file);
				}
			}
		}
	}
	//sort the template options alphabetically
	asort($template_options);

  show_header();
?>
<script type="text/javascript">
function fieldsAreValid() {
	return true;
}
</script>
<h2>Step 3: Site Configuration</h2>
<form method="post" action="install.php">

<p>Please specify some basic configuration parameters for how TUFaT should
operate. These settings can be adjusted in the config.php file after
installation, if necessary. The master password will provide access to all
family trees, allow you to bypass any lock, and provide access to backup tools.</p>

<div class="box">
<?php if (@!empty($errmsg)) echo '<div class="error"><p>', $errmsg, '</p></div>'; ?>
<table>
	<tr>
		<td class="label" style="width:170px;">Master Password</td>
		<td><input size="20" name="smpass" value="<?php echo htmlspecialchars(@$_POST['smpass']) ?>"/></td>
	</tr>
	<tr>
		<td class="label">Supervisor Name</td>
		<td><input size="20" name="suname" value="<?php echo htmlspecialchars(@$_POST['suname']) ?>"/></td>
	</tr>
	<tr>
		<td class="label">Supervisor E-Mail</td>
		<td><input size="20" name="suemail" value="<?php echo htmlspecialchars(@$_POST['suemail']) ?>"/></td>
	</tr>
	<tr>
		<td class="label">Tree Name</td>
		<td><input size="20" name="stn" value="<?php echo htmlspecialchars(@$_POST['stn']) ?>"/></td>
	</tr>
	<tr>
		<td class="label">Allow Cross Tree Searches</td>
		<td><input type="checkbox" name="sacts"<?php if (@$_POST['sacts']) {echo ' checked="checked"';} ?> /></td>
	</tr>
	<tr>
		<td class="label">Allow Multiple Tree Creation</td>
		<td><input type="checkbox" name="sacr"<?php if (@$_POST['sacr']) {echo ' checked="checked"';} ?> /></td>
	</tr>
	<tr>
		<td class="label">Show All Trees at Login</td>
		<td><input type="checkbox" name="salltree"<?php if (@$_POST['salltree']) {echo ' checked="checked"';} ?> /></td>
	</tr>
	<tr>
		<td class="label">Use TUFaT to track</td>
		<td>
			<input type="radio"<?php if (!isset($_POST['shgen']) || $_POST['shgen'] == 'false' || $_POST['shgen'] == false) {echo ' checked="checked"';} ?> name="shgen" value='false' />&nbsp;Human Genealogy<br />
			<input type="radio"<?php if (isset($_POST['shgen']) && ($_POST['shgen'] == 'true' || $_POST['shgen'] == true)) {echo ' checked="checked"';} ?> name="shgen" value='true' />&nbsp;Animal Genealogy
		</td>
	</tr>
	<tr>
		<td class="label">Choose Template</td>
		<td>
			<select name="stplid">
<?php
	foreach ($template_options as $value=>$option) {
	 	echo "			<option value=\"{$value}\">{$option}</option>";
	}
?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="label" style="width:170px;">E-Mail Method<br /><small style="white-space:normal;">If SMTP class is used, SMTP variables must be manually set in config.php after installation.</small></td>
		<td>
			<input type="radio"<?php if (!isset($_POST['stmail']) || $_POST['stmail'] == 0) {echo ' checked="checked"';} ?> name="stmail" value='0' />&nbsp;PHP Mail<br />
			<input type="radio"<?php if (isset($_POST['stmail']) && $_POST['stmail'] == 1) {echo ' checked="checked"';} ?> name="stmail" value='1' />&nbsp;SMTP Mail
		</td>
	</tr>
</table>
</div>
<p style="text-align: right">
  <input type="hidden" name="step" value="3" />
  <input type="submit" value="Continue &gt;&gt;" onclick="javascript:return fieldsAreValid();" />
</p>
</form>
<?php
  show_footer();
  exit;
}
