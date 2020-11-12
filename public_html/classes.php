<?php

/**
 * TUFaT, The Ultimate Family Tree Creator
 * Copyright 1999 - 2007, Darren G. Gates, All Rights Reserved
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

require_once 'config.php';
require_once('classes/family_database.class.php');
require_once('classes/navigation_bar.class.php');
require_once('classes/file.class.php');
require_once('classes/email.class.php');
require_once('includes/init.inc.php');

ob_start( );
@ini_set( 'upload_max_filesize', '6M');
@ini_set( "session.gc_maxlifetime", 600);

// classes file for tufat
// get configuration data from external file
define( 'INCLUDES', 1);
define( 'TUFAT_DEBUG', 0);

/**
 * * This function opens the error.php page and prints the $message string.
 */

function error( $message) {
	/* Modified to incorporate Smarty   Vijay Nair 20/Mar/2004   */
	require 'display_error_message.php';
	exit;
}

/**
 * * This class contains generic database-access functions.
 */

function make_seed() {
	list( $usec, $sec) = explode( ' ', microtime( ));
	return ( float) $sec + ( ( float) $usec * 100000);
}

/**
 * * This class contains calls to Database-access that are specific to family-tree loading.
 */
function DateCheck( $indate) {
	return true;
}

if ( (!isset($inst) || $inst != 1) || ( $inst == 1 && $in_login == 1)) {
	if ( isset( $_COOKIE["templateID"])) {
		$templateID = $_COOKIE["templateID"];
		if ( strlen( $templateID) < 1) {
			$templateID = DEFAULTTEMPLATE;
		}
	} else {
		$templateID = DEFAULTTEMPLATE;
	}
} else {
	if ( !isset( $stplid)) {
		$templateID = DEFAULTTEMPLATE;
	} else {
		$templateID = $stplid;
	}
}

$_SESSION["templateID"] = $templateID;

if (isset($ID) and $ID != "") 
{
	$_SESSION['current_id'] = $ID;
} ;

// Force user to login if they try to access a page that requires it
if ( !$_SESSION['user'] && !$loginPage && install > 1 && !$noAuth)
	header( 'Location: login.php');

function check_slash ( $string) // ->
{
	if ( !get_magic_quotes_gpc( )) {
		return mysql_escape_string ( $string);
	} else {
		return $string;
	}
}
function fix_html ( $data, $p = "") // ->
{
	if ( is_array( $data)) {
		foreach ( $data as $key => $value) {
			$data[$key] = htmlentities( $value);
		}
	} else {
		if ( $p == 2) $data = stripslashes( $data);
		$data = htmlentities( $data);
	}
	return $data;
}

// Get Persons with Email
function getPersEmail( $db, $user) {
  $i = 0;
  $aemail = array( );

  $sql = "select id,data from ! where tag='EMAI' AND tree= ?";
  $r = $db->query( $sql, array( $db->gedcomTable, $user));

  while ( $a = $db->mfa( $r)) {
    $nm = $db->getItem( "name", $a['id']);
    $nm = str_replace( "/", "", $nm);
    $aemail[$i]['name'] = $nm . ' (' . $a['data'] . ')';
    $aemail[$i]['email'] = $a['data'];
    $i++;
  }

  return $aemail;
}