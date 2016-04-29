<?php

use yii\helpers\Html;

use app\models\Post;

/* @var $this yii\web\View */
/* @var $model app\models\Comments */

$this->title = 'Обновить комментарий: ';
$this->params['breadcrumbs'][] = ['label' => 'к посту '. Post::findOne(['id'=>$model->post_id])->title, 'url'=>'/post/view/'.$model->post_id];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="comments-update">


    <h1><?= Html::encode($this->title).' к посту '. Post::findOne(['id'=>$model->post_id])->title ?> </h1>


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
