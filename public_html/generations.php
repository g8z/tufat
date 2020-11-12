<?php
/**
 * TUFaT, The Ultimate Family Tree Creator
 * Copyright 1999 – 2007, Darren G. Gates, All Rights Reserved
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
 * Vijay Nair                                   8/Apr/2004
 */

require_once 'config.php';
if ( !$ID) {
    // we must have an ID in order to show the extended family of someone
    require( VIEWSCRIPT);
    exit;
} 
// #require 'templates/'.$templateID.'/tpl_header.php';
$mydata['ID'] = $ID;
$mydata['templateID'] = $templateID; 
// get the mother, father, and grandparent IDs
$mother = $db->getMother( $ID);
$father = $db->getFather( $ID);

$maternalGrandmother = $db->getParent( $mother, "wife");
$maternalGrandfather = $db->getParent( $mother, "husb");

$paternalGrandmother = $db->getParent( $father, "wife");
$paternalGrandfather = $db->getParent( $father, "husb");

if ( !is_array( $maternalGreatGrandmother1))
    $maternalGreatGrandmother1 = array( );
if ( !is_array( $maternalGreatGrandfather1))
    $maternalGreatGrandfather1 = array( );
if ( !is_array( $maternalGreatGrandmother2))
    $maternalGreatGrandmother2 = array( );
if ( !is_array( $maternalGreatGrandfather2))
    $maternalGreatGrandfather2 = array( );
if ( !is_array( $paternalGreatGrandmother1))
    $paternalGreatGrandmother1 = array( );
if ( !is_array( $paternalGreatGrandfather1))
    $paternalGreatGrandfather1 = array( );
if ( !is_array( $paternalGreatGrandmother2))
    $paternalGreatGrandmother2 = array( );
if ( !is_array( $paternalGreatGrandfather2))
    $paternalGreatGrandfather2 = array( );

$maternalGreatGrandmother1 = $db->getParent( $maternalGrandmother, "wife");
$maternalGreatGrandfather1 = $db->getParent( $maternalGrandmother, "husb");
$maternalGreatGrandmother2 = $db->getParent( $maternalGrandfather, "wife");
$maternalGreatGrandfather2 = $db->getParent( $maternalGrandfather, "husb");

$paternalGreatGrandmother1 = $db->getParent( $paternalGrandmother, "wife");
$paternalGreatGrandfather1 = $db->getParent( $paternalGrandmother, "husb");
$paternalGreatGrandmother2 = $db->getParent( $paternalGrandfather, "wife");
$paternalGreatGrandfather2 = $db->getParent( $paternalGrandfather, "husb");

$greatGrandparents = array( );

if ( $maternalGreatGrandmother1 != "")
    $greatGrandparents[] = $maternalGreatGrandmother1;
if ( $maternalGreatGrandfather1 != "")
    $greatGrandparents[] = $maternalGreatGrandfather1;
if ( $maternalGreatGrandmother2 != "")
    $greatGrandparents[] = $maternalGreatGrandmother2;
if ( $maternalGreatGrandfather2 != "")
    $greatGrandparents[] = $maternalGreatGrandfather2;
if ( $paternalGreatGrandmother1 != "")
    $greatGrandparents[] = $paternalGreatGrandmother1;
if ( $paternalGreatGrandfather1 != "")
    $greatGrandparents[] = $paternalGreatGrandfather1;
if ( $paternalGreatGrandmother2 != "")
    $greatGrandparents[] = $paternalGreatGrandmother2;
if ( $paternalGreatGrandfather2 != "")
    $greatGrandparents[] = $paternalGreatGrandfather2; 
// $maternalGreatGrandmother2, $maternalGreatGrandfather2, $paternalGreatGrandmother1, $paternalGreatGrandfather1, $paternalGreatGrandmother2, $paternalGreatGrandfather2 );
function getName( $ID)
{
    global $db;

    list( $temp, $hidden) = $db->getItems( array( "name", "HIDE"), $ID);

    if ( ANIMALPEDIGREE)
        $temp = $db->changeBrack( $temp);
    else
        $temp = str_replace( "/", "", $temp);
    if ( $hidden == "Yes")
        $temp = $db->obstr( $temp, 1);

    if ( $temp == UNDEFINED) {
        return UNDEFINED;
    } else {
        if ( trim( $temp) == "")
            $temp = "(no name specified)";
        return $temp;
    } 
} 

$mydata['undefined'] = UNDEFINED;
$mydata['maternalGreatGrandmother1name'] = getName( $maternalGreatGrandmother1);
$mydata['maternalGreatGrandfather1name'] = getName( $maternalGreatGrandfather1);
$mydata['maternalGreatGrandmother2name'] = getName( $maternalGreatGrandmother2);
$mydata['maternalGreatGrandfather2name'] = getName( $maternalGreatGrandfather2);
$mydata['paternalGreatGrandmother1name'] = getName( $paternalGreatGrandmother1);
$mydata['paternalGreatGrandfather1name'] = getName( $paternalGreatGrandfather1);
$mydata['paternalGreatGrandmother2name'] = getName( $paternalGreatGrandmother2);
$mydata['paternalGreatGrandfather2name'] = getName( $paternalGreatGrandfather2);
$mydata['maternalGreatGrandmother1'] = $maternalGreatGrandmother1;
$mydata['maternalGreatGrandfather1'] = $maternalGreatGrandfather1;
$mydata['maternalGreatGrandmother2'] = $maternalGreatGrandmother2;
$mydata['maternalGreatGrandfather2'] = $maternalGreatGrandfather2;
$mydata['paternalGreatGrandmother1'] = $paternalGreatGrandmother1;
$mydata['paternalGreatGrandfather1'] = $paternalGreatGrandfather1;
$mydata['paternalGreatGrandmother2'] = $paternalGreatGrandmother2;
$mydata['paternalGreatGrandfather2'] = $paternalGreatGrandfather2;
$mydata['maternalGrandmothername'] = getName( $maternalGrandmother);
$mydata['maternalGrandfathername'] = getName( $maternalGrandfather);
$mydata['paternalGrandmothername'] = getName( $paternalGrandmother);
$mydata['paternalGrandfathername'] = getName( $paternalGrandfather);
$mydata['maternalGrandmother'] = $maternalGrandmother;
$mydata['maternalGrandfather'] = $maternalGrandfather;
$mydata['paternalGrandmother'] = $paternalGrandmother;
$mydata['paternalGrandfather'] = $paternalGrandfather;
$mydata['mothername'] = getName( $mother);
$mydata['fathername'] = getName( $father);
$mydata['mother'] = $mother;
$mydata['father'] = $father;
$mydata['name'] = getName( $ID);
$mydata['none'] = NONE;
$mydata['link'] = ( $db->getItem( "sex", $ID) == "M") ? "blueLink" : "pinkLink"; 
// First get brothers and sisters details
$brothers = $db->getBrothers( $ID);

$sisters = $db->getSisters( $ID);
$siblingsList = array( );
if ( sizeof( $brothers) > 0 || sizeof( $sisters) > 0) {
    $siblingArrays = array( $brothers, $sisters);

    foreach ( $siblingArrays as $curSiblingArray) {
        /*
           if ( $curSiblingArray == $brothers ) {
           $siblingLinkClass = "blueLink";
           $siblingType = '1';
           } else {
           $siblingLinkClass = "pinkLink";
           $siblingType = '2';
           }
           */
        foreach ( $curSiblingArray as $sibling) {
            $sex = $db->getItem( "sex", $sibling);
            if ( $sex == 'F')
                $siblingLinkClass = "pinkLink";
            else
                $siblingLinkClass = "blueLink"; 
            // check for 1/2 brothers and sisters
            $siblingFatherID = $db->getFather( $sibling);
            $siblingMotherID = $db->getMother( $sibling);

            if ( $father != $siblingFatherID || $mother != $siblingMotherID) {
                $halfString = "&frac12;";
            } else {
                $halfString = '';
            } 
            $siblingsList[$siblingType][$sibling]->name = getName( $sibling);
            $siblingsList[$siblingType][$sibling]->linkclass = $siblingLinkClass;
            $siblingsList[$siblingType][$sibling]->halfstring = $halfString;
        } 
    } 
} 
$mydata['siblingcnt'] = sizeof( $siblingsList);
$smarty->assign( "siblingsList", $siblingsList); 
// Now get Spouse Information
$spouses = $db->getSpouses( $ID); 
// determine the sex of this person, assume spouses are opposite sex
$sex = $db->getItem( "sex", $ID);
$spouseSex = strtolower( $db->reverseGender( $sex));
$spouseLinkClass = ( strtolower( $spouseSex) == "m") ? "blueLink" : "pinkLink";
$mydata['spousecnt'] = sizeof( $spouses);
foreach ( $spouses as $spouse) {
    $spouseList[$spouse]->name = getName( $spouse);
    $spouseList[$spouse]->linkclass = $spouseLinkClass;
} 
$smarty->assign( "spouseList", $spouseList); 
// Now take details fo children
$sons = $db->getChildren( $ID, "M");
$daughters = $db->getChildren( $ID, "F");

foreach ( $sons as $son) {
    $childrenList['1'][$son]->name = getName( $son);
    $childrenList['1'][$son]->linkclass = "blueLink";
} 

foreach ( $daughters as $daughter) {
    $childrenList['2'][$daughter]->name = getName( $daughter);
    $childrenList['2'][$daughter]->linkclass = "pinkLink";
} 

$allChildren = array_merge( $sons, $daughters);
$mydata['childrencnt'] = sizeof( $allChildren);
$smarty->assign( "childrenList", $childrenList); 
// Now look for GrandChildren details
$mydata['grandchildrenExist'] = false;

foreach ( $allChildren as $child) {
    $grandSons = $db->getChildren( $child, "M");
    $grandDaughters = $db->getChildren( $child, "F");

    foreach ( $grandSons as $grandSon) {
        $grandchildrenList['1'][$grandSon]->name = getName( $grandSon);
        $grandchildrenList['1'][$grandSon]->linkclass = "blueLink";
        $mydata['grandchildrenExist'] = true;
    } 

    foreach ( $grandDaughters as $grandDaughter) {
        $grandchildrenList['2'][$grandDaughter]->name = getName( $grandDaughter);
        $grandchildrenList['2'][$grandDaughter]->linkclass = "pinkLink";
        $mydata['grandchildrenExist'] = true;
    } 
} 

$smarty->assign( "grandchildrenList", $grandchildrenList); 
// Now look for Nephews and Nieces details
$siblings = array_merge( $brothers, $sisters);

$allNieces = array( );
$allNephews = array( );

foreach ( $siblings as $sibling) {
    // get all the children of this sibling
    $nieces = $db->getChildren( $sibling, "F");

    $nephews = $db->getChildren( $sibling, "M");

    $allNieces = array_merge( $allNieces, $nieces);
    $allNephews = array_merge( $allNephews, $nephews);
} 
// loop through all the nephews...
foreach ( $allNephews as $nephew) {
    $niecesnephewsList['1'][$nephew]->name = getName( $nephew);
    $niecesnephewsList['1'][$nephew]->linkclass = "blueLink";
} 
// loop through all the nieces...
foreach ( $allNieces as $niece) {
    $niecesnephewsList['2'][$niece]->name = getName( $niece);
    $niecesnephewsList['2'][$niece]->linkclass = "pinkLink";
} 

$mydata['niecesnephewscnt'] = sizeof( $niecesnephewsList);
$smarty->assign( "niecesnephewsList", $niecesnephewsList); 
// Now time for Uncles and Aunts details
$uncles1 = $db->getBrothers( $mother);
$aunts1 = $db->getSisters( $father);

$uncles2 = $db->getBrothers( $father);
$aunts2 = $db->getSisters( $mother);

if ( !is_array( $uncles1))
    $uncles1 = array( );
if ( !is_array( $uncles2))
    $uncles2 = array( );

$uncles = array_merge( $uncles1, $uncles2);

if ( !is_array( $aunts1))
    $aunts1 = array( );
if ( !is_array( $aunts2))
    $aunts2 = array( );

$aunts = array_merge( $aunts1, $aunts2);

$newAunts = array( );
$newUncles = array( );

foreach ( $uncles as $uncle) {
    if ( $uncle)
        $newAunts = array_merge( $db->getSpouses( $uncle), $newAunts);
} 

foreach ( $aunts as $aunt) {
    if ( $aunt)
        $newUncles = array_merge( $db->getSpouses( $aunt), $newUncles);
} 

$uncles = array_merge( $uncles, $newUncles);
$aunts = array_merge( $aunts, $newAunts);

reset( $uncles);
reset( $aunts);

foreach ( $uncles as $uncle) {
    $temp = getName( $uncle);
    $sex = $db->getIndexItem( 'sex', $uncle);
    if ( trim( $temp) != "" && $temp != UNDEFINED) {
        $unclesauntsList['1'][$uncle]->name = $temp;
        if ( $sex == 'M')
            $unclesauntsList['1'][$uncle]->linkclass = "blueLink";
        else
            $unclesauntsList['1'][$uncle]->linkclass = "pinkLink";
    } 
} 

/*
   foreach( $aunts as $aunt )
   {
   $temp = getName( $aunt );
   $sex = $db->getIndexItem('sex', $aunt);
   if ( trim( $temp ) != "" && $temp != UNDEFINED )
   {
   $unclesauntsList['2'][$aunt]->name = $temp;
   if ($sex == 'F')
   $unclesauntsList['2'][$aunt]->linkclass = "pinkLink";
   else $unclesauntsList['2'][$uncle]->linkclass = "blueLink";
   }
   }
   */

$unclesaunts = @array_unique( $unclesauntsList);

$mydata['unclesauntscnt'] = sizeof( $unclesauntsList);

$smarty->assign( "unclesauntsList", $unclesauntsList); 
// Now look for cousins details
$auntsAndUncles = array_merge( $aunts, $uncles); 
// loop through all aunts and uncles, and get their children
$femaleCousins = array( );
$maleCousins = array( );

if ( sizeof( $auntsAndUncles) > 0) {
    /* Loop through each uncle and aunt to get cousins  */
    foreach ( $auntsAndUncles as $person) {
        $sons = $db->getChildren( $person, "M");
        $daughters = $db->getChildren( $person, "F");

        $femaleCousins = array_merge( $femaleCousins, $daughters);
        $maleCousins = array_merge( $maleCousins, $sons);
    } 
    // remove duplicates
    $femaleCousins = array_unique( $femaleCousins);
    $maleCousins = array_unique( $maleCousins);

    foreach ( $maleCousins as $cousin) {
        $cousinsList['1'][$cousin]->name = getName( $cousin);
        $cousinsList['1'][$cousin]->linkclass = "blueLink";
    } 

    foreach ( $femaleCousins as $cousin) {
        $cousinsList['2'][$cousin]->name = getName( $cousin);
        $cousinsList['2'][$cousin]->linkclass = "pinkLink";
    } 
} 
$mydata['cousinscnt'] = sizeof( $maleCousins) + sizeof( $femaleCousins);
$smarty->assign( "cousinsList", $cousinsList); 
// Now prepare Great Great Grand Parents List
// loop through greatGrandparents array, getting parents of each
$greatGreatGrandmothers = array( );
$greatGreatGrandfathers = array( );
$mothers = array( );
$fathers = array( );
is_array( $greatGrandparents) ? "" : $greatGrandparents = array( );
foreach ( $greatGrandparents as $gp) {
    $mothers = $db->getParent( $gp, "wife");
    $fathers = $db->getParent( $gp, "husb");

    /*$greatGreatGrandmothers = array_merge( $mothers, $greatGreatGrandmothers );
       $greatGreatGrandfathers = array_merge( $fathers, $greatGreatGrandfathers );
       */
    $greatGreatGrandmothers[] = $mothers;
    $greatGreatGrandfathers[] = $fathers;
} 
// loop throguh all great great grandparents, getting name of each...
foreach ( $greatGreatGrandfathers as $gf) {
    $gpName = getName( $gf);

    if ( $gpName != UNDEFINED) {
        $greatgreatgpList['1'][$gf]->name = $gpName;
        $greatgreatgpList['1'][$gf]->linkclass = "blueLink";
        $mydata['greatgreatgpExists'] = true;
    } 
} 

foreach ( $greatGreatGrandmothers as $gm) {
    $gpName = getName( $gm);

    if ( $gpName != UNDEFINED) {
        $greatgreatgpList['2'][$gm]->name = $gpName;
        $greatgreatgpList['2'][$gm]->linkclass = "pinkLink";
        $mydata['greatgreatgpExists'] = true;
    } 
} 

$smarty->assign( "greatgreatgpList", $greatgreatgpList);
$smarty->assign( "mydata", $mydata); 
// # Get the page we want to display
$smarty->assign( 'rendered_page', $smarty->fetch( 'generations.tpl')); 
// # Display the page.
$smarty->display( 'index.tpl');

?>