<?php

use yii\helpers\Html;
use yii\grid\GridView;

use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RolesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Удалить роль/функцию';
$this->params['breadcrumbs'][] = ['label' => 'Роли ', 'url' => ['/role/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="roles-delete">
    <h1><?=$this->title?></h1>
     <?php $form = ActiveForm::begin(); ?>
     <?php print_r($post)?>


    <?=$form->field($model, 'name')->dropDownList(ArrayHelper::map($roles, 'name', 'description'))->label('Роль') ?>
   

    
        <?= Html::submitButton( 'Удалить',['class' => 'btn btn-warning']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    
</div>
