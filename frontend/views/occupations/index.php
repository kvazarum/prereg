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
            'name',
            'period',
            'created_at',
            'updated_at',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    
    Pjax::end();
        ?>

</div>
