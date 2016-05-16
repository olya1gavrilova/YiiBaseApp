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
use yii\data\Pagination;

/**
 * MessageController implements the CRUD actions for Message model.
 */
class MessageController extends Controller
{
 
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
        if(!Yii::$app->user->isGuest){
            $id=Yii::$app->user->identity->id;

                $data = Dialog::find()->where(['from_id'=>$id])->all();
            

            return $this->render('index', [
                'data' => $data,
            ]);
        }
         else{
            throw new ForbiddenHttpException('Чтобы вести диалоги необходимо авторизоваться на сайте');
        }  
    }

    /**
     * Creates a new Message model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {

        if(!Yii::$app->user->isGuest){
        $model = new Message();
        $author_id =Yii::$app->user->identity->id;
        $messages= Message::isVisible()->andWhere(['or',['to_id'=>$id, 'from_id'=>$author_id],['from_id'=>$id, 'to_id'=>$author_id]])->orderBy('id DESC');
        $pagination=new Pagination([
                'defaultPageSize'=>20,
                'totalCount'=>$messages->count(),
            ]);

       foreach (Message::isVisible()->where(['to_id'=>$author_id,'viewed'=>'0'])->all() as  $value) {
            $value->viewed='1';
             $value->save();
        }

        $messages= $messages->offset($pagination->offset)->limit($pagination->limit)->all();

       
            if ($model->load(Yii::$app->request->post())) {
                //два диалога нужно, чтобы при удалении диалога одним пользователем, он был доступен для другого.
                Dialog::createDialog($id,$author_id);
                Dialog::createDialog($author_id,$id);

                Message::createMessage($model,$author_id,$id);

                return $this->redirect(['create',
                 'id' => $id,
                 'messages'=>$messages,
                 'pagination'=>$pagination]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'id' => $id,
                    'messages'=>$messages,
                    'pagination'=>$pagination
                ]);
            }
        }
        else{
            throw new ForbiddenHttpException('Для того, чтобы написать сообщение пользователю необходимо авторизоваться на сайте');
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
        if(!Yii::$app->user->isGuest){

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
        else{
            throw new ForbiddenHttpException('Недостаточно прав');
        }
    }
    public function actionDelete_dialog($id)
    {
        if(!Yii::$app->user->isGuest){

            $models=Message::find()->where(['or', ['from_id'=>$id], ['to_id'=>$id]])->all();
            
            foreach($models as $model){
                Message::deleteMessage($model);
            }

            Dialog::deleteDialog($id);
            Yii::$app->session->setFlash('success','Диалог удален');
            return $this->redirect(['index']);
        }
        else{
            throw new ForbiddenHttpException('Недостаточно прав');
        }
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
