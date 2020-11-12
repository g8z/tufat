<?php

 ##import the XML tags 
$lfile = file_get_contents( 'languages/english_lll_utf-8_default.xml');

preg_match('/<\?xml.*encoding="([^\"]*)"\?>/', $lfile, $matches); ## get encoding

$imp_enc = $matches[1];

if ( preg_match('/<language code="([^\"]*)" name="([^\"]*)">/', $lfile, $matches)) 
{ 
    ## get language code and name
	$i_lang = $matches[1]; $i_name = $matches[2];
	
	preg_match_all('/<text digest="([^\"]+)">(.+?)<\/text>/sm', $lfile, $tag_matches); 
	
	$sql = "SELECT * FROM ! WHERE w=? AND LCASE(l) = ? AND LCASE(enc) = ?"; 
	
	if (is_array($tag_matches)) 
	{
		foreach ($tag_matches[2] as $key => $translation) 
		{ 
			if (trim($translation)) 
			{
				$w = trim($tag_matches[1][$key]); 
				$m = addslashes(preg_replace('/<!--.*-->/', '', trim($translation)));
				mysql_query("insert into ".$sdbprefix."_lang (w,m,l,enc) values ('$w', '$m', 'english', 'utf-8')");
			};
		};
	};
};

?>