<?php

use yii\helpers\Html;
use yii\grid\GridView;

use app\models\User;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Role;

use yii\helpers\StringHelper;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователь '.$model->username;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">
    <h1><?= Html::encode($this->title) ?> 
        <?php if (Yii::$app->user->identity->id != $id):?>
            <?=Html::a('Отправить сообщение', Url::to(['message/create', 'id'=>$id]),['class'=>'btn btn-success'])?>
        <?php else:?>
            <?=Html::a('Мои сообщения', Url::to(['message/index']),['class'=>'btn btn-success'])?>
        <?php endif?>

         <?=Html::a('Посмотреть профиль', Url::to(['profile/view', 'id'=>$id]),['class'=>'btn btn-info'])?>
         </h1>
         
<?php if(Yii::$app->user->can('role-list') || $model->isAuthor()) :?>
    <div class='row'> 
      <?php if ($model->isAuthor() && (Yii::$app->user->can('role-list') || Yii::$app->user->can('menu-access') || Yii::$app->user->can('update-post') || Yii::$app->user->can('comment-list'))):?>
        <div class='col-md-6'>
        <h2>Функции</h2>
        <table class='table table-bordered'>
            <?php if (Yii::$app->user->can('menu-access')):?>
            <tr>
                <td>Редактирование меню</td>
                <td><?=Html::a('Перейти', Url::to(['menu/index']), ['class' => 'btn btn-info'])?></td>
            </tr>
            <?php endif?> 
            <?php if (Yii::$app->user->can('page-control')):?>
            <tr>
                <td>Редактирование статических страниц</td>
                <td><?=Html::a('Перейти', Url::to(['page/index']), ['class' => 'btn btn-info'])?></td>
            </tr>
            <?php endif?> 

            <?php if (Yii::$app->user->can('role-list')):?>
            <tr>
                <td>Управление ролями</td>
                <td><?=Html::a('Перейти', Url::to(['role/index']), ['class' => 'btn btn-info'])?></td>
            </tr>
          <?php endif?>  
          <?php if (Yii::$app->user->can('delete-post') || Yii::$app->user->can('update-post')):?>  
            <tr>
                <td>Управление постами</td>
                <td><?=Html::a('Перейти', Url::to(['post/index']), ['class' => 'btn btn-info'])?></td>
            </tr>
           <?php endif?> 
           <?php if (Yii::$app->user->can('comment-list')):?>
            <tr>
                <td>Управление комментариями</td>
                <td><?=Html::a('Перейти', Url::to(['comments/index']), ['class' => 'btn btn-info '])?></td>
            </tr>
            <?php endif?>
            <?php if (Yii::$app->user->can('category-update')):?>
            <tr>
                <td>Создать категорию</td>
                <td><?=Html::a('Перейти', Url::to(['category/create']), ['class' => 'btn btn-info'])?></td>
            </tr>
            <?php endif?>
            <?php if (Yii::$app->user->can('user-update')):?>
            <tr>
                <td>Все пользователи</td>
                <td><?=Html::a('Перейти', Url::to(['user/index']), ['class' => 'btn btn-info'])?></td>
            </tr>
            <?php endif?>
            <?php if (Yii::$app->user->can('profile-manager')):?>
            <tr>
                <td>Управление профилями пользователей</td>
                <td><?=Html::a('Перейти', Url::to(['profile/index']), ['class' => 'btn btn-info'])?></td>
            </tr>
            <?php endif?>
            <?php if (Yii::$app->user->can('feature-redact')):?>
            <tr>
                <td>Редактирование свойств профилей</td>
                <td><?=Html::a('Перейти', Url::to(['feature/index']), ['class' => 'btn btn-info'])?></td>
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
                    <td><?=$model->email?></td>
                </tr>
            
            <?php if (Yii::$app->user->can('role-update')):?>
                <tr>
                    <td>Статус</td>
                    <td>
                        
                         <?php $form = ActiveForm::begin(); ?>

                            <?=$form->field($role, 'item_name')->dropDownList(ArrayHelper::map($roles, 'name', 'description'))->label('') ?>
                            <?= Html::submitButton( 'Назначить новую роль',['class' => 'btn btn-warning']) ?>

                         <?php ActiveForm::end(); ?>
                    </td>
                </tr>
            <?php endif?>
            </table>
               
             <?=Html::a('Редактировать данные', Url::to(['update', 'id' => $model->id]), ['class' => 'btn btn-primary col-md-offset-2'])?>
            
            </div>
        </div>

    <?php endif?>

    <h2>Посты пользователя <?=$model->username?></h2>
    <table class='table table-bordered'>
             <tr>
                <td>Дата</td>
                <td>Название</td>
                <td>Анонс</td>
                
            </tr>
        <?php foreach($posts as $post):?>
           <tr>
               
                    <td><?=$post->publish_date?></td>
                    <td><?=Html::a($post->title,Url::to(['post/view','id'=>$post->id]))?></td>
                    <?php if($post->anons !=""):?>
                         <td><?=$post->anons?></td>
                     <?php else:?>
                        <td><?=StringHelper::truncateWords($post->content, 25)?></td>
                     <?php endif?>
               
            </tr>
         <?php endforeach?>  
    </table>
     <?=Html::a('Все посты '.$model->username, Url::to(['post/index', 'id'=> $model->id]), ['class' => 'btn btn-primary'])?>
    <br /><hr />


    <h2>Комментарии пользователя <?=$model->username?></h2>
    <table class='table table-bordered'>
             <tr>
                <td>Дата</td>
                <td>Пост</td>
                <td>Текст комментария</td>
                
            </tr>
            <?php foreach($comments as $comment):?>
           <tr>
               
                    <td><?=$comment->date?></td>
                    <td><?=Html::a($comment->title,Url::to(['post/view','id'=> $comment->post_id]))?></td>
                    <td><?=StringHelper::truncateWords($comment->text, 25)?></td>
               
            </tr>
         <?php endforeach?>  

    </table>
     <?=Html::a('Все комментарии '.$model->username, Url::to(['comments/view', 'id'=>$model->id]), ['class' => 'btn btn-primary'])?>
</div>
