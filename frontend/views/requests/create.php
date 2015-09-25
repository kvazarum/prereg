<?php

use yii\helpers\Html;
use frontend\models\Records;

/* @var $this yii\web\View */
/* @var $model frontend\models\Requests */

$this->title = 'Запись к врачу';

//$this->params['breadcrumbs'][] = ['label' => 'Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="requests-create">

    <h1><?= Html::encode($this->title).' '.$model->specialist->doctor->name ?></h1>
    <?php
        $str_time = $model->start_time;
        $str_time = explode(' ', $str_time);
        $date = $str_time[0];
        $str_time = explode(':', $str_time[1]);
        $hours = $str_time[0];
        $minutes = $str_time[1];
        $date = explode('-', $date);
        $day = $date[2];    
        $month = Records::$monthsFull[(int)$date[1]];
        $year = $date[0];
        $str_time = $day.' '.$month.' '.$year.'г. '.$hours.'ч.'.' '.$minutes.' мин.';
        
        echo '<h3>Время приёма: '.$str_time.'</h3>'; 
    ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
