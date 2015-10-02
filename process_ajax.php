<?php
 include_once 'cfg.php';
 include_once 'autoload.php';
 spl_autoload_register('__autoload');
 date_default_timezone_set('Europe/Kiev');
 $id = (isset($_REQUEST[id])) ? (int) $_REQUEST[id] : 0;
 $db = $_REQUEST[db];

 //file_put_contents('d:\classactionid', $_REQUEST['class'].$_REQUEST['action'].$id);

 echo $_REQUEST['class']::getInstance($cfg)->$_REQUEST['action']($id, $db);
?>