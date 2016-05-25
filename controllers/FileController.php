<?php

namespace app\controllers;

use Yii;
use app\models\File;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\web\UploadedFile;
use app\models\Fileextensions;
use yii\web\ForbiddenHttpException;


/**
 * FileController implements the CRUD actions for File model.
 */
class FileController extends Controller
{
   

    /**
     * Lists all File models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->can('file-manager')){
            $dataProvider = new ActiveDataProvider([
                'query' => File::find(),
            ]);

            return $this->render('index', [
                'dataProvider' => $dataProvider,
            ]);
        }
        else{
            throw new ForbiddenHttpException('Недостаточно прав для совершения этого действия');
            
        }
    }

    /**
     * Displays a single File model.
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
     * Creates a new File model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!Yii::$app->user->isGuest){
            $model = new File();
            $dataProvider = new ActiveDataProvider([
                'query' => File::find()->where(['user_id'=>Yii::$app->user->identity->id]),
            ]);


             if (Yii::$app->request->isPost) {
                
               $model->uploadedFile = UploadedFile::getInstance($model, 'uploadedFile');

                if ($model->upload()) {
                    Yii::$app->session->setFlash('success','Ваш файл успешно загружен');
                    // file is uploaded successfully
                    return $this->redirect(['create','model' => $model, 'dataProvider' => $dataProvider,]);
                }
                else{
                    foreach(Fileextensions::find()->all() as $extension){
                      $formats[]=$extension->name;  
                    }
                    
                    Yii::$app->session->setFlash('warning','Формат файла не соответствует политике нашего сайта.<br/>Загрузите, пожалуйста, файл в одном из перечисленных форматов: '.implode(', ', $formats));
                        return $this->render('create', [
                        'model' => $model,
                        'dataProvider' => $dataProvider,
                    ]);
               }
            }
            else{
                 return $this->render('create', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
                ]);
            }
        }
        else{
            throw new ForbiddenHttpException('Недостаточно прав для совершения этого действия');
        }
    }

    /**
     * Updates an existing File model.
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
            ]);
        }
    }*/

    /**
     * Deletes an existing File model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model=$this->findModel($id);
        if(Yii::$app->user->can('file-manager') || $model->isAuthor() ){
            $model->delete();

            return $this->redirect(['create']);
        }
        else{
            throw new ForbiddenHttpException('Недостаточно прав для совершения этого действия');
        }
    }

    /**
     * Finds the File model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return File the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = File::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
