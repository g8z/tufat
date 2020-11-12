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
 * for reliability or accuracy - in other words, you use this script
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

class File {
	/**
 * * Creates a file at $path (the file name is part of the path), with its contents being $data
 */
	function writeFile ( $path, $data) {
      $fp = fopen($path, "wb"); 
      fwrite($fp , $data);
      fclose($fp);
	}

	/**
 * * A binary-safe file reader
 */
	function getFile( $filename)
	{
		$fp = @fopen( $filename, "rb");
		while ( !feof( $fp)) {
			$temp .= fread( $fp, 4096);
		}

		$arr = explode( CRLF, $temp);
		// if the binary file reading did not split the lines (e.g. if WinXP) use built-in file function instead
		if ( sizeof( $arr) == 1) {
			$lines = file( $filename);
			if ( sizeof( $lines) > 1) {
				return $lines;
			}
		}
		return $arr;
	}
  
	/**
 * * Deletes the file specified by $path
 */
	function deleteFile ( $path) {
		if ( !file_exists ( $path)) {
			print $this->mytrans( 'The file or file path $path is invalid! File NOT removed.') . "<br>\n";
			exit;
		} else {
			unlink ( $path);
		}
	}
}