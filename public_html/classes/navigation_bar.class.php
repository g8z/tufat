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

/**
 * * A class to create a navigation bar for a database table - used for the TUFaT image gallery.
 */

 class NavigationBar {
	var $recordsPerPage;
	var $numRows;
	var $link;
	var $linkStyle;
	var $startID;
	var $linkClass;
	var $nonLinkClass;
	var $prevString; // the "previous page" text
	var $nextString; // the "next page" text
	var $barColor; // the background color for the navigation bar
	var $prevLink; // this is constructed by the getLinks() method (do not set)
	var $nextLink; // this is constructed by the getLinks() method (do not set)
	var $idArray;

	function Navbar( )
	{
		$this->recordsPerPage = 10; // default number of records to display per page

		$this->numRows; // number of rows in result set

		$this->linkStyle = "range"; // values = "range" or "page", ie. display navigation links as "1-x, x-y, y-z..." or "page 1 2 3...";

		$this->startID = 0; // the record to start at

		$this->linkClass = "normal";
		$this->nonLinkClass = "normal";
		$this->prevString = "";
		$this->nextString = "";

		$this->barColor = "EEEEEE"; // gray by default

		$this->idArray = Array( );
	}

	/**
 * * Creates the entire navigation links, with prev/next links, and middle links.
 */
	function getNavBar( )
	{
		$linkString = $this->getLinks( );
		// NOTE: you MUST call getLinks() before attempting to print $this->prevLink or $this->nextLink!
		$result = "<table border=0 width=70% cellpadding=1 cellspacing=0 bgcolor=" . $this->barColor . "><tr><td nowrap width=10% align=left>";
		// print the "prev" link here
		$result .= $this->prevLink;

		$result .= "</td><td nowrap width=80% align=center>";
		// print the numeric links here
		$result .= $linkString;

		$result .= "</td><td nowrap width=10% align=right>";
		// print the "next" link here
		$result .= $this->nextLink;

		$result .= "</td></tr></table>";

		return $result;
	}

	/**
 * * Creates just the middle links of the navigation bar.
 */
	function getLinks( )
	{
		$result = "";
		$page = 1;

		global $_SERVER, $individual;
		// only print the next/prev links if we have more than 1 page
		if ( $this->numRows > $this->recordsPerPage) {
			// check to see if we're on the first page
			if ( $this->startID == $this->idArray[0]) {
				$this->prevLink = "<span class=" . $this->nonLinkClass . ">" . $this->prevString . "</span>";
			} else {
				// find out where in the idArray startID is located. $this->numRows is the same as idArray.length
				for ( $i = 0; $i < $this->numRows; $i++) {
					if ( $this->idArray[$i] == $this->startID) {
						$temp = $this->idArray[ $i - $this->recordsPerPage ];
					}
				}

				$this->prevLink = "<a ";

				if ( !empty( $this->linkClass)) {
					$this->prevLink .= "class=\"" . $this->linkClass . "\"";
				}

				$this->prevLink .= " href=" . $_SERVER['PHP_SELF'] . "?individual=$individual&startID=$temp>" . $this->prevString . "</a>";
			}
			// check to see if we are on the last page
			if ( $this->startID == $this->idArray[ $this->numRows - $this->recordsPerPage ]) {
				$this->nextLink = "<span class=" . $this->nonLinkClass . ">" . $this->nextString . "</span>";
			} else {
				// find out where in the idArray startID is located.
				for ( $i = 0; $i < $this->numRows; $i++) {
					if ( $this->idArray[$i] == $this->startID) {
						$temp = $this->idArray[ $i + $this->recordsPerPage ];
					}
				}

				$this->nextLink = "<a ";

				if ( !empty( $this->linkClass)) {
					$this->nextLink .= "class=\"" . $this->linkClass . "\"";
				}

				$this->nextLink .= " href=" . $_SERVER['PHP_SELF'] . "?individual=$individual&startID=$temp>" . $this->nextString . "</a>";
			}
		}

		for ( $i = 0; $i < $this->numRows; $i = $i + $this->recordsPerPage) {
			// the second startID will override the startID appended by getURL function
			// if we're on the page indicated by the navigation bar link, then don't make it a link
			if ( $this->idArray[$i] != $this->startID) {
				// if there is a url String, but it doesn't have a startID variable in it,
				// then append the start ID
				$result .= "<a ";

				if ( !empty( $this->linkClass)) {
					$result .= "class=\"" . $this->linkClass . "\"";
				}
				$result .= " href=" . $_SERVER['PHP_SELF'] . "?individual=$individual&startID=" . $this->idArray[$i] . ">";
				// remove the $ith item in $this->idArray
				$this->idArray = array_diff( $this->idArray, Array( $this->idArray[$i]));

				if ( $this->linkStyle == "range") {
					$result .= "$i - " . ( $i + $this->recordsPerPage);
				} elseif ( $this->linkStyle == "page") {
					$result .= $page++;
				}

				$result .= "</a> ";
			} else {
				$result .= "<span class=" . $this->nonLinkClass . ">";

				if ( $this->linkStyle == "range") {
					$result .= "$i - " . ( $i + $this->recordsPerPage);
				} elseif ( $this->linkStyle == "page") {
					$result .= $page++;
				}
				$result .= "</span> ";
			}
		}
		return $result;
	}
}

