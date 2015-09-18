<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Requests */

$this->title = 'Запись к врачу';

//$this->params['breadcrumbs'][] = ['label' => 'Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="requests-create">

    <h1><?= Html::encode($this->title).' '.$model->specialist->doctor->name ?></h1>
    <?php echo '<h3>Время приёма: '.$model->start_time.'</h3>'; ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
