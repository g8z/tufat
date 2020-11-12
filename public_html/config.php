<?php
define ('VERSION', '3.1.2');
// Turn off PHP error reporting for neater page output, we will track errors ourselves

error_reporting(E_ALL ^ E_NOTICE);

/* This is the default language. Change this according to your requirement */
/* Ensure "_lll_" is there between Language and Encoding  as given below. */
define ('SLANG','english_lll_utf-8');

// DONOT change following lines - These are directory informations
// fully unqualified path to files

define ('FULL_PATH',dirname(__FILE__) . '/');

// http path to document root

define ( 'SMARTY_DIR', FULL_PATH . 'libs/smarty/' );
define ( 'TEMPLATE_DIR', FULL_PATH . 'templates/' );
define ( 'TEMPLATE_C_DIR', FULL_PATH . 'templates_c/' );
define ( 'CACHE_DIR', FULL_PATH . 'cache/' );
define ( 'TEMP_DIR', FULL_PATH . 'temp/' );
define ( 'ROOT_DIR', FULL_PATH );
define ( 'DOC_ROOT', '/' );
define ( 'PEAR_DIR', FULL_PATH . 'libs/pear/' );


// Database Information

define('DBHOST', '');
define('DBUSERNAME', '');
define('DBPASSWORD', '');
define('DBNAME', '');
define('DBTYPE','');


// will be used smtp class if 1 , or php mail () function if 0
define('USE_SMTP_CLASS', '0');

// set include path to application libs used by installer
ini_set('include_path', implode(PATH_SEPARATOR, array(
  '.',
  SMARTY_DIR,
  PEAR_DIR,
  'includes'
)));

$an_include_path_var = get_include_path();

// Open wanted class files
require_once SMARTY_DIR . 'Config_File.class.php';
require_once SMARTY_DIR . 'Smarty.class.php';
require_once SMARTY_DIR . 'tufat_Smarty.class.php';

// DO NOT change above lines

//Start a session if one does not yet exist.
if (!isset($_SESSION))
{
	session_start();
}

//If the existing session is not from this IP, generate a new one.  If no IP has
//been specified yet, assign it to this IP.
if (isset($_SESSION['REMOTE_ADDR']) && $_SESSION['REMOTE_ADDR'] != $_SERVER['REMOTE_ADDR'])
{
	session_regenerate_id();
	$_SESSION['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'];
}
elseif (!isset($_SESSION['REMOTE_ADDR']))
{
	$_SESSION['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'];
}

if ($_SESSION['user'])
{
	$user = $_SESSION['user']; // do not change this line
}
else
{
	$_SESSION['user'] = $user;
}

/**
 * Change these variables to match your server... These are REQUIRED for correct operation of Tufat. If
 * you are unsure of these values, you should contact your web server administrator.
 */


 /* if the navigation menu to be collapsed, set this to true; Otherwise, it must be set to false.  */

define ('COLLAPSIBLEMENU',true);
define('DEFAULTTEMPLATE', 'classic');
define ('HIDELIVING','');


/**
 * Optional system configuration variables.
 */
define ('ALLOWMULTIPLELANGUAGES',true);
define ('ADMINNAME','Family Tree Administrator');
define('TREENAME', 'Our Family Tree');

// allow email notifications when a record is updated? (1=yes, 0=no)
define ('USEMAIL','1');
define('ALLOWCREATE', 'on');
define('ALLOWCROSSTREESEARCH', 'on');
define('SUPERVISNAME', 'Site Supervisor');
define('SUPERVISEMAIL', 'g8z@yahoo.com');
define('SHOWALLTREES', '0');


/* To setup multiple columns for displaying the full family tree for those trees which has more than 300 members, set following field. Maximum should be 5. Default is 3   */
define ('MULTICOLUMNDISPLAY','4');

/**

 * Master Password: this is the admin password to control all other admin passwords. Even though each
 * invidual tree has an admin password, using this password will give you admin privileges to *any* tree.
 */

define('MASTERPASSWORD', 'passmaster123');


/**
 * Default User Feature: If you would like to bypass the login screen altogether, or implement your own login
 * system (e.g. using a more advanced technology like .htaccess), then you should may wish to set a "default"
 * tree and view. By setting a default user, you are allowing people to go directly into the default tree
 * without having to login. You should only set this variable AFTER your have created a valid tree via standard
 * login (the initial tree-creation routine is bypassed when a default user is set). I.e., the $defaultUser
 * login name + password MUST exist in the tufat_users table for this feature to be work.
 */

// the default user name (set this value to bypass login form)
 define ('DEFAULTUSER','');
// 1 = read only, 2 = editable by all users, 3 = administrator, 4 = master admin
 define ('DEFAULTVIEW','1');

/* allow any sex partner */
define('PARNER_ANY_SEX', 0);


/**
 * Script Naming options. Export and import script names are variables in case you need more protection, you
 * can change the PHP file names to be more obscure. $viewScript is the PHP page for whole tree viewing,
 * and is the default page.
 */
 define ('VIEWSCRIPT','index.php');
 define ('EXPORTSCRIPT','export.php');
 define ('IMPORTSCRIPT','import.php');
 define ('BACKUPSCRIPT','backup.php');
 define ('DROPSCRIPT','drop.php');


/**
 * If you would like to name the MySQL tables differently, you can change their names here. Please note that
 * these table names MUST reflect the table names that are created using the SQL commands in the tufat.sql file.
 */

define('MYSQLPREFIX', 'tufat_');

define ('TBL_USER',MYSQLPREFIX .'users'); // the table holding user login information
define ('TBL_TEMP',MYSQLPREFIX .'temp'); // table to hold temporary info
define ('TBL_GEDCOM',MYSQLPREFIX .'gedcom'); // table to hold gedcom entries
define ('TBL_BLOBS',MYSQLPREFIX .'blobs'); // table to hold biography and portrait data
define ('TBL_LOCKS',MYSQLPREFIX .'locks');  // table to hold record lock passwords
define ('TBL_LANG', MYSQLPREFIX .'lang'); // table to hold language tables
define ('TBL_FAMGAL', MYSQLPREFIX .'famgal'); // table to hold family gallery
define ('TBL_ILOGI',MYSQLPREFIX .'ilogins');  // individual login table
define ('TBL_ILINK',MYSQLPREFIX .'ilinks');  // individual links table
define ('TBL_INDEX',MYSQLPREFIX .'index');
define ('TBL_EVENTS',MYSQLPREFIX .'events');
define ('TBL_SEARCHES',MYSQLPREFIX .'searches'); // saved search results


// Switches between animal pedigree / human mode
define('ANIMALPEDIGREE', false);

/**
 * Display options.
 */

define ('CRLF',"\n");
define ('LONGDATEFORMAT','%M %D, %Y'); // unabbreviated date format (e.g. January 26th, 1975)
define ('SHORTDATEFORMAT','%m/%d/%Y'); // abbreviated date format (e.g. 01/26/1975)
define ('MAXPORTRAITWIDTH',200); // max dimensions before system resizes, in pixels
define ('MAXPORTRAITHEIGHT',300);
define ('PREVIOUSIMAGESTRING','Previous Image'); // Gallery Previous Image link text
define ('NEXTIMAGESTRING','Next Image'); // Gallery Next Image link text
define ('NAVIGATIONBARCOLOR','EAEAD2'); // color of the Gallery image navigation bar
define ('MAXLINELENGTH',74); // max # of characters before NOTE fields are broken into pieces
define ('UNDEFINED','Undefined'); // what to print when a person cannot be found in the database
define ('NONE','(none)'); // what to print when a list of individuals cannot be found
define ('MYSQL',1); // do not change this value (future TUFaT version may make MySQL an option)
define ('IMG_H',300); // portrait height .width will calculate auto
define ('INSTALL',2);


/**
 * MySQL DATE FORMAT OPTIONS:
 *
 * %M  Month name (January..December)
 * %W  Weekday name (Sunday..Saturday)
 * %D  Day of the month with English suffix (1st, 2nd, 3rd, etc.)
 * %Y  Year, numeric, 4 digits
 * %y  Year, numeric, 2 digits
 * %X  Year for the week where Sunday is the first day of the week, numeric, 4 digits, used with '%V'
 * %x  Year for the week, where Monday is the first day of the week, numeric, 4 digits, used with '%v'
 * %a  Abbreviated weekday name (Sun..Sat)
 * %d  Day of the month, numeric (00..31)
 * %e  Day of the month, numeric (0..31)
 * %m  Month, numeric (01..12)
 * %c  Month, numeric (1..12)
 * %b  Abbreviated month name (Jan..Dec)
 * %j  Day of year (001..366)
 * %H  Hour (00..23)
 * %k  Hour (0..23)
 * %h  Hour (01..12)
 * %I  Hour (01..12)
 * %l  Hour (1..12)
 * %i  Minutes, numeric (00..59)
 * %r  Time, 12-hour (hh:mm:ss [AP]M)
 * %T  Time, 24-hour (hh:mm:ss)
 * %S  Seconds (00..59)
 * %s  Seconds (00..59)
 * %p  AM or PM
 * %w  Day of the week (0=Sunday..6=Saturday)
 * %U  Week (0..53), where Sunday is the first day of the week
 * %u  Week (0..53), where Monday is the first day of the week
 * %V  Week (1..53), where Sunday is the first day of the week. Used with '%X'
 * %v  Week (1..53), where Monday is the first day of the week. Used with '%x'
 */

//@added by Andrew, php version compatibility
//php version compatibilities
if ( phpversion() >= "4.2.0")
{
   extract($_REQUEST);
}

// NOTE: DO NOT type anything past the end PHP tag ( ? > ), not even a space or carriage return!

if ( $_SESSION['templateID'] )
   $templateID = $_SESSION['templateID'];
else
   $templateID = DEFAULTTEMPLATE ;

$_SESSION['templateID']=$templateID;

if (!isset($smarty))
{
	$smarty = new tufat_Smarty();

	$smarty->template_dir = TEMPLATE_DIR;
	$smarty->compile_dir = TEMPLATE_C_DIR;
	$smarty->cache_dir = CACHE_DIR;

	//$smarty->force_compile = true;
	$smarty->caching = false;
	$smarty->compile_check = true;
	$smarty->cache_lifetime = 3000;
}


// Enable these lines for debugging information
//           $smarty->debugging = true;
//           $smarty->assign('debugging', $smarty->debugging);

if ( $_SESSION['templateID'] )
    $templateID = $_SESSION['templateID'];

$smarty->assign('templateID',$templateID);
$smarty->assign('currtime',mktime());


// Added by Pat K to include new language parser
// langconfig Must be included *after* $smarty object is declared here

if ( !defined( 'INCLUDES' ) && (!isset($inst) || ($inst != 1)))
{
   require 'classes.php';

 	$db = new FamilyDatabase();   	    // connect to the database
	require 'langconfig.php';						// include the language parsers

	//$db->setOption('portability', MDB2_PORTABILITY_ALL ^ MDB2_PORTABILITY_EMPTY_TO_NULL);

	include_once('navMenu.php');				// include the navigation menu

	$smarty->assign('navMenu', $smarty->fetch('navMenu.tpl'));

}
else { $inst = 1; }

?>
