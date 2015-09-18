<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Requests */

$this->title = 'Изменить заявку';
$this->params['breadcrumbs'][] = ['label' => 'Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->start_time, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="requests-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php
        echo 'Врач: ' . $model->specialist->doctor->name;
        echo ', время приёма: ' . $model->start_time;
        echo '<p />';
    ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
