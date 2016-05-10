<?php

namespace app\controllers;

use Yii;
use app\models\Feature;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Command;
use yii\web\ForbiddenHttpException;

/**
 * FeatureController implements the CRUD actions for Feature model.
 */
class FeatureController extends Controller
{
      /**
     * Lists all Feature models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->can('feature-redact')){
            $dataProvider = new ActiveDataProvider([
                'query' => Feature::find(),
                'pagination' => [
                        'pageSize' => 10,
                    ],
            ]);

            return $this->render('index', [
                'dataProvider' => $dataProvider,
            ]);
        }
        else{
            throw new ForbiddenHttpException('Доступ запрещен');
        }
    }

    /**
     * Displays a single Feature model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
         if(Yii::$app->user->can('feature-redact')){
                return $this->render('view', [
                    'model' => $this->findModel($id),
                ]);
         }
        else{
            throw new ForbiddenHttpException('Доступ запрещен');
        }
    }

    /**
     * Creates a new Feature model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
         if(Yii::$app->user->can('feature-redact')){

            $model = new Feature();

             if ($model->load(Yii::$app->request->post()) && $model->save()) {
                    
                    Feature::addCol($model);
                     if($model->type==="string" || $model->type==='text'){
                        return $this->redirect(['view', 'id' => $model->id]);
                        }
                        else{
                            return $this->redirect(['featuretype/create', 'id' => $model->id]);
                        }
                 return $this->redirect(['index']);
            } 
            else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
         }
        else{
            throw new ForbiddenHttpException('Доступ запрещен');
        }
    }

    /**
     * Updates an existing Feature model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
       if(Yii::$app->user->can('feature-redact')){
            $model = $this->findModel($id);
            $oldname=$model->name;

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Feature::renameCol($oldname, $model->name);
                return $this->redirect(['index']);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
        else{
            throw new ForbiddenHttpException('Доступ запрещен');
        }
    }

    /**
     * Deletes an existing Feature model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(Yii::$app->user->can('feature-redact')){
            $model=$this->findModel($id);
            Feature::deleteCol($model);
            $model->delete();

            return $this->redirect(['index']);
        }
        else{
            throw new ForbiddenHttpException('Доступ запрещен');
        }
    }

    /**
     * Finds the Feature model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Feature the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Feature::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
