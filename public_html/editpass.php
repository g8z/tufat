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

global $a;

if ( isset( $_SESSION['admin'])) {
    if ( $_POST['updatik'] == 1) {
        if ( $db->validEmail( $_POST["email"])) {
            if ( strlen( $pass) > 0 && strlen( $apass) > 0 && strlen( $rpass) > 0 && trim($suser) != '') {
                $sql = "update ! set email= ?,  password= ?, read_only_password= ?, admin_password = ?, aname= ?, dname= ? where username= ?";
                $r = $db->query( $sql, array( $db->userTable, $email, $pass, $rpass, $apass, check_slash( $aname), check_slash( $dname), $user));

                if ( $solduser != $suser) {
                    $sql = "select username from ! where username= ?";
                    $p = $db->query( $sql, array( $db->userTable, $suser));
                    if ( $p != false) {
                        if ( $db->rowsInResult( $p) > 0) {
                            $l = 1;
                            $mydata['updmsg'] = $db->mytrans( '##The Username $suser is already being used. Please choose another username and try again.##');
                        } elseif ( $db->rowsInResult( $p) == 0) {
                            $sql = "update ! set username = ? where username= ?";
                            $r1 = $db->query( $sql, array( $db->userTable, $suser, $solduser));

                            $sql = "update ! set tree = ? where tree= ?";
                            $r2 = $db->query( $sql, array( $db->gedcomTable, $suser, $solduser));

                            if ( $r1 != false && $r2 != false) {
                                $mydata['updmsg'] = $db->mytrans( '##The username $solduser has been renamed to $suser##');
                                $user = $suser;
                                $_SESSION['user'] = $suser;
                            } else {
                                $mydata['updmsg'] = $db->mytrans( '##There has been some problem with renaming username $solduser to $suser##');
                            }
                            $l = 1;
                        }
                    }
                }

                if ( $r != false && $l == 0)
                    $mydata['updmsg'] = $db->mytrans( '##Information successfully updated##');
            }
        } else {
            $mydata['updmsg'] = $db->mytrans( '##E-Mail not valid##');
        }
    }

    $sql = "select * from ! where username= ?";
    $r = $db->query( $sql, array( $db->userTable, $user));
    if ( $r != false && $db->rowsInResult( $r) > 0) {
        $a = $db->mfa( $r);
    }
    $a['aname'] = stripslashes( $a['aname']);
    $a['dname'] = stripslashes( $a['dname']);
    $smarty->assign( 'mydata', $mydata);
    $smarty->assign( 'a', $a);
    $smarty->assign( 'crossTreeSearch', $crossTreeSearch);
}
// # Get the page we want to display
$smarty->assign( 'rendered_page', $smarty->fetch( 'editpass.tpl'));
// # Display the page.
$smarty->display( 'index.tpl');

?>