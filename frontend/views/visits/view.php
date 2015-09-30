<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use frontend\models\Records;
use frontend\models\Insurers;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model frontend\models\Visits */

$this->title = 'Визит';
$this->params['breadcrumbs'][] = ['label' => 'Визиты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="visits-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Удалить запись о визите?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'record.name',
            'record.start_time',
            [
               'attribute' => 'insurer_id',
                'value' => $model->type ? Insurers::findOne($model->insurer_id)->name : 'Не задано'
            ],
            [
                'attribute' => 'type',
                'value' => $model->type ? 'Страховая компания' : 'Наличные'
            ],
            [
                'attribute' => 'user_id',
                'value' => User::findOne($model->user_id)->name
            ],
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
