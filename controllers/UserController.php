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
use app\models\Role;
use app\models\Profile;



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
           throw new ForbiddenHttpException('Недостаточно прав для совершения этого действия');
        }
    }

    //просмотр своей записи
      public function actionView($id)
    {
        

                $model = $this->findModel($id);
         if(Yii::$app->user->can('update-post')  && !Yii::$app->user->isGuest){
                $posts=Post::find()->where(['author_id'=>$id])->OrderBy('publish_date DESC')->limit(3)->all();
            }
          else{
                $posts=Post::isPublished()->andWhere(['author_id'=>$id])->OrderBy('publish_date DESC')->limit(3)->all();
            }
        if(Yii::$app->user->can('comment-update') && !Yii::$app->user->isGuest)
                $comments=Comments::find()->where(['auth_id'=>$id])->OrderBy('date DESC')->limit(3)->all();
            else{
                $comments=Comments::find()->where(['auth_id'=>$id, 'status'=>'publish'])->OrderBy('date DESC')->limit(3)->all();
            }
            
               
                

          if(Yii::$app->user->can('role-update'))
           {   
              
                $role=Assignments::findOne(['user_id'=>$id]);
                 $roles=Role::find()->where(['type'=>1])->all();

                if(Yii::$app->request->post() && $id!=1){
                    Assignments::deleteAll(['user_id'=>$id]);
                    $role=new Assignments;
                    $role->item_name=Yii::$app->request->post('Assignments')['item_name'];
                    $role->user_id=$id;
                    $role->save();
                }
            }

   
                return $this->render('view', [
                    'model' => $model,
                    'role' => $role,
                    'roles' => $roles,
                    'posts' => $posts,
                    'comments'=>$comments,
                    'id'=>$id
                   
                ]);
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

        $model = new User(['scenario' => User::SCENARIO_CREATE]);
        
        
        if ($model->load(Yii::$app->request->post())) {

            if(User::find()->where(['username'=>$model->username])->one())
            {
                 $this->render('create', [
                          'model' => $model,
                  ]); 
            }
            else{
            $model->createuser();

            Yii::$app->session->setFlash('success','Вы зарегистрированы на сайте. Введите свой логин и пароль для входа');
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
        $model = $this->findModel($id);

        if(Yii::$app->user->can('user-update') || $model->isAuthor($id)){
            $model = $this->findModel($id);
            $model->scenario=User::SCENARIO_UPDATE;

            if ($model->load(Yii::$app->request->post()) && $model->validate())
            {
                $model->save();
                Yii::$app->session->setFlash('success','Данные пользователя изменены');
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
            throw new ForbiddenHttpException('Недостаточно прав для совершения этого действия');            
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
        $user=  $this->findModel($id); 

         if(Yii::$app->user->can('user-delete') || $user->isAuthor()){
                 $user->delete();

                return $this->redirect(['index']);
        }
         else{
            throw new ForbiddenHttpException('Недостаточно прав для совершения этого действия');            
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
           Yii::$app->session->setFlash('success', 'Данные пользователя успешно изменены');
            return $this->redirect('change_password');
          }

          else{
           
            return $this->render('change_password',['user'=>$user]);
          }
    }

   
}
