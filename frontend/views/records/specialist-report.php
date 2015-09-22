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
        <div class="panel panel-info">
            <div class="panel panel-heading row">
                <div class="col-lg-5">
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
                </div>
                
                <div class="col-lg-5">                
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
                </div>
                        <?php
//                            $list = ArrayHelper::map(Occupations::find()->all(), 'id' , 'name');
//                            echo Select2::widget([
//                            'class' => "form-control",
//                            'id' => "occupation",
//                            'name' => "occupation",
//                            'value' => '',
//                            'data' => $list,
//                            'options' => ['multiple' => false, 'placeholder' => 'Выберите специальность ...']
//                        ]);
                        ?>
                        <!--<select id="specialist" class="form-control"></select>-->
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
                <div class="col-lg-2">
                    <input id="specialist-report" type="button" class="btn btn-success" value="Сформировать">
                </div>
            </div>
            <div class="panel-body" id="body">

            </div>
        </div>
    </div>
    <?php
    
$script = '

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
