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

/*
   Where we might want to use the 'basename' URL, as in all of the links in this page,
   we must force ampersands (&) to be outputted initially as a character encoded refrence (&amp;)
   */

$smarty->assign( "base_url", str_replace( '&', "&amp;", $_SERVER['REQUEST_URI']));

/* Assign values passed onto the TPL file  */

$smarty->assign( "collapsibleMenu", COLLAPSIBLEMENU);
$smarty->assign( "currtime", mktime( ));
$smarty->assign( "layerWidth", "$layerWidth");
$smarty->assign( "layerHeight", "$layerHeight");
$smarty->assign( "viewScript", VIEWSCRIPT);
$smarty->assign( "importScript", IMPORTSCRIPT);
$smarty->assign( "exportScript", EXPORTSCRIPT);
$smarty->assign( "backupScript", BACKUPSCRIPT);
$smarty->assign( "dropScript", DROPSCRIPT);
$smarty->assign( "selectedLanguage", $selectedLanguage);
if ( !$loginPage && !$inst) {
  if ( $_REQUEST['qsearch']) {
        $_SESSION['qsearch'] = $_REQUEST['qsearch'];
  }
	
  $smarty->assign( 'qsearch', $_SESSION['qsearch']);

  $smarty->assign( "layerWidth", 140);
  $smarty->assign( "layerHeight", 250);

  if ($_SESSION['current_id']) {
    $chname = $db->getitem( 'name', $_SESSION['current_id']);
	
    if ( $chname) {
      $smarty->assign( "ID", $_SESSION['current_id']);
    } else {
      $smarty->assign( 'ID', false);
    }
  }

  if ( $inst != 1)
    $ct = $db->getIndiCount( );

  if ( $_SESSION["user"] != '' && $ct >= 0) {
    require_once 'calendar.php';

    $today = getdate( );

    $month = $mon;
		
    if ( !isset( $mon))
      $month = $today['mon'];

    if ( isset( $_REQUEST['year']))
      $_SESSION['year'] = $_REQUEST['year'];
			
    if ( isset( $_SESSION['year']))
      $year = $_SESSION['year'];
    else
      $year = $today['year'];
			
    $startDay = "0";

    $menu_cache_id = 'menu|calendar|'.implode('|', array_merge(array(crc32($_REQUEST['mitem']), $year, $month, $startDay), $_SESSION));
    if (count($_POST)) {
      $smarty->clear_cache('navMenu.tpl', $menu_cache_id);
    }

    $newcal = calendar( $year, $month, $startDay, $_SESSION['user'], 1);
    $smarty->assign("calendar", $newcal);
    $smarty->assign('navMenu', $smarty->fetch('navMenu.tpl', $menu_cache_id));
  }
}

//@ Calendar Event - users list	
//@ check if user logged	
if ( $_SESSION['user'] != '' ) {		
	$birthdays_list = array();
	
  $sql = "select id,name,surn,bdate,ddate,hide from ! where tree= ? order by id";
  $result = $db->query( $sql, array( $db->indexTable, $_SESSION['user']));

  if ( $db->rowsInResult( $result) > 0) {			
    while ( list( $id, $name, $surn, $birt_date, $deat_date, $hidden) = $db->mfr( $result ) ) {				
      if ( $hidden != "No" )
        continue;
			   
			$surn = $db->getBrackName($name);
			$name = $db->removeFam($name);
				
			//@ that not occurs db query
			if ( $birt_date)
				$birt_date = $db->mysqlDateFormat( $birt_date );
																	
			//@ if person is deat skip it
			if( trim($deat_date) != '' )
				continue;
																
			//@ create valid date
			if( !ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})", $birt_date, $regs ) )
				continue;
					
			$bdate = date('Y').'-'.$regs[2].'-'.$regs[3];
			$cdate = date( "Y-m-d" );

			$interval_days = (int) ( ( strtotime($bdate) - strtotime($cdate) )/(24*3600) );
																
			//@ check for this week birthdays
			if( $interval_days >= 0 && $interval_days < 7 ) {
				$birthdays_list[$id] = array(
											'name' => $name,
											'surname' => $surn,
											'rest_days' => $interval_days
											);
			}
		}
	}
	$smarty->assign( "birthdays", $birthdays_list);	
	$smarty->assign( "birthdays_len", count($birthdays_list));	
}