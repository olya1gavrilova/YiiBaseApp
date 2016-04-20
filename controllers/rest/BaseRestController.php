<?php
namespace app\controllers\rest;
use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBasicAuth;
class BaseRestController extends ActiveController
{	
	public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];
	public function init()
	{
		parent::init();
		Yii::$app->user->enableSession = false;
	}
	
	public function behaviors()
	{
		$behaviors = parent::behaviors();
		$behaviors['authenticator'] = [
			'class' => HttpBasicAuth::className(),
		];
		return $behaviors;
	}
}