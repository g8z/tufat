<?php

class Language {

  // #########################
  function get_files($dir, $extension = 'tpl') {
      $filelist = array( );
      $td = opendir( $dir);
      while ( $fname = readdir( $td)) {
          if ( preg_match( '/.*\.'.$extension.'$/', $fname)) {
              $filelist[$fname] = $fname;
          }
      }

      return $filelist;
  }

  function get_langtags( $fname, $lang) {
    global $tag_regex, $db;

    $fle = file_get_contents( $fname);
    if ( preg_match_all( $tag_regex, $fle, $matches)) {
      $tags = array_unique($matches[1]);
      foreach($tags as $tag) {
        $sql_tags[] = $db->dbh->quote($tag);
      };
      
      $sql = "SELECT w, m FROM {$db->langTable} WHERE w IN (".(implode(', ', $sql_tags)).") AND lcase(l) = ".$db->dbh->quote(strtolower($lang[0]))." AND lcase(enc) = ".$db->dbh->quote(strtolower($lang[1]));
      $db->dbh->loadModule('Extended');
      $translations = $db->dbh->extended->getAssoc($sql);
      
      $translated_tags = array();
      foreach ( $tags as $tag) {
        $translated_tags[$tag] = $translations[$tag];
      }

      ksort($translated_tags);
      return $translated_tags;
    } else {
      return array( );
    }
  }

  function replace_tags( $filename) {
      $fcontents = file_get_contents( $filename);

      if ( eregi( 'tufat_mytrans', $fcontents)) {
          $fcontents = preg_replace( '/\{\ {0,5}tufat_mytrans\ *getvalue\ {0,5}=\ {0,5}["\']([^\"\}]+)["\']\ {0,5}\}/', '##$1##', $fcontents);
          $fp = fopen( $filename, "w");
          fwrite( $fp, $fcontents);
          fclose( $fp);
          return true;
      } else {
          return false;
      } 
  } 

  function update_langdb( $filename, &$db) {
    $fcontents = file_get_contents( $filename);
    list ( $lang['l'], $lang['enc']) = split( '_lll_', SLANG);
    
    // # Get the lang tags
    preg_match_all( '/\x23\x23(.+?)\x23\x23/s', $fcontents, $tagmatches);
    $qsql = "SELECT w FROM {$db->langTable} WHERE w=".$db->dbh->quote($langtag)." AND LCASE(l)=".$db->dbh->quote($lang['l'])." AND LCASE(enc)=".$db->dbh->quote($lang['enc']);
    $isql = 'INSERT INTO ! (w,l,enc) VALUES (?,?,?)';
    foreach ( $tagmatches[1] as $key => $langtag) {
        $tag_key = $db->getRow($qsql);
        if ( !( $tag_key['w'])) {
            $db->query( $isql, array( $db->langTable, $langtag, $lang['l'], $lang['enc']));
            $in_count ++;
        } 
    }
    return $in_count;
  } 

  function export_language ( $filename, $lang, $opts, &$db) {
    // # Build the language XML file
    // # Get the tags from the database
    list( $l, $enc) = split ( "_lll_", $lang['l']); ## our language and encoding
    list( $d_lang['l'], $d_lang['enc']) = split( '_lll_', SLANG); #default language and encoding

    $header = "<?xml version=\"1.0\" encoding=\"" . $enc . "\"?>\n";
    $header .= "<date>" . time( ) . "</date>\n"; ## timestamp
    $header .= "<language code=\"$l\" name=\"$lang[name]\">\n";
    $footer = "\n</language>";
    $sql = "SELECT w, m FROM ! WHERE LCASE(l)=? AND LCASE(enc)=? ORDER BY w";
    $sql2 = "SELECT m FROM {$db->langTable} WHERE w=".$db->dbh->quote($row['w'])." AND LCASE(l)=".$db->dbh->quote(strtolower($l))." AND LCASE(enc)=".$db->dbh->quote(strtolower($enc));
    if ( $opts == 'trans') { // # output any untranslated tags for the selected language
        $rs = $db->query( $sql, array( $db->langTable, strtolower( $d_lang['l']), strtolower( $d_lang['enc'])));
        if ( $rs->result) {
            if ( $fp = fopen( $filename, "w")) { // # Open in overwrite mode
                fwrite( $fp, $header); ## output the header
                while ( $row = $db->mfa( $rs)) {
                    if ( $row['w'] && $row['m']) {
                        $trow = $db->getRow($sql2);
                        if ( !trim( $trow['m'])) {
                            $line = "  <text digest=\"" . addslashes( $row['w']) . "\"><!-- $row[m] --></text>\n"; ## output the text
                            fwrite( $fp, $line);
                        } ;
                    } ;
                } ;
                fwrite( $fp, $footer); ##output the footer
                fclose( $fp); ## close the file
            } else {
                return false;
            } ;
        } else {
            return false;
        } ;
        return true;
    } elseif ( $opts == 'all') { // # output tags for the selected language where available, otherwise use default lang
        $rs = $db->query( $sql, array( $db->langTable, strtolower( $l), strtolower( $enc)));
        if ( $fp = fopen( $filename, "w")) { // # Open in overwrite mode
            fwrite( $fp, $header);
            while ( $row = $db->mfa( $rs)) {
                if ( $row['w'] && $row['m']) {
                    $our_langtags[$row['w']] = $row['m'];
                } ;
            } ;
            $rsd = $db->query( $sql, array( $db->langTable, strtolower( $d_lang['l']), strtolower( $d_lang['enc'])));
            while ( $row = $db->mfa( $rsd)) {
                if ( $our_langtags[$row['w']]) {
                    $line = "  <text digest=\"" . addslashes( $row['w']) . "\">" . $our_langtags[$row['w']] . "</text>\n";
                    fwrite( $fp, $line);
                } else {
                    if ( $row['w']) {
                        $line = "  <text digest=\"" . addslashes( $row['w']) . "\"><!-- $row[m] --></text>\n";
                        fwrite( $fp, $line);
                    } ;
                } ;
            } ;
            fwrite( $fp, $footer);
            fclose( $fp);
        } ;
        return true;
    } else {
        return false;
    }
  } 
}