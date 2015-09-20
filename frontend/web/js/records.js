/**
 * Если установлен признак "одним днём", то
*  при изменении даты "от" меняем дату "до"
*/       
$("#date_from").change(function(){
    if (compareDates())
    {
        if ($('#one_day').prop('checked') == true)
        {
            $('#date_to').val($('#date_from').val());
        }
        $('#generate').prop('disabled', false);
    }
    else
    {
        showAlert('<div>Дата начала диапазона не может быть больше даты конца.</div>');
    }
});

/**
*   При изменении даты "до" устанавливаем доступность кнопки "генерировать"
*/       
$("#date_to").change(function(){
    var old_value = $("#date_from").val();
    
    if (compareDates())
    {
        $('#generate').prop('disabled', false);
    }
    else
    {
        $(this).val(old_value);
        $(".modal-content").addClass('alert-danger');
        $("div.modal-body").html('<div>Дата конца диапазона не может быть меньше даты начала.</div>');
        $("#modal").modal('show');
    }
});

function showAlert(text)
{
    $(".modal-content").addClass('alert-danger');
    $("div.modal-body").html(text);
    $("#modal").modal('show');    
}

function compareDates()
{
    var result = true;
    var from = new Date($("#date_from").val()).valueOf();
    var to = new Date($("#date_to").val()).valueOf();    
    if (to < from)
    {
        result = false;
        showAlert('<div>Дата конца диапазона не может быть меньше даты начала.</div>');
    }
    return result;
}

/**
*   При изменении checkbox "одним днём"
*/    
$("#one_day").change(function(){
    if ($(this).prop('checked') == true)
    {
        $('#date_to').attr('disabled', true);
    }
        else
    {
        $('#date_to').attr('disabled', false);
    }
});

/**
*   Прорисовка одной ячейки таблицы
**/
function drawCell(strtime, strtime2, date)
{
    var val = date + '&' + strtime;
    var data = '<div class="" style="display: inline-block; font-size: smaller; margin: 2px; white-space: nowrap; width: 105px;">';
        data += '<div style="display: inline-block;">';
            var text = '<input class="tag" type="checkbox" checked value="' + val + '" />';
            data += text;
        data += '</div>';
        data += '<div class="" style="display: inline-block; margin-left: 5px; width: 96%; text-align="left">';
            data += '<span>' + getHour(strtime) + ':' + getMinute(strtime) + ' - ' + getHour(strtime2) + ':' + getMinute(strtime2) + '</span>';
        data += '</div>';
    data += '</div>';

    return data;
}

function getHour(data)
{
    data = Number(data);
    var rest = data%60;
    result = 0;

    if (rest == 0)
    {
        result = data/60;
    }
    else
    {
        result = (data - rest)/60;
    } 
    return result;
}

function getMinute(data)
{
    data = Number(data);
    var result = data%60;
    if (result < 10)
    {
        result = '0' + result;
    }
    return result;
}

/**
*   Разница между двумя датами
*/    
function getCountOfDays(data_start, data_end)
{
    var result = (new Date(data_end).getTime() - new Date(data_start).getTime())/1000/60/60/24;
    return result;
}

/**
 * При смене врача
 */
$("#specialist").change(function(){
    
    $("#records").empty();
    $("#generate").attr("disabled", false);
        
    var id = $(this).val();
    var doctor_id;

//    $.get("/specialists/get-name", {id : id}, function(data){
//        data = $.parseJSON(data);
//        $("#name").text(data);
//    });
        
    $.get("/specialists/get-data", {id : id}, function(data){
        data = $.parseJSON(data);
        getII(data);
    });
});

function getII(data)
{
        setPeriod(data.occupation_id);
        
        $.get("/doctors/get-data", {id : data.doctor_id}, function(data){
            data = $.parseJSON(data);

            var ds = data.start_time;
            var de = data.end_time;
            
            var arr = getHours(ds);
            
            var sh = arr[1];
            var sm = arr[0];
            
            arr = getHours(de);
            
            var eh = arr[1];
            var em = arr[0];
            if (sh < 10)
            {
                sh = "0" + sh;
            }
            if (sm < 10)
            {
                sm = "0" + sm;
            }
            if (eh < 10)
            {
                eh = "0" + eh;
            }
            if (em < 10)
            {
                em = "0" + em;
            }             
            $("#start_time").attr("value",sh  + ":"+ sm);
            $("#end_time").attr("value",eh  + ":"+ em);
           
            $("#name").attr("html", data.name);
        });
};

function setPeriod(id)
{
    $.get("/occupations/get-data", {id : id}, function(data){
        data = $.parseJSON(data);
        $("#period").attr("value", data.period);   
    });
}

function getHours(data)
{
    minutes = data%60;
    hours = (data - minutes)/60;
    var result = [minutes, hours];
    return result;    
}

/**
 * При нажатии на кнопку "Сохранить"
 */
$("#create").click(function(){
    var date = $('#date_from').val();
    var spec = $('#specialist').val();
    $('#records').fadeOut();        

    $('.tag:checked').each(function()
    {
        data = $(this).val();
        data = data.split('&');
        time = data[1];
        date = data[0];

        $(this).prop('checked', false);

        $('#create').attr('disabled', true);

        $.get("/records/save-record", {date : date, spec : spec, time: time}, function(data){        

        });
    })
});


$("#generate").click(function()
{       
    $('#records').fadeIn();  
    $(this).attr('disabled', true);
        
    var period = $("#period").val();
    var start = $("#start_time").val(); //время начала рабочего дня
    var end = $("#end_time").val(); //время конца рабочего дня
        
    var data_start = $("#date_from").val(); // начальный день периода графика
    var data_end = $("#date_to").val(); // последний день периода графика
        
    var countDay = getCountOfDays(data_start, data_end); // количество дней в графике
    start = start.split(":");
    end = end.split(":");

    var start_hour=Number(start[0]);
    var start_minute=Number(start[1]);
    var end_hour=Number(end[0]);
    var end_minute=Number(end[1]);

    var strtime_main = Number(start[0])*60 + Number(start[1]);   //начальное время работы в минутах

    var interval = (end_hour*60 + end_minute) - strtime_main;    //кол-во минут в рабочем дне
    var rest = interval%period; 

    var count;          //кол-во приёмов больных в рабочем дне
    if (rest === 0)
    {
        count = interval/period;
    }
    else
    {
        count = (interval - rest)/period;
    }
    
    $('#records').empty();  //  убираем предыдущие графики
    
    var data = '';
    var days = ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'];
    for (j = 0; j<= countDay; j++)    
    {   
        var strtime = strtime_main;
        var date = new Date(data_start);
        var number = date.getDay();
        var dayOfWeek = days[number];
        
        var text;
        
        if (number == 0 || number == 6)
        {
            text='panel-danger';
        }
        else
        {
            text='panel-info';
        }
        data += '<div class="panel ' + text + '">';
            data += '<div class="panel-heading">';
                data += '<div class="panel-title">'
                    data += '<input onclick="dayTitle()" value="' + data_start + '" class="dayTitle" type="checkbox" checked>&nbsp' + dayOfWeek + '<span style="margin-left: 10px;"><b>'+data_start + '</span></b>';
                data += '</div>';
            data += '</div>';
            data += '<div class="panel-body" >';
            for (i = 0; i < count; i++)
            {
                var strtime2 = Number(strtime) + Number(period);    // время конца приёма больного

                data += drawCell(strtime, strtime2, data_start);
                strtime += Number(period);

            }
            data += '</div>';
            
        data += '</div>';
        var D = new Date(data_start);
        D.setDate(D.getDate() + 1);
        var month = D.getMonth()+1;
        if (month < 10)
        {
            month = '0' + month;
        }
        newDate = [D.getFullYear(), month, D.getDate()];
        data_start = newDate.join('-');
        
    }
    $("#records").append(data);
    $("#create").attr('disabled', false);
});