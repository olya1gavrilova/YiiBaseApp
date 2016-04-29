<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\MenuType;

/* @var $this yii\web\View */
/* @var $model app\models\Menu */

$this->title = 'Создать новый пункт меню';
$this->params['breadcrumbs'][] = ['label' => 'Меню', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-create">
	<?php if(!Yii::$app->request->get('id')):?>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
	<?php else:?>

		<?php $form = ActiveForm::begin(); ?>

	    <?= $form->field($model, 'menu_type')->textInput(['maxlength' => true])->label('Название') ?>


	    <div class="form-group">
	        <?= Html::submitButton( 'Создать' , ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>

    <?php ActiveForm::end(); ?>

	<?php endif?>
</div>
