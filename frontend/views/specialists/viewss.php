<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\models\Specialists;
use yii\helpers\Url;
use frontend\models\Doctors;
use frontend\models\Occupations;
use frontend\models\Records;

/* @var $this yii\web\View */
/* @var $model frontend\models\Specialists */
/* @var $form ActiveForm */
?>
<div class="viewSpec">

        <div class="jumbotron">
            <p class="lead">
            <?php
                $occupation = Occupations::findOne(['id' => $id]);
                $this->title = 'Выбор даты приёма';
                $this->params['breadcrumbs'][] = $this->title;
            ?>
            </p>
            <?php            
            $models = Specialists::findAll(['occupation_id' => $id]);
            //setlocale(LC_ALL, 'ru_RU.UTF-8');
            //setlocale(LC_TIME, 'ru_RU.CP1251', 'rus_RUS.CP1251', 'Russian_Russia.1251', 'ru_RU.UTF-8');          
            
            if (count($models) > 0)
            {
                echo '<div class="page-header">';
                    echo '<h2>'.$occupation->name.': Выберите дату приёма</h2>';
                echo '</div><p />';
                $tm = time();
                $dt = date("w", $tm);
                $numberOfDay = date("w", $tm);
                //echo '<table><tr style="border-bottom: groove; border-top: groove; font-weight: bold;">';
                echo '<table><tr style="border-bottom: groove; font-weight: bold;">';
                    echo '<td style="width: 300px; text-align: justify;">Врач</td>';
                    for ($i = 0; $i < 7; $i++)
                    {
                        if($numberOfDay == 0 || $numberOfDay == 6)
                        {
                            $color = 'red';
                        }else{
                            $color = 'black';
                        }
                        $month = date("n", $tm);
                        $month = Records::$months[$month - 1];
                        echo '<td style="color: '.$color.';">'.Records::$days[$dt].'<br />'.date("j ", $tm).$month.'</td>';
                        $tm += 60*60*24;
                        $numberOfDay = date("w", $tm);
                        $dt = date("w", $tm);
                    }
                echo '</tr>';            
                 
                for ($i = 0; $i <count($models); $i++)
                {
                    $dt = time();// сегодняшняя дата
                    
                    echo '<tr>';
                    $url = '';
                    $doctor = Doctors::findOne(['id'=> $models[$i]->doctor_id]);

                    $text = '<strong>'.$doctor->name.'</strong><br />';
                    $text .= $doctor->description;
                    echo '<td style="border-bottom: 1px solid red; text-align: justify;">'.$text.'</td>';
                    for ($j = 0; $j < 7; $j++)
                    {
                        $count = $models[$i]->getCountRecords($dt);
                        $href= Url::to(['records/list', 'date' => $dt, 'id' => $models[$i]->id]);
//                        $href= 'records/list?id='.$models[$i]->id;
                        if($count != 0)
                        {
                            $type = '';
                            $class = 'btn-primary btn-lg';
                            $text = '9:00-14:00<br/ >приёмов - '.$count;
                        }
                        else
                        {
                            $type = 'disabled';
                            //$class = 'btn-success';
                            //$class = 'btn-primary';
                            //$class = 'btn-warning';
                            //$class = 'btn-danger';
                            //$class = '';
                            $class = 'btn-info';
                            //$class = 'btn btn-default btn-lg';
                            $text = 'нет приёма';
                            $href = '';
                        }
                        
                        echo '<td>';
                            echo '<a style="padding: 0px; margin: 2px; font-size: small; width: 90px; height: 75px;" class="btn '.$class.'" '.$type.' href="'.$href.'">';
                                echo $text;
                            echo '</a>';
                        echo '</td>';
                        $dt += 60*60*24;
                    }
                    echo '</tr>';
                }
                echo '</table>';
            }else{
                echo 'По заданным условиям специалистов нет.';
            }
            ?> 
        </div>

</div><!-- viewSpec -->
