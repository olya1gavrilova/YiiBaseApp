<?php

namespace app\controllers;

use Yii;
use app\models\Page;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;

/**
 * PagesController implements the CRUD actions for Pages model.
 */
class PageController extends Controller
{
    /**
     * Lists all Page models.
     * @return mixed
     */
    public function actionIndex()
    {   
        if(Yii::$app->user->can('page-control'))
        {
            $dataProvider = new ActiveDataProvider([
                'query' => Page::find(),
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
     * Displays a single Page model.
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
     * Creates a new Page model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(Yii::$app->user->can('page-control'))
        {
            $dataProvider = new ActiveDataProvider([
                'query' => Page::find(),
            ]);

            $model = new Page();

            if ($model->load(Yii::$app->request->post()) ) {
               $model->url = $model->translit($model->title);

                $model->save();
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
         }
        else{
            throw new ForbiddenHttpException;
            
        }
    }

    /**
     * Updates an existing Page model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {   
        if(Yii::$app->user->can('page-control'))
        {
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
            throw new ForbiddenHttpException;
            
        }
    }

    /**
     * Deletes an existing Pages model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(Yii::$app->user->can('page-control'))
        {
            $this->findModel($id)->delete();

            return $this->redirect(['index']);
         }
        else{
            throw new ForbiddenHttpException;
            
        }
    }

    /**
     * Finds the Pages model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pages the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Page::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
