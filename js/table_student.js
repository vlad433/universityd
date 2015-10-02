t =  $('#ftable').DataTable({
 "bProcessing":true,
 "bServerSide":true,
 //"ajax": "ajax_student.php",
 "ajax": "process_ajax.php?class=student&action=getdata",
 "columnDefs": [
  {"targets":0,  bSearchable: true,  bSortable: true},
  {"targets":1,  bSearchable: true,  bSortable: true},
  {"targets":2,  bSearchable: true,  bSortable: true},
  {"targets":3,  bSearchable: true,  bSortable: true},
  {"targets":4,  bSearchable: true,  bSortable: true},
  {"targets":5,  bSearchable: false, bSortable: false,
   "render":function(data, type, row) {
    var aa = '<td><a href="#" onclick=\'fx.editR("student", '+row[0]+');\'>Змінити</a><br>';
       aa +=     '<a href="#" onclick=\'fx.askdelR("student", '+row[0]+');\'>Видалити</a></td>';
    return aa;
   }
  }],

 "createdRow": function (row, data, index) {
  var r = $('td', row);
  r.eq(0).addClass("dt-body-center");
  r.eq(2).addClass("dt-body-center");
  r.eq(5).addClass("dt-body-center");
  r.parent('tr').attr('id', 'i'+r.eq(0).text());
  var ss = sessionStorage;
  if ('i'+r.eq(0).text() == ss.student_RowId) {r.parent('tr').addClass('selected');}
 },

// "rowCallback": function(row, data) {
//  var ss = sessionStorage;
//  var r = $('td', row);
//  if ('i'+r.eq(0).text() == ss.student_RowId) {r.parent('tr').addClass('selected');}
// },
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
 // var name = $('td', this).eq(1).text(); alert( 'You clicked on '+name+'\'s row' );
 t.$('tr.selected').removeClass('selected');
 $(this).addClass('selected');
 var ss = sessionStorage;
 ss.student_RowId = $(this).attr('id');
});

/********* показать какие-то скрытие данные *//*
 $('#example tbody').on( 'click', 'button', function () {
  var data = table.row( $(this).parents('tr') ).data();
   alert( data[0] +"'s salary is: "+ data[ 1 ] );});*/

//--- валидация данных формы

function recform_validate() {
 var errors = 0;
 errors += vx.vEmpty('name', 'Поле прізвище не може бути пустим');
 errors += vx.vDate('birthdate', 0, '01.01.1915', '31.12.2010');
 return (errors == 0);
}
