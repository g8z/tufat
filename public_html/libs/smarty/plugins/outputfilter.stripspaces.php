<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty strip outputfilter plugin
 *
 * File:     outputfilter.stripspaces.php<br>
 * Type:     outputfilter<br>
 * Name:     stripspaces<br>
 * Date:     Jan 25, 2003<br>
 * Purpose:  trim leading white space and blank lines from
 *           template source after it gets interpreted, cleaning
 *           up code and saving bandwidth. Does not affect
 *           <<PRE>></PRE> and <SCRIPT></SCRIPT> blocks.<br>
 * Install:  Drop into the plugin directory, call
 *           <code>$smarty->load_filter('output','trimwhitespace');</code>
 *           from application.
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @author Contributions from Andrew Zagarichuk <zagarichuk@gmail.com>
 * @version  1.3
 * @param string
 * @param Smarty
 */
function smarty_outputfilter_stripspaces($source, &$smarty)
{
	$replace = ' ';
    return preg_replace('!\s+!', $replace, $source);
}


?>
