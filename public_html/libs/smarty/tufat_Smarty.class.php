<?php

class tufat_Smarty extends Smarty{

  function clear_compiled_templates() {
    @set_time_limit(300);
    $dh = opendir($this->compile_dir);
    while (($file = readdir($dh)) !== false) {
      $path = $this->compile_dir . $file;
      if (is_file($path) && preg_match('/\.tpl\.php$/', $path)) {
        @unlink($path);
      }
    }
    closedir($dh);
  }

	/**
	 * assigns values to template variables
	 *
	 * @param array|string $tpl_var the template variable name(s)
	 * @param mixed $value the value to assign
	 */
	/**
	 * assigns values to template variables
	 *
	 * @param array|string $tpl_var the template variable name(s)
	 * @param mixed $value the value to assign
	 */
	function assign($tpl_var, $value = null) {
		//print $tpl_var." = ".$value."<br>";
		
		if (is_array($tpl_var)) {
			foreach ($tpl_var as $key => $val) 
			{
				if ($key != '') 
				{

					if ( !is_array( $value ) ) 
					{
						$this->_tpl_vars[$key] = stripslashes( $val );
					}
					else 
					{
						foreach( $val as $index => $v ) 
						{
							if ( !is_array( $v ) )
								$val[ $index ] = stripslashes( $v );
							else
								$val[ $index ] = $v;
						}

						$this->_tpl_vars[$key] = $val;
					}
				}
			}
		} 
		else 
		{
			if ($tpl_var != '') 
			{

				if ( !is_array( $value ) ) 
				{
					//@fixed type cast bug (Andrew)
					//$this->_tpl_vars[$tpl_var] = stripslashes( $value );
					$this->_tpl_vars[$tpl_var] = $value;
				}
				else 
				{
					foreach( $value as $index => $v ) 
					{
						if ( !is_array( $v ) ) 
						{

							if ( is_object( $v ) ) 
							{
								continue;
							}

							$value[ $index ] = stripslashes( $v );
						}
						else 
						{
							$value[ $index ] = $v;
						}
					}

					$this->_tpl_vars[$tpl_var] = $value;
				}
			}
		}
	}

  function fetch($resource_name, $cache_id = null, $compile_id = null, $display = false) {
    //this exlude for files which was tuned for cache manually
    $tuned_cache = array('navMenu.tpl');

    if (!in_array($resource_name, $tuned_cache)) {
      //$this->load_filter('output', 'stripspaces');

  		if($this->caching) {
  			$cache_id = $this->cache_name();
  			$compile_id = $this->compile_id();
  		}
  				
  		//@ there master user, don't cache				
  		if ( count($_POST))
  			$this->clear_all_cache();
    }

		return parent::fetch($resource_name, $cache_id, $compile_id, $display);
  }	  
	
	
	function cache_name() {		
		return crc32(__FILE__).crc32(serialize($_REQUEST));
	}

	function compile_id() {
		return  crc32(__FILE__).md5(serialize($_REQUEST));
/*		
		//@ clear timestamps, for right caching
		$tpl_vars['currtime'] = '';
		$cache_key = '';

		foreach($tpl_vars as $key => $value) {
			if( is_array( $value ) )
				//@ clear timestamps, for right caching
				$value['filetime'] = '';
				
			$svalue = serialize($value);
			$cache_key .= $svalue.'<br>';
		}
										
		$cache_id = md5( $cache_key );
//		$cache_id = base64_encode(gzcompress( serialize( $cache_keys ) ) );
//		$cache_id = serialize( $cache_keys );
				
		return $cache_id;
    */
	}
	
}

?>