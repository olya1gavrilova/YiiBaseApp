<?php

use yii\helpers\Html;



/* @var $this yii\web\View */
/* @var $model app\models\Comments */

$this->title = 'Добавить комментарий';
$this->params['breadcrumbs'][] = ['label' => $post->title , 'url' => ['../post/view','id'=>$post->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comments-create">
    <h1><?= Html::encode($this->title).' к посту '.$post->title ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>


</div>
