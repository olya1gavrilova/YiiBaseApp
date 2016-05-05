<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Message */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="message-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    
    <div class="form-group">
        <?= Html::submitButton('Отправить',['create', 'id'=>$id], 'class' => 'btn btn-success' ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
