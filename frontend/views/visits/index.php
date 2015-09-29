<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\User;
use frontend\models\Insurers;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\VisitsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Посещения';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="visits-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<!--    <p>
        <?= Html::a('Create Visits', ['create'], ['class' => 'btn btn-success']) ?>
    </p>-->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
//            [
//                'attribute' => 'id',
//                'headerOptions' => [
//                    'class' => 'col-lg-1'
//                ]
//            ],
            [
                'attribute' => 'record_id',
                'format' => 'raw',
                'value' => function ($model){
                    $url = '/records/view?id='.$model->record_id;
                    return Html::a($model->record_id, $url, ['target' => '_blank']);
                },
                'headerOptions' => [
                    'class' => 'col-lg-1'
                ]
            ],
            [
                'attribute' => 'insurer_id',
                'format' => 'raw',
                'value' => function ($model){
                    if ($model->insurer_id != null)
                    {
                        return Insurers::findOne($model->insurer_id)->name;
                    }
                    else
                    {
                        return '';
                    }
                },
            ],
            [
                'attribute' => 'type',
                'format' => 'raw',
                'value' => function ($model){
                    if ($model->type)
                    {
                        return 'Страховая компания';
                    }
                    else
                    {
                        return 'Наличные';
                    }
                }
            ],
            [
                'attribute' => 'user_id',
                'value' => function($model){
                    $user = User::findOne($model->user_id);
                    return $user->name;
                }
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
