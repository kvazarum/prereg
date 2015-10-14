<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model \frontend\modules\insurer\models\Insurers */

$this->title = 'Добавить страховую компанию';
$this->params['breadcrumbs'][] = ['label' => 'Страховые компании', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="insurers-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
