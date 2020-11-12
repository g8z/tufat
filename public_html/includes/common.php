<?php

function escape_string($string) {
  if (is_array($string)) {
    return $string;
  } else {
    mysql_real_escape_string($string);
  }
}