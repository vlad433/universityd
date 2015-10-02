$(function() { //alert('jQuery is loaded');
// $('#inform').text('jQuery is loaded');
 var class_table = $('#ftable').filter('table').data('class');
 if (class_table !== undefined) { $.getScript('js/'+'table_' + class_table+'.js');};
});
 // функции для манипуляции модальной формой редактирования
 fx = {
  "mydelay":int = 250,   	// задержка появления
  // Создает модальную форму и загружает в нее html данные из data
  // заряжает клавиши PgDn, Esc на кнопки Ybtn, Nbtn
  "showmodal": function(fclass, fid, data) {
   if ($('#modal').length == 0) {
    //$('body').addClass('modal-overlay');
    //t.addClass('modal-overlay');
    $("<div id='modal'>").hide().append(data).appendTo("body").removeClass('modal-overlay').fadeIn(fx.mydelay);
   } else {
    $("#modal").children('form').replaceWith(data);
   }

   $('form input[type=submit],input[type=button]').click(function(e) {e.stopPropagation();});
   $('form input[type=text]:visible').first().focus();

   $('#Nbtn').click(function(e) {fx.removemodal();});

   $(document).click(function(event) { // если щелкнул не по форме - закрыть нафиг
    var isform = $(event.target).parent('form').andSelf('form').parent('div').is($('#modal'));
    if (($('#modal').length !== 0) && !isform) {fx.removemodal()};
   });

   $(document).keyup(function(e) {
    if (($('#Nbtn').length !== 0) && (e.which == 27)) {$('#Nbtn').click();}
    if (($('#Ybtn').length !== 0) && (e.which == 34)) {$('#Ybtn').click();}
   });
  },

  // Удаляет модальное окно
  "removemodal": function() { 
   $('#modal').hide().remove();
   //$('body').removeClass('modal-overlay');
   $(document).unbind('keyup click');
  // alert('removemodal');
  },

  //--- Выдать запись на экран для редактирования ч/з аякс
  "editR": function(fclass, fid) {
   $.post("process_ajax.php", 'class=' + fclass + '&action=getRecord&id=' + fid,
    function(data) {
    fx.showmodal(fclass, fid, data);
   });
  },

  "askdelR": function(fclass, fid, fname) {
   $.post("process_ajax.php", 'class=' + fclass + '&action=askDeleteRecord&id=' + fid,
   function(data) {
    fx.showmodal(fclass, fid, data);
   });
  },

  "delR": function(fclass, fid) {
    $.post("process_ajax.php", 'class=' + fclass + '&action=DeleteRecord&id=' + fid,
    function(data) {
     if (data.length == 0) {
      t.row('#i'+fid).remove().draw(false);
     } else {fx.showmodal(fclass, fid, data);} // ошибка
    }
   );
   fx.removemodal();
   return false;
  },

  //--- сохранить запись
  "saveR": function (fclass, fid) {
   //if (!vx.validate(fclass)) {return false;} // проверка на клиенте
   if (!recform_validate()) {return false;} // проверка на клиенте
   var formData = $('.recform').serialize();
   formData = decodeURIComponent(formData); 
   //alert(formData);
   formData = "class=" + fclass + "&action=saveRecord&id=" + fid + "&" + formData;
   //alert(fclass+'\n'+fid);
   $.ajax({
    type: "POST",
    //dataType: 'json',
    url: "process_ajax.php",
    data: formData,
    success: function(data) {
     var html = $('<div>').hide().append(data).andSelf();
     // не прошел валидацию
     if (html.find('.err_mess').length > 0)
      {fx.showmodal(fclass, fid, data); return false;}

     // ошибка на сервере
     if (html.find('form[name=errorform]').length > 0)
      {fx.showmodal(fclass, fid, data); return false;}

     fx.removemodal();
     // ничего не изменили (affected_rows==0) - нафиг
     if (data.length == 0) {return false;}
     a = [];
     html.find('span').each(function(i,e) {a.push($(e).text());});
     var row = t.row($('#i'+fid));
     if (fid == 0) {t.row.add(a).draw(false);} else {row.data(a).draw(false);}
    }
   });
   return false;
  },

  //--- вызвать функцию обьекта
  "callfunc": function (fclass, funcname, params) {
   fclass = eval(fclass);
   params = params || [];
   if (fclass[funcname] == undefined) {alert('не определена функция '+fclass+'/'+funcname); return true;}
   return fclass[funcname].apply(fclass, params);
  }
 }

 // функции для валидации ввода
 vx = {
  //--- проверка обязат.ввода 1 - пустой
  "vEmpty": function(fieldname, fmessage) {
   $("span[name*='err["+fieldname+"]']").text('');
   var myfld = $("input[name*='db["+fieldname+"]']");
   if (!$.trim(myfld.val())) {
    $("span[name*='err["+fieldname+"]']").text(fmessage); return 1;
   } else return 0;
  },

  "vDate": function (s, opt, fmin, fmax){
   $("span[name*='err["+s+"]']").text('');
   opt = opt || 0;
   fmin = fmin || '01.01.1900';
   fmax = fmax || '01.01.2020';
   var myfld = $("input[name*='db["+s+"]']");
   var myval = $.trim(myfld.val());
   var myerr = $("span[name*='err["+s+"]']");
   if ((opt == 0) && (myval == "")) {myerr.text('Поле даты не может быть пустым');return 1;}

   if (!/^(\d{2})\.(\d{2})\.(\d{4})$/.test(fmin)) {alert('Ошибка в минимальной дате '+fmin); return 1;}
   var a = fmin.split('.'); var dmin = new Date(a[2], a[1]-1, a[0]);
   if (dmin.getMonth() !== (a[1]-1) && dmin.getDate() !== a[0]) {alert('Ошибка в минимальной дате '+fmin); return 1;}

   if (!/^(\d{2})\.(\d{2})\.(\d{4})$/.test(fmax)) {alert('Ошибка в максимальной дате '+fmax); return 1;}
   a = fmax.split('.'); var dmax = new Date(a[2], a[1]-1, a[0]);
   if (dmax.getMonth() !== (a[1]-1) && dmax.getDate() !== a[0]) {alert('Ошибка в максимальной дате '+fmax); return 1;}
//alert(myval);
   var r =/^(\d{2})\.(\d{2})\.(\d{4})$/.test(myval);
   if (!r) {myerr.text('Формат даты dd.mm.yyyy!'); return 1;}
   var day;
   a = myval.split('.');
   day = new Date(a[2], a[1]-1, a[0]);
   if (day.getMonth() !== (a[1]-1) && day.getDate() !== a[0]) {myerr.text('Неверная дата '); return 1;}
   if (dmin > day || day > dmax) {myerr.text('Дата выходит за диапазон '+fmin+' - '+fmax);return 1;}
   return 0;
  }
 }
