<?php

namespace app\controllers;

use Yii;
use app\models\Profile;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Feature;

use yii\web\ForbiddenHttpException;
/**
 * ProfileController implements the CRUD actions for Profile model.
 */
class ProfileController extends Controller
{
    
    /**
     * Lists all Profile models.
     * @return mixed
     */
    public function actionIndex()
    {
         
        $dataProvider = new ActiveDataProvider([
            'query' => Profile::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'features'=> $features
            
        ]);
    }

    /**
     * Displays a single Profile model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Profile model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $id= Yii::$app->user->identity->id;
        if(!Profile::findOne(['user_id'=>$id]))
        {
            $model = new Profile();
            $features = Feature::find()->all();

            if ($model->load(Yii::$app->request->post()) ) {
                 $model->user_id= $id;
                $model->save();
                return $this->redirect(['view', 'id' => $model->user_id]);
            } else {
                return $this->render('create', [
                    'model' => $model, 'features'=>$features 
                ]);
            }
        }
        else{
            throw new ForbiddenHttpException("Запись уже существует");
            
        }
    }

    /**
     * Updates an existing Profile model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $features = Feature::find()->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->user_id]);
        } else {
            return $this->render('create', [
                'model' => $model, 'features'=>$features 
            ]);
        }
    }

    /**
     * Deletes an existing Profile model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Profile model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Profile the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Profile::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
