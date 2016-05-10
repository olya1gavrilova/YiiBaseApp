<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Feature;


/* @var $this yii\web\View */
/* @var $model app\models\Profile */

$this->title = 'Create Profile';
$this->params['breadcrumbs'][] = ['label' => 'Profiles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="profile-create">

   <?php $form = ActiveForm::begin(); ?>

    <?//= $form->field($model, 'user_id')->textInput(['maxlength' => true]) ?>
   
     <?php foreach($features as $feature): ?>
    <?= $form->field($model, $feature->name)->textInput(['maxlength' => true]) ?>
	<?php endforeach?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
