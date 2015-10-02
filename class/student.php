<?php
class student extends DB_Connect {

 public function __construct($cfg) {
  parent::__construct($cfg);
  $this->ltitle = "Студенты";
 }

 //--- нарисовать таблицу
 public function table() {
  $this->cfg->smarty -> assign('ltitle', $this->ltitle);
  $this->cfg->smarty -> display('student_list.tpl');
 }

 //--- получить данные с сервера
 public function getdata() {
  $table = 'student';
  $primaryKey = 'id';
  $columns = array(
   array(
    'db' => 's.id',
    'dt' => 'DT_RowId',
    'formatter' => function($d, $row) {
     return 'row_'.$d;
    },
    'field' => 'id'
   ),
   array( 'db' => 's.id', 'dt' => 0, 'field' => 'id',
   // 'formatter' => function($d, $row) {return "<a href='#'>$d</a>"};
   ),
   array( 'db' => 's.name', 'dt' => 1, 'field' => 'name'),
   array( 'db' => "DATE_FORMAT(s.birthdate, '%d.%m.%Y')",  'dt' => 2, 'sort' => 's.birthdate',
    'field' => 'birthdate', 'as' => 'birthdate',
   // 'formatter' =>function($d, $row) {return date('d.m.Y', strtotime($d));}
   ),
   array( 'db' => 'f.name', 'dt' => 3, 'field' => 'faculty_name', 'as' => 'faculty_name'),
   array( 'db' => 'g.name', 'dt' => 4, 'field' => 'group_name', 'as' => 'group_name'),
   array( 'db' => 'null', 'dt' => 5),
  );
  require_once('ssp.ado.class.php');
  $joinQuery = " FROM `student` s JOIN `faculty` f ON (f.id = s.faculty_id)
                                  JOIN `group` g ON (g.id = s.group_id)";
  echo json_encode(SSP::simple($this->cfg->dbcnx, $_GET, $table, $primaryKey, $columns, $joinQuery,
   $extraWhere));
 }

 //--- взять запись для редактирования/поля для вставки и вызвать форму
 public function getRecord($id) {
  require_once 'utils.php';
  if ($id > 0) { // update
   $query1 = "select * from `student` where id = $id LIMIT 1";
   $rs = $this->cfg->dbcnx->Execute($query1);
   if ($rs->RecordCount() == 0) {$this->showerror('Запись не найдена. Обновите экран'); exit;}
   $db = $rs->fields;
   $db[birthdate] = DRUS($db[birthdate]);
  } else {
   $db=array();$db[id]=0;$db[name]='';
  }
  $this->showRecordForm($id, $db);
 }

 //--- выдать форму вставки/редактирования
 public function showRecordForm($id, $db, $err = NULL) {
  require_once 'utils.php';
  $facul = buildSelect($this->cfg->dbcnx, 'Факультет:', 'db[faculty_id]', 'id', 'name', $db[faculty_id],
  'select id, name from `faculty` order by id');
  $grp   = buildSelect($this->cfg->dbcnx, 'Група:', 'db[group_id]', 'id', 'name', $db[group_id],
  'select id, name from `group` order by id');
  $this->cfg->smarty -> assign('db', $db);
  $this->cfg->smarty -> assign('err', $err);
  $this->cfg->smarty -> assign('facul', $facul);
  $this->cfg->smarty -> assign('grp', $grp);
  $this->cfg->smarty -> assign('ltitle', $this->__editTitle($id));
  $this->cfg->smarty -> display('student_record.tpl');
 }

 //--- проверить правильность заполнения формы
 public function validateRecord($id, $db) {
  $err = array();
  require_once 'utils.php';
  checkempty('name', $db, $err, 'Поле прізвище не може бути пустим!');
  $errdate = mycheckdate($db[birthdate], 0, '01.01.1915', '31.12.2010');
  if ($errdate <> '') $err[birthdate] = $errdate;
  if (sizeof($err)>0) { // не прошел валидацию, возврат код 0 и нафиг
   echo $this->showRecordForm($id, $db, $err);
  }
  return (sizeof($err) == 0);
 }

 //--- Сохранить отредактированную запись
 public function saveRecord($id, $db) {
  $db[name] = trim(iconv('UTF-8', 'Windows-1251', $db[name]));
  if (!$this->validateRecord($id, $db)) exit;

  if ($id > 0) {
   $query1 = "select id from `student` where id = $id;";
   $rs = @$this->cfg->dbcnx->Execute($query1);
   if ($rs->RecordCount()==0) {$this->showerror('Запись не найдена. Обновите экран');exit;};

   $query1 = "update `student` set name = ?, birthdate = ?, faculty_id = ?, group_id = ?
    where id = $id;";
   $params = array($db[name], DMSQL($db[birthdate]), $db[faculty_id], $db[group_id]);
   $rs = @$this->cfg->dbcnx->Execute($query1, $params);
  } else {
   $query1 = "insert into `student` (name, birthdate, faculty_id, group_id) values(?, ?, ?, ?)";
   $params = array($db[name], DMSQL($db[birthdate]), intval($db[faculty_id]), intval($db[group_id]));
   $rs = @$this->cfg->dbcnx->Execute($query1, $params);
   $id = $this->cfg->dbcnx->Insert_ID();
  }
  $affected_rows = $this->cfg->dbcnx->Affected_Rows();
  if (!$rs) {$this->showerror($this->cfg->dbcnx->ErrorMsg());exit;};

  if ($affected_rows==0) {exit;};
  echo $this->refreshRecord($id);
 }

 //--- выдать форму вставки/редактирования
 public function refreshRecord($id) {
  $query1 = "select s.id, s.name, DATE_FORMAT(s.birthdate, '%d.%m.%Y') as birthdate,
   f.name as faculty_name, g.name as group_name
   from `student` s
   join `faculty` f on f.id = s.faculty_id
   join `group` g on g.id = s.group_id
   where s.id = $id LIMIT 1 ";
  $rs = $this->cfg->dbcnx->Execute($query1);
  $db = $rs->fields;
  return $this->scatterHTML($db);
 }

 //--- Попросить подтверждения удалению записи
 public function askdeleteRecord($id) {
  $query1 = "select name from `student` where id = $id LIMIT 1";
  $rs = @$this->cfg->dbcnx->Execute($query1);
  if ($rs->RecordCount() == 0) {$this->showerror('Запись не найдена. Обновите экран'); exit;}
  $name = trim($rs->fields('name'));
  $this->cfg->smarty -> assign('id', $id);
  $this->cfg->smarty -> assign('fclass', 'student');
  $this->cfg->smarty -> assign('warn', 'Ви справді хочете видалити студента ? ');
  $this->cfg->smarty -> assign('warn1', $name);
  $this->cfg->smarty -> display('record_delete.tpl');
 }

 //--- Удалить запись
 public function deleteRecord($id) {
  $query1 = "delete from `student` where id = $id";
  $rs = @$this->cfg->dbcnx->Execute($query1);
  $affected_rows = $this->cfg->dbcnx->Affected_Rows();
  if (!$rs) {$this->showerror($this->cfg->dbcnx->ErrorMsg());exit;};
  if ($affected_rows==0) {$this->showerror('Запись не найдена. Обновите экран');exit;};
 }
}
?>