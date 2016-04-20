<?php

use yii\helpers\Html;

use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Update User: ' . ' ' . $model->id;

if(Yii::$app->user->can('user-update') ):
	$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
endif;

$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['index', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

     <?php if(User::isAuthor($id)):?>

             <?= Html::a('Изменить пароль','../change_password', ['class' => 'btn btn-info']) ?>

    <?php endif?>
    <br /><br />

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    <br />
    

</div>
