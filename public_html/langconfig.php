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

require_once( "config.php");

// Added 2006/05/21 Pat K. <cicada@edencomputing.com>
// Get our LANG setting from $GET or $POST, else use the default.
if ( ( $_GET["slang"]) || ( $_POST["slang"])) {
  $smarty->clear_all_cache(); 
  $smarty->clear_compiled_templates();
    if ( $_POST["slang"])
        $x = split( "_lll_", $_POST["slang"]);
    // GET always overrides POST for language settings,
    // for debugging, or so the user might specify the lang directly.
    if ( $_GET["slang"])
        $x = split( "_lll_", $_GET["slang"]);
    // assume default if we didn't get anything
    if ( !( $x[0] && $x[1]))
        $x = split( "_lll_", SLANG);
} elseif ( $_SESSION['slang'] && $_SESSION['encType']) {
    $x[0] = $_SESSION['slang'];
    $x[1] = $_SESSION['encType'];
} else {
    $x = split( "_lll_", SLANG);
}

if ( count( $x) == 2) {
    $vslang = $x[0];
    $encType = $x[1];
}

if ( $_SESSION["slang"] != '' || $vslang != '') {
    if ( $vslang != '')
        $mylang = strtolower( $vslang);
    else
        $mylang = strtolower( $_SESSION["slang"]);
} else
    $mylang = 'english';

if ( $_SESSION["encType"] != '' || $encType != '') {
    if ( $encType != '')
        $lencType = $encType;
    else
        $lencType = $_SESSION["encType"];
} else
    $lencType = 'utf-8';

$_SESSION["slang"] = $mylang;
$_SESSION["encType"] = $lencType;
// Added 2006/05/22 Pat K <cicada@edencomputing.com>
/* assign variables to be displayed in the language form  */
if ( ALLOWMULTIPLELANGUAGES) {
    $smarty->assign( 'allowMultipleLanguages', ALLOWMULTIPLELANGUAGES);
    $db = new FamilyDatabase( );
    $sql = "select lower(l) as l,lower(enc) as enc from ! group by lower(l),lower(enc) order by l";
    $r = $db->query( $sql, array( $db->langTable));

    if ( $r != false) {
        $n = $db->rowsInResult( $r);
        if ( $n > 1) {
            for ( $i = 0; $i < $db->rowsInResult( $r); $i++) {
                $a = $db->mfa( $r);

                $vsla = $a['l'];
                $vsenc = $a['enc'];
                $sql = "select l from {TBL_LANG} where lower(l) =".$db->dbh->quote($vsla)." and enc =".$db->dbh->quote($vsenc);
                $vsla = $db->getRow($sql);
               // $a['l'] = $vsla['l'];
                $key = $a['l'] . '_lll_' . $a['enc'];
                $val = ucfirst( $a['l']) . ' (' . strtoupper( $a['enc']) . ')';
								
                $languages[strtolower( "$key")] = $val;
            }
        } else {
            $key = SLANG;
            list( $la, $en) = split( "_lll_", $key);
            $val = ucfirst( $la) . " (" . strtoupper( $en) . ")";
            $languages["$key"] = $val;
        }
    }

    $smarty->assign( 'languages', $languages);
}
$vsla = null;
$vsec = null;
$smarty->assign( 'selectedLanguage', $_SESSION["slang"] . " (" . strtoupper( $_SESSION['encType']) . ")");
$smarty->assign( 'slang', $_SESSION["slang"] . "_lll_" . $_SESSION["encType"]);

$mydata['sla'] = $_SESSION["slang"];
$sla = $mydata['sla'];
$mydata['senc'] = $_SESSION["encType"];
$senc = $mydata['senc'];
header( "Content-Type: text/html; charset=" . $lencType . ";");
// # Modified by Pat K. <cicada@edencomputing.com> 2006/05/19
// #  lanugage prefilter -
// #  Tags like ##..## become {function_call value="data key"} before the
// #    template is compiled.  This happens transparently to the user.
require_once( "libs/smarty/plugins/prefilter.langfilter.php");
require_once( "libs/smarty/plugins/outputfilter.langfilter.php");
$smarty->register_prefilter( "smarty_prefilter_langfilter");
$smarty->register_outputfilter( "smarty_outputfilter_langfilter");
$tag_regex = '/\x23\x23(.+?)\x23\x23/s';

?>
