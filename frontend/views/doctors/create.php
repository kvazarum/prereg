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
    ]);

$script = <<< JS
    $("#doctors-name").change(function(){
        var name = $("#doctors-name").val();

        $.get("/doctors/is-double", {'name' : name}, function(data){
            if (data)
            {
                showAlert('Запись с данными "' + name + '" уже добавлена в базу данных!');
            }
        });
    });
JS;
    $this->registerJs($script);
    ?>

</div>
