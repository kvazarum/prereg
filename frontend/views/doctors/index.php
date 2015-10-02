<?php

use yii\helpers\Html;
use yii\grid\GridView;
use frontend\models\Doctors;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\DoctorsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Врачи';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="doctors-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить врача', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function ($model){
                    $url = '/doctors/view?id='.$model->id;
                    $result = Html::a($model->name, $url, []);
                    return $result;
                }
            ],            
            'number',
            'description:ntext',
            'phone',
            [
                'attribute' => 'start_time',
                'format' => 'raw',
                'value' => function ($model){
                    if (is_numeric($model->start_time) && $model->start_time > 0)
                    {
                        $result = Doctors::timeToString($model->start_time);
                    }
                    else
                    {
                        $result = '<span class="text-danger">Не задано</span>';
                    }
                    return $result;
                }
            ],
            [
                'attribute' => 'end_time',
                'format' => 'raw',
                'value' => function ($model){
                    if (is_numeric($model->end_time) && $model->end_time > 0)
                    {
                        $result = Doctors::timeToString($model->end_time);
                    }
                    else
                    {
                        $result = '<span class="text-danger">Не задано</span>';
                    }
                    return $result;
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
