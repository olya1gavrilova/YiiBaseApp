<?php

namespace app\controllers;

use Yii;
use app\models\Message;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\web\ForbiddenHttpException;
use app\models\Dialog;
use yii\web\Session;

/**
 * MessageController implements the CRUD actions for Message model.
 */
class MessageController extends Controller
{
  /*  public function behaviors()
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
     * Lists all Message models.
     * @return mixed
     */
  /*  public function actionView()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Message::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }*/

    /**
     * Displays a single Message model.
     * @param integer $id
     * @return mixed
     */
    public function actionIndex()
    {
        $id=Yii::$app->user->identity->id;

            $data = Dialog::find()->where(['from_id'=>$id])->all();
        

        return $this->render('index', [
            'data' => $data,
        ]);
    }

    /**
     * Creates a new Message model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new Message();
        $author_id =Yii::$app->user->identity->id;
        $messages= Message::isVisible()->andWhere(['or',['to_id'=>$id, 'from_id'=>$author_id],['from_id'=>$id, 'to_id'=>$author_id]])->orderBy('id DESC')->all();
       
            if ($model->load(Yii::$app->request->post())) {
                if(!Dialog::find()->where(['to_id'=>$id, 'from_id'=>$author_id])->one()) {
                    $dialog=new Dialog;
                    $dialog->from_id=$author_id;
                    $dialog->to_id=$id;
                    $dialog->insert();
                    $dialog->from_id=$id;
                    $dialog->to_id=$author_id;
                    $dialog->insert();
                }

                $model->from_id=Yii::$app->user->identity->id;
                $model->to_id=$id;
                $model->save();
                return $this->redirect(['create',
                 'id' => $id,
                 'messages'=>$messages,]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'id' => $id,
                    'messages'=>$messages,
                ]);
            }
        
    }

    /**
     * Updates an existing Message model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
   /* public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'id' => $id,
            ]);
        }
    }*/

    /**
     * Deletes an existing Message model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model=$this->findModel($id);
       
        Message::deleteMessage($model);

        if( $model->to_id==Yii::$app->user->identity->id)
        {
            $redirect_id=$model->from_id;
        }
        else{
            $redirect_id=$model->to_id;
        }
       Yii::$app->session->setFlash('success','Сообщение удалено');

        return $this->redirect(['create','id'=>$redirect_id]);
    }
    public function actionDelete_dialog($id)
    {
        $models=Message::find()->where(['or', ['from_id'=>$id], ['to_id'=>$id]])->all();
        
        foreach($models as $model){
            Message::deleteMessage($model);
        }

        Dialog::deleteDialog($id);
        Yii::$app->session->setFlash('success','Диалог удален');
        return $this->redirect(['index']);
    }

    /**
     * Finds the Message model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Message the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Message::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
