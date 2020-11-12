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

@ini_set( "max_execution_time", 600);

$ftree = $user;

if ( $tu == 1) {
    $fname = $_FILES["gedcom"]["tmp_name"];
} else if ( $tu == 2) {
    $fname = $_FILES["gedcompart"]["tmp_name"];
} else if ( $tu == 3) {
    $fname = $_POST["gedcomftp"];
}

if ( $tu == 3 && $compressed != 1) 
{
    $fi = fopen( $fname, "r");
	
    if ( $fi == false) 
	{
        header( "Location:import.php?ne=1");
        exit;
    } 
	else 
	{
        $data = "";
		
        while ( !feof( $fi)) 
		{
            $data .= fread( $fi, 8192);
        }
		
        fclose( $fi);

        srand( make_seed( ));
        $xi = rand( 1, 50000);

        $fi2 = fopen( "temp/" . "ftpfile_" . $xi, "wb");
        fwrite( $fi2, $data);
        fclose( $fi2);

        $line = fileX( "temp/" . "ftpfile_" . $xi);
    }
}

if ( $compressed == 1) 
{
    $x = require 'classes/archive.php';
	
    if ( $x == true) 
	{
        srand( make_seed( ));
        $tmpid = rand( 1, 55000);
        $dn = "temp/" . "iged_" . $tmpid;
        mkdir( $dn);

        $sz = filesize( $fname) * 10;
        $gf = gzopen( $fname, "rb");
        $data = gzread( $gf, $sz);
        gzclose( $gf);

        $f = fopen( $dn . "/x.tar", "wb");
        fwrite( $f, $data);
        fclose( $f);

        if ( ! ( $ID > 0))
		{
            $ID = 0;
        }

        $tar = new tarfile( $dn);
        $fl = $tar->extract( $data);

        if ( 1 != 1) 
		{
            $message = $db->mytrans( "##err_extr_gzip##");
            $smarty->assign( 'error_message', $message);
            $smarty->assign( 'rendered_page', $smarty->fetch( 'display_error_message.tpl'));
            $smarty->display( 'index.tpl');
            exit;
        } 
		else 
		{
            if ( count( $fl) > 1) 
			{
                $message = $db->mytrans( "##The tar.gz appears to contains more than one file.##");
                $smarty->assign( 'error_message', $message);
                $smarty->assign( 'rendered_page', $smarty->fetch( 'display_error_message.tpl'));
                $smarty->display( 'index.tpl');
                exit;
            }

            foreach( $fl as $k => $v) 
			{
                $fnm2 = $v["filename"];
                $abspath = $dn . "/" . $fnm2;

                fwrite( fopen( $abspath, "wb"), $v["data"]);

                $line = fileX( $abspath);
            }
        }
    }
} 
else if ( $tu != 3) 
{
    $line = fileX( $fname);	
}

//@ coded by Andrew
//@ ged file verifier
$individuals = array();
$indi_id = 1;
$fams_id = 1;

foreach($line as $id => $data)
{
	//@ utf-8 conversion
	$line[$id] = utf8_encode($line[$id]);

	list($lvl, $typ, $dat) = split(" ", trim($line[$id]), 3);
		
	if( preg_match("/@([A-Z0-9-]+)@/",$typ,$matches) )
	{
		switch( $dat )
		{
		 case "INDI":
				$individuals[ $matches[0] ] = "@I".($indi_id++)."@";
				break;
				
		 case "FAM":
				$individuals[ $matches[0] ] = "@F".($indi_id++)."@";
				break;
		}
	}
}

foreach($line as $id => $data)
{
	if( preg_match("/@([A-Z0-9-]+)@/",$data,$matches) )
	{
		$line[$id] = preg_replace("/@[A-Z0-9-]+@/",$individuals[ $matches[0] ],$data);
	}
}

global $qr;

$indi_id = '';
$fam_id = '';
$sour_id = '';
$even_id = '';
$notik_id = '';
$l = 0;
$inBirt = false;
$inDeat = false;

if ( ( $tu != 2 || $_POST["fr"] != 1 || ( $tu == 2 && $compressed = 1)) && $append != "on") 
{
    // empty the current tree, unless we're appending the data
    $sql = "delete from ! where tree= ?";
    $db->query( $sql, array( $db->gedcomTable, $ftree));

    $sql = "delete from ! where tree=?";
    $db->query( $sql, array( $db->indexTable, $ftree));

    $sql = "delete from ! where tree= ?";
    $db->query( $sql, array( $db->blobsTable, $ftree));

    $sql = "delete from ! where kd = '1' and tree= ?";
    $db->query( $sql, array( $db->famgalTable, $ftree));
}

if ( $append == 'on') 
{
    // if we're appending the data, get the indexes so we can start at max+1
    $sql = "SELECT max(?) as max FROM ! WHERE tree=? AND type=? AND tag=? AND level = '0'";

    $rh = $db->mfa( $db->query( $sql, array( 'id', $db->gedcomTable, $ftree, 'I', 'INDI')));
    $max_indi = $rs['max'];

    $rh = $db->mfa( $db->query( $sql, array( 'id', $db->gedcomTable, $ftree, 'F', 'FAM')));
    $max_fam = $rh['max'];

    $rh = $db->mfa( $db->query( $sql, array( 'hid', $db->gedcomTable, $ftree, 'I', 'SOUR')));
    $max_sour['I'] = $rh['max'];

    $rh = $db->mfa( $db->query( $sql, array( 'hid', $db->gedcomTable, $ftree, 'F', 'SOUR')));
    $max_sour['F'] = $rh['max'];

    $rh = $db->mfa( $db->query( $sql, array( 'hid', $db->gedcomTable, $ftree, 'I', 'NREF')));
    $max_note['I'] = $rh['max'];

    $rh = $db->mfa( $db->query( $sql, array( 'hid', $db->gedcomTable, $ftree, 'F', 'NREF')));
    $max_note['F'] = $rh['max'];

    $rh = $db->mfa( $db->query( $sql, array( 'hid', $db->gedcomTable, $ftree, 'I', 'SREF')));
    $max_cit['I'] = $rh['max'];

    $rh = $db->mfa( $db->query( $sql, array( 'hid', $db->gedcomTable, $ftree, 'F', 'SREF')));
    $max_cit['F'] = $rh['max'];

    $rh = $db->mfa( $db->query( $sql, array( 'hid', $db->gedcomTable, $ftree, 'I', 'EVEN')));
    $max_even['I'] = $rh['max'];

    $rh = $db->mfa( $db->query( $sql, array( 'hid', $db->gedcomTable, $ftree, 'F', 'EVEN')));
    $max_even['F'] = $rh['max'];
}



// Divide the array into individual records
$key = 0;

$ged_array = array( );

while ( $key < count( $line)) 
{
    $my_array = array( );

    list( $lvl, $typ, $dat) = split( " ", trim( $line[$key]), 3);
	
    // test conditions here
    switch ( $lvl) 
	{
        case "0":
            // this is a new record, find out what kind and add it to the array
            if ( preg_match( "/@([A-Z])([0-9]+)@/", $typ, $matches)) 
			{
                $tid = $matches[2];

                switch ( $dat) 
				{
                    case "INDI":
                        $indi_id = $tid + $max_indi;
                        $recType = "INDI";
                        // new individual
                        break;

                    case "FAM":
                        $fam_id = $tid + $max_fam;
                        $recType = "FAM";
                        // new family
                        break;

                    case "SOUR":
                        $sour_id = $tid + $max_sour[$mrtyp];
                        $recType = "SOUR";
                        // new source
                        break;

                    case "EVEN":
                        $even_id = $tid + $max_even[$mrtyp];
                        $recType = "EVEN";
                        // new event
                        break;

                    default:
                        // we can't handle other record types, so lets not use them
                        $recType = '';
                        break;
                }
            }
            break;

        default:
            switch ( $recType) 
			{
                case "INDI":
                    $tid = $indi_id;
                    break;
                case "FAM":
                    $tid = $fam_id;
                    break;
                case "SOUR":
                    $tid = $sour_id;
                    break;
                case "EVEN":
                    $tid = $even_id;
                    break;
            }

            if ( $lvl > 0 && $tid && $recType) 
			{
                $ged_array[$recType][$tid][] = array( 'lvl' => $lvl, 'tag' => $typ, 'data' => $dat);
            }
            break;
    }
    $key++;
}


$rsql = "INSERT INTO ! ( id, tree, type, tag, level, data, hid ) VALUES ( ?, ?, ?, ?, ?, ?, ? )";

foreach( $ged_array as $recType => $val) 
{
    $citID = 0;
    $noteID = 0;
		
    foreach( $val as $id => $rec) 
	{
        switch ($recType) 
		{
            // record header
            case "INDI":
                $rh = $db->query( $rsql, array( $db->gedcomTable, $id, $ftree, 'I', 'INDI', '0', '@I' . $id . '@', $id));
                $mrtyp = 'I';
                break;
            case "FAM":
                $rh = $db->query( $rsql, array( $db->gedcomTable, $id, $ftree, 'F', 'FAM', '0', '@F' . $id . '@', $id));
                $mrtyp = 'F';
                break;
            case "SOUR":
                $rh = $db->query( $rsql, array( $db->gedcomTable, $id, $ftree, 'S', 'SOUR', '0', '@S' . $id . '@', $id));
                $mrtyp = 'S';
                break;
        }

        foreach( $rec as $rid => $record) 
		{
            // record data
            if ( $record['data'] == null) 
			{
                $record['data'] = " ";
            }
						
            // mysql doesn't like null data, make it a blank string
            switch ( $recType) 
			{
                case 'FAM':
                    if ( preg_match( '/@I([0-9]+)@/', $record['data'], $mtch)) 
					{
                        $recID = $mtch[1];
                    }
                    break;

                case 'INDI':
                    if ( $mrtyp != 'C') 
					{
                        $recID = $id;
                    }
                    if ( !$mrtyp) 
					{
                        $mrtyp = 'I';
                    }
                    break;

                default:
                    // $recID = $id;
                    break;
            }

            switch ($record['tag']) 
			{
                case "SOUR":
                    // within an INDI or FAM record, this is a citation and not really a source
                    if ( !$citID) 
					{
                        $citID = $max_cit[$mrtyp] + 1;
                    } 
					else 
					{
                        $citID++;
                    } ;
					
                    $recID = $citID;
					
                    if ( preg_match( '/@S([0-9]+)@/', $record['data'], $mtch)) 
					{
                        $rh = $db->query( $rsql, array( $db->gedcomTable, $id, $ftree, $mrtyp, 'SREF', '0', $mtch[1], $recID));
                        $mrtyp = 'C';
                    }
					
                    break;
					
                case "QUAY":
                    // end of a CIT record
                    $mrtyp = '';
                    break;

                case "NAME":
				
					// Check if name exist, if no set default value
					if( trim($db->removeFam($record['data'])) == '' )
						$record['data'] = $db->mytrans( '##(no name given)##');
				
                    // make sure we add surname records if they don't exist in the GEDCOM file
                    $rh = $db->query( $rsql, array( $db->gedcomTable, $id, $ftree, $mrtyp, 'NAME', '1', $record['data'], $recID));
                    $surn_exists = 0;
					$hide_exists = 0;
					
                    foreach( $rec as $r_id => $r_rec) 
					{
                        if ( $r_rec['tag'] == 'SURN' && strlen( $r_rec['data']) > 0) 
						{
                            $rh = $db->query( $rsql, array( $db->gedcomTable, $id, $ftree, $mrtyp, 
															'SURN', '1', $r_rec['data'], $recID));
                            $surn_exists = true;
                        }
						
                        if ( $r_rec['tag'] == 'HIDE' && strlen( $r_rec['data']) > 0) 
						{
                            //$rh = $db->query( $rsql, array( $db->gedcomTable, $id, $ftree, $mrtyp, 
							//								'HIDE', '1', $r_rec['data'], $recID));
                            $hide_exists = true;
                        }
                    }
					
					// ----					
					
                    if (!$surn_exists) 
					{
						//print "'SURN':".$record['data']."<br>";
						
                        if ( ( $x = $db->getBrackName( $record['data'])) != false) 
						{
						//	print "'SURN':$x<br>";
							
                            $rh = $db->query( $rsql, array( $db->gedcomTable, $id, $ftree, $mrtyp, 'SURN', '1', $x, $recID));
                        }
                    }
					
                    if (!$hide_exists) 
					{
                        $rh = $db->query( $rsql, array( $db->gedcomTable, $id, $ftree, $mrtyp, 'HIDE', '1', "No", $recID));
                    }
					
                    break;
					
                // case "NOTE":
                // if (!$noteID) { $noteID = $max_note[$mrtyp] + 1; } else { $noteID++; };
                // $recID = $noteID;
                // $rh = $db->query($rsql, array($db->gedcomTable, $id, $ftree, $mrtyp, 'NOTE', '1', $record['data'], $noteID));
                // break;
				
                default:
					//print $record['tag']."<br>";
				
                    $rh = $db->query( $rsql, array( $db->gedcomTable, $id, $ftree, $mrtyp,
													$record['tag'], $record['lvl'], $record['data'], $recID));

                    break;
            }
        }		
    }
}


// now filling index table
$sql = "select id from {$db->gedcomTable} where tree= ".$db->dbh->quote($user)." and type='I' and level='0' group by id";
$r = $db->getCol($sql);

if ( count( $r) > 0) 
{
    foreach( $r as $id) 
	{
        $db->updateIndex( $id);
    }
}

if ( $tu == 3) 
{
    unlink( "temp/" . "ftpfile_" . $xi);
}

if ( $tu == 2 && $compressed != 1) 
{
    header( "Location: import.php?fr=1");
    exit;
} 
else 
{
    header( "Location: index.php");
    exit;
}

// fileX by aTufa - extend PHP function file() for Macintosh
// file() works fine *only* for Linux and Windows due EOF convention:
// Macintosh puts: CR (means \r)
// Windows   puts: CR+LF (means \r\n)
// Linux     puts: LF (means \n)

function fileX( $filen)
{
    $lines = file( $filen);

    if ( count( $lines) > 1) 
	{
        // Windows or Linux
        return $lines;
    } 
	else 
	{
        // Macintosh
        $fp = fopen( $filen, "r");
        $data = fread( $fp, filesize( $filen));
        fclose( $fp);
        $lines = explode( "\r", $data);
        return $lines;
    }
}

?>

