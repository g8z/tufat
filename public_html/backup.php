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

$isBackup = true;

@ini_set( "max_execution_time", 200);

if ( !isset( $_SESSION['master'])) {
    // if not an administrator, then do not allow backup to be made
    $msg = "Only the site owner allowed to make database backups.";
    require 'error.php';
    exit;
}
// connect to the database
$db = new FamilyDatabase( );

$tableResult = $db->getValueArray( "show tables");

foreach ( $tableResult as $row) {
    $table = $row;
    if ( substr( $table, 0, strlen( MYSQLPREFIX)) == MYSQLPREFIX) {
        $sql = "SELECT * FROM ! ";
        $result = $db->query( $sql, array( $table));
        // #
        $sql = $result->dbh->last_query;

        if ( $result != false) {
            $numFields = $db->numFields( $result);
            // Checks whether the field is an integer or not
            $schema_insert = "INSERT INTO $table VALUES (";
            $current_row = 0;

            while ( $row = $db->mfr( $result)) {
                $current_row++;
                $values = array( );

                for ( $j = 0; $j < $numFields; $j++) {
                    if ( !isset( $row[$j])) {
                        $values[] = 'NULL';
                    } elseif ( $row[$j] != '') {
                        $values[] = "'" . mysql_escape_string( $row[$j]) . "'";
                    } else {
                        $values[] = "''";
                    }
                }

                $sql = $schema_insert . implode( ', ', $values) . ");\n";
                $sql1[$i++] = $sql;
                unset( $values);
            }
        }
    }
}

$len = strlen( $sql);
// suffix for download filename
$date = date( "njy");

header( "Content-disposition: filename=db_backup_$date.sql");
header( "Content-type: application/octetstream");
header( "Pragma: no-cache");
header( "Expires: 0");

foreach ( $sql1 as $s) {
    print $s;
}
unset( $sql1);

?>