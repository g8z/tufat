<?php

#@ Smarty Language Filter plugin
##  Pat K. <cicada@edencomputing.com>  2005/05/19
## ver 1.0
#  This output filter translates any ##...## present in the Smarty templates
include_once('function.tufat_mytrans.php');

function smarty_outputfilter_langfilter ( $compiled_template ) {
	$replace_regex = '/\x23\x23(.+?)\x23\x23/s';
	return preg_replace_callback( $replace_regex, "_tufat_mytrans", $compiled_template);
};
	
