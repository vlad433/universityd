<?php
 function __autoload($class_name) {
  $filename = dirname(__FILE__).'/class/'.strtolower($class_name).'.php';
  if (file_exists($filename)) {
   include_once $filename;
  }
 }

 function varDump($data) {
  ob_start();
  var_dump($data);
  $ret_val = ob_get_contents();
  ob_end_clean();
  return $ret_val;
 }
?>