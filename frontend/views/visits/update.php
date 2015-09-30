<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Visits */

$this->title = 'Изменить';
$this->params['breadcrumbs'][] = ['label' => 'Визиты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Визит', 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="visits-update">
<table class="table table-striped table-bordered detail-view">
    <h1><?= Html::encode($this->title) ?></h1>
        <tr>
            <td>
                Пациент
            </td>
            <td>
            <?= $model->record->name ?>
            </td>
        </tr>
        <tr>
            <td>
                Время посещения
            </td>
            <td>
                <?= $model->record->start_time ?>
            </td>
        </tr>
        <tr>
            <td>
                Врач
            </td>
            <td>
            <?= $model->record->specialist->doctor->name ?>
            </td>
        </tr>
        <tr>
            <td>
                Специальность
            </td>
            <td>
            <?= $model->record->specialist->occupation->name ?>
            </td>
        </tr>
    </table>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
