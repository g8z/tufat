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
if ( strstr( strtolower( $_SERVER['HTTP_USER_AGENT']), "opera")) $operastuff = 'marginheight="0" marginwidth="0"';
print <<<END
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<style type="text/css">
.leftRepeat {
 background-image: url(images/$templateID/leftRepeat.gif?$currtime);
 background-repeat: repeat-y;
 background-position: left top;
 margin-top: 0px;
 margin-left: 0px;
 margin-right: 0px;

}
.topRepeat {
 background-image: url(images/$templateID/topRepeat.gif?$currtime);
 background-repeat: repeat-x;
 background-position: left top;
}
</style>

<script src="javascript.js" type="text/javascript"></script>

<title> $treeName </title>

<link href="templates/$templateID/tufat.css" rel="stylesheet" type="text/css" />

</head>
<body class="leftRepeat" $operastuff>
   <table width="100%" class="topRepeat" cellpadding="0" cellspacing="0" border="0"><tr><td valign="top">
      <table border="0" cellpadding="0" cellspacing="0" width="100%">
         <tr>
            <td colspan="2" valign="top"><img name="Tufat_r1_c1" src="images/$templateID/Tufat_r1_c1.gif" border="0" alt="" /></td>
         </tr>
         <tr>
                        <td valign='top' width="1">
                        <img name='Tufat_r2_c1' src="images/$templateID/Tufat_r2_c1.gif" border="0" style="position:absolute; left:0px; top:89px;" alt="" />
                 </td>
                 <td valign="top" width="99%">
                 <div id="main001" style="position:absolute; width: 632px; z-index:1; left:166px; top: 100px;">

END;

?>