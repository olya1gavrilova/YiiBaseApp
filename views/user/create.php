<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login col-md-6">
    <h1><?= Html::encode($this->title) ?></h1>

 <p>Пожалуйста, заполните эти поля, чтобы зарегистрироваться:</p>

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'first_name')->textInput(['autofocus' => true])->label('Ваше имя') ?>

        <?= $form->field($model, 'last_name')->textInput(['autofocus' => true])->label('Ваша фамилия') ?>
        <?= $form->field($model, 'username')->textInput(['autofocus' => true])->label('Введите логин') ?>

        <?= $form->field($model, 'password')->passwordInput()->label('Введите пароль') ?>

        <?/*= $form->field($model, 'rememberMe')->checkbox([
            'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
        ]) */?>

        <div class="form-group">
            <div class="col-lg-11">
                <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-success']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

    
</div>
