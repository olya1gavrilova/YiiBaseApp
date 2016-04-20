<?php

use yii\helpers\Html;
use yii\grid\GridView;

use yii\widgets\LinkPager;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Comments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comments-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Comments', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

     

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'post_id',
            'auth_nick',
            'auth_email:email',
            'title',
            // 'text:ntext',
            // 'short_text',
            'status',
            // 'date',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

     

</div>
