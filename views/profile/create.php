<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Feature;
use vova07\imperavi\Widget;


use yii\helpers\ArrayHelper;
use app\models\Featuretype;
use app\models\Profile;

/* @var $this yii\web\View */
/* @var $model app\models\Profile */

$this->title = 'Редактировать профиль '.Profile::findOne(['user_id'=>$id])->user->username;
if(Yii::$app->user->can('profile-manager'))
  {
    $this->params['breadcrumbs'][] = ['label' => 'Все профили', 'url' => ['index']];
  }
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="profile-create">
  <?//php print_r( $model->attributes)?>

   <?php $form = ActiveForm::begin(); ?>

    <?//= $form->field($model, 'user_id')->textInput(['maxlength' => true]) ?>
   
     <?php foreach($features as $feature): ?>

     	<?php if($feature->type==="string"):?>
  			<?= $form->field($model, $feature->name)->textInput(['maxlength' => true]) ?>
  		<?php elseif($feature->type=="text"):?>
  			<?php echo $form->field($model, $feature->name)->widget(Widget::className(), [
			    'settings' => [
			        'lang' => 'ru',
			        'minHeight' => 100,
			        'plugins' => [
			            'clips',
			            'fullscreen'
			        ]
			    ]
			    ])->label($feature->description); ?>
  		<?php elseif($feature->type=="select"):?>
   			 <?= $form->field($model, $feature->name)->dropDownList(ArrayHelper::map(Featuretype::find()->where(['feature_id'=>$feature->id])->all(), 'id', 'value'))->label($feature->description) ?>
     	<?php elseif($feature->type=="radio"):?>
    	<?= $form->field($model, $feature->name)->radioList(ArrayHelper::map(Featuretype::find()->where(['feature_id'=>$feature->id])->all(), 'id', 'value'))->label($feature->description);?>
      <?php elseif($feature->type=="checkbox"):?>
        <?= $form->field($model, $feature->name)->checkboxList(ArrayHelper::map(Featuretype::find()->where(['feature_id'=>$feature->id])->all(), 'id', 'value'))->label($feature->description) ?>
    	<?php endif?>

	<?php endforeach?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
