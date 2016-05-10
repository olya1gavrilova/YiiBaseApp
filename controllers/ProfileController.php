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
        if(Yii::$app->user->can('profile-manager'))
        {
            $dataProvider = new ActiveDataProvider([
                'query' => Profile::find(),
                'pagination' => [
                        'pageSize' => 10,
                    ],
            ]);

            return $this->render('index', [
                'dataProvider' => $dataProvider,
                'features'=> $features
                
            ]);
        }
        else{
            throw new ForbiddenHttpException('Недостаточно прав');
        }
    }

    /**
     * Displays a single Profile model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $features = Feature::find()->all();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'features'=> $features,'id'=>$id
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

        if(!Yii::$app->user->isGuest){
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
                  return $this->redirect(['update', 'id' => $id]);
                
            }
        }
        else{
            throw new ForbiddenHttpException('Для создания профиля вам необходимо зарегистрироваться ');
            
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
        if(Yii::$app->user->can('profile-manager') || Profile::isAuthor($id)){
            $model = $this->findModel($id);
            if($model){
                $features = Feature::find()->all();

                if ($model->load(Yii::$app->request->post()) && $model->save()) {
                    return $this->redirect(['view', 'id' => $model->user_id]);
                } else {
                    return $this->render('create', [
                        'model' => $model, 'features'=>$features 
                    ]);
                }
            }
            else{
                return $this->redirect(['create', 'id' => $id]);
            }
        }
        else{
            throw new ForbiddenHttpException('Недостаточно прав');
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
         if(Yii::$app->user->can('profile-manager'))
        {
            $this->findModel($id)->delete();

            return $this->redirect(['index']);
        }
        else{
            throw new ForbiddenHttpException('Недостаточно прав');
        }
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
