<?php

use yii\helpers\Html;
use yii\grid\GridView;

use app\models\User;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователь '.$model->username;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class='row'> 
      <?php if (Yii::$app->user->can('role-list') || Yii::$app->user->can('menu-access') || Yii::$app->user->can('update-post') || Yii::$app->user->can('comment-list')):?>
        <div class='col-md-6'>
        <h2>Функции</h2>
        <table class='table table-bordered'>
            <?php if (Yii::$app->user->can('menu-access')):?>
            <tr>
                <td>Редактирование меню</td>
                <td><?=Html::a('Перейти', '/menu/index', ['class' => 'btn btn-info'])?></td>
            </tr>
            <?php endif?> 
            <?php if (Yii::$app->user->can('page-control')):?>
            <tr>
                <td>Редактирование статических страниц</td>
                <td><?=Html::a('Перейти', '/page/index', ['class' => 'btn btn-info'])?></td>
            </tr>
            <?php endif?> 

            <?php if (Yii::$app->user->can('role-list')):?>
            <tr>
                <td>Управление ролями</td>
                <td><?=Html::a('Перейти', '/role/index', ['class' => 'btn btn-info'])?></td>
            </tr>
          <?php endif?>  
          <?php if (Yii::$app->user->can('delete-post') || Yii::$app->user->can('update-post')):?>  
            <tr>
                <td>Управление постами</td>
                <td><?=Html::a('Перейти', '/post/index', ['class' => 'btn btn-info'])?></td>
            </tr>
           <?php endif?> 
           <?php if (Yii::$app->user->can('comment-list')):?>
            <tr>
                <td>Управление комментариями</td>
                <td><?=Html::a('Перейти', '/comments/index', ['class' => 'btn btn-info '])?></td>
            </tr>
            <?php endif?>
            <?php if (Yii::$app->user->can('category-update')):?>
            <tr>
                <td>Создать категорию</td>
                <td><?=Html::a('Перейти', '/category/create', ['class' => 'btn btn-info'])?></td>
            </tr>
            <?php endif?>
            <?php if (Yii::$app->user->can('user-update')):?>
            <tr>
                <td>Все пользователи</td>
                <td><?=Html::a('Перейти', '/user/index', ['class' => 'btn btn-info'])?></td>
            </tr>
            <?php endif?>
         </table> 
         </div>
     <?php endif?>


            <div class='col-md-6'>
            <h2>Данные</h2>   
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
               
             <?=Html::a('Редактировать данные', ['update', 'id' => $model->id], ['class' => 'btn btn-primary col-md-offset-2'])?>
            
            </div>
        </div>

    


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
     <?=Html::a('Все посты '.$model->username, ['../post/all', 'id' => $model->id], ['class' => 'btn btn-primary'])?>
    <br /><hr />


    <h2>Комментарий</h2>
    <table class='table table-bordered'>
             <tr>
                <td>Дата</td>
                <td>Пост</td>
                <td>Текст комментария</td>
                <td></td>
            </tr>
            <?php foreach($comments as $comment):?>
           <tr>
               
                    <td><?=$comment->date?></td>
                    <td><?=Html::a($comment->title,['../post/view', 'id' => $comment->post_id])?></td>
                    <td><?=$comment->short_text?></td>
                
                <td><?=Html::a('Редактировать',['../post/update', 'id' => $post->id], ['class' => 'btn btn-info btn-xs'])?></td>
            </tr>
         <?php endforeach?>  

    </table>
     <?=Html::a('Все комментарии '.$model->username, ['../comments/view', 'id' => $model->id], ['class' => 'btn btn-primary'])?>
</div>
