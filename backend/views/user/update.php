<?php

use yii\helpers\Html;
use backend\models\AuthAssignment;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $roles backend\models\AuthAssignment */

$this->title = 'Изменить пользователя: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]); 
    
    ?>
<!--    <table>
        <tr>
            <td style="margin: 5px;">
    <?php
        echo Html::tag('p', 'Назначенные роли');
        $assignment = [];
        foreach ($roles as $item)
        {
            $assignment[] = $item->item_name;
        }
        echo Html::listBox('roles', null,   $assignment);
    
    ?>
            </td>
            <td style="margin: 5px;">
    <?php
        echo Html::tag('p', 'Доступные роли');

        $auth = AuthAssignment::find()->all();
        $assignment = [];
        foreach ($auth as $item)
        {
            $assignment[] = $item->item_name;
        }
        echo Html::listBox('roles', null,   $assignment);
    
    ?>
            </td>            
        </tr>
    </table>-->
</div>
