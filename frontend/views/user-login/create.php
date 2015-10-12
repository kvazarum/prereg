<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\UserLogin */

$this->title = 'Create User Login';
$this->params['breadcrumbs'][] = ['label' => 'User Logins', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-login-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
