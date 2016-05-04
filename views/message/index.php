<?php

use yii\helpers\Html;
use yii\grid\GridView;

use app\models\Dialog;

use yii\helpers\Url;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Все диалоги '.Yii::$app->user->identity->username ;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="message-index">

    <h1><?= Html::encode($this->title) ?></h1>

    
    <div>
        <table class="table table-hover">
            <tr>
                <td>Имя</td>
                <td>Последнее сообщение</td>
            </tr>
            <?php foreach($data as $partner):?>
                <tr>
                
                    <td><?=$partner->to->username?></td>
                    <td><?=Html::a(StringHelper::truncateWords($partner->lastmessage->text, 20), Url::to(['create','id'=>$partner->to_id]))?></td>
                </tr>
             <?php endforeach?>
        </table>
    </div>
</div>
