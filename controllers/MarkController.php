<?php

namespace app\controllers;

use Yii;
use app\models\Mark;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\web\ForbiddenHttpException;
use yii\helpers\Json;

/**
 * MarkController implements the CRUD actions for Mark model.
 */
class MarkController extends Controller
{
      /**
     * Lists all Mark models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->can('mark-manager')){
            $dataProvider = new ActiveDataProvider([
                'query' => Mark::find(),
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
     * Displays a single Mark model.
     * @param integer $id
     * @return mixed
     */
    public function actionView()
    {
        
        if(!Yii::$app->user->isGuest){
            $id=Yii::$app->user->identity->id;

            $marks=Mark::findMarks()->asArray()->all();

            $dataProvider = new ActiveDataProvider([
                'query' => Mark::findMarks(),
                'pagination' => [
                        'pageSize' => 10,
                    ],
            ]);
            
            
            return $this->render('view', [
                'model' => $this->findModel($id),
                'dataProvider'=>$dataProvider,
                
                
                
            ]);
        }
        else{
            throw new ForbiddenHttpException('Для выставления метки зарегистрируйтесь на сайте');
        }
    }

    /**
     * Creates a new Mark model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
   /* public function actionCreate()
    {
        if(!Yii::$app->user->isGuest){
            $model = new Mark();
            $id= Yii::$app->user->identity->id;
            if(Mark::findOne($id)){
                 return $this->redirect(['update', 'id' => $id]);
            }
            if ($model->load(Yii::$app->request->post())) {
                $model->user_id = $id; 
                $model->save();
                return $this->redirect(['view', 'id' => $model->user_id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
        else{
            throw new ForbiddenHttpException('Для выставления метки зарегистрируйтесь на сайте');
        }
    }*/

    /**
     * Updates an existing Mark model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
   /* public function actionUpdate($id)
    {
        if(!Yii::$app->user->isGuest){
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->user_id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
         }
        else{
            throw new ForbiddenHttpException('Для выставления метки зарегистрируйтесь на сайте');
        }
    }*/

    /**
     * Deletes an existing Mark model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(!Yii::$app->user->isGuest){

            $this->findModel($id)->delete();
            if(Yii::$app->user->can('mark-manager')){
                 return $this->redirect(['index']);
            }
            else{
                return $this->redirect(['view']);
            }
            

        }
        else{
            throw new ForbiddenHttpException('Для выставления метки зарегистрируйтесь на сайте');
        }
    }
    public function actionActivate()
    {
        $id= Yii::$app->user->identity->id;
        if(!Yii::$app->user->isGuest){
            $model=$this->findModel($id);

             $model->activateMark();
              return $this->redirect(['view']);
        }
        else{
            throw new ForbiddenHttpException('Для выставления метки зарегистрируйтесь на сайте');
        }
    }

    /**
     * Finds the Mark model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mark the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        //if (($model = Mark::findOne($id)) !== null) {
            return $model = Mark::findOne($id);
        /*} else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }*/
    }
   /*public function beforeAction($action) {
    $this->enableCsrfValidation = false;
    return parent::beforeAction($action);
    }*/

    public function actionMarkupdate(){
        $id= Yii::$app->user->identity->id;
        $model = Mark::findOne(['user_id'=>$id]);
       

       if(Yii::$app->request->post('long') && Yii::$app->request->post('lat')){
            if(!$model){
                $model=new Mark;
                $model->user_id=$id;
                $model->long=Yii::$app->request->post('long');
                $model->lat=Yii::$app->request->post('lat');
                $model->status_text=Yii::$app->request->post('marktext');
                $model->insert();
             }
             else{
                $model->long=Yii::$app->request->post('long');
                $model->lat=Yii::$app->request->post('lat');
                $model->status_text=Yii::$app->request->post('marktext');
                $model->save();
            }

            echo Json::encode($model);
        }
        if(Yii::$app->request->post('leftlong') && Yii::$app->request->post('leftlat'))
         {
            $leftlat=Json::decode(Yii::$app->request->post('leftlat'));
            $leftlong= Json::decode(Yii::$app->request->post('leftlong'));
            $rightlat=Json::decode(Yii::$app->request->post('rightlat'));
            $rightlong=Json::decode(Yii::$app->request->post('rightlong'));

        $marks=Mark::findMarks()
                ->andwhere(['>','lat',$leftlat])
                ->andwhere(['<','lat',$rightlat])
                ->andwhere(['>','long',$leftlong])
                ->andwhere(['<','long',$rightlong])
                ->asArray()->all();
          echo Json::encode($marks);
       }
    }


}