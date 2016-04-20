<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

use app\models\Post;
use app\models\Comments;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\Assignments;
use yii\base\Security;

use yii\web\Session;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
        Просмотр всех пользователей
     */
    public function actionIndex()
    {
        $user=new User;

        if(Yii::$app->user->can('user-update'))
        {
            $searchModel = new UserSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $dataProvider->pagination->pageSize=5;

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
        else{
           throw new ForbiddenHttpException;
        }
    }

    //просмотр своей записи
      public function actionView($id)
    {
        $user= new User;

        if(Yii::$app->user->can('all-users') || $user->isAuthor($id))
            {
                $model = $this->findModel($id);
                $role=Assignments::find()->where(['user_id'=>$id])->one();
                $posts=Post::find()->where(['author_id'=>$id])->OrderBy('publish_date DESC')->limit(3)->all();
                $comments=Comments::find()->where(['auth_id'=>$id])->OrderBy('date DESC')->limit(3)->all();
                return $this->render('view', [
                    'model' => $model,
                    'role' => $role,
                    'posts' => $posts,
                    'comments'=>$comments,
                ]);
            }

            else{
               throw new ForbiddenHttpException;
            }
    }

     
    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
 

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
     if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new User();
        
        $assignments= new Assignments();

        if ($model->load(Yii::$app->request->post())) {
            if(User::find()->where(['username'=>$model->username])->one())
            {
               $this->render('signup', [
                'model' => $model,
            ]); 
            }
            else{
            $model->access_token=$model->tokenGenerator();
            $model->password=md5($model->password);
            $model->validate();
            $model->save();

            $id=User::findIdentityByAccessToken($model->access_token)->id;
            $assignments->user_id=$id;
            $assignments->item_name='user';
            $assignments->save();
            return $this->redirect(['../site/login']);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */

    //РЕДАКТИРОВАТЬ ДАННЫЕ ПОЛЬЗОВАТЕЛЯ
    public function actionUpdate($id)
    {
        $user = new User;

        if(Yii::$app->user->can('user-update') || $user->isAuthor($id)){
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->validate())
            {
                $model->save();
                return $this->redirect(['update', 'id' => $model->id]);
            }
            else {
                return $this->render('update', [
                    'model' => $model,
                    'id'=>$id,
                ]);
            }
        }
        else{
            throw new ForbiddenHttpException;            
        }
    }
   

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $user= new User; 

         if(Yii::$app->user->can('user-delete') || $user->isAuthor($id)){
                 $this->findModel($id)->delete();

                return $this->redirect(['index']);
        }
         else{
            throw new ForbiddenHttpException;            
        }
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionTokenchange($id)
    {
          $user=User::findIdentity($id);
          $user->access_token=$user->tokenGenerator();
          $user->save();
          return $this->redirect(['update', 'id'=>$id]);
    }

     public function actionChange_password()
    { 
          $user=Yii::$app->user->identity;
          $loadedinfo=$user->load(Yii::$app->request->post());

          if($loadedinfo && $user->validate())
          {
            $user->password=md5($user->newPassword);
            $user->save(false);
            
            return $this->redirect(['update', 'id'=>$user->id]);
          }

          else{
            return $this->render('change_password',['user'=>$user]);
          }
    }
}
