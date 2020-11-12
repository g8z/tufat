<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {TUFaT_mytrans} function plugin
 *
 * Type:     function<br>
 * Name:     TUFaT_mytrans<br>
 * Date:     July 1, 2002<br>
 * Purpose:  Take languave specific texts from database to display
 * @link     To be attached with TUFaT package and topied to Smarty/plugins directory
 * @author   Vijay Nair <vijay@nairvijay.com>
 * @version  1.0
 * @param    DB Database Opbect ID
 * @param    Text_to_check Text for Language Check
 * @return   string 
 */


## Modified by Pat K. <cicada@edencomputing.com> to include new language parser

include_once("prefilter.langfilter.php"); 

function smarty_function_tufat_mytrans ($params, &$smarty) {
	## this is included for backwards compatibility with {tufat_mytrans getvalue='...'} calls in the templates
	return _tufat_mytrans(array( NULL , $params['getvalue']));
};
 
function _tufat_mytrans($matches) {  
  global $db;
  
  $dbx = $db; #new FamilyDatabase;
  $x = $matches[1];

  return stripslashes($dbx->mytrans($x));   
};	

 
/* ##  Old tufat_mytrans ## 
function _disabled_smarty_function_tufat_mytrans($params, &$smarty )
{  include_once "config.php";
   include_once "classes.php";
   $dbx = new FamilyDatabase;
   $x = $params['getvalue'];
   $y = stripslashes($dbx->mytrans($x));
  # if (!$y) return ($params['getvalue']);
   return $y;
}
*/

/* vim: set expandtab: */

?>
