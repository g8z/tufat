<?php
ob_start(); 
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
/**
 * This page is modified to include Smarty and Formsess Packages
 * Vijay Nair										21-Mar-2004
 */
/**
 * Modified 2006/05/22 Pat K. <cicada@edencomputing.com>
 * to abstract language parser
 */

$loginPage = true;
require_once 'config.php';
//unset($_SESSION['user']);
$db->dbh->loadModule('Extended');

$sql = "SELECT username FROM {$db->userTable} limit 1";

$x = $db->getValue($sql);
$sql = "SELECT LOWER(enc) AS enc FROM {$db->langTable} WHERE LOWER(l) = LOWER(".$db->dbh->quote($_SESSION["slang"]).") AND LOWER(enc) = LOWER(".$db->dbh->quote($senc).") GROUP BY LOWER(l),LOWER(enc)";
$r = $db->getValue($sql);

if ( $r != false) {
	$_SESSION['encType'] = $r;
} else {
	$_SESSION['encType'] = 'utf-8';
}

if ($_COOKIE['keep-alive'] && strlen($_COOKIE['keep-alive']) == 32) {
  $user = $db->dbh->getAssoc("SELECT * FROM {$db->userTable} WHERE MD5(CONCAT(username, admin_password, created)) = ?", null, array($_COOKIE['keep-alive']), array('text'));
  if (count($user) == 1) {
    $user = array_shift($user);
    $username = $user['username'];
    $_REQUEST['username'] = $user['username'];
    $password = $user['admin_password'];
    $login = true;
    $newTree = false;
    $usernameTaken = false;
  }
}

// check for default login values...
if (DEFAULTUSER && !$_GET['defaultUser'] && !$_POST['defaultUser']) {
	// set the defaults
	$username = DEFAULTUSER;

	list($generalPassword, $readOnlyPassword, $adminPassword) = $db->getPasswords($username);

	if ( DEFAULTVIEW == 1)
		$password = $readOnlyPassword;
	elseif ( DEFAULTVIEW == 2)
		$password = $generalPassword;
	elseif ( DEFAULTVIEW == 3)
		$password = $adminPassword;
	elseif ( DEFAULTVIEW == 4)
		$password = MASTERPASSWORD;

	$login = true;
}

$loginPage = true;
              
if (!$_REQUEST['username'] && !$_COOKIE['keep-alive']) {
  $username = $user;
} else {
  $username = $_REQUEST['username'];
}

$redirect = VIEWSCRIPT;

if ($newTree || $login) {
	// check for new tree error conditions
	if ($newTree && !$guidelines) {
		$error_title = $db->mytrans( '##Please Read Guidelines##');
		$error = $db->mytrans( '##You must read and consent to the guidelines before creating a family tree.##');
	} elseif ($newTree && $usernameTaken) {
		$error_title = $db->mytrans( '##Login Error##');
		$error = $db->mytrans( '##The user name that you requested is already in use. Please enter a different one.##');
	} else {
		list($generalPassword, $readOnlyPassword, $adminPassword) = $db->getPasswords($username);
		$sql = "SELECT tp,ID FROM {$db->ilogiTable} WHERE user=".$db->dbh->quote($username)." AND pass=".$db->dbh->quote($password);
		$r = $db->query($sql);
		if ( $r != false && $db->rowsInResult($r) > 0) {
			$a = $db->mfa($r);

			if ( $a['tp'] == 0) {
				$_SESSION['edit_only'] = 1;
				session_unregister( 'read_only');
				session_unregister( 'my_rec');
			} elseif ( $a['tp'] == 1) {
				$_SESSION['read_only'] = 1;
				$_SESSION['my_rec'] = $a['ID'];
				session_unregister( 'edit_only');
			} elseif ( $a['tp'] == 2) {
				$_SESSION['read_only'] = 1;
				$_SESSION['my_rec'] = '';
				session_unregister( 'my_rec');
				session_unregister( 'edit_only');
			} elseif ( $a['tp'] == 3) {
				$_SESSION['admin'] = 1;
				session_unregister( 'read_only');
				session_unregister( 'my_rec');
				session_unregister( 'edit_only');
			}

			$isUser = true;
		}

		// this person is an administrator
		if ( $password == $adminPassword || $password == MASTERPASSWORD) {
			// set $admin session variable to true
			$_SESSION['admin'] = 1;
			$isUser = true;
			// this is the tree owner logging in
			if ( $password == MASTERPASSWORD) {
				// session_register( "master" );
				$_SESSION['master'] = 1;
			}
		} elseif ( $password == $readOnlyPassword) {
			// set $read_only session variable to true
			$_SESSION['read_only'] = 1;

			$isUser = true;
		} elseif ( $password == $generalPassword) {
			$isUser = true;
		}

		if ( $isUser) {
			$sql = "SELECT username, dname FROM ! WHERE username= ?";
			$r = $db->query( $sql, array( $db->userTable, $username));

			if ( $db->rowsInResult( $r) > 0) {
				$a = $db->mfa( $r);
				$treeName = stripslashes( $a['dname']);
				$_SESSION['treeName'] = $treeName;
			}

			if ( $r != false && $db->rowsInResult( $r) == 0) {
				$isUser = false;
			}
		}
    
		if ( $isUser) {
    
			// set $user session variable
			$_SESSION['user'] = $username;
			$user = $username;
			// redirect to the screen that we were originally trying to get to
			$loginPage = false;
			// update the lastlogin field of the users table
			$today = date( 'Y-m-d');
			$_SESSION['treeName'] = $treeName;
            //support keep-alive option for login form
            if (isset($_REQUEST['keep-alive']) && $_REQUEST['keep-alive']) {
              if (isset($_SESSION['admin']) && $_SESSION['admin']) {
                setcookie('keep-alive', $db->getOne("SELECT MD5(CONCAT(username, admin_password, created))  FROM {$db->userTable} WHERE username='$username'"), time() + 3153600);
              } else {
                setcookie('keep-alive', $db->getOne("SELECT MD5(CONCAT(username, password, created))  FROM {$db->userTable} WHERE username='$username'"), time() + 3153600);
              }
            }

            $db->setLoginValue( 'lastlogin', $today);
			die(header( "Location: " . VIEWSCRIPT));
		} else {
			/* Modified to incorporate Smarty Template  */
			$error_title = $db->mytrans( '##Login Error##');
			// $error = $db->mytrans( 'The user name and/or password that you entered could not be found or is invalid. Please try again.' );
			$error = $db->mytrans( '##The user name and/or password that you entered could not be found or is invalid. Please try again.##');
		}
	}
}

if ( $login) {
	$generalPassword = '';
	$readOnlyPassword = '';
	$adminPassword = '';
}

$llg = 1;
/* Enable formsess now   */

$smarty->assign( 'isTUFaT', stristr( $path, 'tufat.com'));

if ( SHOWALLTREES) {
	$smarty->assign( 'ShowAllTrees', SHOWALLTREES);

	$sql = "select * from ! where crosstree='1'";
	$r = $db->query( $sql, array( $db->userTable));

	if ( $r != false) {
		for ( $i = 0; $i < $db->rowsInResult( $r); $i++) {
			if ( $i % 2 == 0)
				$col = '#cccccc';
			else
				$col = '#ffffff';

			$a = $db->mfa( $r);

			$userrec[$i]->read_only_password = urlencode( $a['read_only_password']);
			$userrec[$i]->username = urlencode( $a['username']);
			$userrec[$i]->dname = stripslashes( $a['dname']);
			$userrec[$i]->aname = $a['aname'];
			$userrec[$i]->email = $a['email'];
			$userrec[$i]->BGCOLOR = "$col";
		}
	}
	$smarty->assign( 'ShowUsers', $userrec);
}
if ( !$treeName) {
	$treeName = 'The Ultimate Family Tree';
}
$smarty->assign( "allowCreate", ALLOWCREATE);
$smarty->assign( 'redirect', "$redirect");
$smarty->assign( 'treeName', $treeName);
// #$smarty->assign( 'mytrans_welcome', $db->mytrans( "Welcome to $treeName" ) );
$smarty->assign( 'Lang_Loginhere', $Lang['Login Here']);
$smarty->assign( 'SupervisName', SUPERVISNAME);
$smarty->assign( 'SupervisEmail', SUPERVISEMAIL);
/* Now display the page  */

if ( $error) {
	/* Modified to show generic error message method using Smarty  Vijay  21/3/3004 *
	   ************************************************************************/
	$smarty->assign( 'error_message', "$error");
	$smarty->assign( 'error_title', "$error_title");
	// !! Don't pass this to the display_error page, instead let the login script handle the errors
	// #$smarty->display( 'display_error_message.tpl' );
}

$smarty->assign( 'rendered_page', $smarty->fetch( 'login.tpl'));
// # Display the page.
$smarty->display( 'index.tpl', 'login');
