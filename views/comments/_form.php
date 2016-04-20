<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $model app\models\Comments */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="comments-form">

    <?php $form = ActiveForm::begin(); ?>

    

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    <?php if(Yii::$app->user->can('comment-update')):?>

    <?= $form->field($model, 'status')->dropDownList([ 'draft' => 'Draft', 'publish' => 'Publish', ], ['prompt' => '']) ?>

     <?php endif?>

     <?php if(Yii::$app->user->isGuest): ?>
            <?= $form->field($model, 'auth_nick')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'auth_email')->textInput(['maxlength' => true]) ?>

           
    
    <?php endif?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
