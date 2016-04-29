<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use vova07\imperavi\Widget;

/* @var $this yii\web\View */
/* @var $model app\models\Pages */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pages-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?//= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'text')->widget(Widget::className(), [
    'settings' => [
        'lang' => 'ru',
        'minHeight' => 200,
        'plugins' => [
            'clips',
            'fullscreen'
        ]
    ]
    ])->label('Текст страницы'); ?>

    <?= $form->field($model, 'meta_description')->textInput(['maxlength' => true]) ?>
     <?= $form->field($model, 'status')->dropDownList([ 'draft' => 'Draft', 'publish' => 'Publish', ])->label('Публикация') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
