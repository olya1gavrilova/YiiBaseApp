<?php

use yii\helpers\Html;

use app\models\MenuType;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $model app\models\Menu */

$this->title = 'Создать новый пункт меню';
$this->params['breadcrumbs'][] = ['label' => 'Меню', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-delete">

    <h1>Удалить меню </h1>
	<?php $form = ActiveForm::begin(); ?>

         <?=$form->field($model, 'id')->dropDownList(ArrayHelper::map($menu_type, 'id', 'menu_type'))->label('Выберите меню для удаления') ?>
         <?= Html::submitButton( 'Назначить новую роль',['class' => 'btn btn-warning']) ?>

    <?php ActiveForm::end(); ?>
   

</div>
