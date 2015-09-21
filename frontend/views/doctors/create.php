<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;


/* @var $this yii\web\View */
/* @var $model frontend\models\Doctors */

$this->title = 'Добавить врача';
$this->params['breadcrumbs'][] = ['label' => 'Врачи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

Modal::begin([
    'id' => 'modal',
    'size' => 'modal-sm'
    ]);
Modal::end();
?>
<div class="doctors-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
