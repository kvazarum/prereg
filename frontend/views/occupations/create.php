<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;


/* @var $this yii\web\View */
/* @var $model frontend\models\Occupations */

$this->title = 'Добавить специальность';
$this->params['breadcrumbs'][] = ['label' => 'Специальности', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

Modal::begin([
    'header' => '<b>' . 'Ошибка' . '</b>',
    'id' => 'modal',
    'size' => 'modal-sm',
    'footer' => Html::button('Закрыть', [
        'class' => 'btn btn-default',
        'data-dismiss' => 'modal'
        ])
    ]);
Modal::end();
?>
<div class="occupations-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]); 
    
$script = <<< JS
$("#occupations-name").change(function(){
    var name = $("#occupations-name").val();
    
    $.get("/occupations/is-double", {'name' : name}, function(data){
        if (data)
        {
            showAlert('Специальность "' + name + '" уже существует!');
        }
    });
});
JS;
$this->registerJs($script);     
?>
</div>
