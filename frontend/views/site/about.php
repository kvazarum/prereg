<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'О нас';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    
    <?php
        echo html::beginTag('div', ['class' => 'col-lg-4']);
            echo html::beginTag('div', ['class' => 'panel panel-info ']);
            echo Html::beginTag('div', ['class' => 'panel-heading'] );
            echo Html::tag('h1', Html::encode($this->title), ['class' => 'panel-title'] );
            echo Html::endTag('div');

            echo Html::beginTag('div', ['class' => 'panel-body'] );
    ?>
    <p><b>Больница находится по адресу:</b><br />
        Тверская область, г.Тверь, Петербургское шоссе, 105</p>
    <p><b>Телефон для записи на прием за наличный расчёт:</b><br>
        47-51-58, 77-54-65
    </p>

    <p><b>Телефон для записи на прием по полисам ДМС:</b><br />
        77-53-77, 77-54-77</p>
    <?php
                echo Html::endTag('div');
            echo Html::endTag('div');
        echo Html::endTag('div');
            ?>
    
</div>
