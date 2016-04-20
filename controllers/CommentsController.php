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
use yii\helpers\StringHelper;

/**
 * CommentsController implements the CRUD actions for Comments model.
 */
class CommentsController extends Controller
{
   /* public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }*/

    /**
     * Lists all Comments models.
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
                ]);
         }
        else{
            throw new ForbiddenHttpException;
            
        }
    }

    /**
     * Displays a single Comments model.
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
     * Creates a new Comments model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new Comments();
        $post= Post::find()->where(['id'=>$id])->one();

        if ($model->load(Yii::$app->request->post())) {

            $model->post_id = $id;
            $model->short_text=StringHelper::truncateWords($model['text'], 25);
            $model->status='draft';
            if(!Yii::$app->user->isGuest)
            {
                $model->auth_email='1@mail.ru';
                $model->auth_id=Yii::$app->user->identity->id;
                $model->auth_nick=Yii::$app->user->identity->username;
            }
            $model->save();
            return $this->redirect(['../post/view', 'id' => $id, 'ok'=>'ok']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'post' => $post,
            ]);
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
        if(Yii::$app->user->can('comment-update')){
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) &&  $model->save() ) {
               
                return $this->redirect(['index']);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
        else{
            throw new ForbiddenHttpException;
            
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
            throw new ForbiddenHttpException;
            
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
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
