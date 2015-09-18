<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use yii\widgets\Pjax;
//use dosamigos\datepicker\DatePicker;
use kartik\date\DatePicker;
use kartik\grid\GridView;

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
        ?>
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="glyphicon glyphicon-calendar"></i> Графики</h3>
        </div>
    </div>
    <?= GridView::widget([
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
                'value' => 'specialist.occupation.name',
                'format' => 'text',
                'label' => 'Специальность',
            ],
            [
                'attribute' => 'start_time',
//                'value' => function($model){
//                    $result = Yii::$app->formatter->asDatetime($model->start_time, 'd-MM-Y H:i');
//                    return $result;
//                },                
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
            'email',
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
    ?>

</div>
