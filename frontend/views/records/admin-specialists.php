<?php

use yii\helpers\Html;
use dosamigos\datepicker\DatePicker;
use yii\helpers\ArrayHelper;
use frontend\models\Occupations;
use kartik\select2\Select2;
use yii\bootstrap\Modal;
use yii\widgets\MaskedInput;

    $specialists = Specialists::findAll(['occupation_id' => $occupation_id]);
    $occupation = Occupations::findOne($occupation_id);  
    
    echo '<div class="page-header">';
        $content = $occupation->name.', <span style="padding-left: 5px;" id="name"></span>'.' создание графика приёма';
        echo Html::tag('h3', $content);
    echo '</div>';
    
    $this->title = "Создание графика";
    
    Modal::begin([
//        'header' => '<h4>Ошибка</h4>',
        'id' => 'modal',
        'size' => 'modal-sm',
        'footer' => Html::button('Закрыть', [
            'class' => 'btn btn-default',
            'data-dismiss' => 'modal'
        ])
    ]);
    
    Modal::end();
    ?>
    <div class="col-lg-3">
    <div class="panel panel-info">
        <div class="panel-heading">
            <?= Html::tag('h4', 'Заполните начальные данные', ['class' => "panel-title"]) ?>
        </div><!-- panel-heading -->    
        
<!-- Панель настройки графика -->
        
        <div class="panel-body">
        <label for="specialist">
            Врач
        </label>            
            <?php
            $list = ArrayHelper::map(Specialists::find()->where(['occupation_id' => $occupation_id])->all(), 'id' , 'doctor.name');
            echo Select2::widget([
                'class' => "form-control",
                'id' => "specialist",
                'name' => "specialist",
                'value' => '',
                'data' => $list,
                'options' => ['multiple' => false, 'placeholder' => 'Выберите врача ...']
            ]);            

            echo '<p /><label for="date_from">
                Дата приёма с:
            </label>';
            echo DatePicker::widget([
            'id' => 'date_from',
            'name' => 'date_from',
            'language' => 'ru',
            'value' => date('Y-m-d', time()),
            'template' => '{addon}{input}',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ]);
            echo '<p /><label for="date_to">
                Дата приёма по:
            </label>';
            echo DatePicker::widget([
            'id' => 'date_to',
            'name' => 'date_to',
            'language' => 'ru',
            'value' => date('Y-m-d', time()),
            'template' => '{addon}{input}',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ]);
            
?>
            <p /><p />
<!--            <a class="btn btn-default" href="">Показать</a>-->
            <div class="form-group form-inline">
                
                <div class="checkbox">
                    <input id="one_day"  type="checkbox" class=”form-control”/>
                    <span>одним днём</span>
                </div>
            </div>
            <label for="start_time">
                Начало рабочего дня, (чч:мм)
            </label> 
            <!--<input id="start_time" onchange="changeTimeTable()" placeholder="Время начала дня" class="form-control">-->
            <?php
                echo MaskedInput::widget([
                    'name' => "start_time",
                    'mask' => '99:99',
                    'id' => "start_time",
                    ]);
            ?>
            <label for="end_time">
                Конец рабочего дня, (чч:мм)
            </label>             
<!--            <input id="end_time" placeholder="Время окончания дня" class="form-control">-->
            <?php
                echo MaskedInput::widget([
                    'type' => 'text',
                    'name' => "end_time",
                    'mask' => '99:99',
                    'id' => "end_time",
                    ]);
            ?>
            <label for="period">
                Время одного приёма, (мин.)
            </label>                         
            <!--<input id="period" placeholder="Длительность приёма" class="form-control">-->
            <?php
                echo MaskedInput::widget([
                    'type' => 'text',
                    'name' => "period",
                    'mask' => '99',
                    'id' => "period",
                    ]);
            ?>
        </div><!-- panel-body -->
        <div class="panel-footer">
            <button id='generate' class="btn btn-info" onclick="" disabled="true">Создать график</button>            
        </div>
    </div> <!-- panel-info -->
     </div>
    
    <!-- График приёмов -->
    <div class="col-md-9">
    <div class="panel panel-info" style="">
        <div class="panel-heading">
            <h4 class="panel-title">
                График
            </h4>
        </div><!-- panel-heading -->
        <div id="main" class="panel-body">
            <div class="container" id="records" style="width: 100%;">
<!--                <ul class="list-group" >
                </ul>-->
            </div>
        </div><!-- panel-body -->
        <div class="panel-footer">
            <button id="create" class="btn btn-info" disabled="true">Сохранить</button>
        </div>
    </div>
    </div>
<?php