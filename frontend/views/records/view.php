<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use frontend\models\Specialists;

/* @var $this yii\web\View */
/* @var $model frontend\models\Records */

$this->title = 'Приём к врачу';
$this->params['breadcrumbs'][] = ['label' => 'График', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="records-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        if ( !Yii::$app->user->isGuest ) {
            echo Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
            echo Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]);
        }
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'specialist.doctor.name',
                'format' => 'raw',               
            ],
            [
                'attribute' => 'start_time',
                'format' => ['datetime', 'dd-MM-Y H:i']                
            ],
            [
                'attribute' => 'name',
            ],
            [
                'attribute' => 'phone',
            ],
            [
                'attribute' => 'user_id',
//                'format'=>'raw',
//                'value' => function($model)
//                {
//                    return 'skljdf';
//                }
            ],
            [
                'attribute' => 'email',
            ],            
            [
                'attribute' => 'visited',
                'format'=>'raw',
                'value'=>$model->visited ? '<span class="label label-success">Да</span>' : '<span class="label label-danger">Нет</span>',
            ],
            [
                'attribute' => 'reserved',
                'format'=>'raw',
                'value'=>$model->reserved ? '<span class="label label-success">Да</span>' : '<span class="label label-danger">Нет</span>',
            ]
        ],
    ]) ?>

</div>
