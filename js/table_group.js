t =  $('#ftable').DataTable({
 "bProcessing":true,
 "bServerSide":false,
 //"ajax": "ajax_group.php",
 "ajax": "process_ajax.php?class=group&action=getdata",
 "columnDefs": [
  {"targets":0, bSearchable: true,  bSortable: true},
  {"targets":1, bSearchable: true,  bSortable: true},
  {"targets":2, bSearchable: false, bSortable: false,
   "render":function(data, type, row) {
    var aa = '<td><a href="#" onclick=\'fx.editR("group", '+row[0]+');\'>Змінити</a><br>';
       aa +=     '<a href="#" onclick=\'fx.askdelR("group", '+row[0]+');\'>Видалити</a></td>';
    return aa;
   }
  }],

 "createdRow": function (row, data, index) {
  var r = $('td', row);
  r.eq(0).addClass("dt-body-center");
  r.eq(2).addClass("dt-body-center");
  r.parent('tr').attr('id', 'i'+r.eq(0).text());
  //var ss = sessionStorage;
  //if ('i'+r.eq(0).text() == ss.student_RowId) {r.parent('tr').addClass('selected');}
 },


 "rowCallback": function(row, data) {
  var ss = sessionStorage;
  var r = $('td', row);
  if ('i'+r.eq(0).text() == ss.group_RowId) {r.parent('tr').addClass('selected');}
 },
  ///   'sScrollY':"400px", 'sScrollCollapse':true, "bJQueryUI": true,
 "bPaginate":true,
 "sPaginationType": "full_numbers",
 "oLanguage": {"sUrl": "js/russian.js"},
 "aLengthMenu": [10, 25, 50, 100],
 "iDisplayLength": 10,  // defaultное значение pagelength
 "bStateSave": true
 });


//**** выделить строку
$('#ftable tbody').on('click', 'tr', function () {
 t.$('tr.selected').removeClass('selected');
 $(this).addClass('selected');
 var ss = sessionStorage;
 ss.group_RowId = $(this).attr('id');
});


//--- валидация данных формы
function recform_validate() {
 var errors = 0;
 errors += vx.vEmpty('name', 'Поле назви групи не може бути пустим');
 return (errors == 0);
}
