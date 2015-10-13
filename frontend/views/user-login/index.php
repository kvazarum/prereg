<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\User;
use frontend\models\UserLogin;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\UserLoginSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'User Logs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-login-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
//            'id',
            [
                'attribute' => 'user_id',
                'format' => 'raw',
                'value' => function ($model){
                    $user = User::findOne($model->user_id);
                    return Html::a($user->name, '/user/view?id='.$model->user_id, ['target' => '_blank']);
                }
            ],
            [
                'attribute' => 'action',
                'value' => function ($model){
                    return UserLogin::getActionName($model->action);
                }               
            ],
            [
                'attribute' => 'login_at',
                'value' => function ($model){
                    return date('d-m-Y H:i:s', $model->login_at);
                }
            ],
            'data:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
