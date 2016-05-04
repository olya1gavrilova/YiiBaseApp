<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\helpers\Url;

use app\models\Message;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\models\Message */

$this->title = 'Написать сообщение для '.User::findOne(['id'=>$id])->username;
$this->params['breadcrumbs'][] = ['label' => 'Сообщения', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="message-create">
	 
	    <h1><?= Html::encode($this->title) ?></h1>

	    <div class="message-formcol-md-6">

		    <?php $form = ActiveForm::begin(); ?>


			    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

			    
			    <div class="form-group">
			        <?=Html::submitButton('Отправить', ['class'=>'btn btn-success'])?>
			    </div>

		    <?php ActiveForm::end();?>
		</div>
		<div class="message-list">
			<?php foreach($messages as $message):?>
				<div class='one-message' style=" padding: 15px;
					<?php if($message->from_id==Yii::$app->user->identity->id):?>
					 background: #edfadb; text-align: right; margin-top: 
					<?php else: ?>
					 background: #fadbf3; text-align: left; margin-top:
					<?php endif?>
					30px;"
					>
					<?=$message->date?>	<br />
					<b><?=$message->from->username?> </b> написал(а):<br /><br />
					<?=$message->text?>
					<br/><br/>
					<?=Html::a('Удалить сообщение', Url::to(['delete', 'id'=>$message->id]), ['class'=>'btn btn-warning btn-xs'])?>
				</div>
			<? endforeach?>
		</div>

</div>
