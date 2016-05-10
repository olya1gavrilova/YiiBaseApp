<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Featuretype */

$this->title = 'Update Featuretype: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Featuretypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="featuretype-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
