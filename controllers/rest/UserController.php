<?php
namespace app\controllers\rest;
use Yii;
use app\models\User;
use app\controllers\rest\BaseRestController;
use yii\web\ForbiddenHttpException;
class UserController extends BaseRestController
{
	public $modelClass = 'app\models\User';
	
	/**
	 * Проверяет права текущего пользователя.
	 *
	 * Этот метод должен быть переопределен, чтобы проверить, имеет ли текущий пользователь
	 * право выполнения указанного действия над указанной моделью данных.
	 * Если у пользователя нет доступа, следует выбросить исключение [[ForbiddenHttpException]].
	 *
	 * @param string $action ID действия, которое надо выполнить
	*	[[yii\rest\IndexAction|index]]: постраничный список ресурсов;
	*	[[yii\rest\ViewAction|view]]: возвращает подробную информацию об указанном ресурсе;
	*	[[yii\rest\CreateAction|create]]: создание нового ресурса;
	*	[[yii\rest\UpdateAction|update]]: обновление существующего ресурса;
	*	[[yii\rest\DeleteAction|delete]]: удаление указанного ресурса;
	*	[[yii\rest\OptionsAction|options]]: возвращает поддерживаемые HTTP-методы.
	 * @param \yii\base\Model $model модель, к которой нужно получить доступ. Если null, это означает, что модель, к которой нужно получить доступ, отсутствует.
	 * @param array $params дополнительные параметры
	 * @throws ForbiddenHttpException если у пользователя нет доступа
	 */
	public function checkAccess($action, $model = null, $params = [])
	{
		// проверить, имеет ли пользователь доступ к $action и $model
		// выбросить ForbiddenHttpException, если доступ следует запретить
		
		$user = new User;
		
		if($action == 'index') {
			if(!Yii::$app->user->can('all-users') && !$user->isAuthor($model->id)) throw new ForbiddenHttpException;
		}
		if($action == 'view') {
			if(!Yii::$app->user->can('all-users') && !$user->isAuthor($model->id)) throw new ForbiddenHttpException;
		}
		if($action == 'create') {
			throw new ForbiddenHttpException;
		}
		if($action == 'update') {
			if(!Yii::$app->user->can('user-update') && !$user->isAuthor($model->id)) throw new ForbiddenHttpException;
		}
		if($action == 'delete') {
			if(!Yii::$app->user->can('user-delete')) throw new ForbiddenHttpException;
		}
	}
}