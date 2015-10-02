<?php
 include_once 'autoload.php';
 spl_autoload_register('__autoload');
 include_once 'cfg.php';
 date_default_timezone_set('Europe/Kiev');
 
 $class = (isset($_REQUEST['c'])) ? $_REQUEST['c'] : '';
 $action = (isset($_REQUEST['a']) and isset($_REQUEST['c'])) ? $_REQUEST['a'] : '';
 $classaction = $class.$action; 

 if ($classaction=='')
  {header("Location: home.php");} else
  echo $class::getInstance($cfg)->$action();
?>
