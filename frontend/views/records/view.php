<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use frontend\models\Specialists;

/* @var $this yii\web\View */
/* @var $model frontend\models\Records */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Записи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="records-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'specialist.doctor.name',
            'start_time',
            'name',
            'phone',
            
            'reserved:boolean',
            'visited:boolean'
//            'created_at',
//            'updated_at',
        ],
    ]) ?>

</div>
