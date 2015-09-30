<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\User;
use frontend\models\Insurers;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\VisitsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Визиты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="visits-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
        echo Html::beginTag('div', ['class' => 'panel panel-info']);
            $content = Html::tag('h3', '<i class="glyphicon glyphicon-calendar"></i> Визиты', ['class' => "panel-title"]);
            echo Html::tag('div', $content, ['class' => "panel-heading"]);
        echo Html::endTag('div');
    ?>
<!--    <p>
        <?= Html::a('Create Visits', ['create'], ['class' => 'btn btn-success']) ?>
    </p>-->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
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
                'attribute' => 'user_id',
                'value' => function($model){
                    $user = User::findOne($model->user_id);
                    return $user->name;
                }
            ],
            [
                'attribute' => 'record_id',
                'label' => 'Время посещения',
                'value' => 'record.start_time'
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
