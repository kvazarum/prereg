<?php

use yii\helpers\Html;
use yii\grid\GridView;
use frontend\modules\admin\models\User;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <?php
        echo Html::beginTag('div', ['class' => 'panel panel-info']);
            $content = Html::tag('h3', '<i class="glyphicon glyphicon-calendar"></i>Пользователи', ['class' => "panel-title"]);
            echo Html::tag('div', $content, ['class' => "panel-heading"]);
        echo Html::endTag('div');
    ?>
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
                    return Html::a($model->username, '/admin/user/view?id='.$model->id,['target' => '_blank'])  ;
                }
            ],
            'name',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($model){
                    if ($model->status == 10)
                    {
                        $class = 'label-success';
                    }
                    else
                    {
                        $class = 'label-danger';
                    }
                    return '<span class="label '.$class.'">'.User::getStatusName($model->status).'</span>';
                }
            ],
            [
                'label' => 'Роли',
                'value' => function ($model){
                    return implode(',', $model->roles);
                }
                ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
