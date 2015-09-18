<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Records */

$this->title = 'Запись к специалисту';
//$this->params['breadcrumbs'][] = ['label' => 'Записи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="records-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
