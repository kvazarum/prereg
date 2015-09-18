<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use frontend\models\Specialists;
use frontend\models\Occupations;

/* @var $this yii\web\View */
/* @var $model frontend\models\Requests */
/* @var $form ActiveForm */

$this->title = 'Отчёт по специалистам';
?>
<div class="specialist-report">
    <div class="container">
        <div class="panel-default panel-info">
            <div class="panel panel-heading">
                <table class="table">
                    <tr>
                <td class="col-lg-3">
                    <label for="date_from">
                        Дата начала отчёта
                    </label>
                        <?= DatePicker::widget([
                            'id' => 'date_from',
                            'name' => 'date_from', 
                            'language' => 'ru',
                            'value' => date('Y-m-d', time()),
                            'options' => ['placeholder' => 'Выберите дату ...'],
                            'pluginOptions' => [
                                'format' => 'yyyy-mm-dd',
                                'autoclose' => true,
                                'todayHighlight' => true
                            ]
                        ])
                    ?>
                </td>
                <td class="col-lg-3">
                    <label for="date_to">
                        Дата конца отчёта
                    </label>
                        <?= DatePicker::widget([
                            'id' => 'date_to',
                            'name' => 'date_to', 
                            'language' => 'ru',
                            'value' => date('Y-m-d', time()),
                            'options' => ['placeholder' => 'Выберите дату ...'],
                            'pluginOptions' => [
                                'format' => 'yyyy-mm-dd',
                                'autoclose' => true,
                                'todayHighlight' => true
                            ]
                        ])
                    ?>
                </td>
            </tr>
            <tr>
                    <td>
                        <?php
                            $list = ArrayHelper::map(Occupations::find()->all(), 'id' , 'name');
                            echo Select2::widget([
                            'class' => "form-control",
                            'id' => "occupation",
                            'name' => "occupation",
                            'value' => '',
                            'data' => $list,
                            'options' => ['multiple' => false, 'placeholder' => 'Выберите специальность ...']
                        ]);
                        ?>
                    </td>
                    <td>
                        <select id="specialist" class="form-control"></select>
                        <?php
//                            echo Select2::widget([
//                            'class' => "form-control",
//                            'id' => "specialist",
//                            'name' => "specialist",
//                            'value' => '',
//    //                        'data' => $list,
//                            'options' => [
//                                'multiple' => false,
//                                'placeholder' => 'Выберите врача ...'
//                            ]
//                        ]);
                        ?>
                    </td>                
                </tr>
                </table>
            </div>
            <div class="panel-body" id="body">

            </div>
        </div>
    </div>
    <?php
    
$script = '
//  при изменении врача    
    $("#specialist").change(function(){
        var occupation_id = $("#occupation").val();
        var date_from = $("#date_from").val();
        var date_to = $("#date_to").val();
        
        $.get("/records/get-report-by-specialist", {occupation_id : occupation_id, date_from: date_from, date_to:date_to}, function(data){
            data = $.parseJSON(data);
            $("#body").empty();
            data.forEach(function(item)
            {
//                var class="panel panel-heading";
                var text = "<div>";
                    text += "<h3>"+item.oname+"</h3>";
                    text += "<div>";
                        text += "<h4>"+item.dname+"</h4>";
                    text += "</div>";
                text += "</div>";
                $("#body").append(text);
            });
            
            
        });
    });

//  добавление <option> с данными заданного специалиста
    function getSpecList(doctor_id, specialist_id)
    {
        // получаем имя врача по заданному id
        $.get("/doctors/get-data", {id : doctor_id}, function(data2){
            data2 = $.parseJSON(data2);
            var name = data2.name;
            text = "<option value=" + specialist_id + ">" + name + "</option>";
            $("#specialist").append(text);
        });    
    }

//  при изменении специальности
    $("#occupation").change(function(){
        $("#specialist").empty();   // очищаем старый список специалистов
        var occupation_id = $(this).val();
        var doctor_id;
             
        $.get("/specialists/get-by-occup", {id : occupation_id}, function(data){   // получаем список специлистов по заданой специальности
            data = $.parseJSON(data);
            var text = "";
            var count = data.length;
            if (count > 0)
            {
                text = "<option selected disabled>Выберите врача ...</option>";
                $("#specialist").append(text);            
            }
            data.forEach( function(item)
            {
                doctor_id = item.doctor_id; // id доктора
                specialist_id = item.id;    // id специалиста

                // получаем строку с option по заданному id доктора и id специалиста
                getSpecList(doctor_id, specialist_id);
                
            });
        });
    });
';
$this->registerJs($script); 
    ?>

</div><!-- specialist-report -->
