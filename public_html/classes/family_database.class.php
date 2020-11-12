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

 class FamilyDatabase {
	var $fieldNameList;
	var $dbh;
  
	function FamilyDatabase() {
		// attempt to connect to the requested database
		require_once(PEAR_DIR.'MDB2.php');

		//		require_once PEAR_DIR . 'DB.php';

		$this->gedcomTable = TBL_GEDCOM;
		$this->blobsTable = TBL_BLOBS;
		$this->locksTable = TBL_LOCKS;
		$this->ilogiTable = TBL_ILOGI;
		$this->userTable = TBL_USER;
		$this->langTable = TBL_LANG;
		$this->famgalTable = TBL_FAMGAL;
		$this->ilinkTable = TBL_ILINK;
		$this->tempTable = TBL_TEMP;
		$this->indexTable = TBL_INDEX;
		$this->eventsTable = TBL_EVENTS;
		$this->searchesTable = TBL_SEARCHES;
		// Define the connection details
		$dsn = array(
		'username' => DBUSERNAME,
		'password' => DBPASSWORD,
		'hostspec' => DBHOST,
		'database' => DBNAME,
		'phptype' => DBTYPE
		);
    if (empty($dsn['phptype'])) $dsn['phptype'] = 'mysql';

		// Persistent is good to keep the connection live for MySQL
		// $dboptions = array( 'portability' => DB_PORTABILITY_ALL);
		$this->dbh = &MDB2::connect($dsn);

		// Check for any DB connection error
		if (MDB2::isError($this->dbh)) {
			global $smarty;
			$inst = 1;
			require 'need_to_install_message.php';
			exit;
		}
		$this->dbh->loadModule('Reverse', null, true);
		$this->dbh->setFetchMode(MDB2_FETCHMODE_ASSOC);
	}

	function DateCheck($indate) {
		return true;
	}

	function DateNum($dt) {
		// Returns the date converted into number yyyymmdd
		// Input date should be in the format yyyy-mm-dd or yyyy/mm/dd
		list($dtyr, $dtmth, $dtdy) = preg_split("|[-/]|", $dt);
		$dtnum = ( $dtyr * 10000) + ( $dtmth * 100) + $dtdy;
		return $dtnum;
	}

	function DateDiff($dt1, $dt2) {
		/* this function checks for two dates. If date1 is less than date2, it returns - value othterwise positive value . All dates should be in the format yyyy-mm-yy or yyyy/mm/yy	*/
		if ( $dt1 == '' || $dt2 == '') return "0";
		if ( $this->DateNum( $dt1) < $this->DateNum( $dt2)) {
			return "-1";
		} elseif ( $this->DateNum( $dt1) == $this->DateNum( $dt2)) {
			return "0";
		} else return "1";
	}

	/**
	 * * Execute a simple MySQL query.
	 */
	function query($queryString) {
		if ( TUFAT_DEBUG == 1 ) {
			print "$queryString<br>";
		}

		$args = func_get_args();
				
		//Here we attempt to interpolate parameters into the query string for compatability with old PEAR::DB
		//From now on you should do this BEFORE calling FamilyDatabase->query
		if (is_array($args[1])) 
		{
			$params = $args[1];
			unset($args[1]);
										
//			if( preg_match_all('/[\!\?]/',$args[0], $matches, PREG_OFFSET_CAPTURE) ) 
			if( preg_match_all("/[\!\?]/", $args[0], $matches, PREG_OFFSET_CAPTURE | PREG_PATTERN_ORDER) ) 
			{
				for ($i = count($matches[0])-1; $i >= 0; $i--) 
				{
					$replacement = ($matches[0][$i][0] == '!') ? array_pop($params) : $this->dbh->quote(array_pop($params));
					$args[0] = substr_replace($args[0], $replacement, $matches[0][$i][1], 1);
				}
			}
		}
		
		//if( strpos($args[0],"update") )
		//	print "$args[0]<br>";
		
		return call_user_func_array(array($this->dbh, 'query'), $args);
	}

	function mfa(&$r) {
    if (is_a($r, 'MDB2_Error')) {
			print "<b>MDB2 Error:".MDB2::errorMessage($r)."</b>";
			exit;
		}
	
		$row = &$r->fetchRow(MDB2_FETCHMODE_ASSOC);
		return $row;
	}

  /**
   * @brief function fetch row from passed result
   * @param $r mysql result
   */
	function mfr(&$r) {		
    if (is_a($r, 'MDB2_Error')) {
			print "<b>MDB2 Error:".MDB2::errorMessage($r)."</b>";
			exit;
		}
		
		$row = &$r->fetchRow(MDB2_FETCHMODE_ORDERED);
		return $row;
	}

	/**
	 * * A platform-safe email checker, but does NOT check DNS records, only email syntax.
	 */
	function validEmail($email) {
		if (!empty($email) && !is_null($email) && eregi( "^[0-9a-z]([-_.]?[0-9a-z]*)*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]*$", $email, $check)) {
			if ( strstr( $check[0], '@')) {
				return true;
			}
		}
    
		return false;
	}

	function numFields(&$result) {
		return $result->numCols();
	}

	/**
	 * * Returns the number of rows in a result set.
	 */
	function numRows($result) {
		return $result->numRows();
	}

	function rowsInResult(&$result) {
		 if( is_a($result, 'MDB2_Error') )
		 	return 0;
			
		return ($result) ? $result->numRows($result):0;
	}

	function getCol($qry, $col=0) {
		$result = &$this->dbh->queryCol($qry, null, $col);
		return (MDB2::isError($result)) ? false : $result;
	}

	/**
	 * * Return the entire first row of the result set of the query.
	 */
	function getRow($query) {
		$result = &$this->dbh->queryRow($query);
		return (MDB2::isError($result)) ? false : $result;
	}

	function getOne() {
		$args = func_get_args();
		//Here we attempt to interpolate parameters into the query string for compatability with old PEAR::DB
		//From now on you should do this BEFORE calling FamilyDatabase->query
		if (is_array($args[1])) {
			$params = $args[1];
			unset($args[1]);
			if(preg_match_all('/[\!\?]/',$args[0], $matches, PREG_OFFSET_CAPTURE | PREG_PATTERN_ORDER)) {

				for ($i = count($matches[0])-1; $i >= 0; $i--) {
					$replacement = ($matches[0][$i][0] == '!')? array_pop($params):$this->dbh->quote(array_pop($params));
					$args[0] = substr_replace($args[0], $replacement, $matches[0][$i][1], 1);
				}
			}
		}
		return call_user_func_array(array($this->dbh, 'queryOne'), $args);
	}

	/**
	 * * Return only the first item of the first row of a query.
	 */
	function getValue($query) {
		$result = &$this->dbh->queryOne($query);
		if (MDB2::isError($result)) {
			print $this->mytrans('ERROR IN: ') . $query ;
			return $result->getMessage();
		}
		return $result;
	}

	/**
	 * * Iterator.
	 */
	function next( $resultSet) {
		return $this->mfa($resultSet);
	}

	/**
	 * * Given a query to the database, returns an array of values that correspond to the query.
	 * If the query in question specifies more than one field, then the field results are concatenated
	 * in a string, with values separated by commas.
	 */

	function getValueArray($query) {
		$result = $this->query($query);
		$resultArray = Array();

		while ($row = $this->mfr($result)) {
			$resultArray [] = implode(',', $row);
		}

		return $resultArray;
	}

	function repvar($v) {
		$j = 0;
		$f = 0;
		while ( $j < strlen( $v)) {
			$s = '';

			$p1 = strpos( $v, '$', $j);
			if ( $p1 == false) {
				if ( $f == 0) $v1 = $v;
				break;
			}

			$j = $p1 + 1;

			while ( $v{$j} != '\'' && $v{$j} != "\\" && $v{$j} != ' ' && $v{$j} != '<' && $j < strlen( $v) && $v{$j} != '.' && $v{$j} != '"') { // "
				$s .= $v{$j};
				$j++;
			}
			global ${$s};

			if ( $$s != '') {
				if ( $f == 1) {
					$v = $v1;
				}
				$v1 = str_replace( '$' . $s, $$s, $v);
				$f = 1;
			} else {
				$v1 = $v;
			}
		}
		return $v1;
	} // end function repvar
	function checkTags( $fld, $val)
	{
		$x = '';
		// This will validate the field for HTML tags.
		if ( $val != strip_tags( $val)) {
			$x = $this->mytrans( 'HTML tags are not allowed') . "<br />";
		}
		return $x;
	}
	// # Modified 2006/05/19 Pat K <cicada@edencomputing.com>
	function mytrans( $word) {
		global $mylang, $lencType;
		$m = '';

		$s = addslashes($word);
        if (strpos($s, '##')) {
          $s = preg_replace( "/##/", "", $s); ## remove any hashes in the text if we got it first 
        }

		$sql = "select w,m from ! where w = ? and lcase(l) = ? and lcase(enc) = ?";
		// MySQL isn't case sensitive, so we might get more than one match
		// lets be sure we've got the right one.
		$rh = $this->query( $sql, array( $this->langTable, $s, $mylang, $lencType));
		while ( $a = $this->mfa( $rh)) {
			if ( $s == $a['w']) { // case sensitive match
				$r = $a['m']; #
			}
		}

		if ( $r != false) {
			$r1 = stripslashes( $r);
			$m = stripslashes( $this->repvar( $r1));
		} else {
			// $menc = ( $_SESSION["encType"] != '' )? $_SESSION["encType"] : "english";
			$lang = split( '_lll_', SLANG); ## Use the default language is none is specified

			$sql = "select m from ! where w = ? and lcase(l) = ? and lcase(enc) = ?";
			$r = $this->getOne( $sql, array( $this->langTable, $s, strtolower( $lang[0]), strtolower( $lang[1]))); #

			if ( $r != false) {
				$r1 = stripslashes( $r);
				$m = stripslashes( $this->repvar( $r1));
			}
		}
		if ( $m) {
			return trim( $m);
		} else {
			return $word;
		} ;
	} // End of mytrans function
	
  function check_tables() {
    $res = mysql_query('show tables');
    while ($row = mysql_fetch_row($res)) {
      mysql_query('REPAIR table '.$row[0]);
      if (mysql_errno()) echo mysql_error();
    }

    $r = $this->dbh->reverse->tableInfo( $this->indexTable);
		if ( MDB2::isError( $r)) {
			return false;
    }
		$r = $this->dbh->reverse->tableInfo( $this->blobsTable);
		if ( MDB2::isError( $r)) {
			return false;
		}
    $r = $this->dbh->reverse->tableInfo( $this->gedcomTable);
		if ( MDB2::isError( $r)) {
			return false;
		}
		$r = $this->dbh->reverse->tableInfo( $this->famgalTable);
		if ( MDB2::isError( $r)) {
			return false;
		}
		$r = $this->dbh->reverse->tableInfo( $this->ilogiTable);
		if ( MDB2::isError( $r)) {
			return false;
		}
		$r = $this->dbh->reverse->tableInfo( $this->ilinkTable);
		if ( MDB2::isError( $r)) {
			return false;
		}
		$r = $this->dbh->reverse->tableInfo( $this->userTable);
		if ( MDB2::isError( $r)) {
			return false;
		}
		$r = $this->dbh->reverse->tableInfo( $this->langTable);
		if ( MDB2::isError( $r)) {
			return false;
		}
		$r = $this->dbh->reverse->tableInfo( $this->tempTable);
		if ( MDB2::isError( $r)) {
			return false;
		}


    return true;
	} // End of checktable function

  function obstr( $n, $t = 0) {
		$hideType = $this->getHideType( );
		if ( $hideType > 5) {
			$hideType = 1;
		}

		switch ( $hideType) {
			case '5' :
				return $n;
				break;

			case '4' :
				return ( $t == 0) ? str_repeat( '*', strlen( $n)) : $n;
				break;

			case '1' :
				if ( $t == 1 || $t == 2) {
					$r = split( " ", $n);
					$s = '';
					foreach( $r as $k1 => $v1) {
						$r[$k1] = trim( $r[$k1]);
						$s .= substr( $r[$k1], 0, 1) . str_repeat( '*', strlen( $r[$k1])) . ' ';
					}
				} else {
					return str_repeat( '*', strlen( $n));
					break;
				}
				return $s;
				break;

			case '3' :
				if ( $t == 1) {
					$r = split( " ", $n);
					$s = '';
					foreach( $r as $k1 => $v1) {
						$r[$k1] = trim( $r[$k1]);
						if ( $k1 == 0) {
							$s .= $r[$k1] . ' ';
						} else {
							$s .= str_repeat( '*', strlen( $n)) . ' ';
						}
					}
					return $s;
				} else {
					return str_repeat( '*', strlen( $n));
				}
				break;
			case '2' :
			default :
				return str_repeat( '*', strlen( $n));
		}
	} // end of obstr funtion
  
	// Returns array name -> id , name->id of individulas not members of any family in $tree
	function getNotFam( )
	{
		global $user;
		$list = array( );

		$sql = "select id from {$this->gedcomTable} where type='I' and tree = ".$this->dbh->quote($user)." group by id";
		$r = $this->getCol($sql);

		if ( count( $r) != 0) {
			foreach ( $r as $id) {
				$f1 = $this->lgetFamilyP( $id);
				$f2 = $this->lgetFamilyC( $id);
				if ( $f1 == false && $f2 == false) {
					$list[] = $id;
				}
			}
		}
		return $list;
	} //End of getNotFam function
	function removeG( $be, $ee, $txt)
	{
		$lines = split( CRLF, $txt);
		$s = '';
		for ( $i = 0; $i < count( $lines); $i++) {
			if ( $i < $be || $i > $ee) {
				$s .= $lines[$i] . CRLF;
			}
		}
		return $s;
	}
/*
* convert something
*/
	function fromtoG( $txt, $xtag, $ct) {
		$c = 0;
		$line = split( CRLF, $txt);
		for ( $i = 0;$i < count( $line);$i++) {
			if ( substr( $line[$i] , 0, strlen( "1 $xtag")) == "1 $xtag" && ( strlen( $xtag) == 4 || ( ( strlen( $xtag) == 3) &&
			substr( $line[$i], 5, 1) != 'F' && substr( $line[$i], 5, 1) != 'A'))) {
				$c++;
				if ( $c == $ct) {
					$b = $i;
					$j = $i + 1;
					while ( $j <= count( $line)) {
						if ( substr( $line[$j], 0, 2) == "1 " or substr( $line[$j], 0 , 2) == "0 " or $j == ( count( $line) -1)) {
							$e = $j;
							break;
						}
						$j++;
					}
					$e--;
				}
			}
		}

		return "$b:$e";
	} 
  
	function getGN($txt) {
		$s = '';
		for ( $i = 0 ; $i < strlen( $txt); $i++) {
			if ( ( ord( $txt[$i]) >= ord( 'a') && ord( $txt[$i]) <= ord( 'z')) ||
			( ord( $txt[$i]) >= ord( 'A') && ord( $txt[$i]) <= ord( 'Z'))) {
				$s .= $txt[$i];
			}
		}
		return $s;
	} 
  
	function gettxtfromtoG( $be, $ee, $txt) {
		$s = '';
		$lines = split( CRLF, $txt);
		foreach ( $lines as $line) {
			if ( $i >= $be && $i <= $ee) {
				$s .= $line . CRLF;
			}
		}
		return $s;
	} 
  
	function insertG( $b, $n, $txt)
	{
		$line = split( CRLF, $txt);
		$nlin = split( CRLF, $n);
		$s = '';
		for ( $i = 0;$i < count( $line);$i++) {
			if ( $i < $b) {
				$s = $s . $line[$i] . CRLF;
			} else {
				for ( $j = 0;$j < count( $nlin);$j++) {
					$s = $s . $nlin[$j] . CRLF;
				} while ( $i < count( $line)) {
					$s = $s . $line[$i] . CRLF;
					$i++;
				}
				break;
			}
		}
		return $s;
	} // end of insertG function
	function removeFromFamily( $ID, $isex)
	{
		global $user;
		$famc = $this->lgetFamilyC( $ID);
		if ( $isex == "M") {
			$sql = "select hid from ! where tree= ? and type='F' and tag='HUSB' and id= ?";
			$r = $this->getOne( $sql, array( $this->gedcomTable, $user, $famc)); #

			if ( $r != false) {
				$hid = $r;

				$sql = "delete from ! where tree= ? and type='I' and tag='FAMS' and hid= ? and id = ?";
				$r = $this->query( $sql, array( $this->gedcomTable, $user, $famc, $hid)); #
			}

			$sql = "delete from ! where tree= ? and type='F' and tag='HUSB' and id= ?";
			$r = $this->query( $sql, array( $this->gedcomTable, $user, $famc)); #
		} else {
			$sql = "select hid from ! where tree= ? and type='F' and tag='WIFE' and id= ?";
			$r = $this->getOne( $sql, array( $this->gedcomTable, $user, $famc)); #
			if ( $r != false) {
				$hid = $r ;

				$sql = "delete from ! where tree= ? and type='I' and tag='FAMS' and hid= ? and id = ?";
				$r = $this->query( $sql, array( $this->gedcomTable, $user, $famc, $hid)); #
			}

			$sql = "delete from ! where tree= ? and type='F' and tag='WIFE' and id= ? ";
			$r = $this->query( $sql, array( $this->gedcomTable, $user, $famc));
		}

		$sql = "select id from ! where tree= ? and type='F' and (tag = 'WIFE' or tag = 'HUSB') and id = ?";
		$r = $this->query( $sql, array( $this->gedcomTable, $user, $famc));

		if ( $r != false && $this->rowsInResult( $r) < 1) {
			$sql = "delete from ! where tree= ?  and type='F' and id = ?";
			$r = $this->query( $sql, array( $this->gedcomTable, $user, $famc));

			$sql = "delete from ! where tree= ? and tag='FAMC' and hid = ?  and type='I' and id = ?";
			$r = $this->query( $sql, array( $this->gedcomTable, $user, $famc, $ID));
		}
	} // end of funtion removeFromFamily
	function changeFamily( $ID, $chni, $isex = 'M')
	{
		global $user;
		$p = array( );
		$psex = ( $chni >= 0) ? $this->getItem( "sex", $chni) : $isex;
		$myoldfam = $this->lgetFamilyC( $ID);
		if ( $myoldfam) {
			$sql = "select id, hid from ! where tree= ? and tag='CHIL' and type='F' and id = ?";
			$r = $this->query( $sql, array( $this->gedcomTable, $user, $myoldfam));

			if ( !( MDB2::isError( $r))) {
				if ( $psex == 'M') {
					$x = 'HUSB';
					$y = 'WIFE';
				} else {
					$x = 'WIFE';
					$y = 'HUSB';
				}

				$sql = "select id, hid from ! where tree= ? and tag= ? and type='F' and id = ?";
				$r4 = $this->query( $sql, array( $this->gedcomTable, $user, $y, $myoldfam));

				if ( $r4 != false && $this->rowsInResult( $r4) > 0) {
					$a = $this->mfa( $r4);
					$p['id'] = $a['hid'];
					$p['tag'] = $y;
				}
				if ( $this->rowsInResult( $r) == 1) {
					$a = $this->mfa( $r);
					if ( $a['hid'] == $ID) {
						$sql = "delete from ! where tree= ? and  type='F' and id = ?";
						$this->query( $sql, array( $this->gedcomTable, $user, $myoldfam));
					}
				}
			}
		}
		if ( !$famc || ( 1 == 1)) {
			$sql = "delete from ! where type='F' and tree= ? and tag='CHIL' and hid= ?";
			$r = $this->query( $sql, array( $this->gedcomTable, $user, $ID));

			$sql = "delete from ! where type='I' and tree= ? and tag='FAMC' and id= ?";
			$r = $this->query( $sql, array( $this->gedcomTable, $user, $ID));

			$familyID = $this->lgetNewID( 'F');
			$this->lputValue( $familyID, 'F', 'FAM', 0, '@F' . $familyID . '@');
			$famc = $familyID;
		}
		if ( $chni >= 0) {
			if ( $psex == "M") {
				$this->lputValue( $famc, 'F', 'HUSB', 1, '@I' . $chni . '@');
			} else {
				$this->lputValue( $famc, 'F', 'WIFE', 1, '@I' . $chni . '@');
			}
		}
		if ( count( $p) > 0) {
			$this->lputValue( $famc, 'F', $p['tag'], 1, '@I' . $p['id'] . '@');
		}

		$this->lputValue( $chni, 'I', 'FAMS', 1, '@F' . $famc . '@');
		$this->addChild( $famc, $ID);
		$this->updateIndex( $ID);
	} // end of changeFamily function
	function getIndiCount( )
	{
		global $user;

		$sql = "select count(distinct id) as idcnt from ! where type='I' and tree = ?";
		$r = $this->getOne( $sql, array( $this->gedcomTable, $user));

		return $r;
	}

	function getIndi( $sex = '') {
		global $user;
		$list = array( );

		$sql = "select id from {$this->gedcomTable} where type='I' and tree = ".$this->dbh->quote($user)." group by id";
		$r = $this->getCol($sql);

		if ( count( $r) != 0) {
			foreach ( $r as $id) {
				$rsex = ( $sex != '') ? $this->getItem( "sex", $id):'';
				if ( strtoupper( $rsex) == strtoupper( $sex)) {
					if ( ANIMALPEDIGREE) {
						$list[$this->changeBrack( $this->getItem( "name", $id))] = $id;
					} else {
						$list[$this->getItem( "name", $id)] = $id;
					}
				}
			}
		}
		return $list;
	}

	function getAllBy( $field, $v)
	{
		global $user;
		$list = array( );

		$sql = "select id,tag from ! where type='I' and tree = ? group by id,tag";
		$r = $this->query( $sql, array( $this->gedcomTable, $user));

		if ( $r != false) {
			while ($a = $this->mfa($r)) {
				if ( $this->getItem( $field, $a["id"]) == $v) {
					$list[] = $a["id"];
				}
			}
		}
		return $list;
	} // end of getAllBy function
	function getHideType( )
	{
		global $user;

		$sql = "select hide_type from ! where username= ?";
		$r = $this->getOne( $sql, array( $this->userTable, $user));

		if ( $r != false) {
			return $r;
		}
	}

	function setHideType( $v)
	{
		global $user;

		$sql = "update ! set hide_type= ? where  username= ?";
		$r = $this->query( $sql, array( $this->userTable, $v, $user));
		return $r;
	}

	function getIndexMother( $id)
	{
		$r = $this->getMother( $id);
		return $r ;
	}

	function getIndexFather( $id)
	{
		$r = $this->getFather( $id);
		return $r;
	}

	function getSameLev( $a, $b, $c, $parent = "F")
	{
		$x = $this->getLev( $a, $c);
		$y = $this->getLev( $b, $c);
		if ( $x > $y) {
			$s = $y;
			$sid = $b;
			$b = $x;
			$bid = $a;
		} else {
			$s = $x;
			$sid = $a;
			$b = $y;
			$bid = $b;
		}
		$t = $b;
		$v = $bid;
		while ( $t > $s) {
			$v = ( $parent == "F") ? $this->getFather( $bid) : $this->getMother( $bid);
			if ( $v != false) {
				$t = $this->getLev( $v, $c);
			} else {
				break;
			}
		}
		return $v;
	} // end of getSameLev function
	function getIndexSpouses( $id)
	{
		$sp = array( );
		$r = $this->getSpouses( $id);
		return $r;
	}
	
	/*
	 * return array with all fathers for passed ID
	 */
	function getFathers($id) {
		$fathers = array();
	
		$m = $this->getMother( $id);
		$f = $this->getFather( $id);
		$s = $this->getSpouses( $id);

		if ( $f == false && $m == false && count( $s) == 0) {
			return $fathers;
		}

		if ( $f != false) {
			$fathers[] = $f;
			if ( @!in_array( $f, $fathers)) {
				$fathers[] = $f;
			}
			$x = $this->getFathers( $f);
			if ( count( $x) > 0) {
				foreach( $x as $k => $v) {
					if ( @!in_array( $v, $fathers))
					$fathers[] = $v;
				}
			}
		}

		if ( $m != false) {
			$x = $this->getFathers( $m);
			foreach( $x as $k => $v) {
				if ( @!in_array( $v, $fathers)) {
					$fathers[] = $v;
				}
			}
			$x = $this->getSpouses( $m);
			if ( count( $x) > 0) {
				foreach( $x as $k => $v) {
					if ( @!in_array( $v, $fathers)) {
						$fathers[] = $v;
					}
					$y = $this->getFathers( $v);
					if ( count( $y) > 0) {
						foreach( $y as $k2 => $v2) {
							if ( @!in_array( $v2, $fathers)) {
								$fathers[] = $v2;
							}
						}
					}
				}
			}
		}
		if ( $s != false) {
			foreach ( $s as $k1 => $v1) {
				$y = $this->getIndexFather( $v1);
				if ( $y != false) {
					if ( @!in_array( $y, $fathers)) {
						$fathers[] = $y;
					}
				}
			}
		}
		return $fathers;
	} // end of getFathers function

	// Extended Function used in relfind.php
	// Very sensitive code - Do Not Modify !!
	// by aTufa May/2005
	function getFathersRel( $id) {
		$fathers = array( );
		
		
		$m = $this->getMother( $id);
		$f = $this->getFather( $id);
		$s = $this->getSpouses( $id);

		if ( $f == false && $m == false && count( $s) == 0) {
			return $fathers;
		}

		if ( $f != false) {
			if ( @!in_array( $f, $fathers['id'])) {
				$fathers['id'][] = $f;
				$fathers['par'][] = $id;
				$fathers['debug'][] = 'Ffat1';
			}
			$xm = $this->getFathersRel($f);
			$x = $xm['id'];
			if ( count( $x) > 0) {
				for ( $k = 0; $k < count( $x); $k++) {
					if ( @!in_array( $x[$k], $fathers['id'])) {
						$fathers['id'][] = $x[$k];
						$fathers['par'][] = $xm['par'][$k];
						$fathers['debug'][] = 'Ffat2';
					}
				}
			}
		}
		if ( $m != false) {
			$xm = $this->getFathersRel( $m);
			$x = $xm['id'];

			if ( count( $x) > 0) {
					/* fix bug
					 * Incorrect: Maternal grandfather >> Son-in-law >> Grandson.
					 * Correct: Maternal grandfather >> Daughter >> Grandson.
  					*/
				$fathers['id'][] = $f; // Add father(as mother) to tree!
				$fathers['par'][] = $f;
				
				for ( $k = 0; $k < count( $x); $k++) {
					if ( @!in_array( $x[$k], $fathers['id'])) {
						$fathers['id'][] = $x[$k];
						$fathers['par'][] = $xm['par'][$k];
						$fathers['debug'][] = 'Fmot1';
					}
				}
			}

			$x = $this->getSpouses( $m);
			if ( count( $x) > 0) {
				foreach( $x as $k => $v) {
					if ( @!in_array( $v, $fathers)) {
						$fathers['id'][] = $v;
						$fathers['par'][] = $id;
						$fathers['debug'][] = 'Fspo1';
					}
					$ym = $this->getFathersRel( $v);
					$y = $ym['id'];
					if ( count( $y) > 0) {
						for ( $k2 = 0; $k2 < count( $y); $k2++) {
							if ( @!in_array( $y[$k2], $fathers['id'])) {
								$fathers['id'][] = $y[$k2];
								$fathers['par'][] = $ym['par'][$k2];
								$fathers['debug'][] = 'Fspo2';
							}
						}
					}
				}
			}
		}
		// This is Father in LAW ! Need on Aunt/Uncle.
		// Why on getMothers this part does not exist ?!
		if ( $s != false) {
			foreach ( $s as $k1 => $v1) {
				$y = $this->getIndexFather( $v1);
				if ( $y != false) {
					if ( @!in_array( $y, $fathers)) {
						$fathers['id'][] = $y;
						$fathers['par'][] = $id;
						$fathers['debug'][] = 'FspoL';
					}
				}
			}
		}

		return $fathers;
	} // end of getFathersRel function

	function getMothers( $id) {
		$mothers = array( );
		$m = $this->getMother( $id);
		$f = $this->getFather( $id);
		if ( $f == false && $m == false) {
			return $mothers;
		}
		if ( $m != false) {
			if ( @!in_array( $m, $mothers)) {
				$mothers[] = $m;
			}
			$x = $this->getMothers( $m);
			if ( count( $x) > 0) {
				foreach( $x as $k => $v) {
					if ( @!in_array( $v, $mothers)) {
						$mothers[] = $v;
					}
				}
			}
		}
		if ( $f != false) {
			$x = $this->getMothers( $f);
			if ( count( $x) > 0) {
				foreach( $x as $k => $v) {
					if ( @!in_array( $v, $mothers)) {
						$mothers[] = $v;
					}
				}
			}
			$x = $this->getSpouses( $f);
			if ( count( $x) > 0) {
				foreach( $x as $k => $v) {
					if ( !@in_array( $v, $mothers)) {
						$mothers[] = $v;
					}
					$y = $this->getMothers( $v);
					if ( count( $y) > 0) {
						foreach( $y as $k2 => $v2) {
							if ( @!in_array( $v2, $mothers)) {
								$mothers[] = $v2;
							}
						}
					}
				}
			}
		}
		return $mothers;
	} // end of getMothers function
	// Extended Function used in relfind.php
	// Very sensitive code - Do Not Modify !!
	// by aTufa May/2005
	function getMothersRel( $id)
	{
		$mothers = array( );
		$m = $this->getMother( $id);
		$f = $this->getFather( $id);
		if ( $f == false && $m == false) {
			return $mothers;
		}
		if ( $m != false) {
			if ( @!in_array( $m, $mothers['id'])) {
				$mothers['id'][] = $m;
				$mothers['par'][] = $id;
				$mothers['debug'][] = 'Mmot1';
			}
			$xm = $this->getMothersRel( $m);
			$x = $xm['id'];
			if ( count( $x) > 0) {
				for ( $k = 0; $k < count( $x); $k++) {
					if ( @!in_array( $x[$k], $mothers['id'])) {
						$mothers['id'][] = $x[$k];
						$mothers['par'][] = $xm['par'][$k];
						$mothers['debug'][] = 'Mmot2';
					}
				}
			}
		}
		if ( $f != false) {
			$xm = $this->getMothersRel( $f);
			$x = $xm['id'];
			if ( count( $x) > 0) {
				$mothers['id'][] = $f; // Add Father to tree!
				$mothers['par'][] = $id;

				for ( $k = 0; $k < count( $x); $k++) {
					if ( @!in_array( $x[$k], $mothers['id'])) {
						$mothers['id'][] = $x[$k];
						$mothers['par'][] = $xm['par'][$k];
						$mothers['debug'][] = 'Mfat1';
					}
				}
			}
			$x = $this->getSpouses( $f);
			if ( count( $x) > 0) {
				foreach( $x as $k => $v) {
					if ( @!in_array( $v, $mothers['id'])) {
						$mothers['id'][] = $v;
						$mothers['par'][] = $id;
						$mothers['debug'][] = 'Mspo1';
					}
					$ym = $this->getMothersRel( $v);
					$y = $ym['id'];
					if ( count( $y) > 0) {
						for ( $k2 = 0; $k2 < count( $y); $k2++) {
							if ( @!in_array( $y[$k2], $mothers['id'])) {
								$mothers['id'][] = $y[$k2];
								$mothers['par'][] = $ym['par'][$k2];
								$mothers['debug'][] = 'Mspo2';
							}
						}
					}
				}
			}
		}
		return $mothers;
	} // end of getMothersRel function
	function getLev( $id, $id2)
	{
		$k = $this->getXLev( $id, $id2, 0);
		return $k;
	}

	function getIndexChildren( $id)
	{
		global $user;
		$r = $this->getChildren( $id);
		return ( count( $r) != 0) ? $r : false;
	}

	function getIndexChildren2p( $id1, $id2)
	{
		global $user;
		$r1 = $this->getChildren( $id1);
		$r2 = $this->getChildren( $id2);
		$r = array_unique( array_merge( $r1, $r2));

		return ( count( $r) != 0) ? $r : false;
	}

	function getXLev( $id, $id2, $i)
	{
		$m = 0;
		$spc = array( );
		$g = array( );

		$c = $this->getChildren( $id2);
		$g = $this->getSpouses( $id2);
		if ( count( $g) > 0) {
			foreach( $g as $k1 => $v1) {
				$m1 = $this->getChildren( $v1);
				if ( count( $m1) > 0) {
					foreach( $m1 as $k3 => $v3) {
						$c[] = $v3;
					}
				}
			}
		}
		foreach ( $c as $k => $v) {
			$g = $this->getSpouses( $v);
			if ( count( $g) > 0) {
				foreach( $g as $k1 => $v1) {
					$spc[] = $v1;
				}
			}
		}

		if ( count( $spc) > 0) {
			foreach ( $spc as $k => $v) {
				$c[] = $v;
			}
		}

		if ( count( $c) > 0) {
			foreach ( $c as $k => $v) {
				if ( $v == $id) {
					$i++;
					return $i;
				}
			}
			$i++;
			foreach ( $c as $k => $v) {
				$m = $this->getXLev( $id, $v, $i);
				if ( $m > 0) {
					return $m;
				}
			}
		}
	}

	function getCommon($a, $b) {
		$com = array( );

		if ( count( $a) == 0 || count( $b) == 0) {
			return $com;
		}

		if ( count( $a) > 0) {
			foreach ( $a as $k => $v) {
				if ( count( $b) > 0) {
					foreach( $b as $k2 => $v2) {
						if ( $v == $v2) {
							if ( @!in_array( $v, $com) && $v > 0) {
								$com[] = $v;
							}
						}
					}
				}
			}
      
			return $com;
		}
	}

	function sG( $id, $name = "", $hide = "-1")
	{
		if ( $hide == "-1") {
			$hid = $this->getItem( "hide", $id);
		}

		$nm = ( $name == "") ? $this->getItem( "name", $id) : $name;
		$nm = str_replace( "/", "", $nm);
		if ( $hid == "Yes") {
			$nm = $this->obstr( $nm, 1);
		}
		return $nm . " (ID #$id)";
	}
	// internal function should be called only within specific context
	function testStepMother( $id_c, $id_p)
	{
		$r = true;

		$c = $this->getChildren( $id_p);

		foreach( $c as $k => $v) {
			if ( $v == $id_c) {
				return false;
			}

			if ( $this->getItem( "sex", $v) == "M") {
				$r = $r &$this->testStepFather( $id_c, $v);
			} else {
				$r = $r &$this->testStepMother( $id_c, $v);
			}
		}

		return $r;
	}
	// internal function should be called only within specific context
	function testStepFather( $id_c, $id_p)
	{
		$r = true;

		$c = $this->getChildren( $id_p);
		foreach( $c as $k => $v) {
			if ( $v == $id_c) {
				return false;
			}
			if ( $this->getItem( "sex", $v) == "M") {
				$r = $r &$this->testStepFather( $id_c, $v);
			} else {
				$r = $r &$this->testStepMother( $id_c, $v);
			}
		}
		return $r;
	}

	function getAllFieldList( $field)
	{
		global $user, $lang;
		$list = array( );

		$sql = "select id,data as ? from ! where type='I' and tree = ? and tag= ? group by id, data";
		$r = $this->query( $sql, array( $field, $this->gedcomTable, $user, strtoupper( $field)));

		$no_owner = $this->mytrans( "(no owner specified)");
		$no_breed = $this->mytrans( "(no breed specified)");
		if ( $this->rowsInResult( $r) != 0) {
			for ( $i = 0; $i < $this->rowsInResult( $r);$i++) {
				$a = $this->mfa( $r);
				$rfield = $a[$field];
				$aid = $a['id'];
				if ( strlen( $rfield) > 0) {
					$list[$rfield] = $aid;
				} elseif ( $field == "ownr") {
					$list[$no_owner][] = $aid;
				} elseif ( $field == "bred") {
					$list[$no_breed][] = $aid;
				}
			}
		}
		return $list;
	}

	/**
 * * Given the tree name, this function returns a 2-dimensional sorted associative array of this form:
 *
 * $family = array (
 * "Jackson" => array ("1"=>"Michael", "2"=>"Samuel L.", "5"=>"LaToya"),
 * "Gates" => array ( "3"=>"Darren G.", "12"=>"David Gregory"),
 * "Clinton" => array ("18"=>"William Jefferson")
 * );
 */

	function getTree( $tree)
	{
		$fam = array( );

		$sql = "select id from {$this->gedcomTable} where type='F' and tree = ".$this->dbh->quote($tree)." group by id";
		$r = $this->getCol($sql);

		if ( count( $r) != 0) 
		{
			foreach ( $r as $aid) 
			{
				$sql = "select tag,data,hid from ! where type='F' and tree = ? and id = ? order by id,inum";
				$p = $this->query( $sql, array( $this->gedcomTable, $tree, $aid));

				if ( $p != false) 
				{
					while ($b = $this->mfa($p)) 
					{
						switch ($b['tag']) 
						{
							case 'HUSB' :
									$fam[$this->getFamName( $aid, $tree, $b["hid"])][$b["hid"]] = 
										stripslashes( $this->lgetName( $b["hid"], $tree));
								break;
								
							case 'WIFE' :
									$fam[$this->getFamName( $aid, $tree, $b["hid"])][$b["hid"]] = 
										stripslashes( $this->lgetName( $b["hid"], $tree));
								break;
								
							case 'CHIL' :
									$fam[$this->getFamName( $aid, $tree, $b["hid"])][$b["hid"]] = 
										stripslashes( $this->lgetName( $b["hid"], $tree));
						}
					}
				}
			}
		}
		
		asort( $fam);
		return $fam;
	}
	// returns a list of surnames in the tree
	function getSurnames( $tree)
	{
		$fam = array( );

		$sql = "select distinct data from {$this->gedcomTable} where tree = ".$this->dbh->quote($tree)." and tag='SURN'";
		$r = $this->getCol($sql);

		if ( count( $r) != 0) {
			foreach ( $r as $surn) {
				if ( $surn <> "") {
					if ( @!in_array( $surn , $fam)) {
						$fam[] = $surn;
					} elseif ( @!in_array( '' , $fam)) {
						$fam[] = "";
					}
				}
			}
		}
		/* Try and deduce surnames from name  like  richard /beig/  */
		$sql = "select distinct data from {$this->gedcomTable} where tree = ".$this->dbh->quote($tree)." and tag='NAME'";
		$r = $this->getCol($sql);

		if ( count( $r) != 0) {
			foreach ( $r as $nm) {
				$surn = $this->getBrackName( $nm);
				if ( $surn <> "") {
					if ( @!in_array( $surn , $fam)) {
						$fam[] = $surn;
					} elseif ( @!in_array( '' , $fam)) {
						$fam[] = "";
					}
				}
			}
		}
		return $fam;
	}

	/**
 * * Format the date value according to the rule specified in the config file.
 */
	function dateFormat( $value, $type = 1)
	{
		// insert value into datetime field of $tempTable
		if ( strlen( $value) == 4) {
			return $value;
		}
		// check to see if this date has only the year, e.g. YYYY-00-00
		if ( strstr( $value, "-00-00") || strstr( $value, "-0-0")) {
			list( $year, $month, $day) = explode( "-", $value);
			return $year;
		}
		$value = $this->mysqlDateFormat( $value);

		$sql = "INSERT INTO ! VALUES( NULL, ? )";
		$this->query( $sql, array( TBL_TEMP, $value));
		// now get the last inserted ID from $tempTable, no need to worry about tree = '$user' here
		$sql = "SELECT MAX(ID) FROM !";
		$ID = $this->getOne( $sql, array( TBL_TEMP));

		$sql = "SELECT DATE_FORMAT( datetime, ? ) FROM ! WHERE ID = ?";
		$newValue = $this->getOne( $sql, array( LONGDATEFORMAT, TBL_TEMP, $ID));

		if ( $type == 2) { // DD MMM YYYY format for GEDCOM export
			$sql = "SELECT UPPER( DATE_FORMAT(datetime, '%e %b %Y' ) ) FROM ! WHERE ID = ?";
			$newValue = $this->getOne( $sql, array( TBL_TEMP, $ID));
		}
		if ( $type == 3) {
			$sql = "SELECT DATE_FORMAT( datetime, ? ) FROM ! WHERE ID = ?";
			$newValue = $this->getOne( $sql, array( SHORTDATEFORMAT, TBL_TEMP, $ID));
		}
		// remove the temp value
		$sql = "DELETE FROM ! WHERE ID = ?";
		$this->query( $sql, array( TBL_TEMP, $ID));

		return ( strstr( $newValue, "0000")) ? $value : $newValue;
	}

	function dateField( $name, $default = "" , $srch = 0) {
		// $default is a date string in MySQL format. If not in mysql format, convert to mysql format
		$iok = 0;
		$iok2 = 0;
		$iok3 = 0;

		$orgdef = $default;

		if ( !ereg ( "([0-9]{0,4})-([0-9]{1,2})-([0-9]{1,2})", $default) && trim( $default) != "--") {
			$default = $this->convertDate( $default);
		}
		list( $year, $month, $day) = explode( "-", $default);
		// returns a string with three form objects
		$months = Array( "- ##Select## -", "##January##", "##February##", "##March##", "##April##", "##May##", "##June##", "##July##", "##August##", "##September##", "##October##", "##November##", "##December##");

		$result .= "<select name=\"$name" . "_1\" class=\"normal\">";

		for ( $i = 0; $i < 13; $i++) {
			$result .= "<option value='" . $this->addLZero( $i) . "'";

			if ( $i == $month) {
				$result .= " selected=\"selected\"";
				$iok = 1;
			}
			$result .= ">$months[$i]</option>\n";
		}
		$result .= "</select> ";

		if ( $name == "born_during_month") {
			return $result;
		}
    
		// now add the day list
		$result .= "<select name=\"$name" . "_2\" class=\"normal\">";

		for ( $i = 0; $i < 32; $i++) {
			$result .= "<option value='" . $this->addLZero( $i) . "'";

			if ( $i == $day) {
				$result .= " selected=\"selected\"";
				$iok2 = 1;
			}
			$result .= ( $i == 0) ? ">--</option>\n" : ">$i</option>\n";
		}

		$result .= "</select>";

		if ( $name == "born_on_day") {
			return $result;
		}

		if ($year == '0' || $year == '0000') {
			$year = "";
		}

		if ( $year != "") {
			$iok3 = 1;
		}

		$result .= " <input value=\"$year\" type=\"text\" size=\"4\" maxlength=\"4\" name=\"$name" . "_3\" class=\"normal\" />";

		if ( $iok == 1 && $iok2 == 1 && $iok3 == 1) {
			$orgdef = '';
		}

		if ( $srch == 0) {
      if ($orgdef == '0000') {
        $orgdef = null;
      }
			$result .= " <font class=\"normal\">&nbsp;##or##&nbsp;</font> <input type=\"text\" name=\"$name\" class=\"normal\" size=\"20\" value='$orgdef' />";
		}

		return $result;
	}

	function lgetFamilyC( $ID)
	{
		global $user;
		$sql = "select id from ! where tree= ? and type='F' and hid= ? and tag='CHIL'";
		$r = $this->getOne( $sql, array( $this->gedcomTable, $user, $ID));

		return ( MDB2::isError( $r)) ? false : $r;
	}

	function getMime( $rname, &$type)
	{
		if ( strstr( $rname, ".jpg") != false || strstr( $rname, ".png") != false || strstr( $rname, ".gif") != false || strstr( $rname, ".tif") != false || strstr( $rname, ".bmp") != false) {
			$type = 'image/jpeg';
			$ret = 0;
		}

		if ( strstr( $rname, ".avi") != false) {
			$type = 'video/avi';
			$ret = 1;
		}

		if ( strstr( $rname, ".asf") != false) {
			$type = 'video/asf';
			$ret = 1;
		}

		if ( strstr( $rname, ".mp3") != false) {
			$type = 'audio/mp3';
			$ret = 1;
		}

		if ( strstr( $rname, ".pdf") != false) {
			$type = 'application/pdf';
			$ret = 1;
		}

		return $ret;
	}

	function lgetFamilyP( $ID)
	{
		global $user;
		$sql = "select id from ! where tree= ? and type='F' and hid= ? and ( tag='WIFE' or tag='HUSB')";
		$r = $this->getOne( $sql, array( $this->gedcomTable, $user, $ID));

		return ( MDB2::isError( $r)) ? false : $r;
	}

	function lgetFamiliesP( $ID)
	{
		global $user;
		$fam = array( );

		$sql = "select id,tag from ! where tree= ? and type='F' and hid= ?";
		$r = $this->query( $sql, array( $this->gedcomTable, $user, $ID));

		if ( $r != false && $this->rowsInResult( $r) > 0) {
			while ($a = $this->mfa($r)) {
				if ( $a["tag"] != 'CHIL' && @!in_array( $a["id"], $fam))
				$fam[] = $a["id"];
			}
		} else {
			return false;
		}
		return $fam;
	}

	function getSurn( $indi_id, $tree)
	{
		$sn = '';
		$a = array( );

		$sql = "select data from ! where tree = ? and type = 'I' and id = ? and tag = 'SURN'";
		$r = $this->getOne( $sql, array( $this->gedcomTable, $tree, $indi_id));

		if ($r != false) 
		{
			$sn = $r;
		}
		
		if ( $sn != '')
		{
			return $sn;
		}
		else 
		{
			$name = $this->lgetName( $indi_id, $tree);
			$x1 = strpos( $name, "/");
			
			if ( $x1 != false) 
			{
				$x2 = strpos( $name, "/", $x1 + 1);
				return substr( $name, $x1 + 1, ( $x2 - $x1-1));
			}
		}

		return $sn;		
	}

	function lgetName( $indi_id, $tree)
	{
		$sql = "select data from ! where tree = ? and type = 'I' and id = ? and tag = 'NAME'";
		$r = $this->getOne( $sql, array( $this->gedcomTable, $tree, $indi_id));

		if ( $r != false) {
			return $r;
		}
	}

	function ged2id( $id)
	{
		$normid = substr( $id, 2, strlen( $id) -3);
		return $normid;
	}

	function getFamName( $fam_id, $tree, $hid)
	{
		$sql = "select hid,data from ! where tree = ? and type = 'F' and id = ? and (tag = 'HUSB' or tag='WIFE')" ;
		$r = $this->query( $sql, array( $this->gedcomTable, $tree, $fam_id));

		if ( $r != false && $this->rowsInResult( $r) > 0) 
		{
			$a = $this->mfa( $r);
			$nm1 = $this->getSurn( $a["hid"], $tree);
		}

		$nm2 = $this->getItem( "SURN", $hid);
	
		if( $nm1 != '' )
			return $nm2;
			
		if( $nm2 != '' )
			return $nm2;
		
		return $this->mytrans( '(no surname given)');
	}

	function getBrackName($s) {
		return ( preg_match( "/\/(.+)\//", $s, $m) != false) ? $m[1] : false;
	}

	function getLock( $ID)
	{
		global $user;
		
		if ( !$ID) 
		{
			return false;
		} 
		
		// no lock present
		// get the lock_password field. If present, return true
		$sql =  "SELECT lock_password from {$this->locksTable} WHERE ID =".$this->dbh->quote($ID).
				" AND tree =".$this->dbh->quote($user);
				
		return stripslashes($this->getValue($sql));
	}

	/**
 * * Returns the ID of the mother.
 */
	function getMother( $ID) {
		return $this->getParent( $ID, "wife");
	}

	/**
 * * Returns the ID of the father.
 */
	function getFather( $ID)
	{
		return $this->getParent( $ID, "husb");
	}

	/**
 * * Get a parent. $key = "wife" or $key = "husb"
 */
	function getParent( $ID, $key) {
		global $user;
		if ( empty( $ID)) {
			return "";
		}

		$sql = "select id from {$this->gedcomTable} where tree= ".$this->dbh->quote($user)." and type = 'F' and tag='CHIL' and hid= ".$this->dbh->quote($ID);
		$r = $this->getCol($sql);

		if ( count( $r) != 0) {
			foreach ( $r as $fid) {
				$mk = ( $key == 'wife') ? 'WIFE' : 'HUSB';

				$sql = "select hid from ! where tree= ? and type='F' and id= ? and tag= ?";
				$p = $this->getOne( $sql, array( $this->gedcomTable, $user, $fid, $mk));

				return $p;
			}
		}
		return '';
	}

	/**
 * * Returns an array of ID values that correspond to the brothers of this individual. Gets full as well as half brothers
 */
	function getBrothers( $ID) {
		return ( empty( $ID)) ? "" : $this->getSiblings( $ID, "M");
	}

	/**
 * * Returns an array of ID values that correspond to the sisters of this individual. Gets full as well as half sisters
 */
	function getSisters( $ID) {
		return ( empty( $ID)) ? "" : $this->getSiblings( $ID, "F");
	}

	/*  function getSex( $ID )
	{
	$sql = "select sex from "

	return ;
	}*/

	/**
 * * Returns an array of ID values that correspond to the siblings of the specified sex. $sex = "M" or $sex = "F"
 */
	function getSiblings($ID, $sex) {
		global $user;
    
		$p1id = -1;
		$p2id = -1;
		$sib = array( );

		// Ensure we have 1/2 siblings too
		$fid = $this->lgetFamilyC($ID);
		$p1id = $this->getFather($ID);
		$p2id = $this->getMother($ID);

		if ( "$p1id" == "") {
			$p1id = -1;
		}
		if ( "$p2id" == "") {
			$p2id = -1;
		}

		if ( $p1id == -1 && $p2id == -1) {
			return $sib;
		}

		$sql = "select id from {$this->gedcomTable} where tree= ".$this->dbh->quote($user)." and type='F' and ( hid = ".$this->dbh->quote($p1id)." or hid = ".$this->dbh->quote($p2id)." ) and (tag = 'WIFE' or tag = 'HUSB') group by id";

		$q3 = $this->getCol($sql);

		if ( count( $q3) != 0) {
			foreach ( $q3 as $fid) {
				$sql = "select hid from {$this->gedcomTable} where tree= ".$this->dbh->quote($user)." and type='F' and (hid not like ".$this->dbh->quote($p1id)." and hid not like ".$this->dbh->quote($p2id)." ) and tag = 'CHIL' and id = ".$this->dbh->quote($fid);
				$r = $this->getCol($sql);

				if ( count( $r) != 0) {
					foreach ( $r as $mid) {
						$sql = "select id from ! where tree= ? and type='I' and tag='SEX' and data= ? and id= ?";
						$p = $this->getOne( $sql, 0, array( $this->gedcomTable, $user, $sex, $mid));

						if ( $p != false) {
							if ( $mid != $ID) {
								$sib[] = $mid;
							}
						}
					}
				}
			}
		}

		return $sib;
	}

	/**
 * * Returns an array of ID values that correspond to the sons of this individual.
 */
	function getSons( $ID)
	{
		return $this->getChildren( $ID, "M");
	}

	/**
 * * Returns an array of ID values that correspond to the daughters of this individual.
 */
	function getDaughters( $ID)
	{
		return $this->getChildren( $ID, "F");
	}

	function getChildrenOfFamily( $ID)
	{
		global $user;
		$ch = array( );

		$sql = "select hid from {$this->gedcomTable} where tree= ".$this->dbh->quote($user)." and type='F' and tag = 'CHIL' and id = ".$this->dbh->quote($ID);
		$r = $this->getCol($sql);
		if ( count( $r) != 0) {
			foreach ( $r as $chid) {
				$ch[] = $chid;
			}
		}
		return $ch;
	}

	function getLChildren( $l)
	{
		$r = array( );
		if ( !is_array( $l)) {
			$l = array( $l);
		}
		foreach( $l as $k => $v) {
			$x = $this->getChildren( $v);
			foreach( $x as $k2 => $v2) {
				$r[] = $v2;
			}
		}
		return $r;
	}

	function lputValue( $ID, $type, $tag, $level, $data)
	{
		global $user;

		$data = trim(addslashes( $data));
		$tag = trim($tag);
		$mhid = -1;
		if ( $tag == 'FAMS' || $tag == 'FAMC' || $tag == 'CHIL' || $tag == 'WIFE' || $tag == 'HUSB') {
			$mhid = $this->ged2id( $data);
		}
		if ( $tag == 'DATE') {
			$data = $this->mysqlDateFormat( $data);
		}

		$sql = "insert into ! (id, tree, type, tag, data, level, hid) values ( ?, ? , ? , ? , ? , ? , ?)";
		$r = $this->query( $sql, array( $this->gedcomTable, $ID, $user, $type, $tag, $data, $level, $mhid));
	}

	function lgetLev( $ID, $type, $tag)
	{
		global $user;

		$sql = "select level from ! where tree= ? and id = ? and tag = ? and type = ?";
		$r = $this->getOne( $sql, array( $this->gedcomTable, $user, $ID, $tag, $type));
		return ( $r != false) ? $r : 0;
	}

	function removeFam($name)	{
		return preg_replace( "/\/(.*)\//", "", $name);
	}

	function changeBrack( $name)
	{
		return preg_replace( "/\//", "\"", $name);
	}

	function changeBrack2( $name)
	{
		return preg_replace( "/\//", "", $name);
	}

	function addLZero( $x)
	{
		return ( strlen( $x) == 1) ? "0" . $x : $x;
	}

	/**
 * * Updates a family record to reflect the addition of a child with ID = $childID. Also
 * updates each individuals record to show that the person is a child of $familyID.
 */

	function addChild( $familyID, $childID, $updateFamilyRecordOnly = false)
	{
		global $user;
		$this->lputValue( $familyID, 'F', 'CHIL', 1, '@I' . $childID . '@');
		$this->lputValue( $childID, 'I', 'FAMC', 1, '@F' . $familyID . '@');
		$this->updateIndex( $childID);
	}

	function mysqlDateFormat( $value)
	{
		$nd = '';
		$t = split( " ", trim( $value));
		if ( count( $t) == 3) {
			if ( strlen( $t[2]) == 4) {
				$nd = $t[2] . '-';
			}
			$tm = strtotime( $t[0] . " " . $t[1] . " 2002");
			$m = strftime( "%m", $tm);
			$nd .= $m . "-";
			$nd .= ( strftime( "%d", $tm));
			return $nd;
		}
		return $value;
	}
	/**
 * * Adds $line to the info text in the family or individuals file (specified by $type variable, which
 * is either "family" or "individual").
 */
	function addToGEDCOM( $type, $ID, $line)
	{
		global $user;
		$inBirt = false;
		$inDeat = false;
		$inBuri = false;

		$tr = split( " ", $line);
		$data = trim( addslashes( ( substr( $line, 6, strlen( $line)))));
		$tag = trim( $tr[1]);
		$level = trim( $tr[0]);
		$qr = '';
		$mhid = -1;
		switch ( $tag) {
			case 'FAMS' :
			case 'FAMC' :
			case 'CHIL' :
			case 'WIFE' :
			case 'HUSB' :
				$mhid = $this->ged2id( $data);
				break;
			case 'BIRT' :
				$inBirt = true;
				$inDeat = false;
				break;
			case 'DEAT' :
				$inBirt = false;
				$inDeat = true;
				break;
			case 'BURI' :
				$inBuri = true;
				break;
			case 'DATE' :
				$data = $this->mysqlDateFormat( $data);
		}
		if ( $inBirt) {
			$mhid = -12;
		}
		if ( $inDeat) {
			$mhid = -22;
		}
		if ( $inBuri) {
			$mhid = -33;
		}
		if ( $tag == 'PLAC' and $inBuri) {
			$inBuri = false;
		}
		if ( preg_match( "/([A-Z])*/", $tag) != false)

		$sql = "insert into ! (id, tree, type, tag, level, data, hid) values ( ?, ?, ?, ?, ?, ?, ?)";
		$this->query( $sql, array( $this->gedcomTable, $ID, $user, $type, $tag, $level, $data, $mhid));

		if ( $type == 'I')
		$this->updateIndex( $ID);
	}

	function lgetUser( )
	{
		global $user;
		return $user;
	}

	/**
 * * Removes all references to this ID from individuals table as well as families table. Returns the
 * next-of-kin ID, or false if no next-of-kin.
 */

	function removeRecord($ID) {
		global $user;

		$sql = "delete from ! where id = ? and tree = ?";
		$r = $this->query( $sql, array( $this->gedcomTable, $ID, $user));

		$sql = "delete from ! where hid = ? and tree = ?";
		$r = $this->query( $sql, array( $this->gedcomTable, $ID, $user));

		$sql = "delete from ! where id = ?";
		$this->query( $sql, array( $this->indexTable, $ID));
	}

	/**
 * * Updates a family record to reflect the addition of a spouse with ID = $spouseID.
 */
	function addSpouse( $familyID, $spouseID)
	{
		global $individualsTable, $familiesTable, $user;
		// get the gender of the spouse so that we know if we're adding a husband or wife
		$sex = $this->getItem( "SEX", $spouseID);

		if ( $sex == "M" || $sex == "m") { // add a husband reference
			$this->lputValue( $familyID, 'F', 'HUSB', 1, '@I' . $spouseID . '@');
		} else { // add a wife reference
			$this->lputValue( $familyID, 'F', 'WIFE', 1, '@I' . $spouseID . '@');
		}
		$this->lputValue( $spouseID, 'I', 'FAMS', 1, '@F' . $familyID . '@');
		$this->updateIndex( $spouseID);
	}

	/**
 * * Given the ID of a family file, and a person's ID, returns the (ID) spouse of the person in that specific
 * family file.
 */

	function getSpouse( $familyID, $ID)
	{
		global $user;

		$sql = "select hid from ! where tree= ? and type='F' and id= ? and ( tag='HUSB' or tag = 'WIFE') and hid <> ?";
		$r = $this->getOne( $sql, array( $this->gedcomTable, $user, $familyID, $ID));

		return ( $r != false) ? $r : 0;
	}

	function getSKey( $ID, $l1, $l2, $TreeName = "") {
		global $user;

		if ( $TreeName != "") {
			$user = $TreeName;
		}

		$sql = "SELECT level, inum, hid FROM ! WHERE tree = ? AND id= ? AND tag= ? ORDER BY hid DESC, inum";
		$r = $this->query( $sql, array( $this->gedcomTable, $user, $ID, $l1));

		if ( $r != false && $this->rowsInResult($r) > 0) {
			$a = $this->mfa($r);
			$lev = $a["level"];
			$n1 = $a["inum"];

			$sql = "SELECT data,inum FROM {$this->gedcomTable} WHERE tree = ".$this->dbh->quote($user)." AND id= ".$this->dbh->quote($ID)." AND tag= ".$this->dbh->quote($l2)." AND level > ".$this->dbh->quote($lev)." AND inum > ".$this->dbh->quote($n1).' AND hid='.(int)$a['hid'].' ORDER BY hid DESC, inum';

			$r = $this->getRow($sql);

			if ( $r != false) {
				$b = $r;
				$n2 = $b["inum"];
				$lasttag = $l1;

				if ( ( $l1 == 'BIRT' or $l1 == 'DEAT') and $l2 == 'DATE') {
					return $b["data"];
				}

				$sql = "select tag,level,inum from ! where tree = ? and id= ? and inum >= ? and inum <= ? order by inum";
				$t = $this->query( $sql, array( $this->gedcomTable, $user, $ID, $n1, $n2));

				if ( $t != false) {
					for ( $k = 0;$k < $this->rowsInResult( $t);$k++) {
						$c = $this->mfa( $t);
						if ( $c["level"] <= $lev) {
							$lasttag = $c["tag"];
						}
					}
				}
				if ( $lasttag == $l1) {
					return $b["data"];
				}
			}
		}
	}

	function printGeds( $txt)
	{
		$x = split( CRLF, $txt);
		for ( $i = 0;$i < count( $x);$i++) {
			print "<br>$i: " . $x[$i];
		}
	}

	function getIndiGeds( $ID) {
		global $user;
		$res = "";

		$sql = "SELECT level, tag, data FROM ! WHERE tree= ? AND type='I' AND id = ? ORDER BY inum";
		$r = $this->query( $sql, array( $this->gedcomTable, $user, $ID));

		if ( $r != false) {
			while ( $a = $this->mfa( $r)) {
				if ( $a["tag"] != "INDI") {
					$res .= $a["level"] . " " . $a["tag"] . " " . $a["data"] . CRLF;
				}
			}
		}
    
		return $res;
	}

	function getFamGeds( $ID)
	{
		global $user;
		$res = "";

		$sql = "select level, tag, data from ! where tree= ? and type='F' and id = ? order by inum";
		$r = $this->query( $sql, array( $this->gedcomTable, $user, $ID));

		if ( $r != false) {
			for ( $i = 0;$i < $this->rowsInResult( $r);$i++) {
				$a = $this->mfa( $r);
				if ( $a["tag"] != "FAM") {
					$res .= $a["level"] . " " . $a["tag"] . " " . $a["data"] . CRLF;
				}
			}
		}
		return $res;
	}

	function putIndiGeds( $ID, $txt, $incit = false) {
		global $user;

		$this->updateGEDCOM($txt, $ID, 'I', $incit);
	}

	function putFamGeds( $ID, $txt, $incit = false) {
		global $user;

		$this->updateGEDCOM( $txt, $ID, 'F', $incit);
	}

	function getMaxNoteID( $ID) {
		global $user;

		$sql = "select max(hid) from ! where tree= ? and type='I' AND tag = 'NREF' AND id = ?";
		$r = $this->getOne( $sql, array( $this->gedcomTable, $user, $ID));

		return ( $r != false) ? $r : false;
	}

	function getMaxMarrNoteID( $ID)
	{
		global $user;
		$sql = "SELECT max(hid) FROM ! WHERE tree=? and type='N' and tag='NOTE' and data='MARR' and id=?";
		$r = $this->getOne($sql, array($this->gedcomTable, $user, $ID));

		return ($r != false) ? $r : false;
	}

	function getMaxSourID() {
		global $user;

		$sql = 'SELECT MAX(id) FROM ! WHERE tree=? AND type=\'S\'';
		$r = $this->getOne( $sql, array( $this->gedcomTable, $user));

		return ( $r != false) ? $r : 0;
	}

	function checkExistingID( $ID)
	{
		global $user;

		$sql = "select id from ! where id= ? and tree= ? and type='I'";
		$r = $this->getOne( $sql, array( $this->gedcomTable, $ID, $user));

		return ( $r != false) ? true : false;
	}

	function getFirstID( )
	{
		global $user;

		$sql = "select id from ! where tree= ? and type='I' group by id order by id";
		$r = $this->getOne( $sql, array( $this->gedcomTable, $user));

		return ( $r != false) ? $r : '1';
	}

	function getOKey( $ID, $l1)
	{
		global $user;

		$sql = "select data from ! where tree = ? and id= ? and tag= ?";
		$r = $this->getOne( $sql, array( $this->gedcomTable, $user, $ID, $l1));
		if ( $r != false) {
			return $r;
		}
	}

	/**
 * * Reverse the 'type' of whatever is passed into the function. This function could be a replacement for
 * reverseGender.
 */
	function reversePersonType( $type)
	{
		switch ( $type) {
			case "mother" :
				return "father";
			case "father" :
				return "mother";
			case "son" :
				return "daughter";
			case "daughter" :
				return "son";
			case "brother" :
				return "sister";
			case "sister" :
				return "brother";
			case "M" :
				return "F";
			case "F" :
				return "M";
			default :
		}
	}

	/**
 * * Returns the reverse gender of $gender. $gender is either "M" or "F" or "Male" or "Female"
 */
	function reverseGender( $gender)
	{
		return stristr( $gender, "f") ? "M" : "F";
	}

	/**
 * * Create update an existing family record with the basic elements of a family: husband, wife, and child.
 */

	function removeTree( $user, $all = false)
	{
		$sql = "delete from ! where tree= ?";
		$this->query( $sql, array( $this->gedcomTable, $user));
		// delete entry in users table as well
		if ( $all) {
			$sql = "delete from `tufat_blobs` where `tree`='$user'";
			mysql_query( "$sql");

			$sql = "delete from `tufat_famgal` where `tree`='$user'";
			mysql_query( "$sql");

			$sql = "delete from `tufat_events` where `user`='$user'";
			mysql_query( "$sql");

			$sql = "delete from ! where username = ?";
			$this->query( $sql, array( $this->userTable, $user));

			$sql = "delete from ! where tree= ?";
			$this->query( $sql, array( $this->indexTable, $user));
		}
	}

	function convertDate( $oldDate)
	{
		// $oldDate is assumed to be in DAY MONTH YEAR format, e.g. 31 DEC 1990
		if ( trim( $oldDate) == "") {
			return "";
		}
		list( $day, $month, $year) = explode( " ", $oldDate);
		if ( !( $day && $month && $year))
		list ( $day, $month, $year) = explode( "/", $oldDate);
		if ( $month > 12)
		list ( $month, $day, $year) = array( $day, $month, $year);
		if ( strlen( $day) == 4 && !$month && !$year) { // this is a year-only date field
			$year = $day;
			$day = "";
			$month = "";
			return "$year-$month-$day";
		}
		// check if the date is in m-d-y format instead of d-m-y format
		if ( ereg( "[A-Z,a-z]", $day)) {
			list( $month, $day, $year) = explode( " ", $oldDate);
		}
		$months = Array( "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

		for ( $i = 0; $i < 12; $i++) {
			if ( stristr( $months[$i], $month)) {
				$month = $i + 1;
				break;
			}
		}
		if ( !$month) {
			$month = "00";
		}
		if ( !$day) {
			$day = "00";
		}
		if ( !$year) {
			$year = "0000";
		}
		return "$year-$month-$day";
	}

	function lgetNewID( $type) {
		global $user;

		$sql = "SELECT max(id) FROM ! WHERE tree= ?";
		$r = $this->getOne( $sql, array( $this->gedcomTable, $user));

		return ( $r != false) ? $r + 1 : 1;
	}

	/**
 * * Create a blank individual record, and then populate it with name, gender, and fams variables, since we known a priori that this person
 * is the spouse in the family with familyID = $famsID.
 */

	function createEmptySpouse( $gender, $famsID, $name = "Unknown", $surn = "Unknown")
	{
		global $user;
		$mid = $this->lgetNewID( 'I');
		$this->lputValue( $mid, 'I', 'INDI', 0, '@I' . $mid . '@');
		$this->lputValue( $mid, 'I', 'NAME', 1, "$name /$surn/");
		$this->lputValue( $mid, 'I', 'SURN', 1, "$surn");
		$this->lputValue( $mid, 'I', 'SEX', 1, "$gender");
		return $mid; // return the ID of the new spouse
	}

	/**
 * * Get a list of the form |ID|ID|ID|ID| of all possible families that this new child could be in,
 * based on the spouses. Return as an array of values
 */

	function getFamsList( $ID)
	{
		global $user;
		$res = array( );
		$res[] = $this->lgetFamilyC( $ID);
		return $res;
	}

	/**
 * * Returns an array of ID values that correspond to the spouses of this individual. Does not distinguish
 * between male and female spouses. Thus, gay spouses are acceptable.
 */
	function getSpouses($ID) {
		global $user;
		$res = array( );

		$sql = "select id from {$this->gedcomTable} where tree= ".$this->dbh->quote($user)." and type='F' and (tag='WIFE' or tag='HUSB') and hid = ".(int)$ID." group by id";
		$r = $this->getCol($sql);
		if ( count( $r) != 0) {
			foreach ( $r as $fid) {
				$rid = $this->getSpouse( $fid, $ID);
				if ( $rid > 0) {
					if ( @!in_array( $rid, $res)) {
						$res[] = $rid;
					}
				}
			}
		}
    
		return $res;
	}

	/**
 * * Returns a 2-item array of ($marriagePlace, $marriageDate) for the marriage of these two persons.
 */

	function getMarriageInfo( $sp1, $sp2) {
		global $user;
		$fid = $this->getMarriageID( $sp1, $sp2);
		$gedcom = $this->getGEDCOM( 'F' , $fid);
		$vars = $this->extractGEDCOM( $gedcom);
		$res = array( );
		$res[] = $vars["marr_plac"];
		$res[] = $vars["marr_date"];
		return $res;
	}

	function areDivorced( $sp1, $sp2)
	{
		global $user;
		$fid = $this->getMarriageID( $sp1, $sp2);
		$gedcom = $this->getFamGeds( $fid);
		$line = split ( CRLF, $gedcom);
		for ( $i = 0;$i < count( $line);$i++) {
			if ( $line[$i] == '1 DIV Y') {
				return true;
			}
		}
		return false;
	}

	function doDivorce ( $sp1, $sp2)
	{
		global $user;
		if ( $this->areDivorced( $sp1, $sp2) == false) {
			$fid = $this->getMarriageID( $sp1, $sp2);
			$gedcom = $this->getFamGeds( $fid);

			$gedcom .= '1 DIV Y' . CRLF;
			$this->putFamGeds( $fid, $gedcom);
		}
	}

	function doUnDivorce ( $sp1, $sp2)
	{
		global $user;
		if ( $this->areDivorced( $sp1, $sp2)) {
			$fid = $this->getMarriageID( $sp1, $sp2);
			$gedcom = $this->getFamGeds( $fid);
			$line = split ( CRLF, $gedcom);
			$s = '';
			for ( $i = 0;$i < count( $line);$i++) {
				if ( $line[$i] == '1 DIV Y') {
					continue;
				} else {
					$s .= $line[$i] . CRLF;
				}
			}
			$this->putFamGeds( $fid, $s);
		}
	}

	/**
 * * Updates the raw GEDCOM associated with either the individuals file or the families file.
 */
	function updateGEDCOM($gedcom, $ID, $type, $incit = false) {
		global $user;

		$inBirt = false;
		$inDeat = false;
		$inBuri = false;
    $inChan = false;
    $inAddr = false;

		$lines = array( );
		$lines = split( CRLF , $gedcom);
		
		for ( $i = 0;$i < count( $lines);$i++) {
			$tr = split( " ", $lines[$i]);
			$data = trim( check_slash( ( substr( $lines[$i], 6, ( strlen( $lines[$i])-6)))));
			$tag = trim( $tr[1]);
			$level = trim( $tr[0]);
			$qr = '';
			$mhid = -1;

      if ($level == 1) {
				$inBirt = false;
				$inDeat = false;
        $inChan = false;
        $inAddr = false;
      }

			if ( $tag == 'FAMS' || $tag == 'FAMC' || $tag == 'CHIL' || $tag == 'WIFE' || $tag == 'HUSB') {
				preg_match( "/((\d)+)/", $data, $lik);
				$mhid = $lik[0];

				if ( $tag == 'FAMS' || $tag == 'FAMC') {
					$data = '@F' . $mhid . '@';
				} else {
					$data = '@I' . $mhid . '@';
				}
			}

			if ($tag == 'CHAN')  {
				$inBirt = false;
				$inDeat = false;
        $inChan = true;
        $inAddr = false;
      }

			if (false && $tag == 'ADDR') {
				$inBirt = false;
				$inDeat = false;
        $inChan = false;
        $inAddr = true;
      }
      
			if ( $tag == 'BIRT') {
				$inBirt = true;
				$inDeat = false;
        $inChan = false;
        $inAddr = false;
			}
			
			if ( $tag == 'DEAT') {
				$inBirt = false;
				$inDeat = true;
        $inChan = false;
        $inAddr = false;
			}
      
      if ($inChan) {
        $mhid = -44;
      }
      
			if ( $tag == 'BURI') {
				$inBuri = true;
			}

			if ($inBirt) {
				$mhid = -12;
			}

			if ($inDeat)
        $mhid = -22;

			if ($inBuri) {
				$mhid = -33;
			}

			if ($inAddr) {
				$mhid = -13;
			}

			if ($tag == 'URL' || $tag == 'NOTE')  {
        $mhid = -1;
      }

			if ( $tag == 'PLAC' and $inBuri)  {
				$inBuri = false;
			}
			
			if ( $tag == 'DATE') {
				if ( !$incit) {
					$data = $this->mysqlDateFormat( $data);
				}
			}

			if ( $tag == 'NREF' || $tag == 'SREF') {
				if ( $tag == 'NREF') {
					preg_match( "/N[IS]((\d+))/", $data, $m);
				}
				
				if ( $tag == 'SREF') {
					preg_match( "/S((\d+))/", $data, $m);
				}
				
				$mhid = $m[1];
				if (!($mhid > 0)) {
					if ($tag == 'NREF')
            preg_match( "/N((\d+))/", $data, $m);

					$mhid = $m[1];
				}
			}   

			if ( preg_match( "/\d/", $tag) == 0 && $tag != '') {
				if (empty($data)) {
					$data = " ";
        }

				$params = array( $this->gedcomTable, $ID, $user, $type, $tag, $level, $mhid);
        $sql = "SELECT inum FROM ! WHERE id=? AND tree=? AND type=? AND tag=? AND level=? AND hid=?";
        $inum = $this->getOne($sql, $params);
        if ($inum) {
          $sql = "UPDATE ! SET data=? WHERE inum=?";
          $r = $this->query($sql, array( $this->gedcomTable, $data, $inum));
        } else {
  				$sql = "INSERT INTO ! (id, tree, type, tag, level, data, hid) VALUES ( ?, ?, ?, ?, ?, ?, ?)";
  				$r = $this->query( $sql, array( $this->gedcomTable, $ID, $user, $type, $tag, $level, $data, $mhid));
        }
			}
		}

		if ( $type == 'I') {
			$this->updateIndex( $ID);
		}
	}


  /**
      * remove record for child from family records
      */
  function dropChild($parentID, $childID) {
    global $user;
    
		$sql = "select id from {$this->gedcomTable} where tree= ".$this->dbh->quote($user)." and type='F' and  (tag = 'WIFE' or tag = 'HUSB')  and hid = ".(int)$parentID." group by id";
		list($q) = $this->getCol($sql);
    
    $sql = 'DELETE FROM '.$this->gedcomTable.' WHERE id='.(int)$q.' AND tree= '.$this->dbh->quote($user).' AND type=\'F\' AND tag = \'CHIL\' AND hid = '.(int)$childID;
    $this->dbh->exec($sql);
    
  }

  /**
      * remove record for siblings from family records
      */
  function dropSibling($parentID, $siblingID) {
		global $user;
    
		$p1id = -1;
		$p2id = -1;
		$sib = array( );

		// Ensure we have 1/2 siblings too
		$fid = $this->lgetFamilyC($parentID);
		$p1id = $this->getFather($parentID);
		$p2id = $this->getMother($parentID);

		if ( "$p1id" == "") {
			$p1id = -1;
		}
		if ( "$p2id" == "") {
			$p2id = -1;
		}

		if ( $p1id == -1 && $p2id == -1) {
			return null;
		}

		$sql = "select id from {$this->gedcomTable} where tree= ".$this->dbh->quote($user)." and type='F' and ( hid = ".$this->dbh->quote($p1id)." or hid = ".$this->dbh->quote($p2id)." ) and (tag = 'WIFE' or tag = 'HUSB') group by id";

		list($q) = $this->getCol($sql);
    $sql = 'DELETE FROM '.$this->gedcomTable.' WHERE id='.(int)$q.' AND tree= '.$this->dbh->quote($user).' AND type=\'F\' AND tag = \'CHIL\' AND hid = '.(int)$siblingID;
    $this->dbh->exec($sql);
    
  }
/**
 * * Returns an array of ID values that correspond to the children of the specified sex. $sex = "M" or $sex = "F"
 * This function is similar to getSiblings. If no sex is specified, then all children are retrieved, regardless of sex.
 */
	function getChildren( $ID, $sex = "") {
		global $user;
		$sib = array( );

		$sql = "select id from {$this->gedcomTable} where tree= ".$this->dbh->quote($user)." and type='F' and  (tag = 'WIFE' or tag = 'HUSB')  and hid = ".$this->dbh->quote($ID)." group by id";
		$q = $this->getCol($sql);

		if (count($q) != 0) {
			foreach ( $q as $fid) {
				$sql = "select data from {$this->gedcomTable} where tree= ".$this->dbh->quote($user)." and type='F' and tag = 'CHIL' and id = ".$this->dbh->quote($fid);
        
				$r = $this->getCol($sql);

				if ( count( $r) != 0) {
					foreach ( $r as $aid) {
						$mid = $this->ged2id( $aid);
						if ( $sex == "") {
							if ( @!in_array( $mid, $sib))
							$sib[] = $mid;
						} else {
							$sql = "select id from ! where  tree= ? and type='I' and tag='SEX' and data= ? and id= ?";
							$p = $this->getOne( $sql, array( $this->gedcomTable, $user, $sex, $mid));
							if ( $p != false || $p != '') {
								if ( $mid != $ID) {
									if ( @!in_array( $mid, $sib)) {
										$sib[] = $mid;
									}
								}
							}
						}
					}
				}
			}
		}

		return $sib;
	}

	/**
 * * Takes a GEDCOM string and builds an associative array of variables representing the string.
 */

	function extractGEDCOM($gedcomString) {
		// split the string according to the CRLF specified in the config.php file
		$gedcomLines = explode( CRLF, $gedcomString);
		$result = Array( ); // the result array
		$noteArray = Array( );
		$ctitArray = Array( );
		$hinfArray = Array( );
		$under = 0;

		foreach( $gedcomLines as $line) {
			// get the first two tokens of the line and put into $indent & $gedcomKey variables
			if ( trim( $line) == "") {
				continue;
			}

			$lineTokens = explode( " ", $line);
			// remove the first two tokens and put into variables
			$indent = array_shift( $lineTokens);
			$key = array_shift( $lineTokens);
			// if $key == "note", then we may need to reconstruct the note from CONT lines!
			$keyTest = strtolower( $key);
			if ( ( substr( $line, 0, 1) == 1 && $keyTest == "note") || ( substr( $line, 0, 1) == 2 && ( $keyTest == "cont" || $keyTest == "conc") && $under == 1)) {
				if ( $keyTest == "note") {
					$under = 1;
				}

				if ( substr( $line, 0, 6) == '2 CONC') {
					$noteArray [] = str_replace( "2 CONC", "2 CONT", $line);
				} else {
					$noteArray [] = $line;
				}

				continue;
			}

			if ( $keyTest == "hinf" || ( $keyTest == "cont" && $under == 2)) {
				if ( $keyTest == "hinf") {
					$under = 2;
				}
				$hinfArray [] = $line;
				continue;
			}
			if ( $keyTest == "ctit" || ( $keyTest == "cont" && $under == 3)) {
				if ( $keyTest == "ctit") {
					$under = 3;
				}
				$ctitArray [] = $line;
				continue;
			}
			// reconstruct the remaining tokens as a new string
			$value = implode( " ", $lineTokens);
			$indentTokens[$indent - 1] = $key;
			$fullkey = "";
			// $fullkey is the concatenation of gedcom $key values from prior levels
			for ( $i = $indent - 1; $i >= 0; $i--) {
				if ( $fullkey != "") {
					$fullkey = "_" . $fullkey;
				}
				$fullkey = trim( $indentTokens[$i]) . $fullkey;
			}
			// $fullKey = str_replace( "CRLF_", "_", $fullKey );
			$result[ strtolower( $fullkey) ] = trim( stripslashes( $value));
		}

		// now reconstruct the note field from $noteArray
		$result['note'] = trim( stripslashes( $this->buildNote( $noteArray)));
		$result['hinf'] = trim( stripslashes( $this->buildNote( $hinfArray)));
		$result['ctit'] = trim( stripslashes( $this->buildNote( $ctitArray)));
		return $result;
	}

	/**
 * * Builds a single string from
 *
 * 1 NOTE blah blah
 * 1 NOTE
 * 2 CONT blah blah blah blah...
 * 2 CONT blah blah blah blah...
 * 1 NOTE
 * 2 CONT blah blah blah blah...
 *
 * ... etc ...
 */

	function buildNote( $noteArray) {
		if ( sizeof( $noteArray) == 0) {
			return "";
		}
    
		$result = "";
		foreach( $noteArray as $line) {
			if ( trim( $line) == "1 NOTE" || trim( $line) == "1 CTIT" || trim( $line) == "1 HINF") {
				$result .= CRLF;
			} elseif ( strstr( $line, "1 NOTE ")) {
				$result .= str_replace( "1 NOTE ", "", $line) ;
				if ( strlen( $line) < MAXLINELENGTH - 7) {
					$result .= CRLF;
				}
			} elseif ( strstr( $line, "1 HINF ")) {
				$result .= str_replace( "1 HINF ", "", $line) . CRLF;
			} elseif ( strstr( $line, "1 CTIT ")) {
				$result .= str_replace( "1 CTIT ", "", $line) . CRLF;
			} else {
				$result .= str_replace( "2 CONT ", "", $line);
				if ( strlen( $line) < MAXLINELENGTH - 7) {
					$result .= CRLF;
				}
			}
		}

		return $result;
	}

/**
 * * Builds a GEDCOM string, e.g.
 * 1 NAME George Bush
 * 1 BIRT
 * 2 DATE December 2, 1944
 * from the $_POST passed into it, and returns the resulting string.
 */

	function buildGEDCOM($gedcom_source = null, $skeleton = null) {
		if (is_null($gedcom_source)) {
      $gedcom_source = $_POST;
    } 
    
    if (!is_null($skeleton)) {
      $gedcom_source = array_merge($skeleton, $gedcom_source);
    }
    
    $keys = array_keys($gedcom_source);
		
		$gedcom = array();
		$previousKey = "";

    if (empty($gedcom_source['deat_date'])) {
      $gedcom_source['deat_date'] = '0000-00-00';
    }
			
    if (empty($gedcom_source['birt_date'])) {
      $gedcom_source['birt_date'] = '0000-00-00';
    }
			
    foreach($keys as $key) {
      $gedcom_record = '';
      $value = strip_tags(trim($gedcom_source[$key]));

			if ( ( !$value) || $key == "submit" || $key == "ID" || $key == "personID" || $key == "personType" || $key == "otherParentName" || $key == "otherParentSurn" || $key == "sp1" || $key == "sp2" || $key == "notify" || $key == "submitForm" || $key == "lock_password" || $key == 'new') {
				continue;
			}
      
			// append surname info to name key
			if (strtolower($key) == "name") {
				$value .= " /" . strip_tags( $gedcom_source['surn']) . "/";
      }

			// skip non-standard or unsupported GEDCOM tags (tags that start with _)
			if (strpos($key, "_") === 0) {
				continue;
			} else {
				$keyTokens = explode( "_", $key);
			}
			$indent = sizeof($keyTokens);

			$gedcomKey = $keyTokens[ $indent - 1 ]; // the final token of the gedcomKey
			if ( stristr( $keyTokens[0], "fams") || stristr( $keyTokens[0], "famc")) {
				$list = explode( "|", $value);
				foreach( $list as $ID) {
					if ( $ID) {
						$gedcom_record .= "1 " . strtoupper( $keyTokens[0]) . " @F" . $ID . "@" . CRLF;
					}
				}
			} elseif ( strtolower( $keyTokens[0]) == "note") {
				// MAXLINELENGTH = 74; //the max note line length before splitting onto '2 CONT' lines
				// if this is a note field, then split the note into many lines
				$noteArray = explode( CRLF, $value); // split according to new line chracters
				for( $i = 0;$i < count( $noteArray);$i++) {
					$note = $noteArray[$i];
					if ( strlen( $note) <= MAXLINELENGTH) {
						$gedcom_record .= ( $i == 0) ? "1 NOTE $note" . CRLF : "2 CONT $note" . CRLF;
					} else {
						$gedcom_record .= ( $i == 0) ? "1 NOTE" . CRLF : "2 CONT" . CRLF;
						// split the data onto multiple lines
						$gedcom_record .= $this->splitGEDCOMField( $note, MAXLINELENGTH);
					}
				}
			} elseif ( strtolower( $keyTokens[0]) == "ctit") {
				// MAXLINELENGTH = 74; //the max note line length before splitting onto '2 CONT' lines
				// if this is a note field, then split the note into many lines
				$noteArray = explode( CRLF, $value); // split according to new line chracters
				foreach( $noteArray as $note) {
					if ( strlen( $note) <= MAXLINELENGTH) {
						$gedcom_record .= "1 CTIT $note" . CRLF;
					} else {
						$gedcom_record .= "1 CTIT" . CRLF;
						// split the data onto multiple lines
						$gedcom_record .= $this->splitGEDCOMField( $note, MAXLINELENGTH);
					}
				}
			} elseif ( strtolower( $keyTokens[0]) == "hinf") {
				// MAXLINELENGTH = 74; //the max note line length before splitting onto '2 CONT' lines
				// if this is a note field, then split the note into many lines
				$noteArray = explode( CRLF, $value); // split according to new line chracters
				foreach( $noteArray as $note) {
					if ( strlen( $note) <= MAXLINELENGTH) {
						$gedcom_record .= "1 HINF $note" . CRLF;
					} else {
						$gedcom_record .= "1 HINF" . CRLF;
						// split the data onto multiple lines
						$gedcom_record .= $this->splitGEDCOMField( $note, MAXLINELENGTH);
					}
				}
			} else {
				if (empty($previousKey)) { // to avoid an empty delimiter error
					$previousKey = " ";
				}
        
 				if ( $indent > 1 && !stristr( $key, $previousKey)) {
					$gedcom_record .= "1 " . strtoupper( $keyTokens[0]) . CRLF;
				}
 				$gedcom_record .= $indent . " " . strtoupper($gedcomKey) . " ";

				if ( ereg ( "([0-9]{0,4})-([0-9]{1,2})-([0-9]{1,2})", $value)) {
					$gedcom_record .= $this->dateFormat($value, 2);
				} else {
					$gedcom_record .= $value;
 				}
 				$gedcom_record .= CRLF;
   		}
      	
      $gedcom[] = $gedcom_record;
      $previousKey = $keyTokens[0];
    }

		$gedcom = array_map('trim', $gedcom);
    $gedcom = implode(CRLF, $gedcom);

		return trim($gedcom);
	}

	/**
 * * Splits a large GEDCOM field onto multiple "CONT" lines.
 *
 * E.g. ABCDEFGHIJKLMNOPQRSTUVWXYZ would be split into
 *
 * 2 CONT ABCDEFG
 * 2 CONT HIJKLMN
 * 2 CONT OPQRSTU
 * 2 CONT VWXYZ
 *
 * for MAXLINELENGTH = 7
 */
  function splitGEDCOMField( $note, $maxLineLength) {
		$crlfSize = strlen(CRLF);
		$noteSize = strlen($note);
		for ( $i = 0; $i < $noteSize; $i = $i + $maxLineLength + 1) {
			$note = substr_replace( $note, CRLF, $i, 0); // insert a space at the 30th character in $subjectItem
		}

		$noteArray = explode( CRLF, $note);
		foreach( $noteArray as $note) {
			if ( !$note) {
				continue;
			}
			$result .= "2 CONT $note" . CRLF;
		}
		return $result;
	}

	function procGedcom( $ng, $og) {
		$tags = array('ADOP',	'BLES',	'BAPM',	'BARM',	'BASM',	'BURI',	'CENS',	'CHRA',	'CONF',	'CREM',	'GRAD',	'EDUC',	'EMIG',	'IMMI',	'MILI',	'NATU',	'OCCU',	'ORDN',	'PROB',	'RESI',	'RETI',	'WILL',	'BAPL',	'CONL',	'ENDL',	'EVEN',	'CHR',	'FCOM',	'ORDI',	'ANUL',	'CENS',	'DIV',	'DIVF',	'ENGA',	'MARC',	'MARL',	'MARB',	'MARS');

		$str = array();
		foreach( $tags as $k => $v) {
			$str[$v] = '';
		}

		$bstr = '';
		$dstr = '';
		$bmstr = '';
		$adstr = '';
		$nmstr = '';
		$brstr = '';
		$cnfstr = '';
		$gdstr = '';
		$imstr = '';
		$mrstr = '';
		$dvstr = '';

		$s = '';
		$esstr = '';
		$btag = '';
		$grepl = array( );
		$es = 0;

		$line = split( CRLF, $og);
		for ( $i = 0;$i < count( $line); $i++) {
			if ( substr( $line[$i], 0, 2) == "0 " || substr( $line[$i], 0, 2) == "1 ") {
				$bi = 0;
				$di = 0;
				$bm = 0;
				$nm = 0;
				$ad = 0;
				$br = 0;
				$cn = 0;
				$gr = 0;
				$im = 0;
				$mr = 0;
				$dv = 0;

				foreach( $tags as $k => $v) {
					$ind[$v] = 0;
				}
			}

			if ( substr( $line[$i] , 0, 6) == "1 BIRT") {
				$bi = 1;
			}
			if ( substr( $line[$i] , 0, 6) == "1 MARR") {
				$mr = 1;
			}
			if ( substr( $line[$i] , 0, 6) == "1 DEAT") {
				$di = 1;
			}
			if ( substr( $line[$i] , 0, 6) == "1 NAME") {
				$nm = 1;
				$tn = $line[$i];
			}

			if ( substr( $line[$i], 0, 6) == "2 NREF" || substr( $line[$i], 0, 6) == "2 SREF") {
				$j = $i;
				while ( $j < count( $line)) {
					if ( $bi == 1) {
						$bstr .= $line[$j] . CRLF;
					}
					if ( $di == 1) {
						$dstr .= $line[$j] . CRLF;
					}
					if ( $bm == 1) {
						$bmstr .= $line[$j] . CRLF;
					}
					if ( $nm == 1) {
						$nmstr .= $line[$j] . CRLF;
					}
					if ( $ad == 1) {
						$adstr .= $line[$j] . CRLF;
					}
					if ( $br == 1) {
						$brstr .= $line[$j] . CRLF;
					}
					if ( $cn == 1) {
						$cnfstr .= $line[$j] . CRLF;
					}
					if ( $im == 1) {
						$imstr .= $line[$j] . CRLF;
					}
					if ( $gr == 1) {
						$gdstr .= $line[$j] . CRLF;
					}
					if ( $mr == 1) {
						$mrstr .= $line[$j] . CRLF;
					}
					if ( $dv == 1) {
						$dvstr .= $line[$j] . CRLF;
					}

					$j++;
					if ( substr( $line[$j], 0, 1) <= 2) {
						break;
					}
				}
			}
		}

		if ( strstr( $og, "1 DEAT") != false && strstr( $ng, "1 DEAT") == false) {
			$ng .= CRLF . "1 DEAT" . CRLF;
		}
		if ( strstr( $og, "1 BIRT") != false && strstr( $ng, "1 BIRT") == false) {
			$ng .= CRLF . "1 BIRT" . CRLF;
		}

		if ( strstr( $og, "1 MARR") != false && strstr( $ng, "1 MARR") == false) {
			$ng .= CRLF . "1 MARR" . CRLF;
		}

		$ng = preg_replace( "/1 BIRT/", "1 BIRT" . CRLF . $bstr, $ng, 1);
		$ng = preg_replace( "/1 DEAT/", "1 DEAT" . CRLF . $dstr, $ng, 1);
		$ng = preg_replace( "/1 MARR/", "1 MARR" . CRLF . $mrstr, $ng, 1);
		$ng = str_replace( $tn, $tn . CRLF . $nmstr, $ng);

		foreach ($tags as $k => $v) {
			for ($i = 1; $i < 15; $i++) {
				$res = $this->fromtoG( $og, $v, $i);
				$res = split( ":", $res);
				if ( $res[0] > 0) {
					$txt = $this->gettxtfromtoG( $res[0], $res[1], $og);
					if ( strlen( $txt) > 0) {
						$ng .= CRLF . $txt;
					}
				}
			}
		}

		return $ng;
	}

	/**
 * * Adds a user to the users database.
 */
	function addUser( $saname, $sdname, $username, $password, $readOnlyPassword, $adminPassword, $email, $crosstree)
	{		
		$today = date( "Y-m-d" , time( ));

		$sql = "insert into ! (username, aname, dname, password,read_only_password, admin_password, email, crosstree, created, lastlogin)
						values (  ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$this->query( $sql, array( TBL_USER, $username, $saname, $sdname, $password, $readOnlyPassword, $adminPassword, $email, $crosstree, $today, $today));
	}

	/**
 * * Retrieve the admin and read only password as an array.
 */
	function getPasswords( $username) {
		$sql = "select password, read_only_password, admin_password from ".TBL_USER." where username = ".$this->dbh->quote($username);
		$result = $this->dbh->queryRow($sql, null, MDB2_FETCHMODE_ORDERED);
		return $result;
	}

	/**
 * * Check to see if $username is a valid tree name (case-insensitive).
 */
	function isUser( $username)
	{
		if ( !$username) {
			return false;
		}

		$sql = "select ID from ! where username = ?";
		return $this->getOne( $sql, array( TBL_USER, $username));
	}

	function setLoginValue( $field, $value)
	{
		global $user;

		$sql = "update ! set ! = ? where username = ?";
		return $this->query( $sql, array( TBL_USER, $field, $value, $user));
	}

	function getLoginValue($field) {
		global $user;

		$sql = "select DATE_FORMAT( ! , ? ) FROM ! WHERE username = ?" ;
		return $this->getOne( $sql, array( $field, LONGDATEFORMAT, TBL_USER, $user));
	}

	/**
 * * Retrieve all the information for this person and put into a private associative array.
 */
	function load( $ID)
	{
		global $user; // the login name (also the diretory that the tree is stored in)
		$contents = file( $user . "/$ID.txt");
	}

	/**
 * * Given a GEDCOM key and a body of text, extracts the value associated with the GEDCOM key.
 * Assumes that lines of $data are separated by carriage returns defined by CRLF. This function should
 * be equally usable for the mysql 'info' field as well as .txt files.
 */

	function getGedcomValue( $key, $data) {
		// split the $data block into an array of lines
		$lines = explode( CRLF, $data);

		foreach( $lines as $line) {
			$line = trim( $line);

			if ( !$line) {
				continue;
			}

			$words = explode( " ", $lines[0]);
			// remove the numeric line starter
			array_shift( $words);

			if ( empty( $words[0])) {
				continue;
			}

			if ( strtolower( $key) == strtolower( $words[0])) {
				// shift first element ($key) off beginning of line
				array_shift( $words);
				// concatenate remaining elements & return result
				return implode( " ", $words);
			} elseif ( stristr( $key . "_", $words[0])) { // we are looking for a subkey line
				$keyParts = explode( "_", $key);
				array_shift( $keyParts);
				// reconstruct key and call function recursively on remaining data
				return $this->getGedcomValue( implode( "_", $keyParts), implode( CRLF, $lines));
			}
			// remove this line from the line array
			array_shift( $lines);
		}
	}

	/**
 * * Return the ID value of the family whereby $spouse1 and $spouse2 are spouses.
 */

	function getMarriageID( $spouse1, $spouse2) {
		global $familiesTable, $user;
		$p1 = -1;

		$sql = "select id from {$this->gedcomTable} where tree= ".$this->dbh->quote($user)." and type='F' and hid = ".$this->dbh->quote($spouse1);
		$r = $this->getCol($sql);

		if ( count( $r) != 0) {
			foreach ( $r as $p1) {
				$sql = "select id from ! where tree= ? and type='F' and id = ? and hid = ?";
				$p = $this->getOne( $sql, array( $this->gedcomTable, $user, $p1, $spouse2));
				if ( $p != '') {
					return $p1;
				}
			}
			return false;
		}
	}

  /**
   * @brief verifty if id with some information exists in GEDCOM table
   * @param $ID ID for records in GEDCOM
   */
  function gedcom_id_exists($ID, $tree = null) {
    global $user;

    if (is_null($tree))
      $tree = $user;

    $sql = 'SELECT * FROM %s WHERE ID=%d AND tree=\'%s\' LIMIT 0, 1';
    $sql = sprintf($sql, $this->gedcomTable, $ID, $tree);

    $res =& $this->query($sql);
    if (PEAR::isError($res)) {
      die($res->getMessage());
    }

    if ($res->numRows()) {
      return true;
    } else {
      return false;
    }

  }


	/**
 * * Given a list of GEDCOM-format keys as string, and an ID for the individual, returns an
 * array with a corresponding value for each key by repeatedly calling getItem
 */

	function getItems($gedcomKeyArray, $ID) {
    global $individualsTable, $user, $db;
    $gedcomKeyArray = array_map('strtoupper', $gedcomKeyArray);
    $result = array();

    foreach($gedcomKeyArray as $key) {
      $result[] = $this->getItem($key, $ID);
    }

    return $result;
	}

	function generateFieldNameList($table) {
		$fields = $this->dbh->reverse->tableInfo( $table );
		//$fields = &$this->dbh->tableInfo( $table);

		$this->fieldNameList = Array( );
		foreach ( $fields as $k => $val) {
			$this->fieldNameList[] = $val['name'];
		}
	}

	/**
 * * Retrieves the "info" field of a record, given the ID.
 */
	function getGEDCOM( $type, $ID) {
		global $user;
		$res = '';

		$sql = "SELECT level, tag, data FROM ! WHERE tree= ? AND type = ? AND id = ? ORDER BY hid DESC, inum" ;
		$result = &$this->query( $sql, array( $this->gedcomTable, $user, $type, $ID));

		if ( MDB2::isError( $result)) {
			return ( $result->getMessage( ));
		} else {
			while ($a = $this->mfa($result)) {
				$res .= $a["level"] . " " . $a["tag"] . " " . $a["data"] . CRLF;
			}
		}
		return $res;
	}

    /**
    * Insert or update portrait image
    */
	function addPortrait( $ID, $data) {
		global $individualsTable, $user;

		$sql = "SELECT id FROM {$this->blobsTable} WHERE id = ".$this->dbh->quote($ID)." AND type='P' AND tree = ".$this->dbh->quote($user);
		$r = $this->getCol($sql);

		if (count($r) < 1) {
			$sql = "INSERT INTO ! (id, tree, data, type, ta) VALUES ( ?, ?, '', 'P', UNIX_TIMESTAMP())";
			$this->query( $sql, array( $this->blobsTable, $ID, $user));
		} else {
          $sql = "UPDATE ! SET data = ?, ta = UNIX_TIMESTAMP() where id = ? and tree = ? and type = 'P'";
          $this->query( $sql, array( $this->blobsTable, $data, $ID, $user));		
        }
	}

	function addBio( $ID, $data)
	{
		global $user;

		$sql = "select id from ! where id = ? and type='B' and tree= ?";
		$r = $this->getOne( $sql, array( $this->blobsTable, $ID, $user));

		$t = time();

		if ( $r != $ID) {
			$sql = "insert into ! (id, data, tree, type, ta) values ( ?, ?, ?, 'B' , ? )";
			$r = $this->query( $sql, array( $this->blobsTable, $ID, $data, $user, $t));
		}
		else {
			$sql = "update ! set data = ?, ta = ? where id = ? and tree = ? and type = 'B'";
			$r = $this->query( $sql, array( $this->blobsTable, $data, $t, $ID, $user));
		}
	}

	function getPortrait( $ID) {
		global $user;

		$sql = "select data from ! where tree= ? and id = ? and type='P'";
		$r = $this->getOne( $sql, array( $this->blobsTable, $user, $ID));
		if ( $r != false) {
			return $r;
		}
	}

	function hasPortrait( $ID)
	{
		global $user;
		$sql = "select data from ! where tree= ? and id = ? and type='P'";
		$r = $this->getOne( $sql, array( $this->blobsTable, $user, $ID));

		if ( $r != false) {
			return ( strlen( $r) > 0) ? true : false;
		} else {
			return false;
		}
	}

	function setSourItem( $it, $dat, $sid)
	{
		global $user;

		$sql = "select inum from ! where tree= ? and type='S' and tag = ? AND id = ?";
		$r = $this->getOne( $sql, array( $this->gedcomTable, $user, $it, $sid));
		if ( $r != false) {
			$klinum = $r;
		}
		if ( $it == 'NOTE' || $it == 'TEXT') {
			$sql = "select inum, tag, level from ! where tree= ? and type='S' and id = ? order by inum";
			$r = $this->query( $sql, array( $this->gedcomTable, $user, $sid));

			if ( $r != false && $this->rowsInResult( $r) > 0) {
				for ( $i = 0;$i < $this->rowsInResult( $r);$i++) {
					$b = $this->mfa( $r);
					if ( $b["level"] < 2) {
						break;
					}
					if ( $b["tag"] == "CONT" || $b["tag"] == "CONC") {
						$sql = "delete from ! where tree= ? and type='S' and id = ? and inum = ?";
						$this->query( $sql, array( $this->gedcomTable, $user, $sid, $b["inum"]));
					}
				}
			}
		}
		$sql = "delete from ! where tree= ? and type='S' and id = ? and tag = ?" ;
		$r = $this->query( $sql, array( $this->gedcomTable, $user, $sid, $it));

		if ( $it == 'NOTE' || $it == 'TEXT') {
			$s = array( );
			$i = 0;
			while ( ( $substr = substr( $dat, $i, MAXLINELENGTH)) != false) {
				$s[] = $substr;
				$i += MAXLINELENGTH;
			}

			$sql = "insert into ! (tree, tag, type, data, level, id) values ( ?, ?, 'S', ?, '1', ? )";
			$this->query( $sql, array( $this->gedcomTable, $user, $it, addslashes( $s[0]), $sid));

			for ( $i = 1; $i < count( $s); $i++) {
				$sql = "insert into ! (tree, tag, type, data, level, id) values ( ?, 'CONT', 'S', ?, '2', ?)";
				$this->query( $sql, array( $this->gedcomTable, $user, addslashes( $s[$i]), $sid));
			}
		} else {
			$sql = "insert into ! (tree, tag, type, data, level, id) values ( ?, ?, 'S', ?, '1', ?)";
			$this->query( $sql, array( $this->gedcomTable, $user, $it, addslashes( $dat), $sid));
		}
	}

	function getSourItem( $it, $sid) {
		global $user;
		$res = "";

		$sql = "select * from ! where tree= ? and type='S' and id =? and tag= ? order by inum";
		$r = $this->query( $sql, array( $this->gedcomTable, $user, $sid, $it));

		if ( $r != false) {
			for ( $i = 0;$i < $this->rowsInResult( $r); $i++) {
				$a = $this->mfa( $r);
				$res .= stripslashes( $a["data"]);
				$lind = $a["inum"];
				$llev = $a["level"];
			}
			if ( $lind > 0 && ( $it == 'NOTE' || $it == 'TEXT')) {
				$sql = "select * from ! where type='S' and id=? order by inum";
				$p = $this->query( $sql, array( $this->gedcomTable, $sid));

				if ( $p != false) {
					for ( $j = 0;$j < $this->rowsInResult( $p); $j++) {
						$b = $this->mfa( $p);
						if ( $b["level"] < 2) {
							break;
						}
						$res .= stripslashes( $b["data"]);
					}
				}
			}
			return htmlentities( $res, ENT_QUOTES);
		}
	}

	function setCitItem( $mr, $ID, $xtag, $hid, $data)
	{
		// replaced by Pat K. for better db indexing
		global $user;

		$mrtyp = ( $mr)?'F':'I';
		$sql = "SELECT inum FROM ! WHERE id=? AND tree=? AND type=? AND hid=? AND tag='SREF'";
		$r = $this->query( $sql, array( $this->gedcomTable, $ID, $user, $mrtyp, $hid));
		if ( $r != false) {
			if ( $xtag == 'NOTE') {
				$sql = "DELETE FROM ! WHERE tree=? AND id=? AND hid=? AND type='C' AND tag IN ('NOTE', 'CONT')";
				$this->query( $sql, array( $this->gedcomTable, $user, $ID, $hid));
			} else {
				$sql = "DELETE FROM ! WHERE tree=? AND id=? AND hid=? AND tag=? AND type='C'";
				$this->query( $sql, array( $this->gedcomTable, $user, $ID, $hid, $xtag));
			}
		}

		if ( $xtag == 'NOTE') {
			$notestr = split( CRLF, $data);
			$notearray = array( );
			foreach ( $notestr as $k => $line) {
				$thisline = $line;
				$np = 0;
				$i = 0;
				while ( strlen( $thisline) > MAXLINELENGTH) {
					$i++;
					$notearray[] = substr( $thisline, 0, MAXLINELENGTH);
					$thisline = substr( $thisline, MAXLINELENGTH);
				}

				if ( $thisline != "") {
					$notearray[] = $thisline;
				}
			}

			$sql = "INSERT INTO ! (id, tree, hid, tag, data, type, level) VALUES ( ?, ?, ?, ?, ?, 'C', '1')";
			$this->query( $sql, array( $this->gedcomTable, $ID, $user, $hid, 'NOTE', '@CN' . $hid . '@'));

			$sql = "INSERT INTO ! (id, tree, hid, tag, data, type, level) VALUES ( ?, ?, ?, ?, ?, 'C', '2')";
			foreach ( $notearray as $k => $v) {
				$this->query( $sql, array( $this->gedcomTable, $ID, $user, $hid, 'CONT', addslashes( $v)));
			}
		} else {
			$sql = "INSERT INTO ! (id, tree, hid, tag, data, type, level) VALUES ( ?, ?, ?, ?, ?, 'C', '1')";
			$this->query( $sql, array( $this->gedcomTable, $ID, $user, $hid, strtoupper( $xtag), $data));
		}
	}

	function getCitItem( $ID, $xtag, $hid) {
		global $user;
    
		$sql = "SELECT tag,data FROM ! WHERE tree=? AND id=? AND hid=? AND tag = ? AND type='C' ORDER BY inum";
		if ( $xtag == "NOTE" || $xtag == "TEXT") {
			$tags = 'CONT';
		} else {
			$tags = $xtag;
		}

		$r = $this->query( $sql, array( $this->gedcomTable, $user, $ID, $hid, $tags));
		if ( $xtag == "NOTE" || $xtag == "TEXT") {
			while ( $a = $this->mfa( $r)) {
				if ( $a['tag'] == 'CONT')
				$ret_note .= $a['data'];
			}
			return htmlentities( stripslashes( $ret_note), ENT_QUOTES);
		} else {
			$a = $this->mfa( $r);
			return htmlentities( stripslashes( $a['data']), ENT_QUOTES);
		}
	}

	function setNote( $dat, $ID, $ntype, $mr = false, $nid = false) {
		global $user;
    
		if ( !$nid) {
			$nid = $this->getMaxNoteID( $ID) + 1;
		};
    
		if ( $mr) {
			$it = 'F';
		} else {
			$it = 'I';
		} ;
		$sql = "select inum FROM ! WHERE tree=? AND type=? and hid=? and id=? and tag=?";
		$r = $this->getOne( $sql, array( $this->gedcomTable, $user, $it, $nid, $ID, 'NREF'));

		if ( $r) {
			// Remove the current note with this ID if any
			$sql = "DELETE FROM ! WHERE tree=? AND type=? AND tag='NREF' and hid=? and id=?";
			$this->query( $sql, array( $this->gedcomTable, $user, $it, $nid, $ID));
			$sql = "DELETE FROM ! WHERE tree=? AND type='N' AND hid=? and id=?";
			$r = $this->query( $sql, array( $this->gedcomTable, $user, $nid, $ID));
		} ;

		$notestr = split( CRLF, $dat);
		$notearray = array( );
		foreach ( $notestr as $k => $line) {
			$thisline = $line;
			$np = 0;
			$i = 0;
			while ( strlen( $thisline) > MAXLINELENGTH) {
				$i++;
				$notearray[] = substr( $thisline, 0, MAXLINELENGTH);
				$thisline = substr( $thisline, MAXLINELENGTH);
			} ;

			if ( $thisline != "") {
				$notearray[] = $thisline;
			} 
		} 

		$sql = "insert into ! (tree, tag, type, data, level, hid, id) values ( ?, 'NREF', ?, '@N$nid@', '0', ?, ?)";
		$this->query( $sql, array( $this->gedcomTable, $user, $it, $nid, $ID));

		$sql = "INSERT INTO ! (tree, tag, type, data, level, hid, id) VALUES (?, 'NOTE', 'N', ?, '1', ?, ?)";
		$this->query( $sql, array( $this->gedcomTable, $user, $ntype, $nid, $ID));

		$sql = "INSERT INTO ! (tree, tag, type, data, level, hid, id) VALUES (?, 'CONT', 'N', ?, '2', ?, ?)";
		foreach ( $notearray as $k => $line) {
			$this->query( $sql, array( $this->gedcomTable, $user, addslashes( $line), $nid, $ID));
		} ;
	}

	function getNote($nid, $ID) {
		global $user;

		$res = "";
		$note = array( );

		$sql = "SELECT * FROM ! WHERE tree=? AND id=? AND hid=?  AND type='N' ORDER BY inum";
		$r = $this->query( $sql, array( $this->gedcomTable, $user, $ID, $nid));
		if ( $r != false && $this->rowsInResult( $r) > 0) {
			while ( $res = $this->mfa( $r)) {
				if ( $res['tag'] == "NOTE") {
					$note['typ'] = $res['data'];
				} elseif ( $res['tag'] == 'CONT') {
					$note['str'] .= stripslashes ( $res['data']);
				} ;
			} ;
		} ;
		return ( $note['str'] != "")? $note : false;
	}

	function getInum( $it, $ID, $tp)
	{
		global $user;

		$sql = "select inum from ! where tree= ? and type= ? and id = ? and tag = ?";
		$r = $this->getOne( $sql, array( $this->gedcomTable, $user, $tp, $ID, $it));
		return ( $r != false) ? $r : false;
	}

  /**
    * update person information 
    */
	function updateIndex( $iid) {
		global $user;
    
		// Take data using the function which gets data from gedcom table
		list( $name, $surn, $sex, $bdate, $ddate, $bplac, $occu, $hide, $bred, $cdea) = $this->getItems( array( 'name', 'surn', 'sex', 'birt_date', 'deat_date', 'buri_plac', 'occu', 'hide', 'bred', 'cdea'), $iid);

		if ( strlen( $surn) < 1) {
			if ( ( $x = $this->getBrackName( $name)) != false) {
				$surn = $x;
			}
		}

		$por = ($this->hasPortrait($iid)) ?  1 : 0;
		$bio = ($this->hasBio( $iid)) ?  1 : 0;
		$mother = $this->getMother($iid);
		$father = $this->getFather($iid);
		$s = ($mother > 0) ? ", mother = '$mother'" : ", mother = NULL";
		$s .= ($father > 0) ? ", father = '$father'" : ", father = NULL";
		$spouses = $this->getSpouses( $iid);
		$s2 = "'";
		if ( count( $spouses) > 0) {
			foreach ( $spouses as $k => $v) {
				$s2 .= "$v:";
			}
		}
		$s2 .= "'";

		$sql = "SELECT id FROM {$this->indexTable} WHERE tree= ".$this->dbh->quote($user)." and  id= ".$this->dbh->quote($iid);
		$r = $this->getCol($sql);

		if (count($r) == 0) {
          $sql = 'INSERT INTO ! (id, tree, ta) VALUES ( ?, ?, UNIX_TIMESTAMP())';
          $this->query($sql, array( $this->indexTable, $iid, $user));
		} else {
          $sql = "UPDATE ! SET  name= ?, surn = ?, bdate= ?, ddate= ?, bplace = ?, sex = ?, occu = ?, bred = ?, cdea = ?,por = ?, bio = ?, hide= ?, spouses = $s2 $s where id = ? and tree= ?";
          $this->query( $sql, array( $this->indexTable, $name, $surn, $bdate, $ddate, $bplac, $sex, $occu, $bred, $cdea, $por, $bio, $hide, $iid, $user));
        }
	}

	function hasBio( $ID)
	{
		global $user;

		$sql = "select id from ! where tree= ? and id = ? and type='B'";
		$r = $this->getOne( $sql, array( $this->blobsTable, $user, $ID));
		if ( $r == $ID) {
			return true;
		} else {
			return false;
		}
	}

	function getBio( $ID)
	{
		global $user;

		$sql = "select data from ! where tree= ? and id = ? and type='B'";

		$r = $this->getOne( $sql, array( $this->blobsTable, $user, $ID));

		if ( $r != false && $r != '') {
			return stripslashes( $r );
		}
	}

	function getIndexItem( $nm, $ID)
	{
		global $user;

		$sql = "select " . $nm . " from ! where id = ? and tree= ?";
		$r = $this->getOne( $sql, array( $this->indexTable, $ID, $user));
		return $r;
	}

	/**
 * * Using an ID and GEDCOM string, this function returns the value associated with the string.
 * If "NAME" is passed in as the key, then the data after the key is returned. If "BIRT_DATE" is
 * passed in as the key, then the data associated with line is returned. "DATE" could be a
 * subkey of "BIRT".
 */

	function getItem($gedcomKey, $ID, $subID = null , $TreeName = "") {
    global $user;

		if (empty($ID)) {
			return UNDEFINED;
    }

		if ($TreeName != '') {
			$user = "$TreeName";
    }

		$gedcomKey = strtoupper($gedcomKey);
		if ($gedcomKey == "ID") {
			return $ID;
    }

		if (strpos($gedcomKey, '_')) {
			$parts = split('_', $gedcomKey);
			return $this->getSKey($ID, $parts[0], $parts[1], $user);
		}

		switch ($gedcomey) {
			case 'HIDE' :

				$sql = "SELECT data FROM ! WHERE id= ? AND tag='DEAD' AND tree = ?";
				$r = $this->getOne( $sql, array( $this->gedcomTable, $ID, $user));

				$sql = "SELECT data from ! WHERE id= ? AND tag='HIDE' AND tree = ?";
				$p = $this->getOne( $sql, array( $this->gedcomTable, $ID, $user));

				if ( $p != false) {
					$htag = trim( $p);
				}

				if ( $r != false) {
					$a = $r ;

					if ( $a == "No" && HIDELIVING == "true") {
						return ( $htag == 'No') ? "No" : "Yes";
					}
				}

				$cbp = $this->getItem( "buri_plac", $ID);
				$cdp = $this->getItem( "deat_plac", $ID);
				$cdd = $this->getItem( "deat_date", $ID);
				$cbd = $this->getItem( "birt_date", $ID);

				if ( HIDELIVING == "true") {
					if ( $cdp == "" && $cdd == "") {
						return ( $htag == 'No') ? "No" : "Yes";
					} else {
						return "No";
					}
				}
				break;

			case 'DEAD' :
				$cbp = $this->getItem( "buri_plac", $ID);
				$cdp = $this->getItem( "deat_plac", $ID);
				$cdd = $this->getItem( "deat_date", $ID);
				$cbd = $this->getItem( "birt_date", $ID);

				$sql = "select data from ! where id= ? and tag='DEAD' and tree = ?";
				$r = $this->getOne( $sql, array( $this->gedcomTable, $ID, $user));

				if ( $r != false) {
					if ( $r == "No") {
						return ( $cdp == "" && $cdd == "") ? "No": "Yes";
					} else {
						return "Yes";
					}
				} elseif ( $r != false && $r == "") {
					return ( $cdp == "" && $cdd == "") ? "No" : "Yes";
				}
			break;
		}

    if ($subID) {
      $sql = 'SELECT data FROM ! WHERE id= ? AND tag = ? AND tree = ? AND hid='.(int)$subID;
    } else {
      $sql = 'SELECT data FROM ! WHERE id= ? AND tag = ? AND tree = ?';
    }
		$r = $this->getOne($sql, array( $this->gedcomTable, $ID, $gedcomKey, $user));

		if ( $r != false) {
			return stripslashes($r);
		} elseif ( MDB2::isError( $r) && $gedcomKey == "SURN") {
			/* Try deducing surname from name */
			$nm = $this->getItem( "name", $ID);
			if ( $nm != "") {
				$nm1 = stripslashes( $this->getBrackName( $nm));
				return $nm1;
			}
		}
	}

	function get_user_events($ID) {
		global $ftags;

		$sql = "select * from ! where id = ? and type = 'I' order by level asc" ;
		$result = &$this->dbh->query( $sql, array( $this->gedcomTable, $ID));

		if ( MDB2::isError( $result)) {
			print ( $result->getMessage( ));
		}
	}
} 

