<?php

use yii\helpers\Html;
use yii\widgets\DetailView;



/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

   <h1><?=$model->title?></h1>
        <?php if($ok):?>
            <div class="alert alert-success" role="alert">Ваш комментарий получен и будет опубликован после подтверждения модератором</div>
        <?php endif?>


        <br />
        <?=$model->content?>
        <br /><br /><br />
        <hr />
        <h4>Комментарии</h4>
        <br />
        <?= LinkPager::widget(['pagination' => $pagination]) ?>
        <?php foreach($comments as $comment):?>
            <br />
            <b><?=$comment->auth_nick?></b> написал <b><?=$comment->date?>:</b><br />
            <?=$comment->text?>
            <br />
            <hr />
        <?php endforeach?>
            <?=Html::a('Добавить комментарий','index' ,['class'=>'btn btn-info']);?>
</div>
