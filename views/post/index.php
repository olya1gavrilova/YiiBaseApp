<?php

use yii\helpers\Html;
use yii\grid\GridView;

use yii\models\Category;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Посты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Post', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            'id',
            'title',
            'anons:ntext',
            //'content:ntext',
            'category.title',
            // 'author_id',
            'publish_status',
            // 'publish_date',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
