<?php

use yii\helpers\Html;
use yii\grid\GridView;
use frontend\models\Doctors;
use frontend\models\Specialists;
use frontend\models\Occupations;
use frontend\models\Records;
use yii\helpers\Url;
//use Yii;

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

    echo Html::beginTag('div', ['class' => "jumbotron"]);
        echo Html::beginTag('div', ['class' => "page-header"]);
            echo Html::tag('h3', Html::encode($this->title));
            echo Html::tag('h2', $doctor->name);
            echo Html::tag('h4',$doctor->description);

            $month = Records::$monthsFull[(int)date('m', $date)];
            echo '<p><strong>'.date("d", $date).' '.$month.' '.date('Y', $date).'</strong></p>';
        echo Html::endTag('div');

?>
<div class="records-list">
    
    <?php 
        $records = Records::getRecords($date, $id);

        echo '<div class="jumbotron">';
        for ($i = 0; $i <count($records); $i++)
        {
            if (Yii::$app->user->isGuest)
            {
                $url = Url::to(['requests/create', 'id' => $records[$i]->id]);
            }
            else
            {
                $url = Url::to(['records/update', 'id' => $records[$i]->id]);
            }
            $text = strtotime($records[$i]->start_time);
            $text = date("H:i", $text);
            $classes = 'btn btn-lg btn-info';
            echo Html::beginTag('p');
                echo Html::a($text, $url, ['class' => $classes]);
            echo Html::endTag('p');
        }    
        echo Html::endTag('div');
    ?>
</div>
</div>
