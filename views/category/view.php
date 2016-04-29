<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use app\models\User;
use yii\widgets\LinkPager;
use app\models\Comments;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Category */

$this->title = $model->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-view">

    <?php foreach($posts as $post):?>
            <h3><?=$post->title?></h3>
            Автор: <b><?=Html::a(User::find()->where(['id' => $post->author_id])->one()->username, ['user/view', 'id' => $post->author_id])?></b>
            <br /> <br />
            <?php if($post->anons !=""):?>
                         <?=$post->anons?>
                <?php else:?>
                        <?=StringHelper::truncateWords($post->content, 50)?>
            <?php endif?>
            <br /><br />
            <b> <?=Comments::find()->where(['post_id'=>$post->id])->andWhere(['status'=>'publish'])->count();?> </b> Комментариев
            <br /><br />
            <?=Html::a('Подробнее', ['post/view', 'id' => $post->id] , ['class'=>'btn btn-info'])?>
    <?php endforeach?>

</div>
 <?= LinkPager::widget(['pagination' => $pagination]) ?>
