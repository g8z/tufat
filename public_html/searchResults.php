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
 *
 * This is modified to incorporate Smarty Template Processor
 * Vijay Nair                               15/Apr/2004
 */
//@ini_set('xdebug.profiler_enable', 1);

require_once 'config.php';

$_REQUEST = array_map('escape_string', $_REQUEST);
$_REQUEST = array_map('trim', $_REQUEST);
extract($_REQUEST);

$llg = 1;
if (!$savesearch) {
    // save searched results
  $sql = "INSERT INTO ! SET
           `searchname` = ?, `searchuser` = ?, `ID` = ?,
           `alltrees` = ?, `f1bool` = ?, `name` = ?,
           `f2bool` = ?, `surn` = ?, `f3bool` = ?,
           `birt_date_start_1` = ?, `birt_date_start_2` = ?, `birt_date_start_3` = ?,
           `birt_date_end_1` = ?, `birt_date_end_2` = ?, `birt_date_end_3` = ?,
           `f4bool` = ?, `deat_date_start_1` = ?, `deat_date_start_2` = ?,
           `deat_date_start_3` = ?, `deat_date_end_1` = ?, `deat_date_end_2` = ?,
           `deat_date_end_3` = ?, `f5bool` = ?, `born_on_day_1` = ?,
           `born_on_day_2` = ?,`f6bool` = ?, `born_during_month_1` = ?,
           `f7bool` = ?, `occu` = ?, `f8bool` = ?,
           `bred` = ?, `f9bool` = ?, `sex` = ?,
           `f10bool` = ?, `buri_plac` = ?, `f11bool` = ?,
           `cdea` = ?, `f12bool` = ?, `portrait` = ?,
           `f13bool` = ?, `bio` = ?, `limit` = ?
          ";

    /*   $sql = "insert into ! (kd, id, ta, tree, fid, data, type, name, title, descr, indi, sid) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
       $r = $db->query($sql, array($db->famgalTable,$kd,$maxid,$t,$user,$fp,$data,$mtype,$rname,$stitle,$sdescr,$sindi,$msid));*/

  $r = $db->query( $sql, array( $db->searchesTable, $searchname ? $searchname : "no name", $_SESSION['user'], $ID, $alltrees, $f1bool, $name, $f2bool, $surn, $f3bool, $birt_date_start_1, $birt_date_start_2, $birt_date_start_3, $birt_date_end_1, $birt_date_end_2, $birt_date_end_3, $f4bool, $deat_date_start_1, $deat_date_start_2, $deat_date_start_3, $deat_date_end_1, $deat_date_end_2, $deat_date_end_3, $f5bool, $born_on_day_1, $born_on_day_2, $f6bool, $born_during_month_1, $f7bool, $occu, $f8bool, $bred, $f9bool, $sex, $f10bool, $buri_plac, $f11bool, $cdea, $f12bool, $portrait, $f13bool, $bio, $limit));

  while ($a = $db->mfa($result)) {
    foreach ( $a as $key => $value)  {
      $$key = $value;
    }
  }
} elseif ($searchid > 0) {
  $sql = "select * from ! where searchid = ? and ID= ? limit 0, 1";
  $result = &$db->query( $sql, array( $db->searchesTable, $searchid, $ID));
  list($ID) = $db->mfr($result);

  die(header( "Location: search.php?ID=" . $ID));
}

// automatically construct get string
$getstr = "";

foreach ( $_POST as $x => $y) {
    if ( $x != "sortby")
        $getstr .= $x . "=" . urlencode( $y) . "&amp;";
}
foreach ( $_GET as $x => $y) {
    if ( $x != "sortby")
        $getstr .= $x . "=" . urlencode( $y) . "&amp;";
}

// construct the search query...
if ($alltrees)
    $whereClause = "$db->indexTable.tree <> '" . $_SESSION["user"] . "'";
else
    $whereClause = "$db->indexTable.tree = '" . $_SESSION["user"] . "'";

if ( $name)
  $whereClause .= " $f1bool ( $db->indexTable.name LIKE '%$name%' ) ";

// exclude individuals with no name
$noNameClause = " AND ( $db->indexTable.name  <> '' )  ";
$whereClause .= $noNameClause;

if ( $surn)
    $whereClause .= " $f2bool ( $db->indexTable.surn LIKE '%$surn%' ) ";

if ( $sex)
    $whereClause .= " $f9bool ( $db->indexTable.sex LIKE '$sex' ) ";

if ( $occu)
    $whereClause .= " $f7bool ( $db->indexTable.occu LIKE '%$occu%' ) ";
if ( $bred)
    $whereClause .= " $f8bool ( $db->indexTable.bred LIKE '%$bred%' ) ";
if ( $cdea)
    $whereClause .= " $f11bool ( $db->indexTable.cdea LIKE '%$cdea%' ) ";

if ( $buri_plac)
    $whereClause .= " $f10bool ( $db->indexTable.bplace LIKE '%$buri_plac%' ) ";

if ( $born_during_month_1 != '00')
    $whereClause .= " $f6bool (   MONTH( $db->indexTable.bdate)  = '$born_during_month_1' ) ";

if ( $born_on_day_1 != '00' && $born_on_day_2 != '00')
    $whereClause .= " $f5bool ( DAYOFMONTH( $db->indexTable.bdate)  = '$born_on_day_2' AND MONTH(  $db->indexTable.bdate) = '$born_on_day_1' ) ";

if ( $birt_date_start_1 && $birt_date_start_2 && $birt_date_start_3) 
{
    $birt_date = $birt_date_start_3 . "-" . $birt_date_start_1 . "-" . $birt_date_start_2;
    $whereClause .= " $f3bool ( ( $db->indexTable.bdate  > '$birt_date' ) ";
}

if ( $birt_date_end_1 && $birt_date_end_2 && $birt_date_end_3) 
{
    $birt_date = $birt_date_end_3 . "-" . $birt_date_end_1 . "-" . $birt_date_end_2;
    $whereClause .= " AND ( $db->indexTable.bdate < '$birt_date' ) )";
}

if ( $deat_date_start_1 && $deat_date_start_2 && $deat_date_start_3) 
{
    $deat_date = $deat_date_start_3 . "-" . $deat_date_start_1 . "-" . $deat_date_start_2;
    $whereClause .= " $f4bool ( ( $db->indexTable.ddate > '$deat_date' ) ";
}

if ( $deat_date_end_1 && $deat_date_end_2 && $deat_date_end_3) 
{
    $deat_date = $deat_date_end_3 . "-" . $deat_date_end_1 . "-" . $deat_date_end_2;
    $whereClause .= " AND ( $db->indexTable.ddate < '$deat_date' )) ";
}

if ( $bio)
    $whereClause .= " $f13bool $db->indexTable.bio = 1 ";

if ( $portrait)
    $whereClause .= " $f12bool $db->indexTable.por = 1 ";
$q5 = $db->query( "describe $db->indexTable");
$flist = "";

for ( $i = 0; $i < $db->rowsInResult( $q5); $i++) 
{
    $d5 = $db->mfa( $q5);
	
    if ( $d5["field"] != "id")
        $flist .= "$db->indexTable" . "." . $d5["field"] . " ";
		
    if ( $i < $db->rowsInResult( $q5) - 1 && $d5["field"] != "id")
        $flist .= ", ";
}

$query = "SELECT $db->indexTable.id , $flist ";

$query .= " FROM $db->indexTable ";

if ( $sortby != '') {
    $ordstr = "order by $sortby";
} else
    $ordstr = "";

if ( $sortby2 != '') {
    $ordstr2 = "order by $sortby2";
} else
    $ordstr2 = "";

$query3 = $query . " WHERE $whereClause group by $db->indexTable.id  $ordstr2 LIMIT $limit";
$query .= " INNER JOIN " . TBL_USER . " ON " . TBL_USER . ".username = $db->indexTable.tree WHERE $whereClause AND ( " . TBL_USER . ".crosstree = 1 OR " . TBL_USER . ".username = '$user' )  group by $db->indexTable.id  LIMIT $limit";

srand( make_seed( ));

$tmpid = rand( 1, 60000);
$q1 = "create table tmp_" . $tmpid . " $query";
//echo $q1;
$tmp_r = $db->query( $q1);

$db->query( "alter table tmp_" . $tmpid . " add index (bdate)");
$db->query( "alter table tmp_" . $tmpid . " add index (ddate)");

if ( strstr( $sortby, "date") != false) 
{
    $ir = $db->query( "select id, bdate, ddate from tmp_" . $tmpid);
	
    if ( $ir != false) 
	{
        for ( $k = 0; $k < $db->rowsInResult( $ir); $k++) 
		{
            $ka = $db->mfa( $ir);

            if ( preg_match( "/([a-zA-Z\.]+)/", $ka["bdate"], $m) != false) 
			{
                $x1 = str_replace( $m[0], "", $ka["bdate"]);
            } 
			else
                $x1 = $ka["bdate"];

            if ( preg_match( "/([a-zA-Z\.]+)/", $ka["ddate"], $m) != false) 
			{
                $x2 = str_replace( $m[0], "", $ka["ddate"]);
            } 
			else
                $x2 = $ka["ddate"];

            $x1 = trim( $db->getItem( 'birt_date', $ka['id']));
            $x2 = trim( $db->getItem( 'deat_date', $ka['id']));
            $xid = $ka['id'];

            $sql = "update ! set bdate = ?, ddate = ? where bdate = ? and ddate = ? and id= ?";
            $db->query( $sql, array( "tmp_" . $tmpid, $x1, $x2, $ka['bdate'], $ka['ddate'], $xid));
        }
    }
}

$q2 = "select * from tmp_" . $tmpid . " $ordstr";
$result = $db->query( $q2);

// now get search stats for this tree only
$query2 = str_replace( "tree <>", "tree =", $query);

// remove the "no name clause"
$query2 = str_replace( $noNameClause, "", $query2);

$tmpid2 = rand( 1, 60000);
$q1 = "create table tmp_" . $tmpid2 . " $query2";

$db->query( $q1);
$db->query( "alter table tmp_" . $tmpid2 . " add index (bdate)");
$db->query( "alter table tmp_" . $tmpid2 . " add index (ddate)");

if ( strstr( $sortby, "date") != false) {
    $ir = $db->query( "select id, bdate, ddate from tmp_" . $tmpid2);
    if ( $ir != false) {
        for ( $k = 0; $k < $db->rowsInResult( $ir); $k++) {
            $ka = $db->mfa( $ir);

            if ( preg_match( "/([a-zA-Z\.]+)/", $ka["bdate"], $m) != false) {
                $x1 = str_replace( $m[0], "", $ka["bdate"]);
            } else
                $x1 = $ka["bdate"];

            if ( preg_match( "/([a-zA-Z\.]+)/", $ka["ddate"], $m) != false) {
                $x2 = str_replace( $m[0], "", $ka["ddate"]);
            } else
                $x2 = $ka["ddate"];
            $x1 = trim( $db->getItem( 'birt_date', $ka['id']));
            $x2 = trim( $db->getItem( 'deat_date', $ka['id']));
            $xid = $ka['id'];

            $sql = "update ! set bdate = ?, ddate = ? where bdate = ? and ddate = ? and id = ?";
            $db->query( $sql, array( "tmp_" . $tmpid2, $x1, $x2, $ka['bdate'], $ka['ddate'], $xid));
        }
    }
}

$whereClause = str_replace($db->indexTable.'.', '', $whereClause);
$whereClause = str_replace('\' AND (', '\' AND ((', $whereClause).')';
$query2 = "SELECT * FROM tmp_" . $tmpid2 . " WHERE $whereClause $ordstr";

$result2 = $db->query($query2);

$numFound = $db->rowsInResult( $result2);

$sql = "select distinct id from ! WHERE type='I' and tree = ?";
$r = $db->query( $sql, array( $db->gedcomTable, $user));

$numTotal = $db->rowsInResult( $r);
$mydata['numTotal'] = $numTotal;
$mydata['numFound'] = $numFound;
$mydata['ID'] = $ID;
$mydata['animalPedigree'] = ANIMALPEDIGREE;
$mydata['user'] = $user;
$mydata['treeName'] = TREENAME;
$mydata['alltrees'] = $alltrees;
$mydata['allowCrossTreeSearch'] = ALLOWCROSSTREESEARCH;

if ( $numTotal != 0)
    $percentMatch = 100 * round( $numFound / $numTotal, 2);
else
    $percentMatch = "0";

$mydata['percentMatch'] = $percentMatch;

$mydata['getstr'] = $getstr;

$matchArray = array( );
$result2List = array( );

if ( $db->rowsInResult( $result2) > 0) {
  $mydata['result2Cnt'] = $db->rowsInResult( $result2);

  while ( list( $tempID, $tree) = $db->mfr( $result2)) {
    list( $name, $hidden, $surn, $occu, $bred, $cdea, $buri_plac, $birt_date, $deat_date, $sex) = 
		$db->getItems( array( "name", "HIDE", "surn", "occu", "bred", "cdea", "buri_plac", "birt_date", "deat_date", "sex"), $tempID);

    if ( $hidden == "Yes") {
      $name = $db->obstr( $name, 1);
      $surn = $db->obstr( $surn, 2);
      $occu = $db->obstr( $occu);
      $birt_plac = $db->obstr( $birt_plac);
      $birt_date = $db->obstr( $birt_date);
      $buri_plac = $db->obstr( $buri_plac);
      $deat_plac = $db->obstr( $deat_plac);
    }
		
    // record the name, surname, sex, and birth date in an array so that we can check for matches later on
    $matchArray[] = "$name $surn $sex $birt_date $cdea $bred";

    $name = stripslashes( $name);
        $surn = stripslashes( $surn);
        $occu = stripslashes( $occu);
        $bred = stripslashes( $bred);
        $cdea = stripslashes( $cdea);
        $buri_plac = stripslashes( $buri_plac);

        if ( $sex == "M")
            $sex = "Male";
        elseif ( $sex == "F")
            $sex = "Female";
			
        $name = $db->removeFam( $name);
		
        if ( ANIMALPEDIGREE)
            $name = $db->changeBrack( $name);

        $result2List[$tempID]->name = $name;
        $result2List[$tempID]->surn = $surn;
        $result2List[$tempID]->sex = $sex;
        $result2List[$tempID]->occu = $occu;
        $result2List[$tempID]->bred = $bred;
        $result2List[$tempID]->buri_plac = $buri_plac;
        $result2List[$tempID]->cdea = $cdea;
        $result2List[$tempID]->name = $name;

        if ( $birt_date)
            $birt_date = $db->dateFormat( $birt_date, 3);
			
        if ( $deat_date)
            $deat_date = $db->dateFormat( $deat_date, 3);

        $result2List[$tempID]->birt_date = $birt_date;
        $result2List[$tempID]->deat_date = $deat_date;
    }
}

$intreesList = array( );

// now search all the other trees in the database to see if there are any matches
if ( $alltrees && ALLOWCROSSTREESEARCH) {
  $result = $db->query( $query3);

    $getstr = str_replace( "sortby2=", "xx=", $getstr);

    $mydata['resultcnt'] = $db->rowsInResult( $result);
    $mydata['getstr'] = $getstr;

    if ( $db->rowsInResult( $result) > 0) {
        for ( $i = 0; $i < $db->rowsInResult( $result); $i++) {
            $a = $db->mfa( $result);
            $tempID = $a["id"];
            $tree = $a["tree"];
            list( $name, $hidden, $surn, $occu, $bred, $cdea, $buri_plac, $birt_date, $deat_date, $sex) = $db->getItems( array( "name", "HIDE", "surn", "occu", "bred", "cdea", "buri_plac", "birt_date", "deat_date", "sex"), $tempID);

            if ( $hidden == "Yes") 
			{
                $name = $db->obstr( $name, 1);
                $surn = $db->obstr( $surn, 2);
                $occu = $db->obstr( $occu);
                $birt_plac = $db->obstr( $birt_plac);
                $birt_date = $db->obstr( $birt_date);
                $buri_plac = $db->obstr( $buri_plac);
                $deat_plac = $db->obstr( $deat_plac);
            }
			
            // use only the initials, for security reasons
            $initials = substr( $name, 0, 1) . ". " . substr( $surn, 0, 1) . ".";

            if ( $birt_date)
                $birt_date = $db->dateFormat( $birt_date, 3);
            if ( $deat_date)
                $deat_date = $db->dateFormat( $deat_date, 3);

            $occu = stripslashes( $occu);
            $buri_plac = stripslashes( $buri_plac);

            $rowColor = "";

            if ( $sex == "M")
                $sex = "Male";
            elseif ( $sex == "F")
                $sex = "Female";
				
            // Store in array to display in Smarty TPL file
            $intreesList[$i]->tree = $tree;
            $intreesList[$i]->ID = $tempID;
            $intreesList[$i]->user = $user;
            $intreesList[$i]->initials = $initials;
            $intreesList[$i]->sex = $sex;
            $intreesList[$i]->occu = $occu;
            $intreesList[$i]->bred = $bred;
            $intreesList[$i]->birt_date = $birt_date;
            $intreesList[$i]->deat_date = $deat_date;
            $intreesList[$i]->buri_plac = $buri_plac;
            $intreesList[$i]->cdea = $cdea;
        }
    }
}

$smarty->assign( "mydata", $mydata);
$smarty->assign( "intreesList", $intreesList);
$smarty->assign( "result2List", $result2List);

// # Get the page we want to display
$smarty->assign('rendered_page', $smarty->fetch('searchResults.tpl'));

// Drop the temporary search tables
$db->query( "DROP TABLE tmp_" . $tmpid);
$db->query( "DROP TABLE tmp_" . $tmpid2);

// # Display the page.
$smarty->display( 'index.tpl');


?>
