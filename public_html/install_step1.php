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

echo( '        <font class="subtitle"><b>Step 1 - Checking Basic Requirements</b></font>
        <br /><br />
        <table width="100%" class="normal">
                <tr>
                        <td>
                                TUFaT requires at least PHP version 4.1.2. RedHat Linux users must have at least version 4.3.0. Also, the "temp" folder and config.php file must be writable. Remember: if you see any messages in red in the "Status" column, you should correct those errors before continuing.
                        </td>
                </tr>
        </table>
        <br />
        <table class="normal" width="100%">
                <tr bgcolor="#cccccc">
                        <td><b>Current task</b></td>
                        <td><b>Status</b></td>
                </tr>
                <tr>
                        <td>1. Checking PHP Version >= 4.1.2</td>
                        <td>');

if ( $mydata["phpok"] == "FAIL") {
    echo( "<font color='" . $mydata["rR"] . "'>Version " . $mydata["phpversion"] . " Detected - FAIL</font> ");
} else {
    echo( "<font color='" . $mydata["rG"] . "'>Version " . $mydata["phpversion"] . " Detected - OK</font> ");
} 
echo( "</td>
        </tr>
                <tr>
                        <td>2. Checking for RedHat Linux</td>
                        <td>");

if ( $mydata["linux"] == "FAIL") {
    echo( "<font color='" . $mydata["rR"] . "'>RedHat Linux with PHP Version " . $mydata["phpversion"] . " Detected - FAIL</font>");
} else {
    if ( $mydata["redhat"] == "not detected") {
        echo( "<font color='" . $mydata["rG"] . "'>RedHat Linux not detected - OK</font>");
    } else {
        echo( "<font color='" . $mydata["rG"] . "'>RedHat Linux detected with PHP version " . $mydata["phpversion"] . " - OK</font>");
    } 
} 
echo( "</td>
        </tr>
        <tr>
                <td>
                        3. Checking for writable temporary folder
                </td>
                <td>");

if ( $mydata["temp"] == "FAIL") {
    echo( "<font color='" . $mydata["rR"] . "'>Temporary folder 'temp' is not writable - FAIL</font>");
} else {
    echo( "<font color='" . $mydata["rG"] . "'>Temporary folder writable - OK</font>");
} 
echo( "</td>
        </tr>
        <tr>
                <td>
                        4. Checking for writable cache folder
                </td>
                <td>");

if ( $mydata["cache"] == "FAIL") {
    echo( "<font color='" . $mydata["rR"] . "'>Cache folder 'cache' is not writable - FAIL</font>");
} else {
    echo( "<font color='" . $mydata["rG"] . "'>Cache folder is writable - OK</font>");
} 
echo( "</td>
        </tr>
        <tr>
                <td>
                        5. Checking for writable Smarty Compile folder
                </td>
                <td>");

if ( $mydata["templates_c"] == "FAIL") {
    echo( "<font color='" . $mydata["rR"] . "'>Smarty Compile folder 'templates_c' is not writable - FAIL</font>");
} else {
    echo( "<font color='" . $mydata["rG"] . "'>Smarty Compile folder is writable - OK</font>");
} 
echo( "</td>
        </tr>
        <tr>
                <td>6. Checking for writable config file</td>
                <td>");

if ( $mydata["config"] == "FAIL") {
    echo( "<font color='" . $mydata["rR"] . "'>Config file not writable - FAIL</font>");
} else {
    echo( "<font color='" . $mydata["rG"] . "'>Config file writable - OK</font>");
} 

echo( "</td>
        </tr>
        <tr>
                <td>7. Checking for PHP Sessions </td>
                <td>");

if ( $mydata["sesok"] == "FAIL") {
    echo( "<font color='" . $mydata["rR"] . "'> Sessions does not work - FAIL </font>");
} else {
    echo( "<font color='" . $mydata["rG"] . "'> Sessions works fine - OK </font>");
} 
echo( "</td>
        </tr>");

echo( "</table>

<br />
<form action='install.php' method='post'>
<p>
        <input type='button' onclick='location.href=" . '"install.php?step=1"' . "' value='Refresh' />");
if ( $mydata['ok'] == 1) {
    echo( "<input type='hidden' name='step' value='2' />
                        <input type='submit' value='Step 2 >' />");
} 
echo( "</p></form>");

?>