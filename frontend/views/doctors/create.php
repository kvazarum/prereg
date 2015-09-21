<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;


/* @var $this yii\web\View */
/* @var $model frontend\models\Doctors */

$this->title = 'Добавить врача';
$this->params['breadcrumbs'][] = ['label' => 'Врачи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

Modal::begin([
    'header' => '<b>' . 'Ошибка' . '</b>',    
    'id' => 'modal',
    'size' => 'modal-sm',
//    'closeButton' => [
//        'label' => 'Закрыть',
//        'aria-hidden' => 'true',
//        'class' => 'btn close',
//    ],
    'footer' => Html::button('Закрыть', [
        'class' => 'btn btn-default',
        'data-dismiss' => 'modal'
        ])
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
                showAlert('Запись с данными "<b>' + name + '</b>" уже добавлена в базу данных!');
            }
        });
    });
JS;
    $this->registerJs($script);
    ?>

</div>
