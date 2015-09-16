<?php

use yii\helpers\Html;
use yii\grid\GridView;
//use dosamigos\datepicker\DatePicker;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\SpecialistsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Специалисты';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="specialists-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить специалиста', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php Pjax::begin() ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'doctor_id',
                'value' => 'doctor.name',               
            ],
            [
                'attribute' => 'occupation_id',
                'value' => 'occupation.name',                 
            ],
//            [
//                'attribute' => 'created_at',
//                'value' => 'created_at',
//                'format' => 'raw',
//                'filter' => DatePicker::widget([
//                    'model' => $searchModel,
//                    'attribute' => 'created_at',
//                    'language' => 'ru',
//                    'size' => 'ms',
//                    'clientOptions' => [
//                        'autoclose' => FALSE,
//                        'format' => 'yyyy-mm-dd',
//                        'todayBtn' => false
//                    ]
//                ])
//            ],            
            'created_at',
            'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>
    <?php Pjax::end(); ?>

</div>
