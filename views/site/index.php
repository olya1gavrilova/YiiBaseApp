<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

use app\models\User;

use app\models\Comments;

$this->title = 'Главная';
?>
<div class="site-index">
       
        <h1>Последние записи</h1>
        <?php foreach($posts as $post):?>
           
            <h3><?=$post->title?></h3>
            Автор: <b><?=Html::a(User::find()->where(['id' => $post->author_id])->one()->username, ['post/index', 'id' => $post->author_id])?></b>
            <br /> <br />
            <?=$post->anons?>
            <br /><br />
            <b> <?=Comments::find()->where(['post_id'=>$post->id,'status'=>'publish'])->count()?> </b> Комментариев
            <br /><br />
            <?=Html::a('Подробнее', ['post/view', 'id' => $post->id] , ['class'=>'btn btn-info'])?>
            <hr />
        <?php endforeach?>

    
</div>
