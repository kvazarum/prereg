/**
 * Если установлен признак "одним днём", то
*  при изменении даты "от" меняем дату "до"
*/       
$("#date_from").change(function(){
    if ($('#one_day').prop('checked') == true)
    {
        $('#date_to').val($('#date_from').val());
    }
    $('#generate').prop('disabled', false);
});

/**
*   При изменении даты "до" устанавливаем доступность кнопки "генерировать"
*/       
$("#date_to").change(function(){
    var old_value = $("#date_from").val();
    var from = new Date($("#date_from").val()).valueOf();
    var to = new Date($(this).val()).valueOf();
    if (to >= from)
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
        })
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