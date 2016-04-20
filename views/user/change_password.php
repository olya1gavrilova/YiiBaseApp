<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;



/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Изменение пароля';
$this->params['breadcrumbs'][] = ['label' => Yii::$app->user->identity->username, 'url' => ['view', 'id' => Yii::$app->user->identity->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-form col-md-6">

<h1>Изменение пароля <?=$user->username?></h1>


    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($user, 'currentPassword')->passwordInput() ?>

    <?= $form->field($user, 'newPassword')->passwordInput() ?>

    <?= $form->field($user, 'newPasswordConfirm')->passwordInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить' , ['class' =>'btn btn-success']) ?>

    </div>

</div>