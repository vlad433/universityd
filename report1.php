<?php
 require_once 'cfg.php';
 $dbcnx->SetFetchMode(ADODB_FETCH_ASSOC);
 $query1 = 'select g.id, g.name, count(s.id) kol
  from university.`group` g
  join university.student s on s.group_id = g.id
  group by 1,2';

 $rs = $dbcnx->Execute($query1);
 $db = $rs->GetArray();

 // передача результата smarty
 $smarty -> assign('db', $db);
 $smarty -> display('report1.tpl');
?>
