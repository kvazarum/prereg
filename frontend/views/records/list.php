<?php

use yii\helpers\Html;
use yii\grid\GridView;
use frontend\models\Doctors;
use frontend\models\Specialists;
use frontend\models\Occupations;
use frontend\models\Records;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\RecordsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$dt = date('d-m-Y' ,$date);
$spec = Specialists::findOne($id);
$doctor = Doctors::findOne($spec->doctor_id);
$occupation = Occupations::findOne($spec->occupation_id);

$this->title = 'Выбор времени приёма';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Выбор даты'), 'url' => ['specialists/viewss', 'id' => $spec->occupation_id]];
$this->params['breadcrumbs'][] = $this->title;

    echo '<div class="jumbotron">';
        echo '<div class="page-header">';
            echo '<h3>'.Html::encode($this->title).'</h3>';
            echo '<h2>'.$doctor->name.'</h2>';
        echo '</div>';

?>
<div class="records-list">
    
    <?php 
        $records = Records::getRecords($date, $id);

        echo '<div class="jumbotron">';
        for ($i = 0; $i <count($records); $i++)
        {
//            $url = Url::to(['records/register', 'id' => $records[$i]->id]);
            $url = Url::to(['requests/create', 'id' => $records[$i]->id]);
            $text = strtotime($records[$i]->start_time);
            $text = date("d-m-Yг. H:i", $text);
            $classes = 'btn btn-lg btn-info';
            echo '<p><a style="width: 400px;" class="'.$classes.'" href="'.$url.'">'.$text.'</a></p>';
        }    
        echo '</div>';
    ?>
</div>
</div>
