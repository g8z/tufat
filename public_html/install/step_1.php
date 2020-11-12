<?php
//If stest is not in $_REQUEST, then we haven't yet set the session testing info
//So we'll set it and reload the page
if (!isset($_REQUEST['stest'])) {
	session_start();
	$_SESSION['stest'] = true;

	if(!isset($_SERVER['HTTPS']) || strtolower($_SERVER['HTTPS'])!='on'){
		$http = 'http';
	} else {
		$http = 'https';
	}
	//If SCRIPT_NAME causes problems with some installs, try this:
	//substr($_SERVER['PHP_SELF'], 0, (strlen($_SERVER['PHP_SELF']) - @strlen($_SERVER['PATH_INFO'])));
	header("Location: {$http}://{$_SERVER['HTTP_HOST']}{$_SERVER['SCRIPT_NAME']}?step={$step}&stest=true");
}

$dir_lists = array();
foreach ($DIRS_TO_CHECK as $d) {
	$dir_lists[basename(trim($d, '/'))] = filearray($d);
}

// step 1: check environment
$checks = array();
$allok = true;

$ok = check_writeable(PACKAGE_CONFIG_FILE);
$allok = $allok && $ok;
$checks[] = array('<tt>' . PACKAGE_CONFIG_FILE . '</tt> is writeable', $ok);

$ok = check_writeable(TEMP_DIR);
$allok = $allok && $ok;
$checks[] = array('<tt>' . TEMP_DIR . '</tt> folder is writeable', $ok);

$ok = check_writeable(SMARTY_COMPILE_DIR);
$allok = $allok && $ok;
$checks[] = array('<tt>' . SMARTY_COMPILE_DIR . '</tt> folder is writeable', $ok);

$ok = check_writeable(SMARTY_CACHE_DIR);
$allok = $allok && $ok;
$checks[] = array('<tt>' . SMARTY_CACHE_DIR . '</tt> folder is writeable', $ok);

// if any of the permission checks failed, we may be able to try FTP
$cantryftp = !$allok && function_exists('ftp_connect');

session_start(); // session check aTufa
$ok = ($_SESSION['stest'] === true);
$checks[] = array('PHP Sessions functioning', $ok);

$ok = function_exists('mysql_connect');
$allok = $allok && $ok;
$checks[] = array('MySQL support enabled', $ok);

$ok = function_exists('imagecreatetruecolor');
$allok = $allok && $ok;
$checks[] = array('imagecreatetruecolor function enabled ( requires GD 2.0.1 )', $ok);

// (we've already checked PHP version)
$checks[] = array('PHP version &gt;= ' . PACKAGE_MIN_PHP_VERSION, true);

if ($_SERVER['REQUEST_METHOD'] == 'POST')
  do_ftp();
if (@$_REQUEST['ftp'])
  show_ftp_form();
show_main_form();

// show main form for this step
function show_main_form()
{
  global $checks, $allok, $cantryftp, $dir_hash, $dir_lists;
  show_header();
?>
<p>This wizard will guide you through the setup process.</p>

<h2>Step 1: Checking your server environment</h2>
<p>In this step, I will determine if your system meets the requirements for the
server environment. To use <?php echo htmlspecialchars(PACKAGE_NAME) ?>, you
must have PHP with MySQL support, and write-permissions on certain files and
folders.  After the installation is completed, you may wish to adjust some
file permissions for security.</p>

<div class="box">
  <table width="100%">
    <?php foreach($checks as $k => $arr) { ?>
    <tr>
      <td><?php echo $arr[0] ?></td>
      <td class="center"><?php echo $arr[1] ? '<span class="success bold">Yes</span>' : (@$arr[2] ? '<span class="warn bold">No (optional)</span>' : '<span class="error bold">No</span>') ?></td>
    </tr>
    <?php } ?>
  </table>
</div>
<?php

/*
if ($dir_hash != serialize($dir_lists)) {
	echo "<p class=\"warn\">Warning: Detection of critical files has failed.  Please make sure all files are present before moving to the next step</p>";
}
*/

?>
<?php if ($allok) { ?>
  <div style="text-align: right">
  <p>Your server environment is correct. You may continue with the installation.</p>
  <form method="get" action="install.php">
    <input type="hidden" name="step" value="2" />
    <input type="submit" value="Continue &gt;&gt;" />
  </form>
  </div>
<?php } else { ?>
  <p>I have detected some problems with your server environment that
  will prevent <?php echo htmlspecialchars(PACKAGE_NAME) ?> from operating
  correctly.</p>
  <p>Please correct these problems and then re-run the installer or refresh
  this page to re-check your environment.</p>
  <?php if ($cantryftp) { ?>
    <p>I can attempt to correct file permission problems automatically via
    FTP. You will need FTP connection details to your server.
    <a href="install.php?step=1&amp;ftp=1">Fix Permissions via FTP</a></p>
  <?php } ?>
<?php }
  show_footer();
  exit;
}

// show form to get ftp connection info
function show_ftp_form($errmsg = null)
{
  if ($_SERVER['REQUEST_METHOD'] != 'POST')
    $_POST['ftpHost'] = $_SERVER['SERVER_NAME'];
  show_header();
?>
<script type="text/javascript">
function fieldsAreValid() {
	var f = document.forms[0];

	if (f.ftpHost.value == '') {
		alert('Please specify the FTP host');
		f.ftpHost.focus();
		return false;
	}
	if (f.ftpPath.value == '') {
		alert('Please specify the path to application files');
		f.ftpPath.focus();
		return false;
	}

	if (f.ftpUser.value == '' || f.ftpPassword.value == '') {
		alert('Please specify the FTP username and password');
		f.ftpUser.focus();
		return false;
	}
	return true;
}
</script>

<h2>Step 1: FTP Settings</h2>

<p>I need some information about your FTP account in order to automatically change your file permissions.</p>

<p><strong>Please Note:</strong> FTP information is NOT Database information. FTP (File
Transfer Protocol) is used for uploading and downloading files to and from your
web server, whereas your database is used to store information raw data for
database-driven scripts such as <?php echo htmlspecialchars(PACKAGE_NAME) ?>.</p>

<?php if (!empty($errmsg)) { ?>
<p class="error"><?php echo $errmsg; ?></p>
<?php } ?>

<form action="install.php" method="post">
  <input type="hidden" name="step" value="1" />
  <input type="hidden" name="ftp" value="1" />

<div class="box">
<table>

<tr>
  <td class="label">FTP Host or IP Address:</td>
  <td><input size="25" name="ftpHost" value="<?php echo htmlspecialchars(@$_POST['ftpHost']) ?>" /></td>
</tr>

<tr>
  <td class="label">FTP User:</td>
  <td><input size="15" name="ftpUser" value="<?php echo htmlspecialchars(@$_POST['ftpUser']) ?>" /></td>
</tr>

<tr>
  <td class="label">FTP Password:</td>
  <td><input type="password" size="15" name="ftpPassword" value="<?php echo htmlspecialchars(@$_POST['ftpPassword']) ?>" /></td>
</tr>

<tr>
  <td class="label">Path to <?php echo htmlspecialchars(PACKAGE_NAME) ?> files:</td>
  <td><input size="50" name="ftpPath" value="<?php echo htmlspecialchars(@$_POST['ftpPath']) ?>" /></td>
</tr>

<tr>
<td colspan=2>
<p>Different hosts have different FTP file paths. Here are some examples of
possible paths (yours is likely to be different from any of these,
however):</p>
<p><tt>/home/your_username/htdocs/tufat/<br />
/home/httpd/virtualhosts/www.your_domain.com/htdocs/tufat/<br />
htdocs/tufat/<br />
tufat/</tt></p>
</td>
</tr>

</table>

</div>
<div style="text-align: right">
<p><input type=submit name=submit value="Continue >>" onclick="javascript:return fieldsAreValid();" /></p>
</div>
</form>
<?php
  show_footer();
  exit;
}

// attempt to set the file permissions using the ftp settings
function do_ftp()
{
  require_once(INSTALL_DIR . 'ftp.inc.php');
  @set_time_limit(120);
  $f = new FTP;

  if ($f->connect($_POST['ftpHost']) == false)
    show_ftp_form('Unable to connect to FTP host.<br />Please check your FTP login information and click continue.');

  if (!$f->authenticate($_POST['ftpUser'], $_POST['ftpPassword']))
    show_ftp_form('Invalid username or password.<br />Please check your FTP login information and click continue.');

  if (!$f->chdir($_POST['ftpPath']))
    show_ftp_form('Invalid FTP path.<br />Please check your FTP path and click continue.');

  if (
    !@$f->chmod(PACKAGE_CONFIG_FILE, '0666') ||
    !@$f->chmod(TEMP_DIR, '0777') ||
    !@$f->chmod(SMARTY_COMPILE_DIR, '0777') ||
    !@$f->chmod(SMARTY_CACHE_DIR, '0777')
  )
    show_ftp_form('The FTP login information that you provided does not allow sufficient access to change the necessary file permissions.<br />Please check the file ownership settings, change the permissions manually with an FTP client, or contact your website hosting support.');

  // we were successful, so redirect back to this step
  header("Location: {$http}://{$_SERVER['HTTP_HOST']}{$_SERVER['SCRIPT_NAME']}?step={$step}");
  exit;
}

// check whether a file or directory is writeable by the current
// process. attempt to make it writeable if possible.
function check_writeable($file_or_dir)
{
  $path = PACKAGE_ROOT . $file_or_dir;
  $mode = fileperms($path);
  if ($mode === false)
    show_error("Critical file or directory $path does not exist!");
  if (!is_writeable($path))
    @chmod($path, $mode | 0200);
  return is_writeable($path);
}

?>