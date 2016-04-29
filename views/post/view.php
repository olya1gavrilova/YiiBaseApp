<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Посты', 'url' => ['post/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

   <h1><?=$model->title?></h1>
        Автор: <?=Html::a($author,['../user/view', 'id'=> $model->author_id])?>
         <br />
        Дата публикации: <?=$model->publish_date?>
        <br />


        <br />
        <?=$model->content?>
        <br /><br /><br />
        <hr />
        <h4>Комментарии</h4>
        <br />
        <?= LinkPager::widget(['pagination' => $pagination]) ?>
        <?php foreach($comments as $comment):?>
            <br />
            Коммент от <b>
            <?php if($comment->auth_id!='0'):?>
                <a href="/user/view/<?=$comment->auth_id?>"><?=$comment->auth_nick?></a> 
            <?php else:?>
                <?=$comment->auth_nick?>
            <?php endif?>
            </b>
            в <?=$comment->date?>:<br />
            <b><?=$comment->title?></b><br />
            <?=$comment->text?>
            <br />
            <hr />
        <?php endforeach?>
            <?=Html::a('Добавить комментарий',Yii::$app->user->isGuest ? ['../comments/create/'.$model->id] : ['../comments/create_comm/'.$model->id] ,['class'=>'btn btn-info']);?>
</div>
