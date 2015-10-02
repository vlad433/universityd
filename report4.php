<?php 
 if (isset($_POST[buttOK]) and $_POST[buttOK]=='Відмова') {
  header("Location: index.php"); exit;
 }
 require_once 'utils.php';
 require_once 'cfg.php';

 if (isset($_POST[buttOK])) {
  $date1 = $_POST[date1];
  $date2 = $_POST[date2];
  $smarty -> assign('date1', $date1);
  $smarty -> assign('date2', $date2);
  $smarty -> assign('lhead', 'Дни рожления');
  $smarty -> assign('laction', 'report4.php');

  $err=array();
  $errdate = mycheckdate($date1, 0, '01.01.1915', '31.12.2010');
  if ($errdate <> '') $err[date1] = $errdate;
  $errdate = mycheckdate($date2, 0, '01.01.1915', '31.12.2010');
  if ($errdate <> '') $err[date2] = $errdate;

  if (sizeof($err) == 0) {
   $params = array(DMSQL($date1), DMSQL($date2));
   $query1 = "select s.*, f.name faculty_name, g.name group_name
    from student s
    join faculty f on f.id = s.faculty_id
    join `group` g on g.id = s.group_id
    where s.birthdate between ? and ? 
    order by s.birthdate";
   $rs = $dbcnx->Execute($query1, $params);
   $db = $rs->GetArray();
   $smarty -> assign('db', $db);
   $smarty -> display('report4.tpl');
   exit;
  }
 } else { // 1-й показ формы
 }
 $smarty -> assign('err', $err);
 $smarty -> display('daterange.tpl');
?>
