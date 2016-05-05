<?php

use yii\helpers\Html;
use yii\grid\GridView;

use app\models\Dialog;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Все диалоги '.Dialog::find()->from->username;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="message-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Message', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class='col-md-6'>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
               // 'from_id',
                'to.username',
                'date',
                'text:ntext',
                // 'viewed',
                // 'deleted_by_sender',
                // 'deleted_by_reciever',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
