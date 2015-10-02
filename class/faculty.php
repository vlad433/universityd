<?php
class faculty extends DB_Connect {

 public function __construct($cfg) {
  parent::__construct($cfg);
  $this->ltitle = "����������";
 }

 //--- ���������� �������
 public function table() {
  $this->cfg->smarty -> assign('ltitle', $this->ltitle);
  $this->cfg->smarty -> display('faculty_list.tpl');
 }

 //--- �������� ������ � �������
 public function getdata() {
  $table = 'faculty';
  $primaryKey = 'id';
  $columns = array(
   array(
    'db' => 'id',
    'dt' => 'DT_RowId',
    'formatter' => function($d, $row) {
     return 'row_'.$d;
    },
    'field' => 'id'
   ),
   array( 'db' => 'id', 'dt' => 0, 'field' => 'id'),
   array( 'db' => 'name', 'dt' => 1, 'field' => 'name'),
   array( 'db' => 'null', 'dt' => 2),
  );
  require('ssp.ado.class.php' );
  echo json_encode(SSP::simple($this->cfg->dbcnx, $_GET, $table, $primaryKey, $columns, $joinQuery));
 }

 //--- ����� ������ ��� ��������������/���� ��� ������� � ������� �����
 public function getRecord($id) {
  if ($id > 0) { // update
   $query1 = "select * from `faculty` where id = $id LIMIT 1";
   $rs = $this->cfg->dbcnx->Execute($query1);
   if ($rs->RecordCount() == 0) {$this->showerror('������ �� �������. �������� �����'); exit;}
   $db = $rs->fields;
  } else {
   $db=array();$db[id]=0;$db[name]='';
  }
  $this->showRecordForm($id, $db);
 }

 //--- ������ ����� �������/��������������
 public function showRecordForm($id, $db, $err = NULL) {
  $this->cfg->smarty -> assign('db', $db);
  $this->cfg->smarty -> assign('err', $err);
  $this->cfg->smarty -> assign('ltitle', $this->__editTitle($id));
  $this->cfg->smarty -> display('faculty_record.tpl');
 }

 //--- ��������� ������������ ���������� �����
 public function validateRecord($id, $db) {
  $err = array();
  require_once 'utils.php';
  checkempty('name', $db, $err, '���� ����� ���������� �� ���� ���� ������!');
  if (sizeof($err)>0) { // �� ������ ���������, ������� ��� 0 � �����
   echo $this->showRecordForm($id, $db, $err);
  }
  return (sizeof($err) == 0);
 }

 //--- ��������� ����������������� ������
 public function saveRecord($id, $db) {
  $db[name] = trim(iconv('UTF-8', 'Windows-1251', $db[name]));
  if (!$this->validateRecord($id, $db)) exit;

  if ($id > 0) {
   $query1 = "select id from `faculty` where id = $id;";
   $rs = @$this->cfg->dbcnx->Execute($query1);
   if ($rs->RecordCount()==0) {$this->showerror('������ �� �������. �������� �����');exit;};
   $query1  = "update `faculty` set name = ? where id = $id";
   $params = array($db[name]);
   $rs = @$this->cfg->dbcnx->Execute($query1, $params);
  } else {
   $query1  = "insert into `faculty` (name) values (?)";
   $params = array($db[name]);
   $rs = @$this->cfg->dbcnx->Execute($query1, $params);
   $id = $this->cfg->dbcnx->Insert_ID();
  }

  $affected_rows = $this->cfg->dbcnx->Affected_Rows();
  if (!$rs) {$this->showerror($this->cfg->dbcnx->ErrorMsg());exit;};
  if ($affected_rows==0) {exit;};
  echo $this->refreshRecord($id);
 }

 //--- ������ ����� �������/��������������
 public function refreshRecord($id) {
  $query1 = "select id, name from `faculty` where id = $id LIMIT 1";
  $rs = $this->cfg->dbcnx->Execute($query1);
  $db = $rs->fields;
  return $this->scatterHTML($db);
 }

 //--- ��������� ������������� �������� ������
 public function askdeleteRecord($id) {
  $query1 = "select name from `faculty` where id = $id LIMIT 1";
  $rs = @$this->cfg->dbcnx->Execute($query1);
  if ($rs->RecordCount() == 0) {$this->showerror('������ �� �������. �������� �����'); exit;}
  $name = trim($rs->fields('name'));
  $this->cfg->smarty -> assign('id', $id);
  $this->cfg->smarty -> assign('fclass', 'faculty');
  $this->cfg->smarty -> assign('warn', '�� ������ ������ �������� ��������� ? ');
  $this->cfg->smarty -> assign('warn1', $name);
  $this->cfg->smarty -> display('record_delete.tpl');
 }

 //--- ������� ������
 public function deleteRecord($id) {
  $query1 = "delete from `faculty` where id = $id";
  $rs = @$this->cfg->dbcnx->Execute($query1);
  $affected_rows = $this->cfg->dbcnx->Affected_Rows();
  if (!$rs) {$this->showerror($this->cfg->dbcnx->ErrorMsg());exit;};
  if ($affected_rows==0) {$this->showerror('������ �� �������. �������� �����');exit;};
 }
}
?>