<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use app\models\Featuretype;
use app\models\Profile;

/* @var $this yii\web\View */
/* @var $model app\models\Profile */

$this->title = $model->user->username;
if(Yii::$app->user->can('profile-manager'))
  {
    $this->params['breadcrumbs'][] = ['label' => 'Все профили', 'url' => ['index']];
  }
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="profile-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if($model->isAuthor()):?>
        <?= Html::a('Редактировать профиль', ['update', 'id' => $model->user_id], ['class' => 'btn btn-primary']) ?>
         <?php endif?>
        <?/*= Html::a('Delete', ['delete', 'id' => $model->user_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) */?>
    </p>
    <table class="table table-hover">
        
    <?php foreach($features as $feature):?>
       
        <tr>
            <td><?=$feature->description?></td>
            <?php if($feature->type=='select' || $feature->type=='radio'):?>
                 <td><?=Featuretype::findOne(['id'=>$model[$feature->name]])->value?></td>
            <?php elseif($feature->type=='checkbox' ):?>
                <td>
                     <?php foreach(Featuretype::findAll(['id'=>$model[$feature->name]]) as $featuretype):?>
                        <?=$featuretype->value?><br/>

                    <?php endforeach?>
                </td>
            <?php else:?>
                 <td><?=$model[$feature->name]?></td>
            <?php endif?>
         </tr>
       
     <?php endforeach?>
    
    </table>

    

</div>
