<?php

use yii\helpers\Html;
use yii\grid\GridView;
use frontend\models\Doctors;
use frontend\models\Specialists;
use frontend\modules\occupations\models\Occupations;
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

    echo Html::beginTag('div');
        echo Html::beginTag('div', ['class' => "page-header"]);
            echo Html::tag('h3', Html::encode($this->title));
            echo Html::tag('h2', $doctor->name);
            echo Html::tag('h4',$doctor->description);

            $month = Records::$monthsFull[(int)date('m', $date)];
            echo '<h4><strong>'.date("d", $date).' '.$month.' '.date('Y', $date).'</strong></h4>';
        echo Html::endTag('div');

?>
<div class="records-list">
    
    <?php 
        $records = Records::getRecords($date, $id);
//        $count = 0;
        $hour = 0;
        echo Html::beginTag('div', ['class' => '']);
        echo Html::beginTag('p');
        for ($i = 0; $i <count($records); $i++)
        {
            $data = strtotime($records[$i]->start_time);
            if ($i == 0)
            {
                $hour = date("H", $data);
            }
            if (Yii::$app->user->isGuest)
            {
                $url = Url::to(['requests/create', 'id' => $records[$i]->id]);
            }
            else
            {
                $url = Url::to(['records/update', 'id' => $records[$i]->id]);
            }
            
            $text = date("H:i", $data);
            $classes = 'btn btn-lg btn-info list-group-item modal-content';
            
            if ($hour != date("H", $data))
            {
//                $count = 0;
                $hour = date("H", $data);
                echo Html::endTag('p');
                echo Html::beginTag('p');
            }
//            $count++;
            echo Html::a($text, $url, ['class' => $classes]);
        }
        echo Html::endTag('p');
        echo Html::endTag('div');
    ?>
</div>
</div>
