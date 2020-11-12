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

if ( isset( $_SESSION['read_only'])) 
{
    header( "Location: load.php?ID=$ID");
    exit;
}

// process the submitted form...
if ( $submit && $gedcom) 
{
    // delete all old data for the $user tree
    $db->removeTree( $user);

    if ( $gedcom && sizeof( $gedcom) > 0) 
	{
        $fileName = $gedcom['tmp_name'];

        if ( !empty( $fileName) && $fileName != 'none') 
		{
            $gedcomData = addslashes( fread( fopen( $fileName, 'rb'), filesize( $fileName)));
        }
    }
	
    // use a binary-safe file reader to read lines into an array...
    $filePath = 'temp/gedcom_temp.ged';
    File::writeFile( $filePath, $gedcomData);

    $lines = File::getFile( $filePath);

    $numLines = sizeof( $lines);

    for ( $i = 0; $i < $numLines; $i++) 
	{
        if ( !stristr( $lines[$i], 'SUBM') && ( stristr( $lines[$i], '0 @') || stristr( $lines[$i], '0 TRLR'))) 
		{ 
			// new individual record, or the end of the file
			// strip out all non-numeric characters from this line
			$words = explode( '@', $lines[$i]);
            $ID = $words[1];

            $table = ( stristr( $ID, 'f')) ? $familiesTable : $individualsTable;
            // a familyrecord
            // remove non-numeric characters from  $ID
            $ID = ereg_replace( '[A-Z,a-z]', '', $ID);

            if ( empty( $ID)) 
			{
                continue;
            }
			
            // now we have a valid ID and we know what kind of record it is, so get actual GEDCOM data and insert into DB
            $info = '';

            for ( $j = $i + 1; $j < sizeof( $lines); $j++) 
			{
                // we've hit a new record, so save data and break loop
                if ( !stristr( $lines[$i], 'SUBM') && ( stristr( $lines[$j], '0 @') || stristr( $lines[$j], '0 TRLR'))) 
				{
                    $allinfo .= $info . CRLF;
                    $i = $j - 1;
                    break;
                }
                $info .= $lines[$j] . CRLF;
            }
        }
    }
	
    // loop through the imported data, 'fixing' any errors what are non-TUFaT compliant
    // e.g. a family records with children but no husband/wife information
    require(VIEWSCRIPT);
    exit;
}

if ( $ne == 1) 
{
    $smarty->assign( 'mytrans', $mytrans);
    $mydata['ne'] = $ne;
    $smarty->assign( 'mydata', $mydata);
    $smarty->display( 'import.tpl');
    require 'templates/' . $templateID . '/tpl_footer.php';
    exit;
}

$mydata['name'] = $db->getItem( 'name', $ID);
$mydata['ID'] = $ID;
$mydata['user'] = $user;
$fr = ( $fr == 1) ? 1 : 2;
$mydata['fr'] = $fr;

$smarty->assign( 'mytrans', $mytrans);
$smarty->assign( 'mydata', $mydata);

$smarty->assign( 'rendered_page', $smarty->fetch( 'import.tpl'));
$smarty->display( 'index.tpl');

exit;

?>

