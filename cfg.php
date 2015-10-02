<?php 
 define('DB_HOST', 'localhost');
 define('DB_NAME', 'university');
 define('DB_USER', 'root');
 define('DB_PASS', '');
 define('ROOT_PATH', '../');

 require ROOT_PATH."Smarty/Smarty.class.php";
 $smarty = new Smarty();
 $smarty->template_dir = "smarty/templates";
 $smarty->compile_dir = "smarty/templates_c";
 $smarty->cache_dir = "smarty/cache";
 $smarty->config_dir = "smarty/configs";

 include(ROOT_PATH."adodb5/adodb-exceptions.inc.php");
 include(ROOT_PATH."adodb5/adodb.inc.php");
 $dbcnx = NewADOConnection('mysqli');  
 $dbcnx->PConnect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
 $dbcnx->SetFetchMode(ADODB_FETCH_ASSOC);
 $dbcnx->Execute("set names cp1251");

 $cfg = (object) array(
  'smarty'   => $smarty,
  'dbcnx'    => $dbcnx,
  'appdir'   => $_SERVER[DOCUMENT_ROOT].dirname($_SERVER[PHP_SELF]).'/',
  'apptitle' => 'Университет');
?>
