<?php
 require_once 'cfg.php';
 $dbcnx->SetFetchMode(ADODB_FETCH_ASSOC);
 $query1 = 'select g.id, g.name
  from university.`group` g
  join university.student s on s.group_id = g.id order by g.id';

 $db = array();
 $rs = $dbcnx->Execute($query1);
 $i=0;
 $id_old=$rs->fields[id];
 
 while (!$rs->EOF) {
  $i= ($rs->fields[id] == $id_old) ? $i : $i + 1;
  $db[$i][id]    = $i;
  $db[$i][name]  = $rs->fields[name];
  $db[$i][kol]++;
  $id_old = $rs->fields[id];
  $rs->MoveNext();
 }

 // передача результата smarty
 $smarty -> assign('db', $db);
 $smarty -> display('report1.tpl');
?>
