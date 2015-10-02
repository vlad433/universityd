<?php
 //--- �������� ������ �� ������������ ������� ����
 function mycheckdate($datestr, $optional = 0, $fmin = '01.01.1902', $fmax = '31.12.2037') {
  $datestr = trim($datestr);
  if (($optional == 1) and ($datestr == '')) return '';
  if (($optional == 0) and ($datestr == '')) {return '���� ���� �� ����� ���� ������';}
  if (!preg_match('/^(\d{2})\.(\d{2})\.(\d{4})$/', $datestr, $matches)) {return '������ ���� DD.MM.YYYY';}
  if (!checkdate($matches[2], $matches[1],$matches[3])) {return '�������� ����';}
  $data = DMSQL($datestr);
  $dmin = DMSQL($fmin);
  $dmax = DMSQL($fmax);
  if (($dmin > $data) or ($data > $dmax)) {return '���� ������� �� �������� '.$fmin.' - '.$fmax;}
  return '';
 }

 //--- �������� ������ �� ������������ ������� ����
 function checkempty($fldname, &$arr, &$er1, $message = '���� �� ����� ���� ������') {
  if (trim($arr[$fldname]) == '') {$er1[$fldname] = $message;}
  return (trim($arr[$fldname]) == '');
 }

//--- ��������� ������ ���� � ������� ������ (d.m.Y)
function DRUS($d) {
 $d1 = date_create($d);
 return date_format($d1, 'd.m.Y');
}

//--- ��������� ������ ���� � ������ MSQL (Y-m-d)
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
   $res .= "    <option value='' $sel><�����>";
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