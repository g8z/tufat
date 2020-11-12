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
require_once 'language.class.php';

$language = new Language;

if ( isset( $_SESSION['master'])) {
    $reqvars = null;
    $no_transfer = array( "action", "submit"); #never allow passthrough on these
    foreach ( $_REQUEST as $key => $val) {
        if ( !in_array( $key, $no_transfer)) {
            $reqvars[$key] = $val;
        } ;
    } ;
    $smarty->assign( 'reqvars', $reqvars);

    if ( ( $_REQUEST['tpl_path']) && is_dir( $_REQUEST['tpl_path'])) {
        $template_path = $_REQUEST['tpl_path'];
    } elseif ( is_dir( $_SESSION['tpl_path'])) {
        $template_path = $_SESSION['tpl_path'];
    } else {
        $template_path = TEMPLATE_DIR;
    } ;
    $_SESSION['tpl_path'] = $template_path;

    $filelist = $language->get_files( $template_path, 'tpl');
    asort( $filelist);

    if ( $_REQUEST['tplfilename'] && file_exists( $template_path . $_REQUEST['tplfilename'])) {
        $template_name = $_REQUEST['tplfilename'];
    } else {
        if ( $filelist) {
            $template_name = current( $filelist);
        } else {
            $template_name = null;
        } ;
    } ;

    if ( $_SESSION["treeName"]) {
        $smarty->assign( 'treeName', $_SESSION['treeName']);
    } else {
        $smarty->assign( 'treeName', 'The Ultimate Family Tree');
    } ;

    $filelist = $language->get_files( $template_path, 'tpl');
    asort( $filelist);

    switch ( $_REQUEST['action']) {
        case "exportlang":
            // #Make sure we got all the vars we need
            if ( $_REQUEST['exp_lang'] && $_REQUEST['exp_opts']) {
                if ( is_writable( TEMP_DIR)) {
                    $out_dir = TEMP_DIR;
                    $out_file = $_REQUEST['exp_lang'] . "_" . $_REQUEST['exp_opts'] . "_" . date( "Y-m-d_His") . ".xml";
                    $smarty->assign( 'exp_lang', $_REQUEST['exp_lang']);
                    $smarty->assign( 'exp_opts', $_REQUEST['exp_opts']);
                    $smarty->assign( 'exp_outfile', $out_file);

                    if ( $language->export_language ( $out_dir . $out_file,
                            array( 'l' => $_REQUEST['exp_lang'], 'name' => $languages[$_REQUEST['exp_lang']]),
                                $_REQUEST['exp_opts'],
                                $db)) {
                        // If the export was successful, lets tell the user
                        $msg = "##Language##&nbsp;" . $languages[$_REQUEST['exp_lang']] . "&nbsp;";
                        $msg .= "##successfully exported to##:<br />";
                        $msg .= "<tt>" . $out_dir . $out_file . "</tt>";
                    } else {
                        $msg .= "##Sorry, there was a problem exporting the language!##";
                    } ;
                } else {
                    $msg .= "\r\n<br />##The destination directory is not writable, please change the permissons and try again##";
                } ;
            } else {
                $msg .= "\r\n<br />##Invalid request, please try again##";
            } ;
            break;

        case "importlang":
            // # import an XML language file in the format <text digest="$key">$value|<!-- default value--></text>
            if ( $_REQUEST['imp_file'] && file_exists( TEMP_DIR . $_REQUEST['imp_file'])) {
                $upd_count = 0;
                $ins_count = 0;
                // #import the XML tags
                $lfile = file_get_contents( TEMP_DIR . $_REQUEST['imp_file']);
                // First thing we want to do is strip all comments
                $lfile = preg_replace( '/<!--.*?-->/sm', ' ', $lfile);
                preg_match( '/<\?xml.*encoding="([^\"]*)"\?>/', $lfile, $matches); ## get encoding
                $imp_enc = $matches[1];
                if ( preg_match( '/<language code="([^\"]*)" name="([^\"]*)">/', $lfile, $matches)) { // # get language code and name
                    $i_lang = $matches[1];
                    $i_name = $matches[2];
                    preg_match_all( '/<text digest="([^\"]+)">(.+?)<\/text>/sm', $lfile, $tag_matches);
                    $sql = "SELECT * FROM {$db->langTable} WHERE w=".$db->dbh->quote($w)." AND LCASE(l) =".$db->dbh->quote($i_lang)." AND LCASE(enc) =".$db->dbh->quote($imp_enc);
                    if ( is_array( $tag_matches)) {
                        foreach ( $tag_matches[2] as $key => $translation) {
                            if ( trim( $translation)) {
                                $w = trim( $tag_matches[1][$key]);
                                $m = addslashes( preg_replace( '/<!--.*-->/', '', trim( $translation)));
                                if ($row = $db->getRow($sql)) {
                                    // # If we have an entry for this key, lets see if it's changed
                                    if ( $m && ( $m != $row['m'])) { // # don't import blank entries, don't import entries that haven't changed
                                        $db->query( 'UPDATE ! SET m=? WHERE w=? AND LCASE(l)=? AND LCASE(enc)=?',
                                            array( $db->langTable, $m, $w, $i_lang, $imp_enc)
                                            );
                                        $upd_count++;
                                        // $msg .= "UPDATE: $w, $m, $i_lang, $imp_enc<br />";
                                    } ;
                                } else { // if we don't have en entry for this key, lets add it
                                    if ( $m) {
                                        $db->query( 'INSERT INTO ! (w,m,l,enc) values (?,?,?,?)',
                                            array( $db->langTable, $w, $m, $i_lang, $imp_enc)
                                            );
                                        // $msg .= "INSERT: $w, $m, $i_lang, $imp_enc<br />";
                                        $ins_count++;
                                    } ;
                                } ;
                            } ;
                        } ;
                    } ;
                } ;
                // #once we've updated the language database, lets re-run langconfig to get the new languages (if any)
                require( 'langconfig.php');
                $msg .= "<br />##Updating language##:&nbsp;" . $i_name . "<br />";
                if ( $upd_count) $msg .= "\n<br />##Updated## $upd_count ##language records##.";
                if ( $ins_count) $msg .= "\n<br />##Inserted## $ins_count ##new## ##language records##.";
                // $smarty->assign('imp_matches', $i_tags);
                // $smarty->assign('imp_lang', $i_lang."_lll_".$imp_enc. "--" . $i_name);
            } else {
                $msg .= "\r\n<br />##File not found, or not readable.  Please try again##";
            }
            break;

        case "update_all":
            // # If we got this, we want to scan the current template folder
            // # replacing {tufat_mytrans ... } calls to ##....## tags
            if ( is_dir( $template_path)) {
                $dp = opendir( $template_path);
                while ( $fname = readdir( $dp)) {
                    if ( preg_match( '/\.tpl$/', $fname)) {
                        if ( $language->replace_tags( $template_path . $fname)) {
                            $files_changed[] = $fname;
                        } ;
                    } ;
                } ;
                if ( count( $files_changed)) {
                    $smarty->assign( 'files_changed', $files_changed);
                    $msg .= "##All templates successfully updated##<br />\n";
                } else {
                    $msg .= "##Templates appear to be up to date, nothing changed##<br />\n";
                } ;
            } ;
            break;

        case "update_langdb":
            // # Search for ##..## tags in all templates, and update the database if the tag doesn't exist there (in the default language)
            if ( is_dir( $template_path)) {
                $dp = opendir( $template_path);
                while ( $fname = readdir( $dp)) {
                    if ( preg_match( '/\.tpl$/', $fname)) {
                        $ins_count = $language->update_langdb( $template_path . $fname, $db);
                        if ( $ins_count) {
                            $umsg .= " $ins_count ##new records inserted from## $fname <br />\n";
                        } ;
                    } ;
                } ;
                if ( $umsg) {
                    $msg .= "##Language database sucessfully updated##.";
                    /*$umsg;*/ } ;
            } else {
                $msg .= "##Template directory not found, or is not accessable.  Please check the permissions of that folder##<br />";
            } ;
            break;
    } ;
    // # array of lang tags, sorted alphabetically (after the ##'s)
    $tpl_tags = $language->get_langtags( $template_path . $template_name, array( $_SESSION['slang'], $_SESSION['encType']));
    $imp_filelist = $language->get_files( TEMP_DIR, 'xml');
    asort( $imp_filelist);

    $smarty->assign( 'imp_filelist', $imp_filelist);
    $smarty->assign( 'tpl_tag_count', count( $tpl_tags));
    $smarty->assign( 'filelist', $filelist);
    $smarty->assign( 'tpl_count', count( $filelist));
    $smarty->assign( 'tplfilename', $template_name);
    $smarty->assign( 'template_path', $template_path);
    $smarty->assign( 'msg', $msg);
    // # Get the page we want to display
    $smarty->assign( 'rendered_page', $smarty->fetch( 'editlang_adv.tpl'));
    // #Re-include the navMenu incase the languages have changed

    include( 'navMenu.php');
    $smarty->assign( 'navMenu', $smarty->fetch( 'navMenu.tpl'));

    $smarty->display( 'index.tpl');
} else {
    header( 'Location: login.php');
} ;
// #require 'templates/'.$templateID.'/tpl_footer.php';