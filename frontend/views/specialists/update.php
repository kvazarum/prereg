<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Specialists */

$this->title = 'Изменить данные: ' . $model->doctor->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Специалисты'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->doctor->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Изменить');
?>
<div class="specialists-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
