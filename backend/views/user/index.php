<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\User;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить пользователя', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'id',
                'headerOptions' =>[
                    'class' => 'col-md-1',
                ],
            ],
            [
                'attribute' => 'username',
                'format' => 'raw',
                'value' => function($model){
                    return Html::a($model->username, 'user/view?id='.$model->id,['target' => '_blank'])  ;
                }
            ],
            'name',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($model){
                    if ($model->status == 10)
                    {
                        $class = 'text-success';
                    }
                    else
                    {
                        $class = 'text-danger';
                    }
                    return '<span class="'.$class.'">'.User::getStatusName($model->status).'</span>';
                }
            ],                                          
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
