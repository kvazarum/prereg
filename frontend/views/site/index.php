<?php
use frontend\models\Occupations;
use frontend\models\OccupationsSearch;
use yii\helpers\Url;
/* @var $this yii\web\View */

$this->title = 'Выбор специальности';
?>
<div class="site-index">

<!--    <div class="jumbotron">
        <h1>ГБУЗ ОКБ</h1>


    </div>-->

    <div class="body-content">

        <div class="jumbotron">
            <p class="lead"><h2>Выберите специальность:</h2></p>
            <?php
            
            $models = Occupations::find()->orderBy('name')->all();  //массив 

            for ($i = 0; $i <count($models); $i++)
            {
                $url = Url::to(['specialists/viewss', 'id' => $models[$i]->id]);
                $text = $models[$i]->name;
                $classes = 'btn btn-lg btn-info';
                echo '<p><a style="font-size: small; width: 250px;" class="'.$classes.'" href="'.$url.'">'.$text.'</a></p>';
            }
            ?> 
        </div>
    </div>
</div>
