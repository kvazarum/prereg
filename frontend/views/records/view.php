<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use frontend\models\User;
use frontend\models\Visits;

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
                    'confirm' => 'Удалить запись?',
                    'method' => 'post',
                ],
            ]);
            if (!$model->visited)
            {
                $id = $model->id;
                echo Html::a('Посещение', ['visits/create', 'record_id' => $id], ['class' => 'btn btn-success']);
            }
        }
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'specialist.doctor.name',
                'label' => 'Врач',
                'format' => 'raw',
                'value' => Html::a($model->specialist->doctor->name,
                        ['/specialists/view', 'id' => $model->specialist_id], ['title' => $model->specialist->doctor->description])
            ],
            [
                'attribute' => 'specialist.occupation.name',
                'label' => 'Специальность'
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
                'attribute' => 'email',
            ],
            [
                'attribute' => 'reserved',
                'format'=>'raw',
                'value'=>$model->reserved ? '<span class="label label-success">Да</span>' : '<span class="label label-danger">Нет</span>',
            ],
            [
                'attribute' => 'visited',
                'format'=>'raw',
                'value'=>$model->visited ? '<span class="label label-success">Да</span>' : '<span class="label label-danger">Нет</span>',
            ],
            [
                'attribute' => 'user_id',
                'format'=>'html',
                'value' => $model->user_id ? User::findOne($model->user_id)->name: 'Интернет-пользователь'
            ],
            'created_at',
            'updated_at'
        ],
    ]) ?>

</div>
