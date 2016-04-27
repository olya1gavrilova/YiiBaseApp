<?php

use yii\helpers\Html;
use yii\grid\GridView;

use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RolesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Роли';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="roles-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать роль', ['create', 'id'=>'role'], ['class' => 'btn btn-success']) ?>
         <?= Html::a('Удалить роль', ['delete', 'id'=>'role'], ['class' => 'btn btn-info']) ?>
    </p>

    
    <table class="table table-hover">
    
        <tr>
        <td><td>
        <?php foreach ($roles as $role):?>
            <td>

                
                <?=Html::a($role->description,['update','id'=>$role->name]);?>
                   
                </a>
            <td>
        <?php endforeach?>
        </tr>
        <?php $form = ActiveForm::begin(); ?>

        <?php foreach ($users as $user):?>
            <tr>
                <td><?=Html::a($user->username, ['../user/view','id'=>$user->id])?></a><td>
              
             <?php foreach ($roles as $role):?>
                    <td>
                        <input type="radio" value="<?=$role->name?>" name="roles[<?=$user->id?>]";
                    <?php foreach ($assignments as $key):?>
                          <?php if($key->item_name ===$role->name && $key->user_id==$user->id):?>
                               checked
                          <?php endif?>
                        <?php endforeach?>
                    ><td>
            <?php endforeach?>
             </tr> 
        <?php endforeach?>

        </table>
        <?=Html::submitButton('Сохранить',['class'=>'btn btn-success'])?>

        <?php ActiveForm::end(); ?>
    
         <?= LinkPager::widget(['pagination' => $pagination]) ?>
</div>
