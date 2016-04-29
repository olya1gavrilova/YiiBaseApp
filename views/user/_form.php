<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        
    </div>

    <?php ActiveForm::end(); ?>

    <label>Access token</label>
    <?php if(Yii::$app->user->can('user-update') ):?>
        <?=$model->access_token?>
       
    <?php endif?>
     <br /><br />

    <?=Html::a('Сгенерировать новый токен', ['tokenchange', 'id'=>$model->id], ['class'=>'btn btn-success col-md-offset-1'])?>

</div>
