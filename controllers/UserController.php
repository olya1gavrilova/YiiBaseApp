<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

use app\models\Post;
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


 /*   public function behaviors()
    {

        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
    */

    /**
        Просмотр всех пользователей
     */
    public function actionAll()
    {
        $user=new User;

        if(Yii::$app->user->can('user-update'))
        {
            $searchModel = new UserSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $dataProvider->pagination->pageSize=5;

            return $this->render('all', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
        else{
           throw new ForbiddenHttpException;
        }
    }

    //просмотр своей записи
      public function actionIndex($id)
    {
        $user= new User;

        if(Yii::$app->user->can('all-users') || $user->isAuthor($id))
            {
                $model = $this->findModel($id);
                $role=Assignments::find()->where(['user_id'=>$id])->one();
                $posts=Post::find()->where(['author_id'=>$id])->OrderBy('publish_date DESC')->limit(3)->all();
                return $this->render('index', [
                    'model' => $model,
                    'role' => $role,
                    'posts' => $posts,
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
   /* public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }*/

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
   /* public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }*/

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
