<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\models\Occupations;
use frontend\models\Specialists;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use dosamigos\datepicker\DatePicker;
use kartik\grid\GridView;

/* @var $this yii\web\View */

/* @var $form ActiveForm */
$this->title = "Заявки";
$specList = ArrayHelper::map(Occupations::find()->all(), 'id' , 'name');
?>

<div class="request-edit">
    <div class="panel panel-info" style="width: 300px; float: left;">
        <div class="panel-heading">
            <h4 class="panel-title">
                Заполните начальные данные
            </h4>
        </div><!-- panel-heading -->
        <div class="panel-body form-group">
<?php            
            echo Select2::widget([
                'class' => "form-control",
                'id' => "ocupations",
                'name' => "ocupations",
                'value' => '',
                'data' => $specList,
                'options' => ['multiple' => false, 'placeholder' => 'Выберите специальность ...']
            ]);
            echo '<p />';
            echo Select2::widget([
                'class' => "form-control",
                'id' => "specialists",
                'name' => "specialists",
                'value' => '',
//                'data' => $specList,
                'options' => ['multiple' => false, 'placeholder' => '...']
            ]);
            
            echo '<p /><label for="date">
                Дата приёма с:
            </label>';
            echo DatePicker::widget([
            'id' => 'date_from',
            'name' => 'date_from',
            'language' => 'ru',
            'value' => date('d-m-Y', time()),
            'template' => '{addon}{input}',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'dd-mm-yyyy'
                ]
            ]);
            echo '<p /><label for="date">
                Дата приёма по:
            </label>';
            echo DatePicker::widget([
            'id' => 'date_to',
            'name' => 'date_to',
            'language' => 'ru',
            'value' => date('d-m-Y', time()),
            'template' => '{addon}{input}',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'dd-mm-yyyy'
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
<!--            <div role="chekbox" />-->
                
            <button disabled="true" id='show' class="btn btn-info" onclick="">показать заявки</button>
        </div><!-- panel-body -->
    </div><!-- panel-info -->
    
    <div class="panel panel-info" style="width: 700px; float: right;">
        <div class="panel-heading">
            <h4 class="panel-title">
                <span>График</span>
                <span id="spec_name"></span>
            </h4>
        </div><!--  panel-heading -->
        <div id="main" class="panel-body">
            <div class="container" style="width: 100%;">
                <ul class="list-group" id="requests">
                </ul>
            </div>
        </div><!--  panel-body  -->       
    </div>
</div><!-- request-edit -->


<?php 
$specChange = <<<JS

function setUrl()
{
    var date_from = $('#date-from').val();
    var date_to = $('#date-to').val();
    var specialist_id = $('#specialists').val();
    
    var url = '/records/requests';
    $('a').attr('href', url);
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
*   При изменении даты "от" меняем дату "до"
*/       
$("#date_from").change(function(){
    if ($('#one_day').prop('checked') == true)
    {
        $('#date_to').val($('#date_from').val());
    }
});        
        
/**
*   Заполнение select списком врачей на основании переданной специальности
*/
function fillSpecs(cel){
        $('#specialists').empty();  //  убираем предыдущий список
        var spec_id = cel.value;
        select = '<option disabled  selected value="">Выберите врача ...</option>';
        $('#specialists').append(select);
        $('#select2-specialists-container').empty();
        
    $.get("/specialists/get-by-occup", {id : spec_id}, function(data){
        data = $.parseJSON(data);

        data.forEach(function(specialist)   //  Заполняем селект данными врачей
        {
            $.get("/doctors/get-data", {id : specialist.doctor_id}, function(data){
                data = $.parseJSON(data);
                select = '<option title="' + data.description + '" value="' + specialist.id + '">' + data.name + '</option>';
                $('#specialists').append(select);
            })

        })
    })        
}
        
/**
*   При нажатии кнопки "Показать"
*/    
function showRequests()
{    
    $('#requests').empty();
    
    var date_from = $('#date_from').val();
    var date_to = $('#date_to').val();
    var specialist_id = $('#specialists').val();

    $.get("/records/get-data", {date : date_from, specialist_id: specialist_id}, function(data){
        data = $.parseJSON(data);
        var text = '';
        for (i = 0; i < data.length; i++)
        {
            $.get("/specialists/get-data", {id : specialist_id}, function(data){
                
            });
            text += '<div class="btn" style="display: inline-block; margin-left: 5px; width: 96%; text-align="left">';
                text += '<div class="col-lg-1" style="display: inline-block; margin-left: 5px;">';
                    text += data[i].specialist_id;
                text += '</div>';
//   
                //время
                text += '<div class="col-lg-2" style="display: inline-block; margin-left: 5px;">';
                    text += data[i].start_time;
                text += '</div>';
//                //забронировано
                text += '<div class="col-lg-3 panel" style="display: inline-block; margin-left: 5px;">';
                    text += '<select id="reserved">';
                        var selected = '';
                        if (!data[i].reserved)
                        {
                            text += '<option selected value="0">Не занято<option>';
                        }
                        else
                        {
                            text += '<option value="0">Не занято<option>';
                        }
                        
                        if (data[i].reserved)
                        {
                            text += '<option selected value="1">Занято<option>';
                        }
                        else
                        {
                            text += '<option value="1">Занято<option>';
                        }        
                    text += '</select>';
                text += '</div>';        
//                //посещено
                text += '<div class="col-lg-4" style="display: inline-block; margin-left: 5px;">';
                    text += '<select id="visited">';
                        text += '<option value="0">Не явился<option>';
                        text += '<option value="1">Явился<option>';
                    text += '</select>';
                    $("#visited").val(data[i].visited);
                text += '</div>';
            text += '</div>';
        }
        $('#requests').append(text);
    })
}
        
$('#show').click(function(){
    showRequests();
});        

/**       
*  При выборе врача        
*/        
$('#specialists').change(function(){
    $('#show').attr('disabled', false);
    $('#spec_name').text = $(this).text;
    setUrl();
});
        
/**
*   При выборе специальности
*/
$('#ocupations').change(function(){
    fillSpecs(this);
});
JS;
$this->registerJs($specChange);
?>
