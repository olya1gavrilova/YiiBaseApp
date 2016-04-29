<?php

namespace app\controllers;

use Yii;
use app\models\Menu;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\web\ForbiddenHttpException;
use app\models\MenuType;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class MenuController extends Controller
{
  
     /* Lists all Menu models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->can('menu-access'))
        {
           
            return $this->render('index');
        }
        else{
            throw new ForbiddenHttpException('Недостаточно прав для совершения этого действия');
            
        }
        
    }

    /**
     * Displays a single Menu model.
     * @param integer $id
     * @return mixed
     */
  /*  public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }*/

    /**
     * Creates a new Menu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(Yii::$app->user->can('menu-access'))
        {
            if (Yii::$app->request->get('id')) { $model = new MenuType(); }
            else {$model = new Menu(); }
            

                if ($model->load(Yii::$app->request->post())) {

                    //проверяем первый символ урла
                    //$first_symbol=mb_substr($model->menu_url,0,1);
                    if(isset($model->menu_url) && mb_substr($model->menu_url,0,1)!=='/'){
                          $model->menu_url ='/'.$model->menu_url;
                    }
                    
                    $model->save();
                    return $this->redirect('index');
                } else {
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }

        }
        else{
            throw new ForbiddenHttpException('Недостаточно прав для совершения этого действия');
            
        }
    }

    /**
     * Updates an existing Menu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if(Yii::$app->user->can('menu-access'))
        {
                $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post())) {

                $first_symbol=mb_substr($model->menu_url,0,1);
                if($first_symbol!=='/'){
                      $model->menu_url ='/'.$model->menu_url;
                }

                $model->save();

                  return $this->redirect(['index']);
              } 
            else {
          
                 return $this->render('update', [
                'model' => $model,
                 ]);
                }
        }
        else{
            throw new ForbiddenHttpException('Недостаточно прав для совершения этого действия');
            
        }
    }

    /**
     * Deletes an existing Menu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(Yii::$app->user->can('menu-access'))
        {
            if($id==='menu'){
               
                $model= new MenuType;
                if(Yii::$app->request->post())
                {  
                    $id= Yii::$app->request->post('MenuType')['id'];
                    if(Menu::findAll(['type'=>$id])){
                        Yii::$app->session->setFlash('warning','Нельзя удалить меню в котором есть пункты меню');
                    }
                    else{
                            MenuType::findOne(['id'=>$id])->delete();
                           Yii::$app->session->setFlash('success','Пункт меню успешно удален');
                    }
                    return $this->redirect('index');
                }
                
                else{
                    return $this->render('delete', [
                    'model' => $model,
                    'menu_type'=>MenuType::find()->all(),
                     ]);
                }
            }

            else{
                $this->findModel($id)->delete();

                 return $this->redirect(['index']);
            }
         }
         else{
            throw new ForbiddenHttpException('Недостаточно прав для совершения этого действия');
            
        }
    }

    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Menu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемая страница не существует.');
        }
    }
}
