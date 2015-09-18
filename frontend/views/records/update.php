<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Records */

$this->title = 'Изменить запись.';

$this->params['breadcrumbs'][] = ['label' => 'Записи', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="records-update">

    <h2><?php echo '<h2>'.Html::encode($this->title).'</h2>';
    
    echo '<div class="panel panel-info">';
    echo '<div class="list-group-item">';
        echo 'Врач: '.$model->specialist->doctor->name;
    echo '</div>';
    echo '<div class="list-group-item">';
        echo 'Время записи: '.$model->start_time;
    echo '</div>';
    echo '</div>';
    ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
