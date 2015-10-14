<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\modules\occupations\models\Occupations;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model \frontend\modules\occupations\models\Occupations */
/* @var $form ActiveForm */
?>
<div class="admin-occupations">

    <?php $form = ActiveForm::begin(); ?>

        <div class="jumbotron">
            <p class="lead">Выберите специальность.</p>
            <?php
            
            $models = Occupations::find()->orderBy('name')->all();  //массив 

            for ($i = 0; $i <count($models); $i++)
            {
                $url = Url::to(['records/admin-specialists', 'id' => $models[$i]->id]);
                $text = $models[$i]->name;
                $classes = 'btn btn-lg btn-info';
                echo '<p><a style="font-size: small; width: 250px;" class="'.$classes.'" href="'.$url.'">'.$text.'</a></p>';
            }
            ?> 
        </div>

</div><!-- admin-occupations -->
