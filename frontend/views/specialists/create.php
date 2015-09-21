<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model frontend\models\Specialists */

$this->title = 'Добавить специалиста';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Специалисты'), 'url' => ['index']];
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
<div class="specialists-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
        echo $this->render('_form', [
        'model' => $model,
        ]);
                
$script = <<< JS
$("#specialists-occupation_id").change(function(){
    var doctor_id = $("#specialists-doctor_id").val();
    var occupation_id = $("#specialists-occupation_id").val();
        $.get("/specialists/is-double", {'doctor_id' : doctor_id, 'occupation_id' : occupation_id}, function(data){
            if (data)
            {
                showAlert('Запись с заданными параметрами уже существует!');
            }
        });

});
JS;
$this->registerJs($script); 
    ?>

</div>
