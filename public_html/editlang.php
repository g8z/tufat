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

$recsperpage = 10;

if ( isset( $_SESSION['master'])) {
    $default_lang = strtolower( SLANG);
    // Default available languages/encodings
    $s['Unicode (UTF-8)'] = 'UTF-8';
    $s['Unicode (UTF-16)'] = 'UTF-16';
    $s['Unicode (UTF-32)'] = 'UTF-32';
    $s['Cyrillic (WINDOWS-1251)'] = 'windows-1251';
    $s['Cyrillic (KOI8-R)'] = 'KOI8-R';
    $s['Cyrillic (ISO-8859-5)'] = 'ISO-8859-5';
    $s['Cyrillic (KOI8-U)'] = "KOI8-U";
    $s['Central European (ISO-8859-2)'] = "ISO-8859-2";
    $s['Central European (ISO-8859-16)'] = "ISO-8859-16";
    $s['Central European (windows-1250)'] = "windows-1250";
    $s['Southern European (ISO-8859-3)'] = "ISO-8859-3";
    $s['Baltic (ISO-8859-13)'] = "ISO-8859-13";
    $s['Baltic (ISO-8859-4)'] = "ISO-8859-4";
    $s['Baltic (windows-1257)'] = "windows-1257";
    $s['Japanese (euc-jp)'] = 'euc-jp';
    $s['Japanese (Shift-JIS)'] = 'Shift-JIS';
    $s['Japanese (ISO-2022-JP)'] = 'ISO-2022-JP';
    $s['Chinese (big5)'] = "big5";
    $s['Chinese (big5-HKSCS)'] = "big5-HKSCS";
    $s['Chinese (ISO-2022-CN)'] = "ISO-2022-CN";
    $s['Thai (iso-8859-11)'] = "iso-8859-11";
    $s['Arabic (windows-1256)'] = "windows-1256";
    $s['Arabic (ISO-8859-6)'] = "ISO-8859-6";
    $s['Esperanto/South European (ISO-8859-3)'] = "ISO-8859-3";
    $s['Western/Estonian/Latvian (ISO-8859-1)'] = "ISO-8859-1";
    $s['Western (ISO-8859-15)'] = "ISO-8859-15";
    $s['Western (windows-1252)'] = "windows-1252";
    $s['Greek (ISO-8859-7)'] = "ISO-8859-7";
    $s['Greek (windows-1253)'] = "windows-1253";
    $s['Turkish (ISO-8859-9)'] = "ISO-8859-9";
    $s['Turkish (windows-1254)'] = "windows-1254";
    $s['Vietnamese (windows-1258)'] = "windows-1258";
    $s['Nordic (ISO-8859-10)'] = "ISO-8859-10";
    $s['Nordic (windows-sami-2)'] = "windows-sami-2";
    $s['Korean (EUC-KR)'] = "EUC-KR";
    $s['French/German (windows-850)'] = "windows-850";
    $s['Hebrew (windows-1255)'] = "windows-1255";
    $s['Hebrew (ISO-8859-8)'] = "ISO-8859-8";
    $s['Celtic (ISO-8859-14)'] = "ISO-8859-14";
    $s['Malay/Danish/Finnish/Norwegian/Dutch (windows-1252)'] = "windows-1252";

    ksort( $s);
    foreach ( $s as $key => $val) {
        $langsList[$val] = $key;
    }
    $smarty->assign( "langsList", $langsList);

    if ( $_REQUEST['elang']) {
        $_SESSION['elang'] = $_REQUEST['elang'];
    }
    if ( !isset( $_SESSION['elang'])) {
        $elang = $_SESSION['slang'] . "_lll_" . $_SESSION['encType'];
        $_SESSION['elang'] = $elang;
    } else {
        $elang = $_SESSION['elang'];
    }
    // Test $elang to make sure it's valid, use the default if not
    if ( !array_key_exists( $elang, $languages)) {
        $elang = SLANG;
    }

    $smarty->assign( 'elang', strtolower( $elang));
    if ( !$_REQUEST['newlang']) {
        $smarty->assign( 'showform', true);
    }

    $reqvars = null;
    // never allow passthrough on these
    $no_transfer = array( "add", "removelang", "action", "page", "submit");
    foreach ( $_REQUEST as $key => $val) {
        if ( !in_array( $key, $no_transfer)) {
            $reqvars[$key] = $val;
        }
    }
    $smarty->assign( 'reqvars', $reqvars);
    // # Uncomment this if you use templates in paths other than the default
    // # also uncomment the required lines in editlang.tpl
    /*if (($_REQUEST['tpl_path']) && is_dir($_REQUEST['tpl_path'])) {
       $template_path = $_REQUEST['tpl_path'];
       } else {*/
	   	   
    if ( is_dir( $_SESSION['tpl_path'])) {
        $template_path = $_SESSION['tpl_path'];
    } else {
        $template_path = TEMPLATE_DIR;
    }
    /*};*/
    $_SESSION['tpl_path'] = $template_path;

    $filelist = $language->get_files( $template_path);
    asort( $filelist);

    if ( $_REQUEST['tplfilename'] && file_exists( $template_path . $_REQUEST['tplfilename'])) {
        $template_name = $_REQUEST['tplfilename'];
    } else {
        if ( $filelist) {
            $template_name = current( $filelist);
        } else {
            $template_name = null;
        }
    }

    $smarty->assign( 'default_lang', $default_lang);
    $smarty->assign( 'filelist', $filelist);
    $smarty->assign( 'tplfilename', $template_name);
    $smarty->assign( 'template_path', $template_path);
    $smarty->assign( 'search_opts', array( 'trans' => '##translations##', 'tags' => '##tags##', 'either' => '##tags or translations##', 'both' => '##tags and translations##'));
    $smarty->assign( 'searchw_opts', array( 'thistp' => '##this template##', 'alltp' => '##all templates##'));
	
    // # Find out where to go
    // # Add a new language
    if ( $_REQUEST['add'] == '1') {
        // # do the add language thing here
        $nslang = $_REQUEST['slname'] . " (" . $_REQUEST['slenc'] . ")";
        // # Add the language to the database -
        if ( isset( $_SESSION['master'])) {
            if ( $slname != '' && $slenc != '') {
                $slname = strtolower( $slname);
                $slenc = strtolower( $slenc);
                $sql = "select enc from ! where lcase(enc) = ? and lcase(l) = ?";
                $r = $db->query( $sql, array( $db->langTable, $slenc, $slname));
                if ( $r != false) {
                    $mydata['langsok'] = 1;
                    $errors = "encoding available!";
                    if ( strstr( $slname, "_lll_")) {
                        $errors = "Bad format in language name, please do not use the characters _lll_ when naming the new language.";
                    } else {
                        $sql = "select w,m from ! where  lcase(l) = 'english' and lcase(enc) = 'utf-8'";
                        $p = $db->query( $sql, array( $db->langTable));
                        if ( $p != false) {
                            $n = ( $db->rowsInResult( $p));
                            $mydata['reccnt'] = $n;
                            for ( $i = 0; $i < $n; $i++) {
                                $b = $db->mfa( $p);
                                $mw = $b["w"];
                                $mm = $b["m"];
                                // # Due to the way the new language management works, we don't want to copy the traslations to the
                                // # new language records.  We will leave them blank so that they can be exported and imported properly
                                $sql = "insert into ! (w,m,l,enc) values ( ?, ?, ?, ?)";
                                $rr = $db->query( $sql, array( $db->langTable, $mw, '', $slname, $slenc));
                                if ( !$rr) {
                                    $errors = "Sorry, there was a problem adding the language ( $slname [$slenc] ) to the database.";
                                } else {
                                    $errors = "Language $nslang added successfully";
                                }
                            }
                        }
                    }
                    $elang = $slname . "_lll_" . $slenc;
                    $smarty->assign( 'elang', $elang);
                } else {
                    $errors = "##It appears an invalid encoding type was specified ( $slenc ) - Please try again.##";
                }
            } else {
                $errors = "##Language name or encoding not specified!##";
            }
        } else {
            $errors = "##You must be logged in as Master to add or remove languages##";
        }
        // reset the language menus
        require 'langconfig.php';
        $smarty->assign( 'msg', $errors);
    } elseif ( $_REQUEST['newlang'] == 1) {
        // We want to display the menu to add a new language
        // # Get the page we want to display
        $smarty->assign( 'rendered_page', $smarty->fetch( 'add_lang.tpl'));
    } elseif ( $_REQUEST['removelang']) {
        if ( list( $rlang, $renc) = split( '_lll_', $_REQUEST['removelang'])) {
            // got a (probably) valid language name, lets remove it from the database if it exists
            $sql = 'SELECT id FROM ! WHERE lcase(l) = ? AND lcase(enc) = ?';
            $res = $db->query( $sql, array( $db->langTable, strtolower( $rlang), strtolower( $renc)));
            if ( $res != false) {
                $sql = "DELETE FROM ! WHERE lcase(l) = ? AND lcase(enc) = ?";
                $res = $db->query( $sql, array( $db->langTable, strtolower( $rlang), strtolower( $renc)));
                if ( $res) {
                    $errors = "Language ( $rlang [$renc] ) successfully removed.";
                } else {
                    $errors = "Sorry, there was a problem removing the language ( $rlang [$renc] ) from the database.";
                }
            } else {
                $errors = "Sorry, that language was not found in the database.  Unable to remove.";
            }
        }

        $languages = null;
        $smarty->assign( 'languages', $languages);
        // resets the language menus
        require( 'langconfig.php');
        $smarty->assign( 'msg', $errors);
    }

    if ( !$_REQUEST['newlang'] && !$_REQUEST['removelang']) {
        if ( $_REQUEST['action'] == "updatelang") {
            // # Do the updating as necessary
            // # When updating tags, be sure to replace the text in the templates and not just the database
            // # If a tag doesn't exist in the database, always create it
            // # if it does and the text is different, update the database
            // # if it's the same, ignore it.
            $read_sql = "SELECT * FROM {$db->langTable} WHERE w = ".$db->dbh->quote($tag).
			" AND l = ".$db->dbh->quote($ulang)." AND enc =".$db->dbh->quote($uenc);
            $in_sql = "INSERT INTO ! SET w = ?, m = ?, l = ?, enc = ?";
            $upd_sql = "UPDATE ! SET w=?,m=? WHERE w=? AND l=? AND enc=?";

            if ( $_REQUEST['tag_vals']) {
                $tag_vals = $_REQUEST['tag_vals'];
                $smarty->assign( 'tag_vals', $tag_vals);
            }
            if ( $_REQUEST['tags']) {
                $tags = $_REQUEST['tags'];
                $smarty->assign( 'tags', $tags);
            }
            if ( $_REQUEST['tpltags_orig']) {
                $tpltags_orig = $_REQUEST['tpltags_orig'];
                $smarty->assign( 'tpltags_orig', $tpltags_orig);
            }

            list( $ulang, $uenc) = split( "_lll_", $elang);
            foreach ( $tag_vals as $tag => $value) {
                if ( $value != $tpltags_orig[$tag]) {
                    $res = $db->getRow($read_sql);
                    if ( !$res) {
                        // tag wasn't found, lets add it
                        $ins = $db->query( $in_sql, array( $db->langTable, $tags[$tag], $value, $ulang, $uenc));
                        if ( !$ins) {
                            $error = "COULD NOT INSERT!!";
                            break;
                        }
                    } else {
                        // found it, lets update it
                        $upd = $db->query( $upd_sql, array( $db->langTable, $tags[$tag], $value, $tag, $ulang, $uenc));
                        if ( !$upd) {
                            $error = "COULT NOT UPDATE!!";
                            break;
                        }
                    }
                }
            }
            // $smarty->assign('show_langdebug', TRUE);
            $smarty->assign( 'msg', "##Language tags successfully updated##");
            $tpl_tags = $language->get_langtags( $template_path . $template_name, split( '_lll_', $elang));
        } elseif ( $_REQUEST["search"] == "1") {
            if ( trim( $_REQUEST['searchstr'])) {
                $smarty->assign( 'searchstr', $_REQUEST['searchstr']);

                if ( $_REQUEST['search_for'] && $_REQUEST['search_where']) {
                    $smarty->assign( 'search_for', $_REQUEST['search_for']);
                    $smarty->assign( 'search_where', $_REQUEST['search_where']);

                    $search_reg = "/" . $_REQUEST['searchstr'] . "/i";
                    if ( $_REQUEST['search_where'] == 'alltp') {
                        // ########################################################
                        // # Search 'all templates' - rather, search the database for all tags matching our language
                        $q1 = "SELECT * FROM ! WHERE INSTR( LCASE(%lookfor%), LCASE(?) ) AND LCASE(l)=? AND LCASE(enc)=?";
                        $q2 = "SELECT * FROM ! WHERE ( INSTR( LCASE(w), LCASE(?) ) > 0  %bool%  INSTR( LCASE(m), LCASE(?) ) > 0 ) AND LCASE(l)=? AND LCASE(enc)=?";
                        $thislang = split( '_lll_', $elang);
                        if ( $_REQUEST['search_for'] == 'tags') {
                            $q1 = preg_replace( '/%lookfor%/', 'w', $q1);
                            $rs = $db->query( $q1, array( $db->langTable, trim( $_REQUEST['searchstr']), $thislang[0], $thislang[1]));
                        } elseif ( $_REQUEST['search_for'] == 'trans') {
                            $q1 = preg_replace( '/%lookfor%/', 'm', $q1);
                            $rs = $db->query( $q1, array( $db->langTable, trim( $_REQUEST['searchstr']), $thislang[0], $thislang[1]));
                        } elseif ( $_REQUEST['search_for'] == 'both') {
                            $q2 = preg_replace( '/%bool%/', 'AND', $q2);
                            $rs = $db->query( $q2, array( $db->langTable, trim( $_REQUEST['searchstr']), trim( $_REQUEST['searchstr']), $thislang[0], $thislang[1]));
                        } elseif ( $_REQUEST['search_for'] == 'either') {
                            $q2 = preg_replace( '/%bool%/', 'OR', $q2);
                            $rs = $db->query( $q2, array( $db->langTable, trim( $_REQUEST['searchstr']), trim( $_REQUEST['searchstr']), $thislang[0], $thislang[1]));
                        }
                        if ( $rs->result) {
                            while ( $row = $db->mfa( $rs)) {
                                $tpl_tags[$row['w']] = stripslashes( $row['m']);
                            }
                        }
                        $smarty->assign( 'searchres', $rs);
                    } elseif ( $_REQUEST['search_where'] == 'thistp') {
                        // ########################################################
                        // # Search the tags in the current template only
                        $these_tags = $language->get_langtags( $template_path . $template_name, split( '_lll_', $elang));
                        foreach ( $these_tags as $tag => $trans) {
                            if ( $_REQUEST['search_for'] == 'tags') {
                                if ( preg_match( $search_reg, $tag)) {
                                    $tpl_tags[$tag] = $trans;
                                }
                            } elseif ( $_REQUEST['search_for'] == 'trans') {
                                if ( preg_match( $search_reg, $trans)) {
                                    $tpl_tags[$tag] = $trans;
                                }
                            } elseif ( $_REQUEST['search_for'] == 'both') {
                                if ( preg_match( $search_reg, $tag) || preg_match( $search_reg, $trans)) {
                                    $tpl_tags[$tag] = $trans;
                                }
                            }
                        }
                    }
                } else {
                    $smarty->assign( 'msg', '##Invalid search parameter.##');
                }
            } else {
                $smarty->assign( 'msg', '##Invalid search string##');
            }
        } else {
            $tpl_tags = $language->get_langtags( $template_path . $template_name, split( '_lll_', $elang));
        }
        // # If w're not adding / removing or updating a language, we either got here fresh or are editing the language
        // # array of lang tags, sorted alphabetically (after the ##'s)
        $thispage = $_REQUEST['page'];
        if ( $thispage <= 0) {
            $thispage = 1;
        }
        $totalpages = ceil( count( $tpl_tags) / $recsperpage);
        $total_records = count( $tpl_tags);

        if ( ( $thispage <= $totalpages) && ( $totalpages > 1)) {
            // # If we're paginating, display the proper page.
            if ( $thispage > 1) {
                $prev_page = $thispage - 1;
            } else {
                $prev_page = false;
            }
            if ( $thispage < $totalpages) {
                $next_page = $thispage + 1;
            } else {
                $next_page = false;
            }

            $i = 0;
            $i_low = ( ( $thispage - 1) * $recsperpage);
            $i_high = $i_low + ( $recsperpage - 1);
            if ( $i_high > ( $total_records - 1)) {
                $i_high = $total_records - 1;
            }

            foreach ( $tpl_tags as $key => $val) {
                if ( ( $i < count( $tpl_tags)) && ( $i >= $i_low) && ( $i <= $i_high)) {
                    $new_tpltags[$key] = $val;
                }
                $i++;
            }
            $tpl_tags = $new_tpltags;

            $smarty->assign( 'recs_low', $i_low + 1);
            $smarty->assign( 'recs_high', $i_high + 1);
            $smarty->assign( 'thispage', $thispage);
            $smarty->assign( 'pagenext', $next_page);
            $smarty->assign( 'pageprev', $prev_page);
            $smarty->assign( 'totalpages', $totalpages);
        }

        if ( $_SESSION["treeName"]) {
            $smarty->assign( 'treeName', $_SESSION['treeName']);
        } else {
            $smarty->assign( 'treeName', 'The Ultimate Family Tree');
        }
    }

    $smarty->assign( 'recs_total', $total_records);
    $smarty->assign( 'tpl_tags', $tpl_tags);
    // # Get the page we want to display
    if ( !$_REQUEST['newlang'])
        $smarty->assign( 'rendered_page', $smarty->fetch( 'editlang.tpl'));
    // # Re-include the navMenu incase the languages changed
    require( 'navMenu.php');
    $smarty->assign( 'navMenu', $smarty->fetch( 'navMenu.tpl'));
    // # Display the page.
    $smarty->display( 'index.tpl');
} else {
    header( 'Location: login.php');
}