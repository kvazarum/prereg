<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

$this->title = 'Перечень записавшихся пациентов';
/* @var $this yii\web\View */
/* @var $model frontend\models\Records */
/* @var $form ActiveForm */
?>
<div class="day-report">
    <div class="container">
        <div class="panel panel-info">
                <div class="panel-heading">
               <div class="col-lg-5">
<!--                    <label for="date_from">
                        Дата начала отчёта
                    </label>-->
                        <?= DatePicker::widget([
                            'id' => 'report_date',
                            'name' => 'report_date',
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
                <input id="day-report-submit" type="button" class="btn btn-success" value="Сформировать">
            </div><!-- panel-heading -->
            <div class="panel-body" id="body">

            </div>
        </div><!-- panel panel-info -->
    </div><!-- container -->

</div><!-- day-report -->
