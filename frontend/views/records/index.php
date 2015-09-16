<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
//use dosamigos\datepicker\DatePicker;
use kartik\date\DatePicker;


/* @var $this yii\web\View */
/* @var $searchModel frontend\models\RecordsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Записи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="records-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<!--    <p>
        <?= Html::a('Create Records', ['create'], ['class' => 'btn btn-success']) ?>
    </p>-->
        <?php 
            echo '<a class ="btn btn-success" href="">';
                echo 'Добавить запись';
            echo '</a>';
            Pjax::begin();
        ?>

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
                $class = 'warning';
            }
            return ['class' => $class];
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
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
                'format' => ['datetime', 'dd-MM-Y H:i'],
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
            [
                'attribute'=>'reserved',
                'filter'=>array(
                    "1" => "Занято",
                    "0" =>"Свободно"
                    ),
            ], 
            [
                'attribute'=>'visited',
                'filter'=>array(
                    "1" => "Явился",
                    "0" =>"Не явился"
                    ),
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); 
        Pjax::end();
        ?>

</div>
