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
 * Modified to incorporate Smarty Template Processor
 * Vijay Nair                            24/Apr/2004
 */

require_once 'config.php';

$flow = 1;

$fp = $db->lgetFamilyP( $ID);

if ( $fp == false) 
{
    $fp = $db->lgetFamilyC( $ID);
}

$mydata['semail'] = $semail;

if ( $indi == 1) 
{
    $sql = "select * from ! where indi = '1' and sid = ? and id = ?";
    $r = $db->query( $sql, array( $db->famgalTable, $ID, $mid));
} 
elseif ( $kd == 1) 
{
    $sql = "select * from ! where kd = '1' and id = ?";
    $r = $db->query( $sql, array( $db->famgalTable, $mid));
} 
else 
{
    $sql = "select * from ! where fid = ? and id = ?";
    $r = $db->query( $sql, array( $db->famgalTable, $fp, $mid));
}

if ( $r != false && $db->rowsInResult( $r) > 0)
{
    $a = $db->mfa( $r);
    $mydata['recscnt'] = $db->rowsInResult( $r);

    if ( $semail != '') 
	{
        if ( !preg_match( '/^[a-zA-Z0-9\._-]+\@(\[?)[a-zA-Z0-9\-\.]+/', $semail))
		{
            $mydata['invalidemail'] = true;
        } 
		else 
		{
            $mydata['invalidemail'] = false;

            $sbody = stripslashes( $sbody);

            if (USE_SMTP_CLASS) 
			{
                // use smtp class for mail sending # jrpi:02.02.06
                require 'smtp.class.php';

                $r_e = send_mail( $sfrom, $sfrom, $semail, $ssubj, $sbody, "", $a["name"], "image/jpeg", $a["data"]);
            }
			
            if ( $r_e != "sent" or USE_SMTP_CLASS == 0) 
			{
                // use php mail() function for mail sending
                $sep = "SEPARATORTUFAT0";
                $sep2 = "SEPARATORTUFAT2";

                $headers = "From: $sfrom\r\n";

                $body = $headers . "MIME-Version: 1.0\r\n";
                $body .= "Content-Type: multipart/mixed; ";
                $body .= "boundary = \"$sep\"\r\n";

                $body .= "--$sep\r\n";
                $body .= "Content-Type: text/plain\r\n\r\n";
                $body .= "$sbody\r\n\r\n";
                $body .= "--$sep\r\n";
                $body .= "Content-Type: image/jpeg; name=\"" . $a["name"] . "\";\r\n";
                $body .= "Content-Transfer-Encoding: base64\r\n";
                $body .= "Content-Disposition: attachment;\r\n\r\n";

                $attch = base64_encode( $a["data"]);

                $body .= $attch;
                $body .= "\r\n\r\n--$sep--\r\n";

                $r_p = mail( $semail, $ssubj, '', $body);
            }

            if ( $r_e == "sent" or $r_p) 
			{
                $mydata['emailsentmsg'] = true;
            } 
			else 
			{
                $mydata['emailsentmsg'] = false;
                $mydata['email_error'] = $r_e;
            }
        }
    } 
	else 
	{
        $mydata['msgsize'] = " " . round( strlen( $a["data"]) / 1024, 2) . " ";
        $mydata['title'] = stripslashes( $a["title"]);
        $mydata['descr'] = stripslashes( $a["descr"]);
        $mydata['fp'] = $fp;
        $mydata['mid'] = $mid;

        $mydata['indi'] = $indi ? $indi : "0";
    }
	
    $mydata['wn'] = $wn;
}
//$mydata['imgh'] = IMG_H;
$mydata['ID'] = $ID;
$mydata['kd'] = $kd;
$mydata['fp'] = $fp;

$smarty->assign( "mydata", $mydata);

// # Get the page we want to display
$smarty->assign( 'rendered_page', $smarty->fetch( 'viewfamgalim.tpl'));

// # Display the page.
$smarty->display( 'index.tpl');
// #require 'templates/'.$templateID.'/tpl_footer.php';
?>