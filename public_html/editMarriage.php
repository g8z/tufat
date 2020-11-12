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
 * Vijay Nair                                 4/Apr/2004
 */

require 'config.php';

/*
   flow chart of the logic behind editing/creating family and individual records

   variables passed into this script:
   $famc -> if this new individual is a child of an existing person, then $famc denotes the family record ID
   $fams -> if this new individual is a spouse of an existing person, then $fams denotes the family record ID

   */

if ( isset( $_SESSION['read_only'])) {
    header( "Location: load.php?ID=$org");
    exit;
}
// connect to the database
$db = new FamilyDatabase( );

if ( $submitForm) {
    // store these values for later retrieval
    if ( $sdivorced) {
        $db->doDivorce( $sp1, $sp2);
        $_POST["DIV"] = 'Y';
    } else {
        $db->doUnDivorce( $sp1, $sp2);
    }

    unset( $sdivorced);
    $_POST["sdivorced"] = '';

    if ( $db->getItem( "SEX", $sp1) == "F") {
        $t = $sp2;
        $sp2 = $sp1;
        $sp1 = $t;
    }
    $husbandID = $sp1;
    $wifeID = $sp2;

    $_POST['husb'] = "@I" . $sp1 . "@";
    $_POST['wife'] = "@I" . $sp2 . "@";
    // routine to add all the form data to the database...
    $gedcom = $db->buildGEDCOM( $_POST);
    // restore the husb/wife variables back to original states
    $_POST['husb'] = $husbandID;
    $_POST['wife'] = $wifeID;
    // get the children from the chil field
    $children = $db->getChildrenOfFamily( $ID);

    $gedcom = $gedcom . CRLF;

    foreach ( $children as $k => $v) {
        if ( !empty( $v))
            $gedcom .= "1 CHIL @I" . $v . "@" . CRLF;
    }

    $gedcom = trim( $gedcom);
    // add the variables to the database fields. this function has no effect if not using mysql
    // update the individualsTable with the new raw gedcom info (or write a new file if not using mysql)
    $txt = $db->getFamGeds( $ID);

    $gedcom = $db->procGedcom( $gedcom, $txt);
    $db->updateGEDCOM( $gedcom, $ID, 'F');
    // load the record of the first spouse
    $ID = $sp1;
    header( "Location: load.php?ID=$org");
    exit;
}
// if $new variable is set, then this is the very first person in the entire family tree
elseif ( !$personType) {
    // we are editing a person's record, so we need to populate the $vars[] array with the data
    $edit = true;
    // get the ID value of the marriage that has sp1 and sp2 as its spouses
    $ID = $db->getMarriageID( $sp1, $sp2);
    // get the raw GEDCOM string for this person's record, given the person's ID
    $gedcom = $db->getGEDCOM( 'F', $ID);
    // extract GEDCOM info into variables that we can use to populate our form fields
    $vars = $db->extractGEDCOM( $gedcom);

    $vars['marr_agnc'] = $db->getItem( 'marr_agnc', $ID);
}
// #require 'templates/'.$templateID.'/tpl_header.php';
$mydata['sp1'] = $sp1;

list( $name, $sex) = $db->getItems( array( "name", "sex"), $sp1);
$name = str_replace( "/", "", $name);
$mydata['sp1name'] = $name;
$mydata['sp1sex'] = strtolower( $sex);
list( $name, $sex) = $db->getItems( array( "name", "sex"), $sp2);
$name = str_replace( "/", "", $name);
$mydata['sp2name'] = $name;
$mydata['sp2sex'] = strtolower( $sex);
$mydata['sp2'] = $sp2;
$smarty->assign( "vars", $vars);
$mydata['eg1'] = $lnag["(e.g. Man and Wife, Unmarried Partnership)"];
$mydata['marr_date'] = $db->dateField( "marr_date", $vars["marr_date"]);
$ID = $db->getMarriageID( $sp1, $sp2);
$mydata['ID'] = $ID;
if ( $db->areDivorced( $sp1, $sp2))
    $mydata['divorcedOK'] = ' checked ';
else
    $mydata['divorcedOK'] = '';
// only show left navigation information is we are NOT on the login page!
$smarty->assign( "mydata", $mydata);
// # Get the page we want to display
$smarty->assign( 'rendered_page', $smarty->fetch( 'editMarriage.tpl'));
// # Display the page.
$smarty->display( 'index.tpl');

?>
