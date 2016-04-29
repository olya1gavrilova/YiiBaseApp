<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\helpers\ArrayHelper;
use app\models\MenuType;

/* @var $this yii\web\View */
/* @var $model app\models\Menu */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'menu_item')->textInput(['maxlength' => true])->label('Пункт меню') ?>

    <?= $form->field($model, 'menu_url')->textInput(['maxlength' => true])->label('URL') ?>

    <?= $form->field($model, 'type')->dropDownList(ArrayHelper::map(MenuType::find()->all(), 'id', 'menu_type'))->label('Тип меню') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
