<?php

use yii\helpers\Html;
use app\models\RoleChild;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Roles */

$this->title = 'Редактировать функции:' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Roles', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>

	<?=Html::a('Добавить функцию',['create','id'=>'rule'],['class'=>'btn btn-success']);?>
	<?=Html::a('Удалить функцию',['delete','id'=>'rule'],['class'=>'btn btn-warning']);?>

	<h3>Родительские роли:</h3>
	<?php foreach ($parents as $parent): ?>
		<?php foreach ($roles as $role): ?>
			<?php if($role->name===$parent->parent):?>
					<a href="update?id=<?=$role->name?>"><?=$role->description?></a><br />
			<?php endif?>
		<?php endforeach?>
	<?php endforeach?>


  <?php $form = ActiveForm::begin(); ?>
<div class="roles-update">
	

	<h3>Вложенные роли:<br></h3>
	<h4 style="margin-left:40px;">

		<?php foreach ($roles as $role): ?>
				
			<?php if($role->name!=='admin' && $role->name!==$model->name && $model->name!='user'):?>

				<a href="update?id=<?=$role->name?>"> 
				<?=$role->description?></a>
				<input type="checkbox" value="<?=$role->name?>" name="functions[]" 

				<?php foreach ($children as $child): ?>
					
					
							<?php if($child->child ===$role->name):?>
								
								checked
							<?php endif?>
					
				<?php endforeach?>
				>
			
			<?php endif?>		
		<?php endforeach?>
	<h4>
	

    <h1><?= Html::encode($this->title) ?></h1>
    <div class='col-md-6'>
 
	    <table class="table table-hover">
	    	<?php foreach ($functions as $function): ?>
	    	<tr>
	    		<td><?=$function->description?></td>
	    	<td><input type="checkbox" value="<?=$function->name?>" name="functions[]"
	    	
		    	<?php foreach ($allconn as $conn):?>
		    		<?php if($conn->child === $function->name && $conn->parent===$model->name):?>
		    		checked
		    	<?php endif?>

	    	<?php endforeach?>

	    	
			></td>
	    	</tr>
	    <? endforeach?>
	    </table>
	    <?=Html::submitButton('Сохранить',['class'=>'btn btn-success'])?>
    </div>
   		<?php ActiveForm::end(); ?>
   		
</div>
