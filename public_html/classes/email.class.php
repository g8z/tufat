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

class Email {
	var $to;
	var $from;
	var $cc;
	var $bcc;
	var $subject;
	var $message;
	var $email;
	var $html;
	var $sender;
	var $header;
	var $recipient;

	function Email( )
	{
		$this->cc = "";
		$this->bcc = "";
		$this->subject = "";
		$this->message = "";
		$this->to = "";
		$this->sender = "";
		$this->from = "";
		$this->header = "";
		$this->html = false;
		$this->recipient = "";
	}

	function send( )
	{
		$this->header .= "MIME-Version: 1.0\r\n";

		if ( $this->html) {
			$this->header .= "Content-type: text/html; charset=iso-8859-1\r\n";
		}
		if ( $this->recipient) {
			$this->to = $this->recipient;
		}
		// for backwards-compatability
		if ( $this->sender) {
			$this->from = $this->sender;
		}
		// for backwards-compatability
		// always must include from field
		$this->header .= "From: $this->from\r\n";

		if ( $this->cc) {
			$this->header .= "Cc: $this->cc\r\n";
		}

		global $path;

		/**
	 * * If testing on local system, simply print out the mail variables.
	 */

		if ( stristr( $path, "localhost")) {
			print $this->mytrans( "Now sending mail to: ") . $this->to . "<br>";
			print $this->mytrans( "Subject of mail: ") . $this->subject . "<br>";
			print $this->mytrans( "Mail message: ") . $this->message . "<br>";
		} else {
			if ( USE_SMTP_CLASS) { // use smtp class for mail sending # jrpi:02.02.06
				require 'smtp.class.php';
				return send_mail( $this->from, $namefrom, $this->to, $this->subject, $this->message, $this->header, "", "", "", $this->html);
			} else {
				return mail( $this->to, $this->subject, $this->message, $this->header);
			}
		}
	}
}