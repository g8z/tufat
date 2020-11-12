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
 * for reliability or accuracy - in other words, you use this script
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
//@ini_set( 'upload_max_filesize', 1024);

$ID = (int)$_REQUEST['ID'];
if ( isset( $_SESSION['read_only']) && $_SESSION["my_rec"] != $ID) {
    header( "Location: load.php?ID=$ID");
    exit;
}

if ( isset($_FILES['bio']) || isset($_FILES['portrait'])) {
    if (isset($_FILES['bio']) && $_FILES['bio'])  {
        $fileName = $_FILES['bio']['tmp_name'];
        if ( !empty( $fileName) && $fileName != "none")  {
            if ( getimagesize( $fileName))  {
                // we don't want to allow pictures to be uploaded as BIOs
                $file_error .= $db->mytrans( "##File Type Error##") . ":  ";
                $file_error .= $db->mytrans( "##This file appears to be an image, biographies may only be text or PDF files.##");
            } else {
                // magic quotes will not add slashes here
                $bioData = addslashes(file_get_contents($fileName));
                $db->addBio( $ID, $bioData);
            };
        }
		
        if ($_FILES['bio']['error'] == 1){ 
            $file_error .= $db->mytrans( "##File Upload Error##") . ":  ";
        } elseif ($_FILES['bio']['error'] == 2) {
            $file_error .= $db->mytrans( "##The bio file exceeds the maximum allowed file size.##");
        }
    }

    if (isset($_FILES['portrait']) && $_FILES['portrait'])  {
        $fileName = $_FILES['portrait']['tmp_name']; 
        if ( !empty( $fileName) && $fileName != "none" && filesize( $fileName) )  {
            if ( !getimagesize( $fileName))  {
                $file_error .= $db->mytrans( "##File Type Error##") . ":  ";
                $file_error .= $db->mytrans( "##The uploaded file does not appear to be a valid image.##");
            } else {
                // magic quotes will not add slashes here
                if (ini_get('magic_quotes_gpc')) {
                  $portraitData = fread( @fopen( $fileName, "rb"), filesize( $fileName));
                } else {
                  $portraitData = addslashes( fread( @fopen( $fileName, "rb"), filesize( $fileName)));
                }
                $db->addPortrait($ID, $portraitData);
            }
        }
		
        if ($_FILES['portrait']['error'] == 1){ 
            $file_error .= $db->mytrans( "##File Upload Error##") . ":  ";
        } elseif ($_FILES['portrait']['error'] == 2) {
            $file_error .= $db->mytrans( "##The bio file exceeds the maximum allowed file size.##");
        }
    }

    $db->updateIndex($ID);

    if ( $file_error ) {
        $smarty->assign( 'msg', $file_error);
    } else {
        require( 'load.php');
        exit;
    };
} else {
	if( isset( $MAX_FILE_SIZE ) ) {
		$file_error .= $db->mytrans( "##File Upload Error##") . ":  ";
		$file_error .= $db->mytrans( "##The portrait image exceeds the maximum allowed file size## ".ini_get('upload_max_filesize'));	
    	$smarty->assign( 'msg', $file_error);	
	}
}

// check for the existence of /temp folder, and check its permissions
if (!is_dir(TEMP_DIR)) {
    $mydata['nodir'] = true;
} elseif (!is_writable(TEMP_DIR) || !is_readable(TEMP_DIR)) {
    $mydata['dirnotok'] = true;
}

$name = $db->getItem( "name", $ID);
$name = str_replace( "/", "", $name);
$hidden = $db->getItem( "HIDE", $ID);

if ( $hidden == "Yes") {
    $name = $db->obstr( $name, 1);
}

if ( ANIMALPEDIGREE) {
    $name = $db->changeBrack( $name);
}

$mydata['ID'] = $ID;
$mydata['name'] = $name;
$mydata['animalPedigree'] = ANIMALPEDIGREE;

$mydata['imgmsg'] = MAXPORTRAITWIDTH . '  x  ' . MAXPORTRAITHEIGHT;

$smarty->assign( "mydata", $mydata);
$smarty->assign( "loadphp", true);

// # Get the page we want to display
$smarty->assign( 'rendered_page', $smarty->fetch( 'loadmenu.tpl') . $smarty->fetch( 'upload.tpl'));

// # Display the page.
$smarty->display( 'index.tpl');