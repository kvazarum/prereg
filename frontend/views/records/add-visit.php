<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\models\Records;
use yii\helpers\ArrayHelper;
use frontend\models\Insurers;
use kartik\select2\Select2;


/* @var $this yii\web\View */
/* @var $model frontend\models\Visits */

$this->title = 'Записать визит';
$this->params['breadcrumbs'][] = ['label' => 'Визиты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="records-add-visit-form">

    <?php $form = ActiveForm::begin(); ?>
    <h1><?= Html::encode($this->title) ?></h1>
<table class="table table-striped table-bordered detail-view">
        <tr>
            <th width="200">
                ID
            </th>
            <td>
            <?= $model->id ?>
            </td>
        </tr>
        <tr>
            <th>
                Пациент
            </th>
            <td>
            <?= $model->name ?>
            </td>
        </tr>
        <tr>
            <th>
                Время посещения
            </th>
            <td>
                <?= $model->start_time ?>
            </td>
        </tr>
        <tr>
            <th>
                Врач
            </th>
            <td>
            <?= $model->specialist->doctor->name ?>
            </td>
        </tr>
        <tr>
            <th>
                Специальность
            </th>
            <td>
            <?= $model->specialist->occupation->name ?>
            </td>
        </tr>
            <th>
                Тип визита
            </th>
            <td>
            <?= $form->field($model, 'visit_type')->radioList([
            Records::CASH => 'Наличные',
            Records::INSURER => 'Страховая компания',
        ])->label(FALSE) ?>
            </td>
        </tr>
        <tr>
            <th>
                Страховая компания
            </th>
            <td>
            <?php
            //$model->specialist->occupation->name
            $list = ArrayHelper::map(Insurers::find()->orderBy('name')->all(), 'id' , 'name');

            echo $form->field($model, 'insurer_id')->widget(Select2::classname(), [
                'data' => $list,
                'language' => 'ru',
                'options' => ['placeholder' => 'Выберите страховщика ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label(FALSE)
            ?>
            </td>
        </tr>
    </table>

    <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>

 
    <?php
        $list = ArrayHelper::map(Insurers::find()->orderBy('name')->all(), 'id' , 'name');

        echo $form->field($model, 'insurer_id')->widget(Select2::classname(), [
        'data' => $list,
        'language' => 'ru',
        'options' => ['placeholder' => 'Выберите страховщика ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>

</div>
