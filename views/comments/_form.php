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

    <?= $form->field($model, 'title')->textInput(['maxlength' => true])->label('Заголовок комментария') ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6])->label('Текст комментария') ?>

    <?php if(Yii::$app->user->can('comment-update')):?>

    <?= $form->field($model, 'status')->dropDownList([ 'draft' => 'Не опубликован', 'publish' => 'Опубликован'])->label('Публикация') ?>

     <?php endif?>

     <?php if(Yii::$app->user->isGuest): ?>
            <?= $form->field($model, 'auth_nick')->textInput(['maxlength' => true])->label('Никнейм') ?>

            <?= $form->field($model, 'auth_email')->textInput(['maxlength' => true])->label('Емэйл') ?>
             <?= $form->field($model, 'captcha')->widget(Captcha::className(), [
                        'captchaAction' => ['/site/captcha']
                    ]) ?>           
    
    <?php endif?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
