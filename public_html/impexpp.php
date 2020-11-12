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

if ( $exp == 1)
{
    $flow = 1;
}

$mydata['exp'] = $exp;
$mydata['imp'] = $imp;
$mydata['user'] = $user;
$mydata['ex'] = $ex;

	//if ( $exp != 1) 
    // # require 'templates/'.$templateID.'/tpl_header.php';
    if (isset($_SESSION['master']) || isset( $_SESSION["admin"]))
	{
        if ( $imp != 1 && $exp != 1) 
		{
            $num = @ini_get( "upload_max_filesize");
            $mydata['num'] = $num;

            if (isset($_SESSION["master"])) 
			{
                $sql = "select tree from ! group by tree order by tree";
                $r = $db->query( $sql, array( $db->blobsTable));

                if ( $r != false && $db->rowsInResult( $r) > 0) 
				{
                    for ( $i = 0;$i < $db->rowsInResult( $r);$i++) 
					{
                        $a = $db->mfa( $r);

                        $sql = "select dname from ! where username= ? order by dname";
                        $p = $db->query( $sql, array( $db->userTable, $a["tree"]));

                        if ( $p != false && $db->rowsInResult( $p) > 0) 
						{
                            $b = $db->mfa( $p);
                            $treeList[$i]->tree = $a["tree"];
                            $treeList[$i]->dname = $b["dname"];
                        } 
                    } 
					
                    $mydata['treelistcnt'] = $i;
                    $smarty->assign( "treeList", $treeList);
                } 
				else 
				{
                    $mydata['nop'] = 1;
                } 
            } 
        } 

        if ( $imp == 1) 
		{
            $fn = $_FILES["sfile"]["tmp_name"];
            $mydata['fn'] = $fn;
            $x = require 'classes/archive.php';
			
            if ( $fn != "" && $x == true && $test != 1) 
			{
                srand( make_seed( ));
                $tmpid = rand( 1, 55000); 
                // $dn = "temp/" . "epr_" . $tmpid;
                // mkdir( $dn );
                $dn = "temp";

                $sz = filesize( $fn) * 100;

                $gf = gzopen( $fn, "rb");
                $data = gzread( $gf, $sz);
                gzclose( $gf);

                $f = fopen( $dn . "/x.tar", "wb");
                fwrite( $f, $data);
                fclose( $f);

                if ( ! ( $ID > 0))
                    $ID = 0;

                $tar = new tarfile( $dn);
                $fl = $tar->extract( $data);
                $sf = 0;
                $i = 0;
				
                foreach( $fl as $k => $v) 
				{
                    $fname = $v["filename"];
                    $abspath = $dn . "/" . $fname;
                    $fname = basename( $fname);
                    $fdata = addslashes( $v["data"]);
                    $y = split( "_", $fname);
					
                    if ( $y[1] == "indip")
                        $imp_id = substr( $y[2], 0, strlen( $y[2]) - 4);

                    $sql = "select id from ! where id = ? and type='P'";
                    $r = $db->query( $sql, array( $db->blobsTable, $imp_id));

                    if ( $r != false && $db->rowsInResult( $r) < 1) 
					{
                        $t = time( );

                        $qr = "insert into ! (tree,id,type,ta) values ( ?, ?, 'P', ?)";
                        $r = $db->query( $sql, array( $db->blobsTable, $user, $imp_id, $t));
                        $new = 1;
                    } 
					else
					{
                        $new = 0;
					}

                    $t = time( );

                    $qr = "update ! set ta= ?, data= ? where id = ? and type='P'";
                    $r = $db->query( $sql, array( $db->blobsTable, $t, $fdata, $imp_id));

                    $impList[$i]->fname = $fname;
					
                    if ( $r != false) 
					{
                        $impList[$i]->impok = true;
						
                        if ( $new == 1)
                            $txt = "(creating)";
                        else
                            $txt = "(updating)";
							
                        $impList[$i]->txt = $txt;
                        $impList[$i]->size = round( strlen( $fdata) / 1024 , 2);
                    } 
					else 
					{
                        $impList[$i]->sf = 1;
                    } 
					
                    if (file_exists($abspath)) 
						unlink($abspath);
					
                    if (file_exists( $dn . "/x.tar")) 
						unlink( $dn . "/x.tar"); 
						
                    // rmdir( $dn );
                    $i++;
                } 
            } 
        } 
		elseif ( $exp == 1) 
		{
            if ( $stree != '') 
			{
                $x = require 'classes/archive.php';

                $sql = "select id,data,type from ! where tree= ? and type='P'";
                $r = $db->query( $sql, array( $db->blobsTable, $stree));

                if ( $r != false) 
				{
                    srand( make_seed( ));
                    $tmpid = rand( 1, 80000);
                    $fn = array( );

                    $tmpid = "pr_" . $tmpid;

                    mkdir( "temp/" . $tmpid);
                    $n = $db->rowsInResult( $r);
					
                    if ( $zip == 1)
					{
                        $tar = new zipfile( "temp/" . $tmpid) ;
					}
                    else
					{
                        $tar = new tarfile( "temp/" . $tmpid) ;
					}

                    if ( $n > 0) 
					{
                        for ( $i = 0;$i < $n;$i++) 
						{
                            $a = $db->mfa( $r);
							$a["data"] = stripslashes($a["data"]);

                            if ($zip == 1)
							{
                                $tar->addfile( $a["data"], $db->getGN( $db->getIndexItem( "Name", $a["id"])) . "_indip_" 
								. $a["id"] . ".jpg");
							}
                            else
							{
                                $tar->addfile( $a["data"], $db->getGN( $db->getIndexItem( "Name", $a["id"])) . "_indip_" 
								. $a["id"] . ".jpg");
							}
                        } 
                    } 

                    if ($zip == 1)
					{
                        $tar->filewrite( "temp/" . $tmpid . "/down.zip");
					}
                    else
					{
                        $tar->filewrite( "temp/" . $tmpid . "/down.tar");
					}
					
                    if ( $zip != 1) 
					{
                        $t = fopen( "temp/" . $tmpid . "/down.tar", "rb");
                        $dt = fread( $t, filesize( "temp/" . $tmpid . "/down.tar"));
                        fclose( $t);

                        $g = gzopen( "temp/" . $tmpid . "/down.tar.gz", "wb4");
                        gzwrite( $g, $dt);
                        fflush( $g);						
                        fclose( $g);

                        $gz = fopen( "temp/" . $tmpid . "/down.tar.gz", "rb");
                        $data = fread( $gz, filesize( "temp/" . $tmpid . "/down.tar.gz"));
                        fclose( $gz);
						
						//@ remove temprorary files
						unlink( "temp/" . $tmpid . "/down.tar");
						unlink( "temp/" . $tmpid . "/down.tar.gz");									
                    } 
					else 
					{
                        $gz = fopen( "temp/" . $tmpid . "/down.zip", "rb");
                        $data = fread( $gz, filesize( "temp/" . $tmpid . "/down.zip"));
                        fclose( $gz);
						
						//@ remove temprorary file
						unlink( "temp/" . $tmpid . "/down.zip");
                    } 

                    foreach ( $fn as $k => $v) 
                       unlink( $v);

                    if ($zip == 1) 
					{
                        header( "Content-Disposition: inline; filename=TreePortraits.zip");
                        header( "Content-Title: TreePortraits.zip");
                        header( "Content-Type: application/special");
                        print $data;
                    } 
					else 
					{
                        header( "Content-Disposition: inline; filename=TreePortraits.tar.gz");
                        header( "Content-Title: TreePortraits.tar.gz");
                        header( "Content-Type: application/special");
                        print $data;
                    } 
					
					//@ remove temprorary folder
                    rmdir( "temp/" . $tmpid);
					
					exit;
                } 
            } 
        } 
    } 

    $smarty->assign( "impList", $impList);
    $smarty->assign( "mydata", $mydata); 
	
    // # Get the page we want to display
    $smarty->assign( 'rendered_page', $smarty->fetch( 'impexpp.tpl')); 
	
    // # Display the page.
    $smarty->display( 'index.tpl');

    ?>