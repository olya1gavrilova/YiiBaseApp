<?php

use yii\helpers\Html;
use yii\grid\GridView;

use app\models\User;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователь '.$model->username;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <?php if (Yii::$app->user->can('enter-admin')):?>
        <?=Html::a('Управление ролями', '../roles/index', ['class' => 'btn btn-info'])?>
     <?php endif?>


     <?php if (Yii::$app->user->can('delete-post') || Yii::$app->user->can('update-post')):?>
        <?=Html::a('Управление постами', '../post/all', ['class' => 'btn btn-info'])?>
    <?php endif?>


    <?php if (Yii::$app->user->can('comment-list')):?>
        <?=Html::a('Управление комментариями', '../comments/index', ['class' => 'btn btn-info '])?>
     <?php endif?>

      <?php if (Yii::$app->user->can('category-create')):?>
        <?=Html::a('Создать категорию', '../category/create', ['class' => 'btn btn-info'])?>
     <?php endif?>

     <?php if (Yii::$app->user->can('user-update')):?>
        <?=Html::a('Все пользователи', 'all', ['class' => 'btn btn-info'])?>
     <?php endif?>

    


    <h1><?= Html::encode($this->title) ?></h1>
    <table class='table table-bordered'>
        <tr>
            <td>Имя</td>
            <td><?=$model->first_name?></td>
        </tr>
        <tr>
            <td>Фамилия</td>
            <td><?=$model->last_name?></td>
        </tr>
        <tr>
            <td>Имя</td>
            <td><?=$model->username?></td>
        </tr>
        <tr>
            <td>E-mail</td>
            <td></td>
        </tr>
    
    <?php if (Yii::$app->user->can('role-update')):?>
        <tr>
            <td>Статус</td>
            <td>
            

            <?=$role->item_name?>
           
            </td>
        </tr>
    <?php endif?>
    </table>
    <?=Html::a('Редактировать данные', ['update', 'id' => $model->id], ['class' => 'btn btn-primary'])?>
    <br /><hr />


    <h2>Посты</h2>
    <table class='table table-bordered'>
             <tr>
                <td>Дата</td>
                <td>Название</td>
                <td>Анонс</td>
                <td></td>
            </tr>
        <?php foreach($posts as $post):?>
           <tr>
               
                    <td><?=$post->publish_date?></td>
                    <td><?=Html::a($post->title,['../post/view', 'id' => $post->id])?></td>
                    <td><?=$post->anons?></td>
                
                <td><?=Html::a('Редактировать',['../post/update', 'id' => $post->id], ['class' => 'btn btn-info btn-xs'])?></td>
            </tr>
         <?php endforeach?>  
    </table>
     <?=Html::a('Все посты '.$model->username, ['../post/index', 'id' => $model->id], ['class' => 'btn btn-primary'])?>
    <br /><hr />


    <h2>Комментарий</h2>
    <table class='table table-bordered'>
             <tr>
                <td>Дата</td>
                <td>Пост</td>
                <td>Текст комментария</td>
                <td></td>
            </tr>


    </table>
     <?=Html::a('Все Комментарии '.$model->username, ['../post/index', 'id' => $model->id], ['class' => 'btn btn-primary'])?>
</div>
