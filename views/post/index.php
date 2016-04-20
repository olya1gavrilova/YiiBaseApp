<?php

use yii\helpers\Html;

use yii\grid\GridView;

use yii\widgets\LinkPager;
use app\models\User;


$this->title = 'Все посты '.$author;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


?>
<div class="post-index">

     <h1><?= Html::encode($this->title) ?></h1>

    <?= LinkPager::widget(['pagination' => $pagination]) ?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                <td>
                    <?=Html::a('Создать пост','create', ['class' => 'btn btn-info btn-дп'])?>                    
                </td>
                <br />
                <br />


    <table class='table table-bordered'>
             <tr>
                <td>Дата</td>
                <td>Название</td>
                <td>Анонс</td>
                <?php 
                        if(Yii::$app->user->can('update-post') || $isauthor):
                ?>
                <td>Cтатус</td>
                <td></td>
                <?php endif ?>
            </tr>
        <?php foreach($posts as $post):?>
           <tr>
               
                    <td><?=$post->publish_date?></td>
                    <td>
                        <?=Html::a($post->title,['post/view', 'id' => $post->id])?>
                    </td>
                    <td><?=$post->anons?></td>
                    <?php 
                       
                        if(Yii::$app->user->can('update-post') ||  $isauthor):
                    ?>  
                    <td>
                        <?php if($post->publish_status ==="draft"):?>
                            Не опубликован
                        <?php else:?>
                            Опубликован
                         <?php endif?>
                    </td>
                     

                        <td><?=Html::a('Редактировать',['post/update', 'id' => $post->id], ['class' => 'btn btn-info btn-xs'])?></td>

                    <?php endif ?>
            </tr>
         <?php endforeach?>  
    </table>

    <?= LinkPager::widget(['pagination' => $pagination]) ?>
    

</div>
