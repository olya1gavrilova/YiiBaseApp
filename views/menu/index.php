<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
use app\models\MenuType;
use app\models\Menu;
use yii\data\ActiveDataProvider;


$this->title = 'Меню';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать пункт меню', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Добавить меню', ['create','id'=>'menu'], ['class' => 'btn btn-info']) ?>
        <?= Html::a('Удалить меню', ['delete','id'=>'menu'], ['class' => 'btn btn-warning']) ?>
    </p>
    <?php foreach(MenuType::find()->all() as $menu):?>
    <h3><?=$menu->menu_type?></h3>
    
    <?= GridView::widget([
        'dataProvider' => new ActiveDataProvider([ 'query' => Menu::find()->where(['type'=>$menu->id]) ]),
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'menu_id',
            'menu_item',
            'menu_url',

            ['class' => 'yii\grid\ActionColumn',
            'template' => '{update} {delete}'],
        ],
    ]); ?>
    <br /><br />
    <?php endforeach?>

    

</div>
