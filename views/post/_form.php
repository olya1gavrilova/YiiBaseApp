<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\helpers\ArrayHelper;
use app\models\Category;
use vova07\imperavi\Widget;


/* @var $this yii\web\View */
/* @var $model app\models\Post */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true])->label('Заголовок') ?>

    <?php echo $form->field($model, 'content')->widget(Widget::className(), [
    'settings' => [
        'lang' => 'ru',
        'minHeight' => 200,
        'plugins' => [
            'clips',
            'fullscreen'
        ]
    ]
    ])->label('Текст поста'); ?>
    
    <?= $form->field($model, 'publish_date')->textInput(['value' => $model->isNewRecord ? date('Y-m-d H:i:s') : $model->publish_date])->label('Время начала публикации') ?>
    <?= $form->field($model, 'end_publish')->textInput(['value' => $model->isNewRecord ?'2999-01-01 00:00:00': $model->end_publish])->label('Время окончания публикации') ?>

    <?php echo $form->field($model, 'anons')->widget(Widget::className(), [
    'settings' => [
        'lang' => 'ru',
        'row' => '3',
        'maxHeight' => 200,
        'plugins' => [
            'clips',
            'fullscreen'
        ]
    ]
    ])->label('Анонс'); ?>

    <?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map(Category::find()->all(), 'id', 'title'))->label('Категория') ?>
   

    <?//= $form->field($model, 'author_id')->textInput(['maxlength' => true]) ?>

    <?php if(Yii::$app->user->can('confirm-post')):?>
    <?= $form->field($model, 'publish_status')->dropDownList([ 'draft' => 'Draft', 'publish' => 'Publish', ], ['prompt' => ''])->label('Публикация') ?>
    <?php endif?>

    <?//= $form->field($model, 'publish_date')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
