<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\models\Category;
use app\models\Post;

use yii\helpers\StringHelper;
use app\models\Comments;
use app\models\Menu;
use app\components\AlertWidget;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
<?= Html::csrfMetaTags() ?>
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
        'brandLabel' => 'My Company',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
        
    ]);
        $items=[];
         $items[]=['label' => 'Личный кабинет', 'url' => ['user/view', 'id'=>Yii::$app->user->identity->id], 'visible' => !Yii::$app->user->isGuest];

         foreach (Menu::find()->where(['type'=>1])->all() as $menu) {
           
                $items[]= ['label' => $menu->menu_item, 'url' => [$menu->menu_url], 'active'=> $menu->menu_url == Yii::$app->controller->getRoute().'/'.Yii::$app->request->get('id') ];
             
        }

        $items[]=['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest];
        $items[]=['label' => 'Logout', 'url' => ['site/logout'],'class' => 'btn btn-link', 'visible' => !Yii::$app->user->isGuest];

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items'=>$items, 
        
    ]);
   
    NavBar::end();
    ?>
    
    <div class="container">
   
            <div class="left">
            
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
                <?=AlertWidget::widget()?>
                <?= $content ?>
        </div><!--end right-->
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
