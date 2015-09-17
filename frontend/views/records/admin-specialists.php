<?php

use yii\helpers\Html;
use frontend\models\Records;
use frontend\models\Specialists;
use frontend\models\Doctors;
use yii\helpers\Url;
use dosamigos\datepicker\DatePicker;
use yii\helpers\ArrayHelper;
use frontend\models\Occupations;
use kartik\select2\Select2;
//use kartik\date\DatePicker;

    $specialists = Specialists::findAll(['occupation_id' => $occupation_id]);
    $occupation = Occupations::findOne($occupation_id);
    
    echo '<div class="page-header">';
        echo '<h2>'.$occupation->name.'<span id="name"></span>'.', создание графика приёма</h2>';
    echo '</div>';
    
    $this->title = "Создание графика";
    ?>
    <div class="col-md-3">
    <div class="panel panel-info" style="">
        <div class="panel-heading">
            <h4 class="panel-title">
                Заполните начальные данные
            </h4>
        </div><!-- panel-heading -->    
        
<!-- Панель настройки графика -->
        
        <div class="panel-body">
        <label for="specialist">
            Врач
        </label>            
            <?php
            $list = ArrayHelper::map(Specialists::find()->where(['occupation_id' => $occupation_id])->all(), 'id' , 'doctor.name');
            echo Select2::widget([
                'class' => "form-control",
                'id' => "specialist",
                'name' => "specialist",
                'value' => '',
                'data' => $list,
                'options' => ['multiple' => false, 'placeholder' => 'Выберите врача ...']
            ]);            

            echo '<p /><label for="date_from">
                Дата приёма с:
            </label>';
            echo DatePicker::widget([
            'id' => 'date_from',
            'name' => 'date_from',
            'language' => 'ru',
            'value' => date('Y-m-d', time()),
            'template' => '{addon}{input}',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ]);
            echo '<p /><label for="date_to">
                Дата приёма по:
            </label>';
            echo DatePicker::widget([
            'id' => 'date_to',
            'name' => 'date_to',
            'language' => 'ru',
            'value' => date('Y-m-d', time()),
            'template' => '{addon}{input}',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ]);
            
?>
            <p /><p />
<!--            <a class="btn btn-default" href="">Показать</a>-->
            <div class="form-group form-inline">
                
                <div class="checkbox">
                    <input id="one_day"  type="checkbox" class=”form-control”/>
                    <span>одним днём</span>
                </div>
            </div>            
            
        <?php
           
            
//            echo DatePicker::widget([
//                'id' => 'date',
//                'name' => 'date', 
//                'language' => 'ru',
//                'value' => date('d-m-Y', time()),
//                'options' => ['placeholder' => 'Выберите дату ...'],
//                'pluginOptions' => [
//                    'format' => 'dd-mm-yyyy',
//                    'autoclose' => true,
//                    'todayHighlight' => true
//                ]
//            ]);            
        ?>        
            <label for="start_time">
                Начало рабочего дня, (чч:мм)
            </label> 
            <input id="start_time" onchange="changeTimeTable()" placeholder="Время начала дня" class="form-control">
            <label for="end_time">
                Конец рабочего дня, (чч:мм)
            </label>             
            <input id="end_time" placeholder="Время окончания дня" class="form-control">
            <label for="period">
                Время одного приёма, (мин.)
            </label>                         
            <input id="period" placeholder="Длительность приёма" class="form-control">
            <br />
            <button id='generate' class="btn btn-info" onclick="">Создать график</button>
        </div><!-- panel-body -->
        <div class="panel-footer">
        </div>
    </div> <!-- panel-info -->
     </div>
    
    <!-- График приёмов -->
    <div class="col-md-9">
    <div class="panel panel-info" style="">
        <div class="panel-heading">
            <h4 class="panel-title">
                График
            </h4>
        </div><!-- panel-heading -->
        <div id="main" class="panel-body">
            <div class="container" style="width: 100%;">
                <ul class="list-group" id="records">
                </ul>
            </div>
        </div><!-- panel-body -->
        <div class="panel-footer">
            <button id="create" class="btn btn-info" disabled="true">Сохранить</button>
        </div>
    </div>
    </div>
<!--    <script type="text/javascript" src="/css/create.js" />-->
<?php

/**
 * При сохранении графика
 */
$scriptSave = <<< JS
    $("#create").click(function(){
        var date = $('#date_from').val();
        var spec = $('#specialist').val();
        $('#records').fadeOut();        
            
        $('.tag:checked').each(function()
        {
            time = $(this).val();
            $(this).prop('checked', false);
        
            $('#create').attr('disabled', true);
        
            $.get("/records/save-record", {date : date, spec : spec, time: time}, function(data){
                
                
            });
        })
        

   });    
JS;
$this->registerJs($scriptSave);

/**
 * При изменении даты
 */

$scriptDateChange = <<< JS
    function changeTimeTable()
    {
        $("#create").attr('disabled', false);
        $('#records').empty();
        $("#generate").attr('disabled', false); 
    }        
        
    
        
    $('#date').change(function(){
        changeTimeTable();
   });
JS;
$this->registerJs($scriptDateChange);

/**
 * При нажатии на кнопку генерации графика
 */
$scriptCreateTimetable = <<< JS
        
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
*   При изменении даты "от" меняем дату "до"
*/       
$("#date_from").change(function(){
    if ($('#one_day').prop('checked') == true)
    {
        $('#date_to').val($('#date_from').val());
    }
    $('#generate').prop('disabled', false);
});      
        
/**
*   Разница между двумя датами
*/    
    function getCountOfDays(data_start, data_end)
    {
        return (new Date(data_end).getTime() - new Date(data_start).getTime())/1000/60/60/24;            
    }
        
/**
*   Прорисовка одной ячейки таблицы
**/
    function drawCell(strtime, strtime2)
    {
        var data = '<div class="" style="display: inline-block; font-size: smaller; margin: 2px; white-space: nowrap; width: 105px;">';
        data += '<div style="display: inline-block;">';
            var text = '<input class="tag" type="checkbox" checked value="' + strtime + '" />';
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
        
        
$("#generate").click(function(){
        
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

    var strtime = Number(start[0])*60 + Number(start[1]);   //начальное время работы в минутах

    var interval = (end_hour*60 + end_minute) - strtime;    //кол-во минут в рабочем дне
    var rest = interval%period; 

    var count;          //кол-во приёмов больных в рабочем дне
    if (rest == 0)
    {
        count = interval/period;
    }
    else
    {
        count = (interval - rest)/period;
    }

    $('#records').empty();  //  убираем предыдущие графики
    
    var data = '';
    for (j = 0; j<= countDay; j++)    
    {   
        data += '<b>'+data_start + '</b>';
        data += '<div class="list-group-item success" >';
        for (i = 0; i < count; i++)
        {

            var strtime2 = Number(strtime) + Number(period);    // время конца приёма больного

            data += drawCell(strtime, strtime2);

            

            strtime += Number(period);

        }
        data += '</div>';
    }
    $("#records").append(data);    
    $("#create").attr('disabled', false);
});
JS;

$this->registerJs($scriptCreateTimetable);

/**
 * При выборе специалиста
 */
$script = '
    
function getHours(data)
{
    minutes = data%60;
    hours = (data - minutes)/60;
    var result = [minutes, hours];
    return result;    
}
    
function getII(data)
    {
        setPeriod(data.occupation_id);
        
        $.get("/doctors/get-data", {id : data.doctor_id}, function(data){
            data = $.parseJSON(data);
//            var ds = new Date(data.start_time);
//            var de = new Date(data.end_time);
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

$("#specialist").change(function(){
    
    $("#records").empty();
    $("#generate").attr("disabled", false);
        
    var id = $(this).val();
    var doctor_id;
        
    $.get("/specialists/get-data", {id : id}, function(data){
        data = $.parseJSON(data);
        getII(data);
    })
});  


       ';

$this->registerJs($script);

