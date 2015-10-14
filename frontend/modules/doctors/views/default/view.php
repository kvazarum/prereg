<?php

use yii\helpers\Html;
//use yii\widgets\DetailView;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model \frontend\modules\doctors\models\Doctors */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Врачи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="doctors-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Удалить запись?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'condensed'=>true,
        'hover'=>true,
        'enableEditMode' => false,
        'mode'=>DetailView::MODE_VIEW,
        'panel'=>[
            'heading'=>'Врач # ' . $model->name,
//                'type'=>DetailView::PANEL_INFO,
        ],        
        'attributes' => [
            [
                'attribute' => 'id',
//                'label' => 'Лечащий врач',
                'displayOnly' => true,
            ],
            'name',
            'number',
            'description:ntext',
            'phone',
            'price_initial',
            'price_secondary',
            [
                'attribute' => 'start_time',
                'displayOnly' => true,
            ],
            [
                'attribute' => 'end_time',
                'displayOnly' => true,
            ],
            [
                'attribute' => 'created_at',
                'value' => date('d-m-Y H:i:s', $model->created_at),
            ],
            [
                'attribute' => 'updated_at',
                'value' => date('d-m-Y H:i:s', $model->updated_at),
            ],

        ],
    ]) ?>

</div>
