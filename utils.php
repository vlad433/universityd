<?php
 //--- проверка строки на соответствие формату даты
 function mycheckdate($datestr, $optional = 0, $fmin = '01.01.1902', $fmax = '31.12.2037') {
  $datestr = trim($datestr);
  if (($optional == 1) and ($datestr == '')) return '';
  if (($optional == 0) and ($datestr == '')) {return 'ѕоле даты не может быть пустым';}
  if (!preg_match('/^(\d{2})\.(\d{2})\.(\d{4})$/', $datestr, $matches)) {return '‘ормат даты DD.MM.YYYY';}
  if (!checkdate($matches[2], $matches[1],$matches[3])) {return 'Ќеверна€ дата';}
  $data = DMSQL($datestr);
  $dmin = DMSQL($fmin);
  $dmax = DMSQL($fmax);
  if (($dmin > $data) or ($data > $dmax)) {return 'ƒата выходит за диапазон '.$fmin.' - '.$fmax;}
  return '';
 }

 //--- проверка строки на соответствие формату даты
 function checkempty($fldname, &$arr, &$er1, $message = 'ѕоле не может быть пустым') {
  if (trim($arr[$fldname]) == '') {$er1[$fldname] = $message;}
  return (trim($arr[$fldname]) == '');
 }

//--- ѕереводит строку даты в русский формат (d.m.Y)
function DRUS($d) {
 $d1 = date_create($d);
 return date_format($d1, 'd.m.Y');
}

//--- ѕереводит строку даты в формат MSQL (Y-m-d)
function DMSQL($dt) {
 $d = substr($dt, 0,2);
 $m = substr($dt, 3,2);
 $y = substr($dt, 6,4);
 $d1 = date_create($y.'-'.$m.'-'.$d);
 return date_format($d1, 'Y-m-d');
}

 function buildSelect($dbc, $txt, $tbl_id, $spr_id, $spr_name, $finit, $fselectsql, $addempty=0) {
  $rs = $dbc->Execute($fselectsql);
  $res  = "<label for='$tbl_id'>$txt</label>\n";
  $res .= "    <select  name=\"$tbl_id\" size='1' style='font-family:Courier New'>\n";
  if ($addempty==1) {
   $sel = ('' == $finit) ? " selected='selected' " : "";
   $res .= "    <option value='' $sel><пусто>";
  }
  while (!$rs->EOF) {
   $sel = ($rs->fields[$spr_id] == $finit) ? " selected='selected' " : "";
   $res .= "    <option value={$rs->fields[$spr_id]} $sel ";
   $res .= ">{$rs->fields[$spr_name]}</option>\n";
   $rs->MoveNext();
  }
  return $res . "   </select>";
 }
?>