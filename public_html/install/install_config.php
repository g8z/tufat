<?php

// set these as appropriate for the package being installed
define('PACKAGE_NAME', 'TUFaT');
define('PACKAGE_VERSION', '3.1.2');

// minimum PHP version required by the application
define('PACKAGE_MIN_PHP_VERSION', '4.3.0');

// application default table prefix
define('PACKAGE_DEFAULT_TABLE_PREFIX', 'tufat_');

// Used to request all tables from certain install functions
define('ALL_TABLES', true);

// application config file (relative to PACKAGE_ROOT)
define('PACKAGE_CONFIG_FILE', 'config.php');

// PEAR dirs (relative to PACKAGE_ROOT)
define('PEAR_DIR', 'libs/pear/');

// Smarty dirs (relative to PACKAGE_ROOT)
define('SMARTY_DIR', 'libs/smarty/');
define('SMARTY_TEMPLATE_DIR', 'templates/');
define('SMARTY_COMPILE_DIR', 'templates_c/');
define('SMARTY_CACHE_DIR', 'cache/');

// Other dirs (relative to PACKAGE_ROOT)
define('LIBS_DIR', 'libs/');
define('TEMP_DIR', 'temp/');


// required application directories (relative to PACKAGE_ROOT)
$PACKAGE_REQUIRED_DIRS = array(
	'install/',
  SMARTY_CACHE_DIR,
  LIBS_DIR,
  PEAR_DIR,
  SMARTY_DIR,
  TEMP_DIR,
  SMARTY_TEMPLATE_DIR,
  SMARTY_COMPILE_DIR
);

$DIRS_TO_CHECK = array(
	INSTALL_DIR,
  LIBS_DIR
);

$dir_hash = 'a:2:{s:7:"install";a:11:{s:10:"step_3.php";s:4:"FILE";s:18:"install_footer.php";s:4:"FILE";s:17:"install_funcs.php";s:4:"FILE";s:10:"step_2.php";s:4:"FILE";s:10:"step_4.php";s:4:"FILE";s:18:"install_header.php";s:4:"FILE";s:16:"install_main.php";s:4:"FILE";s:18:"install_config.php";s:4:"FILE";s:16:"initial_data.sql";s:4:"FILE";s:10:"step_1.php";s:4:"FILE";s:11:"ftp.inc.php";s:4:"FILE";}s:4:"libs";a:2:{s:4:"pear";a:30:{s:8:"Date.php";s:4:"FILE";s:15:"Application.php";s:4:"FILE";s:7:"CMD.php";s:4:"FILE";s:4:"Date";a:4:{s:8:"Span.php";s:4:"FILE";s:12:"TimeZone.php";s:4:"FILE";s:8:"Calc.php";s:4:"FILE";s:9:"Human.php";s:4:"FILE";}s:4:"Mail";a:5:{s:8:"null.php";s:4:"FILE";s:10:"RFC822.php";s:4:"FILE";s:12:"sendmail.php";s:4:"FILE";s:8:"smtp.php";s:4:"FILE";s:8:"mail.php";s:4:"FILE";}s:9:"Container";a:9:{s:7:"mdb.php";s:4:"FILE";s:20:"mdb_cache_schema.xml";s:4:"FILE";s:8:"file.php";s:4:"FILE";s:7:"shm.php";s:4:"FILE";s:7:"dbx.php";s:4:"FILE";s:12:"msession.php";s:4:"FILE";s:10:"phplib.php";s:4:"FILE";s:6:"db.php";s:4:"FILE";s:11:"trifile.php";s:4:"FILE";}s:10:"System.php";s:4:"FILE";s:8:"SMTP.php";s:4:"FILE";s:7:"Log.php";s:4:"FILE";s:8:"MDB2.php";s:4:"FILE";s:9:"Cache.php";s:4:"FILE";s:10:"Output.php";s:4:"FILE";s:12:"Function.php";s:4:"FILE";s:8:"PEAR.php";s:4:"FILE";s:10:"Socket.php";s:4:"FILE";s:2:"OS";a:1:{s:9:"Guess.php";s:4:"FILE";}s:4:"PEAR";a:31:{s:14:"ErrorStack.php";s:4:"FILE";s:15:"Dependency2.php";s:4:"FILE";s:12:"Registry.php";s:4:"FILE";s:8:"REST.php";s:4:"FILE";s:10:"Downloader";a:1:{s:11:"Package.php";s:4:"FILE";}s:12:"Frontend.php";s:4:"FILE";s:11:"Builder.php";s:4:"FILE";s:15:"PackageFile.php";s:4:"FILE";s:11:"Command.php";s:4:"FILE";s:12:"Packager.php";s:4:"FILE";s:4:"Task";a:9:{s:11:"Unixeol.php";s:4:"FILE";s:17:"Postinstallscript";a:1:{s:6:"rw.php";s:4:"FILE";}s:7:"Replace";a:1:{s:6:"rw.php";s:4:"FILE";}s:10:"Common.php";s:4:"FILE";s:21:"Postinstallscript.php";s:4:"FILE";s:7:"Unixeol";a:1:{s:6:"rw.php";s:4:"FILE";}s:10:"Windowseol";a:1:{s:6:"rw.php";s:4:"FILE";}s:11:"Replace.php";s:4:"FILE";s:14:"Windowseol.php";s:4:"FILE";}s:10:"Remote.php";s:4:"FILE";s:10:"Config.php";s:4:"FILE";s:11:"RunTest.php";s:4:"FILE";s:9:"Installer";a:2:{s:4:"Role";a:15:{s:8:"Data.php";s:4:"FILE";s:8:"Test.xml";s:4:"FILE";s:7:"Ext.xml";s:4:"FILE";s:8:"Test.php";s:4:"FILE";s:7:"Src.php";s:4:"FILE";s:7:"Php.php";s:4:"FILE";s:10:"Common.php";s:4:"FILE";s:7:"Ext.php";s:4:"FILE";s:7:"Php.xml";s:4:"FILE";s:7:"Doc.xml";s:4:"FILE";s:10:"Script.php";s:4:"FILE";s:10:"Script.xml";s:4:"FILE";s:8:"Data.xml";s:4:"FILE";s:7:"Src.xml";s:4:"FILE";s:7:"Doc.php";s:4:"FILE";}s:8:"Role.php";s:4:"FILE";}s:13:"Installer.php";s:4:"FILE";s:10:"Common.php";s:4:"FILE";s:14:"Downloader.php";s:4:"FILE";s:4:"REST";a:2:{s:6:"10.php";s:4:"FILE";s:6:"11.php";s:4:"FILE";}s:16:"DependencyDB.php";s:4:"FILE";s:7:"Command";a:23:{s:12:"Channels.php";s:4:"FILE";s:12:"Registry.php";s:4:"FILE";s:9:"Build.php";s:4:"FILE";s:8:"Auth.php";s:4:"FILE";s:12:"Channels.xml";s:4:"FILE";s:8:"Test.xml";s:4:"FILE";s:11:"Install.xml";s:4:"FILE";s:11:"Package.php";s:4:"FILE";s:10:"Remote.xml";s:4:"FILE";s:8:"Test.php";s:4:"FILE";s:10:"Mirror.xml";s:4:"FILE";s:8:"Auth.xml";s:4:"FILE";s:10:"Remote.php";s:4:"FILE";s:10:"Config.php";s:4:"FILE";s:10:"Mirror.php";s:4:"FILE";s:10:"Common.php";s:4:"FILE";s:11:"Install.php";s:4:"FILE";s:12:"Registry.xml";s:4:"FILE";s:10:"Config.xml";s:4:"FILE";s:9:"Build.xml";s:4:"FILE";s:10:"Pickle.php";s:4:"FILE";s:10:"Pickle.xml";s:4:"FILE";s:11:"Package.xml";s:4:"FILE";}s:15:"ChannelFile.php";s:4:"FILE";s:14:"Autoloader.php";s:4:"FILE";s:12:"Validate.php";s:4:"FILE";s:11:"ChannelFile";a:1:{s:10:"Parser.php";s:4:"FILE";}s:13:"Exception.php";s:4:"FILE";s:8:"Frontend";a:1:{s:7:"CLI.php";s:4:"FILE";}s:14:"Dependency.php";s:4:"FILE";s:11:"PackageFile";a:5:{s:2:"v2";a:2:{s:6:"rw.php";s:4:"FILE";s:13:"Validator.php";s:4:"FILE";}s:9:"Generator";a:2:{s:6:"v2.php";s:4:"FILE";s:6:"v1.php";s:4:"FILE";}s:6:"v2.php";s:4:"FILE";s:6:"v1.php";s:4:"FILE";s:6:"Parser";a:2:{s:6:"v2.php";s:4:"FILE";s:6:"v1.php";s:4:"FILE";}}s:9:"Validator";a:1:{s:8:"PECL.php";s:4:"FILE";}s:13:"XMLParser.php";s:4:"FILE";}s:16:"HTTP_Request.php";s:4:"FILE";s:9:"Error.php";s:4:"FILE";s:13:"Container.php";s:4:"FILE";s:9:"Cache.xml";s:4:"FILE";s:8:"HTTP.php";s:4:"FILE";s:7:"ITX.xml";s:4:"FILE";s:12:"Validate.php";s:4:"FILE";s:4:"MDB2";a:8:{s:8:"Date.php";s:4:"FILE";s:12:"Iterator.php";s:4:"FILE";s:4:"docs";a:7:{s:12:"CONTRIBUTORS";s:4:"FILE";s:8:"examples";a:3:{s:11:"example.php";s:4:"FILE";s:23:"metapear_test_db.schema";s:4:"FILE";s:16:"example_php5.php";s:4:"FILE";}s:4:"TODO";s:4:"FILE";s:6:"STATUS";s:4:"FILE";s:11:"MAINTAINERS";s:4:"FILE";s:14:"datatypes.html";s:4:"FILE";s:6:"README";s:4:"FILE";}s:7:"LICENSE";s:4:"FILE";s:7:"LOB.php";s:4:"FILE";s:6:"Driver";a:6:{s:7:"Reverse";a:2:{s:9:"mysql.php";s:4:"FILE";s:10:"Common.php";s:4:"FILE";}s:7:"Manager";a:2:{s:9:"mysql.php";s:4:"FILE";s:10:"Common.php";s:4:"FILE";}s:8:"Function";a:2:{s:9:"mysql.php";s:4:"FILE";s:10:"Common.php";s:4:"FILE";}s:9:"mysql.php";s:4:"FILE";s:8:"Datatype";a:2:{s:9:"mysql.php";s:4:"FILE";s:10:"Common.php";s:4:"FILE";}s:6:"Native";a:2:{s:9:"mysql.php";s:4:"FILE";s:10:"Common.php";s:4:"FILE";}}s:5:"tests";a:22:{s:9:"tests.css";s:4:"FILE";s:25:"MDB2_reverse_testcase.php";s:4:"FILE";s:24:"Console_TestListener.php";s:4:"FILE";s:14:"testchoose.php";s:4:"FILE";s:17:"MDB2_testcase.php";s:4:"FILE";s:21:"MDB2_api_testcase.php";s:4:"FILE";s:10:"config.php";s:4:"FILE";s:24:"MDB2_native_testcase.php";s:4:"FILE";s:26:"MDB2_datatype_testcase.php";s:4:"FILE";s:26:"MDB2_extended_testcase.php";s:4:"FILE";s:19:"test_setup.php.dist";s:4:"FILE";s:25:"MDB2_manager_testcase.php";s:4:"FILE";s:23:"MDB2_usage_testcase.php";s:4:"FILE";s:11:"clitest.php";s:4:"FILE";s:26:"MDB2_function_testcase.php";s:4:"FILE";s:21:"MDB2_Connect_Test.php";s:4:"FILE";s:13:"testUtils.php";s:4:"FILE";s:6:"README";s:4:"FILE";s:10:"basic.phpt";s:4:"FILE";s:22:"MDB2_bugs_testcase.php";s:4:"FILE";s:21:"HTML_TestListener.php";s:4:"FILE";s:8:"test.php";s:4:"FILE";}s:12:"Extended.php";s:4:"FILE";}s:8:"Validate";a:16:{s:6:"CH.php";s:4:"FILE";s:6:"UK.php";s:4:"FILE";s:4:"data";a:2:{s:16:"CH_postcodes.txt";s:4:"FILE";s:16:"AT_postcodes.txt";s:4:"FILE";}s:6:"FR.php";s:4:"FILE";s:11:"Finance.php";s:4:"FILE";s:6:"US.php";s:4:"FILE";s:6:"PL.php";s:4:"FILE";s:7:"Finance";a:1:{s:8:"IBAN.php";s:4:"FILE";}s:11:"package.xml";s:4:"FILE";s:8:"ptBR.php";s:4:"FILE";s:6:"AT.php";s:4:"FILE";s:4:"docs";a:1:{s:19:"sample_multiple.php";s:4:"FILE";}s:6:"DE.php";s:4:"FILE";s:6:"ES.php";s:4:"FILE";s:6:"NL.php";s:4:"FILE";s:5:"tests";a:9:{s:15:"credit_card.php";s:4:"FILE";s:20:"validate_Finance.php";s:4:"FILE";s:15:"validate_UK.php";s:4:"FILE";s:15:"validate_DE.php";s:4:"FILE";s:8:"date.php";s:4:"FILE";s:15:"validate_NL.php";s:4:"FILE";s:15:"validate_CH.php";s:4:"FILE";s:10:"number.php";s:4:"FILE";s:15:"validate_AT.php";s:4:"FILE";}}s:8:"Mail.php";s:4:"FILE";s:12:"Graphics.php";s:4:"FILE";s:21:"OutputCompression.php";s:4:"FILE";s:7:"scripts";a:8:{s:8:"pear.bat";s:4:"FILE";s:7:"pear.sh";s:4:"FILE";s:10:"peardev.sh";s:4:"FILE";s:8:"pecl.bat";s:4:"FILE";s:11:"peclcmd.php";s:4:"FILE";s:11:"peardev.bat";s:4:"FILE";s:7:"pecl.sh";s:4:"FILE";s:11:"pearcmd.php";s:4:"FILE";}}s:6:"smarty";a:7:{s:16:"Smarty.class.php";s:4:"FILE";s:9:"internals";a:22:{s:31:"core.process_cached_inserts.php";s:4:"FILE";s:31:"core.write_compiled_include.php";s:4:"FILE";s:29:"core.create_dir_structure.php";s:4:"FILE";s:18:"core.is_secure.php";s:4:"FILE";s:27:"core.run_insert_handler.php";s:4:"FILE";s:33:"core.process_compiled_include.php";s:4:"FILE";s:30:"core.display_debug_console.php";s:4:"FILE";s:32:"core.write_compiled_resource.php";s:4:"FILE";s:21:"core.load_plugins.php";s:4:"FILE";s:32:"core.assign_smarty_interface.php";s:4:"FILE";s:25:"core.write_cache_file.php";s:4:"FILE";s:19:"core.write_file.php";s:4:"FILE";s:22:"core.get_microtime.php";s:4:"FILE";s:19:"core.is_trusted.php";s:4:"FILE";s:16:"core.rm_auto.php";s:4:"FILE";s:25:"core.get_include_path.php";s:4:"FILE";s:33:"core.assemble_plugin_filepath.php";s:4:"FILE";s:27:"core.smarty_include_php.php";s:4:"FILE";s:24:"core.read_cache_file.php";s:4:"FILE";s:14:"core.rmdir.php";s:4:"FILE";s:25:"core.get_php_resource.php";s:4:"FILE";s:29:"core.load_resource_plugin.php";s:4:"FILE";}s:25:"Smarty_Compiler.class.php";s:4:"FILE";s:9:"debug.tpl";s:4:"FILE";s:7:"plugins";a:48:{s:26:"modifier.regex_replace.php";s:4:"FILE";s:16:"modifier.cat.php";s:4:"FILE";s:31:"outputfilter.trimwhitespace.php";s:4:"FILE";s:23:"modifier.capitalize.php";s:4:"FILE";s:20:"function.counter.php";s:4:"FILE";s:18:"modifier.lower.php";s:4:"FILE";s:19:"modifier.indent.php";s:4:"FILE";s:17:"function.math.php";s:4:"FILE";s:18:"function.popup.php";s:4:"FILE";s:23:"function.html_image.php";s:4:"FILE";s:20:"modifier.spacify.php";s:4:"FILE";s:24:"modifier.count_words.php";s:4:"FILE";s:19:"function.mailto.php";s:4:"FILE";s:31:"shared.escape_special_chars.php";s:4:"FILE";s:24:"modifier.date_format.php";s:4:"FILE";s:29:"modifier.count_paragraphs.php";s:4:"FILE";s:25:"shared.make_timestamp.php";s:4:"FILE";s:28:"function.html_checkboxes.php";s:4:"FILE";s:26:"modifier.string_format.php";s:4:"FILE";s:23:"modifier.strip_tags.php";s:4:"FILE";s:19:"compiler.assign.php";s:4:"FILE";s:24:"function.html_radios.php";s:4:"FILE";s:28:"modifier.debug_print_var.php";s:4:"FILE";s:24:"prefilter.langfilter.php";s:4:"FILE";s:23:"function.html_table.php";s:4:"FILE";s:25:"function.html_options.php";s:4:"FILE";s:18:"modifier.upper.php";s:4:"FILE";s:18:"function.fetch.php";s:4:"FILE";s:24:"function.config_load.php";s:4:"FILE";s:20:"modifier.replace.php";s:4:"FILE";s:29:"function.html_select_time.php";s:4:"FILE";s:29:"function.html_select_date.php";s:4:"FILE";s:27:"outputfilter.langfilter.php";s:4:"FILE";s:18:"modifier.nl2br.php";s:4:"FILE";s:17:"function.eval.php";s:4:"FILE";s:26:"function.tufat_mytrans.php";s:4:"FILE";s:18:"function.debug.php";s:4:"FILE";s:21:"modifier.truncate.php";s:4:"FILE";s:20:"block.textformat.php";s:4:"FILE";s:29:"modifier.count_characters.php";s:4:"FILE";s:18:"modifier.strip.php";s:4:"FILE";s:19:"modifier.escape.php";s:4:"FILE";s:30:"function.assign_debug_info.php";s:4:"FILE";s:23:"function.popup_init.php";s:4:"FILE";s:18:"function.cycle.php";s:4:"FILE";s:28:"modifier.count_sentences.php";s:4:"FILE";s:20:"modifier.default.php";s:4:"FILE";s:21:"modifier.wordwrap.php";s:4:"FILE";}s:10:"smarty.rar";s:4:"FILE";s:21:"Config_File.class.php";s:4:"FILE";}}}';

function filearray($start="/") {
	$output = array();
	$dir=opendir($start);
	while (false !== ($found=readdir($dir))) { $getit[]=$found; }
	foreach($getit as $num => $item) {
		if (is_dir($start.$item) && $item!="." && $item!="..") { $output[basename(trim($item, '/'))]=filearray($start.$item."/"); }
		if (is_file($start.$item)) { $output[basename(trim($item, '/'))]="FILE"; }
	}
	closedir($dir);
	return $output;
}

/*
//Uncomment this to generate a new $dir_hash
$dir_lists = array();
foreach ($DIRS_TO_CHECK as $d) {
	$dir_lists[basename(trim($d, '/'))] = filearray($d);
}
echo "<pre>".serialize($dir_lists)."</pre>";
exit;
/**/

// required application files (relative to PACKAGE_ROOT)
$PACKAGE_REQUIRED_FILES = array(
  PACKAGE_CONFIG_FILE,
  'index.php',
);

// set include path to application libs used by installer
ini_set('include_path', implode(PATH_SEPARATOR, array(
  '.',
  PACKAGE_ROOT . SMARTY_DIR,
  PACKAGE_ROOT . PEAR_DIR,
  'includes'
)));


//define tables that need to be set up
$tables['blobs']['id'] = array(
																'field'=>'id',
																'type'=>'int(11)',
																'null'=>'NO',
																'key'=>'MUL',
																'default'=>'0',
																'extra'=>'',
															);

$tables['blobs']['type'] = array(
																'field'=>'type',
																'type'=>'char(1)',
																'null'=>'NO',
																'key'=>'MUL',
																'default'=>'',
																'extra'=>'',
															);

$tables['blobs']['data'] = array(
																'field'=>'data',
																'type'=>'mediumblob',
																'null'=>'NO',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['blobs']['tree'] = array(
																'field'=>'tree',
																'type'=>'varchar(255)',
																'null'=>'NO',
																'key'=>'MUL',
																'default'=>'',
																'extra'=>'',
															);

$tables['blobs']['ta'] = array(
																'field'=>'ta',
																'type'=>'int(11)',
																'null'=>'YES',
																'key'=>'MUL',
																'default'=>'',
																'extra'=>'',
															);

$tables['events']['id'] = array(
																'field'=>'id',
																'type'=>'int(11)',
																'null'=>'NO',
																'key'=>'PRI',
																'default'=>'',
																'extra'=>'auto_increment',
															);

$tables['events']['user'] = array(
																'field'=>'user',
																'type'=>'varchar(20)',
																'null'=>'NO',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['events']['date'] = array(
																'field'=>'date',
																'type'=>'datetime',
																'null'=>'NO',
																'key'=>'',
																'default'=>'0000-00-00 00:00:00',
																'extra'=>'',
															);

$tables['events']['title'] = array(
																'field'=>'title',
																'type'=>'varchar(255)',
																'null'=>'NO',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['events']['event'] = array(
																'field'=>'event',
																'type'=>'text',
																'null'=>'NO',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['events']['location'] = array(
																'field'=>'location',
																'type'=>'varchar(255)',
																'null'=>'NO',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['events']['duration'] = array(
																'field'=>'duration',
																'type'=>'smallint(5)',
																'null'=>'NO',
																'key'=>'',
																'default'=>'0',
																'extra'=>'',
															);

$tables['events']['duration_unit'] = array(
																'field'=>'duration_unit',
																'type'=>'varchar(25)',
																'null'=>'NO',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['events']['repeat'] = array(
																'field'=>'repeat',
																'type'=>'tinyint(1)',
																'null'=>'NO',
																'key'=>'',
																'default'=>'0',
																'extra'=>'',
															);

$tables['events']['repeat_time'] = array(
																'field'=>'repeat_time',
																'type'=>'smallint(5)',
																'null'=>'NO',
																'key'=>'',
																'default'=>'0',
																'extra'=>'',
															);

$tables['events']['repeat_unit'] = array(
																'field'=>'repeat_unit',
																'type'=>'varchar(25)',
																'null'=>'NO',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['famgal']['id'] = array(
																'field'=>'id',
																'type'=>'int(11)',
																'null'=>'NO',
																'key'=>'PRI',
																'default'=>'',
																'extra'=>'auto_increment',
															);

$tables['famgal']['fid'] = array(
																'field'=>'fid',
																'type'=>'int(11)',
																'null'=>'YES',
																'key'=>'MUL',
																'default'=>'',
																'extra'=>'',
															);

$tables['famgal']['kd'] = array(
																'field'=>'kd',
																'type'=>'int(11)',
																'null'=>'YES',
																'key'=>'MUL',
																'default'=>'',
																'extra'=>'',
															);

$tables['famgal']['data'] = array(
																'field'=>'data',
																'type'=>'longblob',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['famgal']['type'] = array(
																'field'=>'type',
																'type'=>'int(11)',
																'null'=>'YES',
																'key'=>'MUL',
																'default'=>'',
																'extra'=>'',
															);

$tables['famgal']['name'] = array(
																'field'=>'name',
																'type'=>'text',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['famgal']['ta'] = array(
																'field'=>'ta',
																'type'=>'int(11)',
																'null'=>'YES',
																'key'=>'MUL',
																'default'=>'',
																'extra'=>'',
															);

$tables['famgal']['title'] = array(
																'field'=>'title',
																'type'=>'text',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['famgal']['tree'] = array(
																'field'=>'tree',
																'type'=>'text',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['famgal']['descr'] = array(
																'field'=>'descr',
																'type'=>'text',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['famgal']['indi'] = array(
																'field'=>'indi',
																'type'=>'int(11)',
																'null'=>'NO',
																'key'=>'MUL',
																'default'=>'0',
																'extra'=>'',
															);

$tables['famgal']['sid'] = array(
																'field'=>'sid',
																'type'=>'int(11)',
																'null'=>'YES',
																'key'=>'MUL',
																'default'=>'',
																'extra'=>'',
															);

$tables['gedcom']['id'] = array(
																'field'=>'id',
																'type'=>'int(11)',
																'null'=>'NO',
																'key'=>'MUL',
																'default'=>'0',
																'extra'=>'',
															);

$tables['gedcom']['tree'] = array(
																'field'=>'tree',
																'type'=>'varchar(255)',
																'null'=>'NO',
																'key'=>'MUL',
																'default'=>'',
																'extra'=>'',
															);

$tables['gedcom']['type'] = array(
																'field'=>'type',
																'type'=>'char(1)',
																'null'=>'NO',
																'key'=>'MUL',
																'default'=>'',
																'extra'=>'',
															);

$tables['gedcom']['tag'] = array(
																'field'=>'tag',
																'type'=>'varchar(4)',
																'null'=>'NO',
																'key'=>'MUL',
																'default'=>'',
																'extra'=>'',
															);

$tables['gedcom']['level'] = array(
																'field'=>'level',
																'type'=>'tinyint(1)',
																'null'=>'NO',
																'key'=>'MUL',
																'default'=>'0',
																'extra'=>'',
															);

$tables['gedcom']['data'] = array(
																'field'=>'data',
																'type'=>'varchar(255)',
																'null'=>'NO',
																'key'=>'MUL',
																'default'=>'',
																'extra'=>'',
															);

$tables['gedcom']['hid'] = array(
																'field'=>'hid',
																'type'=>'int(11)',
																'null'=>'NO',
																'key'=>'MUL',
																'default'=>'0',
																'extra'=>'',
															);

$tables['gedcom']['inum'] = array(
																'field'=>'inum',
																'type'=>'int(11)',
																'null'=>'NO',
																'key'=>'PRI',
																'default'=>'',
																'extra'=>'auto_increment',
															);

$tables['ilinks']['id'] = array(
																'field'=>'id',
																'type'=>'int(11)',
																'null'=>'NO',
																'key'=>'PRI',
																'default'=>'',
																'extra'=>'auto_increment',
															);

$tables['ilinks']['title'] = array(
																'field'=>'title',
																'type'=>'text',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['ilinks']['link'] = array(
																'field'=>'link',
																'type'=>'text',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['ilinks']['tree'] = array(
																'field'=>'tree',
																'type'=>'text',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['ilinks']['descr'] = array(
																'field'=>'descr',
																'type'=>'text',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['ilinks']['sid'] = array(
																'field'=>'sid',
																'type'=>'int(11)',
																'null'=>'YES',
																'key'=>'MUL',
																'default'=>'',
																'extra'=>'',
															);

$tables['ilogins']['user'] = array(
																'field'=>'user',
																'type'=>'varchar(60)',
																'null'=>'YES',
																'key'=>'MUL',
																'default'=>'',
																'extra'=>'',
															);

$tables['ilogins']['pass'] = array(
																'field'=>'pass',
																'type'=>'varchar(60)',
																'null'=>'YES',
																'key'=>'MUL',
																'default'=>'',
																'extra'=>'',
															);

$tables['ilogins']['tp'] = array(
																'field'=>'tp',
																'type'=>'int(11)',
																'null'=>'YES',
																'key'=>'MUL',
																'default'=>'',
																'extra'=>'',
															);

$tables['ilogins']['ID'] = array(
																'field'=>'ID',
																'type'=>'int(11)',
																'null'=>'YES',
																'key'=>'MUL',
																'default'=>'',
																'extra'=>'',
															);

$tables['index']['id'] = array(
																'field'=>'id',
																'type'=>'int(11)',
																'null'=>'NO',
																'key'=>'MUL',
																'default'=>'',
																'extra'=>'',
															);

$tables['index']['name'] = array(
																'field'=>'name',
																'type'=>'text',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['index']['surn'] = array(
																'field'=>'surn',
																'type'=>'text',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['index']['sex'] = array(
																'field'=>'sex',
																'type'=>'varchar(1)',
																'null'=>'YES',
																'key'=>'MUL',
																'default'=>'',
																'extra'=>'',
															);

$tables['index']['hide'] = array(
																'field'=>'hide',
																'type'=>'varchar(10)',
																'null'=>'YES',
																'key'=>'MUL',
																'default'=>'',
																'extra'=>'',
															);

$tables['index']['bdate'] = array(
																'field'=>'bdate',
																'type'=>'varchar(100)',
																'null'=>'YES',
																'key'=>'MUL',
																'default'=>'',
																'extra'=>'',
															);

$tables['index']['ddate'] = array(
																'field'=>'ddate',
																'type'=>'varchar(100)',
																'null'=>'YES',
																'key'=>'MUL',
																'default'=>'',
																'extra'=>'',
															);

$tables['index']['ta'] = array(
																'field'=>'ta',
																'type'=>'int(11)',
																'null'=>'YES',
																'key'=>'MUL',
																'default'=>'',
																'extra'=>'',
															);

$tables['index']['bplace'] = array(
																'field'=>'bplace',
																'type'=>'text',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['index']['tree'] = array(
																'field'=>'tree',
																'type'=>'varchar(250)',
																'null'=>'YES',
																'key'=>'MUL',
																'default'=>'',
																'extra'=>'',
															);

$tables['index']['occu'] = array(
																'field'=>'occu',
																'type'=>'text',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['index']['bred'] = array(
																'field'=>'bred',
																'type'=>'text',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['index']['cdea'] = array(
																'field'=>'cdea',
																'type'=>'text',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['index']['por'] = array(
																'field'=>'por',
																'type'=>'int(11)',
																'null'=>'YES',
																'key'=>'MUL',
																'default'=>'',
																'extra'=>'',
															);

$tables['index']['bio'] = array(
																'field'=>'bio',
																'type'=>'int(11)',
																'null'=>'YES',
																'key'=>'MUL',
																'default'=>'',
																'extra'=>'',
															);

$tables['index']['mother'] = array(
																'field'=>'mother',
																'type'=>'int(11)',
																'null'=>'YES',
																'key'=>'MUL',
																'default'=>'',
																'extra'=>'',
															);

$tables['index']['father'] = array(
																'field'=>'father',
																'type'=>'int(11)',
																'null'=>'YES',
																'key'=>'MUL',
																'default'=>'',
																'extra'=>'',
															);

$tables['index']['spouses'] = array(
																'field'=>'spouses',
																'type'=>'text',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['lang']['id'] = array(
																'field'=>'id',
																'type'=>'int(11)',
																'null'=>'NO',
																'key'=>'PRI',
																'default'=>'',
																'extra'=>'auto_increment',
															);

$tables['lang']['w'] = array(
																'field'=>'w',
																'type'=>'text',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['lang']['m'] = array(
																'field'=>'m',
																'type'=>'text',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['lang']['l'] = array(
																'field'=>'l',
																'type'=>'text',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['lang']['enc'] = array(
																'field'=>'enc',
																'type'=>'text',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['locks']['lock_password'] = array(
																'field'=>'lock_password',
																'type'=>'varchar(20)',
																'null'=>'NO',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['locks']['id'] = array(
																'field'=>'id',
																'type'=>'int(11)',
																'null'=>'NO',
																'key'=>'MUL',
																'default'=>'',
																'extra'=>'',
															);

$tables['locks']['tree'] = array(
																'field'=>'tree',
																'type'=>'varchar(255)',
																'null'=>'NO',
																'key'=>'MUL',
																'default'=>'',
																'extra'=>'',
															);

$tables['searches']['searchid'] = array(
																'field'=>'searchid',
																'type'=>'int(10)',
																'null'=>'NO',
																'key'=>'PRI',
																'default'=>'',
																'extra'=>'auto_increment',
															);

$tables['searches']['searchname'] = array(
																'field'=>'searchname',
																'type'=>'varchar(255)',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['searches']['searchuser'] = array(
																'field'=>'searchuser',
																'type'=>'varchar(255)',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['searches']['ID'] = array(
																'field'=>'ID',
																'type'=>'int(10)',
																'null'=>'YES',
																'key'=>'MUL',
																'default'=>'',
																'extra'=>'',
															);

$tables['searches']['alltrees'] = array(
																'field'=>'alltrees',
																'type'=>"enum('1','0')",
																'null'=>'YES',
																'key'=>'',
																'default'=>'0',
																'extra'=>'',
															);

$tables['searches']['f1bool'] = array(
																'field'=>'f1bool',
																'type'=>"enum('','AND','OR')",
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['searches']['name'] = array(
																'field'=>'name',
																'type'=>'varchar(255)',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['searches']['f2bool'] = array(
																'field'=>'f2bool',
																'type'=>"enum('','AND','OR')",
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['searches']['surn'] = array(
																'field'=>'surn',
																'type'=>'varchar(255)',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['searches']['f3bool'] = array(
																'field'=>'f3bool',
																'type'=>"enum('','AND','OR')",
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['searches']['birt_date_start_1'] = array(
																'field'=>'birt_date_start_1',
																'type'=>'tinyint(4)',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['searches']['birt_date_start_2'] = array(
																'field'=>'birt_date_start_2',
																'type'=>'tinyint(4)',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['searches']['birt_date_start_3'] = array(
																'field'=>'birt_date_start_3',
																'type'=>'smallint(6)',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['searches']['birt_date_end_1'] = array(
																'field'=>'birt_date_end_1',
																'type'=>'tinyint(4)',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['searches']['birt_date_end_2'] = array(
																'field'=>'birt_date_end_2',
																'type'=>'tinyint(4)',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['searches']['birt_date_end_3'] = array(
																'field'=>'birt_date_end_3',
																'type'=>'smallint(6)',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['searches']['f4bool'] = array(
																'field'=>'f4bool',
																'type'=>"enum('','AND','OR')",
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['searches']['deat_date_start_1'] = array(
																'field'=>'deat_date_start_1',
																'type'=>'tinyint(4)',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['searches']['deat_date_start_2'] = array(
																'field'=>'deat_date_start_2',
																'type'=>'tinyint(4)',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['searches']['deat_date_start_3'] = array(
																'field'=>'deat_date_start_3',
																'type'=>'smallint(6)',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['searches']['deat_date_end_1'] = array(
																'field'=>'deat_date_end_1',
																'type'=>'tinyint(4)',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['searches']['deat_date_end_2'] = array(
																'field'=>'deat_date_end_2',
																'type'=>'tinyint(4)',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['searches']['deat_date_end_3'] = array(
																'field'=>'deat_date_end_3',
																'type'=>'smallint(6)',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['searches']['f5bool'] = array(
																'field'=>'f5bool',
																'type'=>"enum('','AND','OR')",
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['searches']['born_on_day_1'] = array(
																'field'=>'born_on_day_1',
																'type'=>'tinyint(4)',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['searches']['born_on_day_2'] = array(
																'field'=>'born_on_day_2',
																'type'=>'tinyint(4)',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['searches']['f6bool'] = array(
																'field'=>'f6bool',
																'type'=>"enum('','AND','OR')",
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['searches']['born_during_month_1'] = array(
																'field'=>'born_during_month_1',
																'type'=>'tinyint(4)',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['searches']['f7bool'] = array(
																'field'=>'f7bool',
																'type'=>"enum('','AND','OR')",
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['searches']['occu'] = array(
																'field'=>'occu',
																'type'=>'varchar(255)',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['searches']['f8bool'] = array(
																'field'=>'f8bool',
																'type'=>"enum('','AND','OR')",
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['searches']['bred'] = array(
																'field'=>'bred',
																'type'=>'varchar(255)',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['searches']['f9bool'] = array(
																'field'=>'f9bool',
																'type'=>"enum('','AND','OR')",
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['searches']['sex'] = array(
																'field'=>'sex',
																'type'=>"enum('','M','F')",
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['searches']['f10bool'] = array(
																'field'=>'f10bool',
																'type'=>"enum('','AND','OR')",
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['searches']['buri_plac'] = array(
																'field'=>'buri_plac',
																'type'=>'varchar(255)',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['searches']['f11bool'] = array(
																'field'=>'f11bool',
																'type'=>"enum('','AND','OR')",
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['searches']['cdea'] = array(
																'field'=>'cdea',
																'type'=>'varchar(255)',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['searches']['f12bool'] = array(
																'field'=>'f12bool',
																'type'=>"enum('','AND','OR')",
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['searches']['portrait'] = array(
																'field'=>'portrait',
																'type'=>"enum('1','0')",
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['searches']['f13bool'] = array(
																'field'=>'f13bool',
																'type'=>"enum('','AND','OR')",
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['searches']['bio'] = array(
																'field'=>'bio',
																'type'=>"enum('1','0')",
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['searches']['limit'] = array(
																'field'=>'limit',
																'type'=>'int(10)',
																'null'=>'YES',
																'key'=>'',
																'default'=>'100',
																'extra'=>'',
															);

$tables['temp']['ID'] = array(
																'field'=>'ID',
																'type'=>'int(11)',
																'null'=>'NO',
																'key'=>'PRI',
																'default'=>'',
																'extra'=>'auto_increment',
															);

$tables['temp']['datetime'] = array(
																'field'=>'datetime',
																'type'=>'datetime',
																'null'=>'NO',
																'key'=>'',
																'default'=>'0000-00-00 00:00:00',
																'extra'=>'',
															);

$tables['users']['ID'] = array(
																'field'=>'ID',
																'type'=>'int(11)',
																'null'=>'NO',
																'key'=>'PRI',
																'default'=>'',
																'extra'=>'auto_increment',
															);

$tables['users']['username'] = array(
																'field'=>'username',
																'type'=>'varchar(20)',
																'null'=>'NO',
																'key'=>'MUL',
																'default'=>'',
																'extra'=>'',
															);

$tables['users']['password'] = array(
																'field'=>'password',
																'type'=>'varchar(20)',
																'null'=>'NO',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['users']['read_only_password'] = array(
																'field'=>'read_only_password',
																'type'=>'varchar(20)',
																'null'=>'NO',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['users']['admin_password'] = array(
																'field'=>'admin_password',
																'type'=>'varchar(20)',
																'null'=>'NO',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['users']['email'] = array(
																'field'=>'email',
																'type'=>'varchar(255)',
																'null'=>'NO',
																'key'=>'',
																'default'=>'0',
																'extra'=>'',
															);

$tables['users']['crosstree'] = array(
																'field'=>'crosstree',
																'type'=>'tinyint(1)',
																'null'=>'NO',
																'key'=>'',
																'default'=>'0',
																'extra'=>'',
															);

$tables['users']['created'] = array(
																'field'=>'created',
																'type'=>'date',
																'null'=>'NO',
																'key'=>'',
																'default'=>'0000-00-00',
																'extra'=>'',
															);

$tables['users']['lastlogin'] = array(
																'field'=>'lastlogin',
																'type'=>'date',
																'null'=>'NO',
																'key'=>'',
																'default'=>'0000-00-00',
																'extra'=>'',
															);

$tables['users']['aname'] = array(
																'field'=>'aname',
																'type'=>'text',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['users']['dname'] = array(
																'field'=>'dname',
																'type'=>'text',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);

$tables['users']['hide_type'] = array(
																'field'=>'hide_type',
																'type'=>'int(11)',
																'null'=>'YES',
																'key'=>'',
																'default'=>'',
																'extra'=>'',
															);
?>
