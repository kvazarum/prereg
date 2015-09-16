<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Requests */

$this->title = 'Запись к врачу';
//$this->params['breadcrumbs'][] = ['label' => 'Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="requests-view">

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
            'record.specialist.doctor.name',
            'record.start_time',
            'name',
            'phone',
            'email:email',
            'created_at',
        ],
    ]) ?>

</div>
