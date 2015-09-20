<?php

use yii\helpers\Html;
//use yii\widgets\DetailView;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Requests */

$this->title = 'Запись к врачу';
if (!Yii::$app->user->isGuest)
{
    $this->params['breadcrumbs'][] = ['label' => 'Графики', 'url' => ['/records/index']];
}
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

    <?php
    
        echo DetailView::widget([
            'model'=>$model,
            'condensed'=>true,
            'enableEditMode' => false,
            'hover'=>true,
            'mode'=>DetailView::MODE_VIEW,
            'panel'=>[
                'heading'=>'Запись # ' . $model->id,
//                'type'=>DetailView::PANEL_INFO,
            ],
            'attributes'=>[
                [
                    'attribute' => 'specialist_id',
                    'label' => 'Лечащий врач',
                    'displayOnly' => true,
                    'value'=> $model->specialist->doctor->name
                ],
                [
                    'attribute' => 'start_time',
                    'label' => 'Время приёма',
//                    'format' => ['datetime', 'dd-M-Y H:i'],
                    'language' => 'ru',
                    'displayOnly' => true,
                    'value'=> $model->start_time
                ],
//                [
//                    'attribute' => 'visited',
//                    'label' => 'Посещение',
//                    'displayOnly' => true,
////                    'type'=>DetailView::INPUT_SWITCH,
//                    'format'=>'raw',
//                    'value'=>$model->visited ? '<span class="label label-success">Да</span>' : '<span class="label label-danger">Нет</span>',
//                    'widgetOptions' => [
//                        'pluginOptions' => [
//                            TRUE => 'Yes',
//                            0 => 'No',
//                        ]
//                    ],
//                ],                
//                'record.specialist.doctor.name',
//                'record.start_time',
                'name',
                'phone',
                'email:email',
//                'created_at'
            ]
        ]);    
    
//        echo DetailView::widget([
//            'model' => $model,
//            'attributes' => [
//                'record.specialist.doctor.name',
//                'record.start_time',
//                'name',
//                'phone',
//                'email:email',
//                'created_at',
//            ],
//        ]) 
        ?>

</div>
