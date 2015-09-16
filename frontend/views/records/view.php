<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use frontend\models\Specialists;

/* @var $this yii\web\View */
/* @var $model frontend\models\Records */

$this->title = 'Приём к врачу';
$this->params['breadcrumbs'][] = ['label' => 'График', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="records-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'specialist.doctor.name',
                'format' => 'raw',               
            ],
            [
                'attribute' => 'start_time',
                'format' => ['datetime', 'dd-MM-Y H:i']                
            ],
            
            'reserved:boolean',
            'visited:boolean'
        ],
    ]) ?>

</div>
