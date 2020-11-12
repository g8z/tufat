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
require_once 'config.php'
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link href="templates/<?php
echo $templateID;

?>/tufat.css" rel="stylesheet" type="text/css" />
<title>Adding a Sibling</title>
</head>
<body <?php
echo( ( strstr( strtolower( $_SERVER['HTTP_USER_AGENT']), "opera")) ? "marginheight=\"0\" marginwidth=\"0\"" : "");

?> style="margin: 0px;">
<table width="200" border="0" cellspacing="0" cellpadding="10" align="left">
 <tr>
   <td class="subtitle" align="center">Adding a Sibling</td>
 </tr>
 <tr>
   <td class="normal" align="center">To add a new sibling, load the appropriate parent record, and
 then add a new child to the family.</td>
 </tr>
 <tr>
    <td align="center"><a href="#" onclick="javascript:window.close()">Close This Window</a></td>
 </tr>
</table>
</body>
</html>