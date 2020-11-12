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

/*
   Picture Gallery - individuals kd=0, indi=1, sid=ID,  fid=0
   - Family      kd=0, indi=0, sid=0, fid=fp
   - Tree        kd=1, indi=2, sid=ID, fid=0
   */

header( "Content-Type: image/png");
header( "Content-Disposition: inline");

if ( $type == "portrait") {
    $data = $db->getPortrait( $ID);
}

if ( $indi != '') {
    $sql = "SELECT data FROM {$db->famgalTable} WHERE indi =".$db->dbh->quote($indi)." AND id=".$db->dbh->quote($mid)." AND tree=".$db->dbh->quote($user);

    if ( $indi == '1') {
        /* Individual gallery */
        $sql .= " AND sid=".$db->dbh->quote($ID);
    } elseif ( $indi == '0') {
        // Family Gallery
        $sql .= " AND fid=".$db->dbh->quote($fp);
    } else {
        // Tree Gallery
        $sql .= " AND kd='1'";
    }

    /* If thumbnail, select type=0  */
    if ( $tnail = 1)
        $sql .= " AND type='0'";
		
    $data = $db->getValue($sql);
}

/////////////////////////////////////////////////////////////////////
//@ image rescalling /////////////////////////////////////////////////

	$portrait = $data;
	$max_img_width = $_GET['maxw'];
	$max_img_height = $_GET['maxh'];
	
    if ( trim($portrait) )
	{
        $filePath = 'temp/' . $ID . '_img.png';

        if ( file_exists( $filePath))
            @unlink( $filePath);

        File::writefile($filePath, stripslashes($portrait) );

        $tmp_portrait = $portrait;
        $portrait = $filePath;

		//////////////////////////////////////////////////////////
        //@ get the dimensions
        list( $img_w, $img_h, $img_type) = getimagesize($filePath);
		
		$new_w = $img_w;
		$new_h = $img_h;
		
		if( isset($max_img_width) and isset($max_img_height) )
		{
			if( $img_h / $img_w >  $max_img_height / $max_img_width )
			{
				$new_h = $max_img_height;
				$new_w = (int)($max_img_height/$img_h*$img_w);			
			}
			else
			{
				$new_h = (int)($max_img_width/$img_w * $img_h);
				$new_w = $max_img_width;			
			}
			
			if( $img_h < $new_h )
				$new_h = $img_h;
				
			if( $img_w < $new_w )
				$new_w = $img_w;
		}
				
		////////////////////////////////////////////////////////////////////				
		//@ detect image type: png,jpeg,gif and resample image for lightweight
		
		   switch( $img_type )
		   {
			   case 1:       //GIF
				   $src_img = imagecreatefromstring($tmp_portrait);
				   break;
			   case 2:       //JPEG
				   $src_img = imagecreatefromstring($tmp_portrait);
				   break;
			   case 3:       //PNG
				   $src_img = imagecreatefromstring($tmp_portrait);
				   break;
			   default:
				   return FALSE;
				   break;
		   }
            $tmp_portrait = null;
		   	
		$dst_img = imageCreateTrueColor($new_w,$new_h); 
		
		imagecopyresampled($dst_img,$src_img,0,0,0,0,$new_w,$new_h,$img_w,$img_h);
				
        if ( file_exists($filePath) )
            @unlink( $filePath);
		
		imagepng($dst_img);
    }

////////////////////////////////////////////////////////////////////

?>
