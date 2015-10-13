<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\OccupationsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Специальности';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="occupations-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить специальность', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php 
    Pjax::begin();

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function ($model){
                    $url = '/occupations/view?id='.$model->id;
                    $result = Html::a($model->name, $url, ['target' => '_blank']);
                    return $result;
                },
                'headerOptions' => [
                    'class' => 'col-xs-4',
                ]                
            ],
            [
                'attribute' => 'period',
                'headerOptions' => [
                    'class' => 'col-xs-1',
                ]
            ],            
            [
                'attribute' => 'created_at',
                'value' => function ($model){
                    return date('d-m-Y H:i:s', $model->created_at);
                }
            ],
            [
                'attribute' => 'updated_at',
                'value' => function ($model){
                    return date('d-m-Y H:i:s', $model->updated_at);
                }
            ],                    
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    
    Pjax::end();
        ?>

</div>
