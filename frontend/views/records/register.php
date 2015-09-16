<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\models\Doctors;
use frontend\models\Records;
use frontend\models\Specialists;

/* @var $this yii\web\View */
/* @var $model frontend\models\records */
/* @var $form ActiveForm */

$record = Records::findOne($id);
        
$spec = Specialists::findOne($record->specialist_id);
$doctor = Doctors::findOne($spec->doctor_id);
$date = strtotime($record->start_time);
$specialist_id = $record->specialist_id;

$this->title = 'Регистрация заявки';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Выбор времени приёма'), 'url' => ['list', 'date' => $date, 'id' => $specialist_id]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="register">
    <div class="jumbotron">

    <?php  

        echo '<h2>'.Html::label($doctor->name).'</h2>';
        echo '<h3>'.Html::label($doctor->description).'</h3>';
        echo '</div>';
        $form = ActiveForm::begin(); 

        echo $form->field($model, 'name')->input('text', ['value'=> '']);

        echo $form->field($model, 'phone')->input('text', ['value'=> '']);    
        echo '<p />';        
        echo '<h3>'.Html::label('Время приёма:', 'lblTime', ['class' => "control-label"]).'   ';
        echo Html::label(date('d-m-Y H:i', $date), 'time', ['class' => "control-label"]).'</h3>';
        echo '<p />';        
        ?>
        
        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>
    

</div><!-- register -->
