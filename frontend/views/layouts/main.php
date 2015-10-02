<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'ГБУЗ ОКБ',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Выбор специальности', 'url' => ['/site/index']],
        ['label' => 'О нас', 'url' => ['/site/about']],
//        ['label' => 'Contact', 'url' => ['/site/contact']],
    ];
    if (Yii::$app->user->isGuest)
    {
        $menuItems[] = ['label' => 'Вход', 'url' => ['/site/login']];
//        $menuItems[] = ['label' => 'Регистрация', 'url' => ['/site/signup']];
    } else {
        $menuItems[] = ['label' => 'Отчёты', 
            "items" => [
                ['label' => 'Отчёт по специалистам за период', 'url' => ['/records/specialist-report']],
                ['label' => 'Перечень записавшихся пациентов', 'url' => ['/records/day-report']],
            ]];        
        $menuItems[] = ['label' => 'Справочники', 
            "items" => [
//                ['label' => 'Кабинеты', 'url' => ['/cabinets']],
                ['label' => 'Специальности', 'url' => ['/occupations']],
                ['label' => 'Врачи', 'url' => ['/doctors']],
                ['label' => 'Специалисты', 'url' => ['/specialists']],
                ['label' => 'Страховщики', 'url' => ['/insurers']],
                ['label' => 'Пользователи', 'url' => ['/user']],
            ]];
        $menuItems[] = ['label' => 'Администрирование', 
            "items" => [
                ['label' => 'Генерация графиков', 'url' => ['/records/admin-occupations']],
                ['label' => 'Графики врачей', 'url' => ['/records/index']],
                ['label' => 'Список пользователей', 'url' => ['/user/index']]
            ]];
        $menuItems[] = [
            'label' => 'Выход (' . Yii::$app->user->identity->username . ')',
            'url' => ['/site/logout'],
            'linkOptions' => ['data-method' => 'post']
        ];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; ГБУЗ ОКБ, г. Тверь <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>