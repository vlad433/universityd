<?php
 require_once 'cfg.php';
 $dbcnx->SetFetchMode(ADODB_FETCH_ASSOC);
 $query1 = 'select s.faculty_id, 0 as group_id, f.name as name,
   count(s.id) kol
   from university.student s
   join university.faculty f on f.id = s.faculty_id
   group by 1,2,3
 union all
 select s.faculty_id, g.id, g.name as name,
   count(s.id) kol
   from university.student s
   join university.group g on g.id = s.group_id
   group by 1,2,3
   order by 1,2,3';

 $rs = $dbcnx->Execute($query1);
 $db = $rs->GetArray();

 // передача результата smarty
 $smarty -> assign('db', $db);
 $smarty -> display('report3.tpl');
?>
