<?php

namespace app\controllers;

use Yii;
use app\models\Comments;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;

use yii\data\Pagination;
use app\models\Post;
use app\models\User;
use yii\helpers\StringHelper;
use yii\base\Security;


/**
 * CommentsController implements the CRUD actions for Comments model.
 */
class CommentsController extends Controller
{
   
     /* Lists all Comments models.
     * @return mixed
     */
    public function actionIndex()
    {
         if(Yii::$app->user->can('comment-list'))
         {
                

                $dataProvider = new ActiveDataProvider([
                    'query' => Comments::find()->orderBy('date DESC'),
                    'pagination'=>array(
                        'pageSize'=>10,
                      ),
                ]);

                return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'posts'=>Post::find()->all()
                ]);
         }
        else{
            throw new ForbiddenHttpException('Недостаточно прав для совершения этого действия');
            
        }
    }

    /**
     * Displays a single Comments model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $user=User::findOne(['id'=>$id]);
             $dataProvider = new ActiveDataProvider([
                    'query' => Comments::find()->where(['auth_id'=>$id])->orderBy('date DESC'),
                    'pagination'=>array(
                        'pageSize'=>10,
                      ),
                ]);

                return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'id'=>$id,
                    'user'=>$user
                ]);
    }

    /**
     * Creates a new Comments model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        if(Yii::$app->user->isGuest)
        {
            $model = new Comments(['scenario' => Comments::SCENARIO_CREATE_GUEST ]);
            $post= Post::find()->where(['id'=>$id])->one();

            if ($model->load(Yii::$app->request->post())) {
                $model->post_id = $id;
               
                $model->save();
                Yii::$app->session->setFlash('success','Ваш комментарий добавлен и ожидает модерации');
                return $this->redirect(['../post/view', 'id' => $id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'post' => $post,
                ]);
        }
        }
        else{
            throw new ForbiddenHttpException('Недостаточно прав для совершения этого действия');
        }
    }
    public function actionCreate_comm($id)
    {
        if(!Yii::$app->user->isGuest){
        $model = new Comments(['scenario' => Comments::SCENARIO_CREATE]);
        $post= Post::find()->where(['id'=>$id])->one();

        if ($model->load(Yii::$app->request->post())) {
              
                $model->post_id = $id;
                $model->auth_id=Yii::$app->user->identity->id;
                $model->auth_nick=Yii::$app->user->identity->username; 
                //$model->validate();
                $model->save();

                Yii::$app->session->setFlash('success','Ваш комментарий добавлен и ожидает модерации');
                return $this->redirect(['../post/view', 'id' => $id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'post' => $post,
            ]);
        }
        }
        else{
            throw new ForbiddenHttpException('Недостаточно прав для совершения этого действия');
        }
    }


    /**
     * Updates an existing Comments model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {

        $model = $this->findModel($id);
        $model->scenario = Comments::SCENARIO_UPDATE;

        if(Yii::$app->user->can('comment-update')  || $model->isAuthor() && Comments::isPublished(3 , $id)){
       

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
               
                Yii::$app->session->setFlash('success','Ваш комментарий обновлен');
               
               return $this->render('update', [
                    'model' => $model,
                ]);
                //return $this->redirect(['update', 'model'=>$model]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
        else{
            Yii::$app->session->setFlash('warning','Срок изменения комментария истек');
            throw new ForbiddenHttpException('Недостаточно прав для совершения этого действия');
            
        }
    }

    /**
     * Deletes an existing Comments model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(Yii::$app->user->can('comment-delete')){
            $this->findModel($id)->delete();

            return $this->redirect(['index']);
        }
        else{

            throw new ForbiddenHttpException('Недостаточно прав для совершения этого действия');
            
        }
    }

    /**
     * Finds the Comments model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Comments the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Comments::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемая страница не существует.');
        }
    }
}
