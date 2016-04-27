<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
use app\models\MenuType;


$this->title = 'Меню';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Menu', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <h3>Главное меню</h3>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'menu_id',
            'menu_item',
            'menu_url:url',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <br /><br />

    <?php if(MenuType::find()->where(['id'=>2])->one()):?>
        <h3>Меню Боковое</h3>
    <?= GridView::widget([
        'dataProvider' => $dataProvider2,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'menu_id',
            'menu_item',
            'menu_url:url',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php endif?>
    <br /><br />

    <?php if(MenuType::find()->where(['id'=>3])->one()):?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider3,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'menu_id',
            'menu_item',
            'menu_url:url',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php endif?>

</div>
