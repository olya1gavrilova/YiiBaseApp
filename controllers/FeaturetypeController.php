<?php

namespace app\controllers;

use Yii;
use app\models\Featuretype;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\web\ForbiddenHttpException;
use app\models\Feature;

/**
 * FeaturetypeController implements the CRUD actions for Featuretype model.
 */
class FeaturetypeController extends Controller
{
    /**
     * Lists all Featuretype models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Featuretype::find(),
            'pagination' => [
                        'pageSize' => 20,
                    ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Featuretype model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Featuretype model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        if(Yii::$app->user->can('featuretype-redact')){
            $model = new Featuretype();
            $dataProvider= new ActiveDataProvider([
                'query' => Featuretype::find()->where(['feature_id'=>$id]),
            ]);

            if ($model->load(Yii::$app->request->post())) {
                $model->feature_id=$id;
                $model->save();
                return $this->redirect(['create', 'id' => $id, 'dataProvider'=> $dataProvider]);
            } else {
                return $this->render('create', [
                    'model' => $model, 'id' => $id, 'dataProvider'=> $dataProvider
                ]);
            }
        }
        else{
            throw new ForbiddenHttpException('Доступ запрещен');
        }
    }

    /**
     * Updates an existing Featuretype model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
         if(Yii::$app->user->can('featuretype-redact')){
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
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
     * Deletes an existing Featuretype model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(Yii::$app->user->can('Featuretype-redact')){
            $this->findModel($id)->delete();

            return $this->redirect(['index']);
        }
        else{
            throw new ForbiddenHttpException('Доступ запрещен');
        }
    }

    /**
     * Finds the Featuretype model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Featuretype the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Featuretype::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
