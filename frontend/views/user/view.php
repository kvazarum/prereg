<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use frontend\models\User;
use frontend\models\AuthAssignment;

/* @var $this yii\web\View */
/* @var $model frontend\models\User */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$timezone = "Europe/Moscow";
date_default_timezone_set($timezone);
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены что хотите удалить этого пользователя?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Изменить пароль', ['password-change', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?php
        $status = User::getStatusName($model->status);
        echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'name',
            'email:email',
//            'role',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => $model->status == 10 ? '<span class="label label-success">'.$status.'</span>' : '<span class="label label-danger">'.$status.'</span>',
            ],
            [
                'attribute' => 'created_at',
                'value' => date('d-M-Y H:i:s', $model->created_at),
            ],
            [
                'attribute' => 'updated_at',
                'value' => date('d-M-Y H:i:s', $model->updated_at),
            ],
        ],
    ]);
        
    echo Html::tag('p', 'Назначенные роли');
    
    $auth = AuthAssignment::findAll(['user_id' => $model->id]);
    $assignment = [];
    foreach ($auth as $item)
    {
        $assignment[] = $item->item_name;
    }
    echo Html::listBox('roles', null,   $assignment);
    ?>

</div>
