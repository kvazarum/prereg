<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
//use dosamigos\datepicker\DatePicker;
use kartik\date\DatePicker;
//use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\RecordsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'График';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="records-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php 
        Pjax::begin();

    echo Html::beginTag('div', ['class' => 'panel panel-info']);
        $content = Html::tag('h3', '<i class="glyphicon glyphicon-calendar"></i> Графики', ['class' => "panel-title"]);
        echo Html::tag('div', $content, ['class' => "panel-heading"]);
    echo Html::endTag('div');
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function($model){
            $class = '';
            if ($model->reserved)
            {
                $class =  'success';
            }
            if ($model->visited)
            {
                $class = 'info';
            }
            return ['class' => $class];
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'class' => 'yii\grid\CheckboxColumn',
                'checkboxOptions' => function($model){
                    return [$model->id];
                },
            ],
            [
                'attribute' => 'specialist_id',
                'value' => 'specialist.doctor.name',
                'format' => 'text',
                'label' => 'Врач'
            ],
            [
                'attribute' => 'occupationName',
                'value' => 'specialist.occupation.name',
                'format' => 'text',
                'label' => 'Специальность',
            ],
            [
                'attribute' => 'start_time',
                'value' => function($model){
                    $day = date('d',strtotime($model->start_time));
                    $year = date('Y',strtotime($model->start_time));
                    $month = date('m',strtotime($model->start_time));
                    $hour = date('H',strtotime($model->start_time));
                    $minut = date('i',strtotime($model->start_time));
                    $text = $day.'-'.$month.'-'.$year.' '.$hour.':'.$minut;
//                    $text = $model->start_time;
                    $result = $text;
                    return $result;
                },                
                'headerOptions' => [
                    'class'=>'col-lg-2'
                ],                
//                'format' => ['datetime', 'dd-MM-Y H:i'],
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'start_time',
                    'name' => 'start_time', 
                    'language' => 'ru',
                    'readonly' => true,
                    'value' => date('d-M-Y', time()),
                    'options' => ['placeholder' => 'Выберите дату ...'],
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'todayHighlight' => true,
                        'todayBtn' => true,
                        'autoclose'=>true,
                    ]
                ])                
            ],
            'name',
//            'phone',
//            [
//                'attribute'=>'email',
//                'format' => 'html',
//                'value' => function($model){
//                    return Html::mailto($model->email);
//                }
//            ],
            [
                'attribute'=>'reserved',
                'format' => 'html',
                'filter'=>array(
                    "1" => "Занято",
                    "0" =>"Свободно"
                ),
                'value' => function($model){
                    if ($model->reserved == 0)
                    {
                        $result = '<span class="glyphicon glyphicon-remove text-danger"></span>';
                    }
                    else
                    {
                        $result = '<span class="glyphicon glyphicon-ok text-success"></span>';
                    }
                    return $result;
                }                
            ], 
            [
                'attribute'=>'visited',
                'format' => 'html',
                'filter'=>array(
                    "1" => "Явился",
                    "0" =>"Не явился"
                ),
                'value' => function($model){
                    if ($model->visited == 0)
                    {
                        $result = '<span class="glyphicon glyphicon-remove text-danger"></span>';
                    }
                    else
                    {
                        $result = '<span class="glyphicon glyphicon-ok text-success"></span>';
                    }
                    return $result;
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); 
        Pjax::end();
        echo Html::tag('button', "Удалить выбранное", ['class' => 'btn btn-danger', 'id' => 'delete-selected']);
    ?>

</div>
