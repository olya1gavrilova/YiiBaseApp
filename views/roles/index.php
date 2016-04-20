<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RolesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Roles';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="roles-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать роль', ['create', 'id'=>'role'], ['class' => 'btn btn-success']) ?>
    </p>

    <!-- GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'type',
            'description:ntext',
            'rule_name',
            'data:ntext',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); -->
    <table class="table table-hover">
        <tr>
        <td><td>
        <?php foreach ($roles as $role):?>
            <td>

                <a href='./update?id=<?=$role->name?>'>
                    <?=$role->description?>
                </a>
            <td>
        <?php endforeach?>
        </tr>
        <?php foreach ($users as $user):?>
            <tr>
                <td><?=Html::a($user->username, ['../user/index','id'=>$user->id])?></a><td>
              
             <?php foreach ($roles as $role):?>
                    <td><?php foreach ($assignments as $key):?>
                          <?php if($key->item_name ===$role->name && $key->user_id==$user->id):?>
                                ok
                          <?php endif?>
                        <?php endforeach?>
                    <td>
            <?php endforeach?>
             </tr> 
        <?php endforeach?>

    </table>

</div>
