<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Pages */
 

$this->title = $model->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pages-view">

    <h1><?=$model->title?></h1> 
    <?=$model->text ?>
        
    

</div>
