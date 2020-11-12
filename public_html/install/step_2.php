<?php

// step_2.php
// database connection, table creation/updating

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	do_post();
}

show_database_form();

// handle post of form. connect to the database and create or
// update the tables.
function do_post() 
{
	global $db, $utf8_ok;

	show_header();
	
	require_once(PEAR_DIR.'MDB2.php'); 
	
	// attempt to connect to the requested database
	$db = MDB2::connect(array(
		'username' => $_POST['user'],
		'password' => $_POST['password'],
		'hostspec' => $_POST['host'],
		'database' => $_POST['name'],
		'phptype' => 'mysql'
	));

	// handle connection errors
	if (MDB2::isError($db)) 
	{
		if ($db->getCode() == MDB2_ERROR_CONNECT_FAILED) {
			show_form('One of the following is invalid: Database Host, Database User,
			or Database Password. Please check these three values for accuracy and try
			again.<br /><br />Remember: the database username and password is usually
			NOT the same as your FTP username and password. For most servers, the host
			is <tt>localhost</tt>, but you may wish to check with your server
			administrator.');
		} elseif ($db->getCode() == MDB2_ERROR_NOSUCHDB) {
			$dbnames = array();

			// try to get a list of databases
			$db =& MDB2::Connect(array(
				'username' => $_POST['user'],
				'password' => $_POST['password'],
				'hostspec' => $_POST['host'],
				'database' => '',
				'phptype' => 'mysql'
			));
			if (!MDB2::isError($db)) {
				$result =& $db->query('CREATE DATABASE `'.$db->escape($_POST['name']).'`');
				if ($db->isError($result)) {
					$result =& $db->getAll('SHOW DATABASES');
					if (!$db->isError($result)) {
						foreach ($result as $k => $row) {
							$dbnames[] = "<li style=\"cursor:pointer;\" onclick=\"document['forms']['step2']['name'].value='{$row[0]}'\">{$row[0]}</li>";
						}
					}
					if (count($dbnames) > 0) {
						show_form('The specified Database Name does not exist, and could not
						be created. The following databases were found at this connection:
						<ul>' .	implode('', $dbnames) . '</ul>');
					} else {
						show_form('The specified Database Name does not exist and could not
						be created. You must create this database separately. Please check
						the name and try again.');
					}
				} else {
					$db->setDatabase($_POST['name']);
					if ($db->isError($result)) {
						show_form('The specified Database Name did not exist.  It was
						created, but cannot be connected to.  Please check the permissions
						on this database and try again.');
					}
				}
			}
		} else {
			show_form('The database connection could not be established: ' .
			$db->getMessage());
		}
	}

	
	// all communication with database will be in UTF-8
	// (no error checking; in case pre-4.1 MySQL)
	$res = $db->query("SET NAMES 'utf8'");
	$utf8_ok = !MDB2::isError($res);

	// disable strict SQL modes for MySQL 5.0.2+
	$db->query("SET sql_mode=''");

	// create or update the tables
	define('MYSQLPREFIX', $_POST['dbPrefix']);
	if ($_POST['oldDbPrefix'] == '') {
		define('OLDMYSQLPREFIX', $_POST['dbPrefix']);
	} else {
		define('OLDMYSQLPREFIX', $_POST['oldDbPrefix']);
	}


	// check to see if any tables with prefix already exist
	$mysqlprefix = str_replace('_', '\_', MYSQLPREFIX);
	$oldmysqlprefix = str_replace('_', '\_', OLDMYSQLPREFIX);
				
	//there is some bug with some version of mysql/php on $db->quote	
//	$res_new_prefix = $db->queryOne('SHOW TABLES LIKE '.$db->quote("{$mysqlprefix}%"));
//	$res_old_prefix = $db->queryOne('SHOW TABLES LIKE '.$db->quote("{$oldmysqlprefix}%"));
	$res_new_prefix = $db->queryOne('SHOW TABLES LIKE '."'{$mysqlprefix}%'");
	$res_old_prefix = $db->queryOne('SHOW TABLES LIKE '."'{$oldmysqlprefix}%'");
	
	if (MDB2::isError($res_new_prefix) || MDB2::isError($res_old_prefix)) 
	{
		show_form('SHOW TABLES command failed: '. $res_new_prefix->getUserInfo());
	}
	
	// make sure table existence corresponds with new/update mode
	if (isset($res_new_prefix) && $_POST['install_type'] == 'new') 
	{
		show_form('You requested a new installation, but one or more tables starting with the specified prefix already exist.');
	} 
	elseif (!isset($res_old_prefix) && $_POST['install_type'] == 'upgrade') 
	{
		show_form('You requested an upgrade installation, but no tables were found to upgrade.');
	} 
	elseif (isset($res_new_prefix) && $_POST['install_type'] == 'upgrade')
	{
		show_form('You requested an upgrade installation, but one or more tables starting with the specified prefix already exist.');
	}

	if ($_POST['install_type'] == 'upgrade') {
		// Handle database upgrade
		global $tables;
		if (OLDMYSQLPREFIX != MYSQLPREFIX) {
			$prefix = OLDMYSQLPREFIX;
			$r = $db->query("SHOW TABLES LIKE '{$prefix}%'");
			while ($info = $r->fetchRow()) {
				$old = array_pop($info);
				$new = MYSQLPREFIX.substr($old,strlen(OLDMYSQLPREFIX));
				//we will only worry about upgrading a table if we know about it.
				//We will still rename in case someone has extended TUFaT, and still
				//uses the default prefix
				if (array_search(substr($old,strlen(OLDMYSQLPREFIX)), array_keys($tables)) !== false) {
					$tables_to_upgrade[] = substr($old,strlen(OLDMYSQLPREFIX));
				}
				$q = "RENAME TABLE `{$old}`  TO `{$new}`";
				$result = $db->query($q);
				if ($db->isError($result)) {
					error_log("Error creating tables: " . $result->userinfo);
					show_form("An error occurred while renaming the database tables:<br/>{$result->message}<br/><pre>{$result->userinfo}</pre>");
				}
			}
		}

		//Now we add any tables that we need which did not already exist
		$tables_to_add = array_diff(array_keys($tables), $tables_to_upgrade);
		$added = add_table($tables_to_add, $db, $utf8_ok);
		if ($added !== true) {
			$error_message = '';
			foreach ($added as $error) {
				error_log("Error creating tables: {$error->userinfo}");
				$error_message .= "<br/>{$error->message}<br/><pre>{$error->userinfo}</pre>";
			}
			show_form("An error occurred while creating the database tables:{$error_message}");
		}

		foreach ($tables_to_upgrade as $tbl) {
			$prefix = MYSQLPREFIX;
			$table_info = $tables[$tbl];
			$columns_to_add = array_keys($table_info);
			$q = "DESCRIBE `{$prefix}{$tbl}`";
			$r = $db->query($q);
			while ($info = $r->fetchRow(MDB2_FETCHMODE_OBJECT)) {
				//If the column shouldn't be there, remove it.  If it should, remove it
				//from columns to add
				$col = array_search($info->field, $columns_to_add);
				if ($col === false) {
					$remove_col_q = "ALTER TABLE `{$prefix}{$tbl}` DROP `{$info->field}`";
					$result = $db->query($remove_col_q);
					if ($db->isError($result)) {
						error_log("Error removing auto increment: " . $result->userinfo);
						show_form("An error occurred while attemting to remove old columns from your tables:<br/>{$result->message}<br/><pre>{$result->userinfo}</pre>");
					}
				} else {
					unset($columns_to_add[$col]);
				}


				//remove the auto-inc (to add back later)
				if ($info->extra != '') {
					$remove_auto_inc_q = "ALTER TABLE `{$prefix}{$tbl}` CHANGE `{$info->field}` `{$info->field}` {$info->type}";
					$result = $db->query($remove_auto_inc_q);
					if ($db->isError($result)) {
						error_log("Error removing auto increment: " . $result->userinfo);
						show_form("An error occurred while attemting to remove an auto increment from your tables:<br/>{$result->message}<br/><pre>{$result->userinfo}</pre>");
					}
				}
				//remove all keys (we'll add these later)
				if (strtolower($info->key) == 'pri') {
					$remove_key_q = "ALTER TABLE `{$prefix}{$tbl}` DROP PRIMARY KEY";
					$result = $db->query($remove_key_q);
					if ($db->isError($result)) {
						error_log("Error removing key: " . $result->userinfo);
						show_form("An error occurred while attemting to remove a key from your table:<br/>{$result->message}<br/><pre>{$result->userinfo}</pre>");
					}
				} elseif ($info->key != '') {
					$remove_key_q = "ALTER TABLE `{$prefix}{$tbl}` DROP INDEX `{$info->field}`";
					$result = $db->query($remove_key_q);
					if ($db->isError($result)) {
						error_log("Error removing key: " . $result->userinfo);
						show_form("An error occurred while attemting to remove a key from your table:<br/>{$result->message}<br/><pre>{$result->userinfo}</pre>");
					}
				}
			}

			foreach (array_keys($table_info) as $cur_col) {
				$field_info = field_info($table_info[$cur_col]);
				if (in_array($cur_col, $columns_to_add)) {
					$q = "ALTER TABLE `{$prefix}{$tbl}` ADD {$field_info->field}";
				} else {
					$q = "ALTER TABLE `{$prefix}{$tbl}` CHANGE `{$cur_col}` {$field_info->field}";
				}
				if ($field_info->key !== false) {
					$q .= ", ADD {$field_info->key}";
				}
				$result = $db->query($q);
				if ($db->isError($result)) {
					error_log("Error adding or modifying column: " . $result->userinfo);
					show_form("An error occurred while attemting to add or modify a column from your table:<br/>{$result->message}<br/><pre>{$result->userinfo}</pre>");
				}
			}
		}
	} 
	else {
		// Handle new installation
		//$error = execute_sql(INSTALL_DIR . 'tables.sql', $db, $utf8_ok);
		$added = add_table(ALL_TABLES, $db, $utf8_ok);
		if ($added !== true) {
			$error_message = '';
			foreach ($added as $error) {
				error_log("Error creating tables: " . $error->userinfo);
				$error_message .= "<br/>{$error->message}<br/><pre>{$error->userinfo}</pre>";
			}
			show_form("An error occurred while creating the database tables:{$error_message}");
		}

		$error = execute_sql(INSTALL_DIR . 'initial_data.sql', $db, $utf8_ok);
		if ($error) {
			error_log("Error creating tables: {$error->userinfo}");
			show_form("An error occurred while creating the database tables:<br/>{$error->message}<br/><pre>{$error->userinfo}</pre>");
		}
	}

	// set the application config file variables
	update_config_file(array(
		'DBHOST' => $_POST['host'],
		'DBUSERNAME' => $_POST['user'],
		'DBPASSWORD' => $_POST['password'],
		'DBNAME' => $_POST['name'],
		'MYSQLPREFIX' => $_POST['dbPrefix']
	));

	// show confirmation
	show_confirmation();
}

// show database info form
function show_database_form($errmsg = '') {
	// set defaults if not a repost
	if ($_SERVER['REQUEST_METHOD'] != 'POST') 
	{
		$_POST['install_type'] = 'new';
		$_POST['dbPrefix'] = PACKAGE_DEFAULT_TABLE_PREFIX;
		$_POST['oldDbPrefix'] = PACKAGE_DEFAULT_TABLE_PREFIX;
		$_POST['host'] = 'localhost';
	}

	show_header();

?>
<script type="text/javascript">
<!--
function fieldsAreValid() {
	var f = document.forms['step2'];

	var formElements = f.elements;
	var numElements = f.elements.length;

	for ( var i = 0; i < numElements; i++ ) {
		var elemName = f.elements[i].name;
		var elemValue = f.elements[i].value;
		var elemType = f.elements[i].type;

		// all fields are required except password and dbPrefix
		if ( elemType == 'text' && elemName != 'password' && elemName != 'dbPrefix' && elemName != 'oldDbPrefix') {
			if ( elemValue == "" ) {
				alert( 'One or more required fields was left empty.' );
				f.elements[i].focus();
				return false;
			}
		}
	}

	// check if user checked 'backup' checkboxes
	if ( f['install_type'][1].checked && ( f['backup_web'].checked == false || f['backup_database'].checked == false ) ) {
		alert( 'Please confirm that your web files and database have been backed up.' );
		f['backup_web'].focus();
		return false;
	}
	return true;
}

function addEvent(el, ev, f) {
  if (el.addEventListener) {
    el.addEventListener(ev, f, false);
        } else if(el.attachEvent) {
    el.attachEvent("on" + ev, f);
        } else {
    el['on' + ev] = f;
        }
        return f;
};

function oldDbPrefixCheck() {
	var oldTblPrefix = document.getElementById('old_table_prefix');
	for (var i=0; i < document['forms']['step2']['install_type'].length; i++) {
		if (document['forms']['step2']['install_type'][i].checked == true) {
			var upgrade = (document['forms']['step2']['install_type'][i].value == 'upgrade')? true:false;
		}
	}
	oldTblPrefix.style.display = (upgrade)? '':'none';
//	alert(upgrade);
}

addEvent(window, 'load', oldDbPrefixCheck);
//-->
</script>
<h2>Step 2: Database Configuration</h2>

<form name="step2" method="post" action="install.php">

<p>Is this a new installation or upgrade from a previous version?</p>

<div class="box">
<table>
<tr>
	<td valign="top">
		<input type="radio" name="install_type" value="new" <?php if (@$_POST['install_type'] != 'upgrade') echo ' checked="checked"'; ?> onchange="oldDbPrefixCheck();" />
	</td>
	<td>This is a NEW installation of <?php echo htmlspecialchars(PACKAGE_NAME) ?></td>
</tr>
<tr>
	<td valign="top">
		<input type="radio" name="install_type" value="upgrade" <?php if (@$_POST['install_type'] == 'upgrade') echo ' checked="checked"'; ?> onchange="oldDbPrefixCheck();" />
	</td>
	<td>This is an upgrade for my current <?php echo htmlspecialchars(PACKAGE_NAME) ?> installation. The installer will add any fields missing from your current tables. Existing fields will NOT be removed. <strong>Be sure to specify the same
	table prefix below as in your original installation.</strong><br />
<input type="checkbox" name="backup_web" value="1" <?php if (@$_POST['backup_web']) echo ' checked="checked"'; ?> />	I have completely backed up my current <?php echo htmlspecialchars(PACKAGE_NAME) ?> web files.<br />
		<input type="checkbox" name="backup_database" value="1" <?php if (@$_POST['backup_database']) echo ' checked="checked"'; ?> />	I have completely backed up my database.<br />
	</td>
</tr>
</table>
<table>
<tr>
	<td>Table Prefix:&nbsp;<input size="20" name="dbPrefix" value="<?php echo htmlspecialchars(@$_POST['dbPrefix']); ?>" /></td>
</tr>
<tr id="old_table_prefix">
	<td>Old Table Prefix (if different from the new prefix):&nbsp;<input size="20" name="oldDbPrefix" value="<?php echo htmlspecialchars(@$_POST['oldDbPrefix']); ?>" /></td>
</tr>
</table>

<p>This prefix will be prepended to each table name created. You may leave this
blank if desired.</p>

</div>

<p>I need some information about how to connect to your database. If you do not
know this information, then please contact your website host or administrator.
Please note that this is probably NOT the same as your FTP login
information!</p>

<div class="box">
<?php if (@!empty($errmsg)) echo '<div class="error">', $errmsg, '</div>'; ?>
<table>

<tr>
	<td class="label">Database Host:</td>
	<td><input size="20" name="host" value="<?php echo htmlspecialchars(@$_POST['host']) ?>" /></td>
</tr>

<tr>
	<td class="label">Database User:</td>
	<td><input size="20" name="user" value="<?php echo htmlspecialchars(@$_POST['user']) ?>" /></td>
</tr>

<tr>
	<td class="label">Database Password:</td>
	<td><input type="password" size="20" name="password" value="<?php echo htmlspecialchars(@$_POST['password']) ?>" /></td>
</tr>

<tr>
	<td class="label">Database Name:</td>
	<td><input size="20" name="name" value="<?php echo htmlspecialchars(@$_POST['name']) ?>" /></td>
</tr>

</table>

</div>
<p style="text-align: right">
	<input type="hidden" name="step" value="2" />
	<input type="submit" value="Continue &gt;&gt;" onclick="javascript:return fieldsAreValid();" />
</p>
</form>
<?php
	show_footer();
	exit;
}

// show confirmation that table creation/update is complete
function show_confirmation() {
	show_header();
?>
<div class="box">
	<p class="success">The database connection was established successfully.</p>
	<p class="success">The database tables were created or updated successfully.</p>
	<p class="success">The application configuration file was updated successfully.</div>
<form method="get" action="install.php">
<p style="text-align: right">
	<input type="hidden" name="step" value="3" />
	<input type="submit" value="Continue &gt;&gt;" onclick="javascript:return fieldsAreValid();" />
</p>
</form>
<?php
	show_footer();
	exit;
}


function add_table ($table_names, $db, $utf8_ok) {
	global $tables;
	$return_array = array();
	$prefix = MYSQLPREFIX;
	foreach ($tables as $tbl_name=>$tbl_info) {
		if ($table_names === ALL_TABLES || array_search($tbl_name, $table_names) !== false) {
			$sql = "CREATE TABLE `{$prefix}{$tbl_name}` (";
			$fields = array();
			$keys = array();
			foreach ($tbl_info as $col=>$col_info) {
				$field_info = field_info($col_info);
				$fields[] = "\r\n	{$field_info->field}";
				if ($field_info->key !== false) {
					$keys[] = "\r\n	{$field_info->key}";
				}
/*
				$field = "\r\n	`{$col_info['field']}` {$col_info['type']}";
				$field .= (strtolower($col_info['null']) == 'no')? ' NOT NULL':'';
				$field .= ($col_info['default'] == '')? '':" DEFAULT '{$col_info['default']}'";
				$field .= ($col_info['extra'] == '')? '':" {$col_info['extra']}";
				$fields[] = $field;
				switch (strtolower($col_info['key'])) {
					case 'pri':
						$keys[] = "\r\n	PRIMARY KEY (`{$col_info['field']}`)";
						break;
					case 'uni':
						$keys[] = "\r\n	UNIQUE KEY `{$col_info['field']}` (`{$col_info['field']}`)";
						break;
					case 'mul':
						$keys[] = "\r\n	KEY `{$col_info['field']}` (`{$col_info['field']}`)";
						break;
				}
*/
			}
			$sql .= implode(',', array_merge($fields, $keys));
			$sql .= "\r\n)";
			// if database doesn't support utf8, remove the character set declaration
			if ($utf8_ok) {
				$sql .= ' DEFAULT CHARACTER SET utf8';
			}
			$result = $db->query($sql);
			if ($db->isError($result)) {
				$return_array[$tbl_name] = $result;
			}
		}
	}
	return (count($return_array) == 0)? true:$return_array;
}

function field_info($col_info) {
	$field = "`{$col_info['field']}` {$col_info['type']}";
	$field .= (strtolower($col_info['null']) == 'no')? ' NOT NULL':'';
	$field .= ($col_info['default'] == '')? '':" DEFAULT '{$col_info['default']}'";
	$field .= ($col_info['extra'] == '')? '':" {$col_info['extra']}";
	switch (strtolower($col_info['key'])) {
		case 'pri':
			$key = "PRIMARY KEY (`{$col_info['field']}`)";
			break;
		case 'uni':
			$key = "UNIQUE KEY `{$col_info['field']}` (`{$col_info['field']}`)";
			break;
		case 'mul':
			$key = "KEY `{$col_info['field']}` (`{$col_info['field']}`)";
			break;
		default:
			$key = false;
	}
	return (object) array('field'=>$field, 'key'=>$key);
}

// executes sql queries from a file
// on success, returns false. on error, returns an array containing error
// details.
function execute_sql($fileName, $db, $utf8_ok) {

	@set_time_limit(120);

	// read the data
	$fd = @fopen ($fileName, 'r');
	if ($fd === false) {
		show_error("Unable to open SQL file '$fileName'");
	}
	$data = fread($fd, filesize ($fileName));
	fclose($fd);

	// split into separate statements
	$queries = splitSql($data);

	// execute each statement
	foreach ($queries as $sql) {
		$sql = trim($sql);

		// skip comments
		if (empty($sql) || $sql[0] == '#' || substr($sql,0,2) == '--') {
			continue;
		}

		// set table prefix
		$sql = str_replace('[prefix]', MYSQLPREFIX, $sql);

		// if database doesn't support utf8, remove the character set declaration
		if (!$utf8_ok) {
			$sql = str_replace(' DEFAULT CHARACTER SET utf8', '', $sql);
		}

		// execute the statement and handle errors
		$result = $db->query($sql);
		if ($db->isError($result)) {
			if ($result->code == MDB2_ERROR_ALREADY_EXISTS) {
				continue;
			}
			return array('info' => $result->userinfo, 'message'=> $result->message, 'code'=> $result->code );
		}
	}
	return false;
}

// split sql script into array of separate statements
function splitSql($sql)
{
	// strip comments (lines beginning with # or --)
	$sql = preg_replace('/^#.*/m', '', $sql);
	$sql = preg_replace('/^--.*/m', '', $sql);

	// break into statements on blank lines
	$sql = preg_replace('/\r/', '', $sql);
	$queries = preg_split('/\n\n+/', $sql, -1, PREG_SPLIT_NO_EMPTY);
	trim_array($queries);

	return $queries;
}

?>
