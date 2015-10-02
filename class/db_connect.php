<?php
class DB_Connect {
 protected static $instance;
 protected $ltitle, $showerror;

 public static function getInstance($cfg) {
  $instance = new static($cfg);
  return $instance;
 }

 protected function __construct($cfg) {
  if (!isset($cfg) or !is_object($cfg)) {$this->showerror('Нет подключения к базе');exit;}
  $this->cfg=$cfg;
 }

 //--- Редактировать/посмотреть запись
 protected function __editTitle($id) {
  switch($id) {
  case 0: $ltitle = 'Добавление записи';break;
  default: $ltitle = 'Корректировка записи';break;
  }
  return $ltitle;
 }

 // разбросать 
 public function scatterHTML($db) {
  $res = '';
  foreach($db as $k=>$v) {
   $res .= '<span>'.$v.'</span>';
  }
  return $res;
 }

 // показать ошибку базы данных
 public function showerror($data) {
  $this->cfg->smarty -> assign('errormsg', $data);
  $this->cfg->smarty -> display('error_form.tpl');
 }
}
?>