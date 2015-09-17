<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\RequestsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заявки пациентов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="requests-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<!--    <p>
        <?= Html::a('Добавить заявку', ['create'], ['class' => 'btn btn-success']); 
            Pjax::begin();
        ?>
    </p>-->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
//        'showFooter' => true,
        'layout' => '{pager}{summary}{items}',
        'rowOptions' => function($model){
            $class = '';
            if ($model->visited)
            {
                $class = 'success';
            }
            return ['class' => $class];
        },        
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
//            'record_id',
            'record.specialist.doctor.name',
            'record.start_time',
            'name',
            'phone',
            'email:email',
            'visited',
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'Действия', 
                'headerOptions' => ['width' => '70'],
                'template' => '{view} {update} {delete}{link}',
            ],            
//            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); 
        Pjax::end();
    ?>

</div>
