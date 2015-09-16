<?php

use yii\helpers\Html;
use frontend\models\Doctors;

/* @var $this yii\web\View */
/* @var $model frontend\models\Doctors */

$this->title = 'Изменить данные: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Врачи', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';

$model->start_time = Doctors::timeToString($model->start_time);
$model->end_time = Doctors::timeToString($model->end_time);

?>
<div class="doctors-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
