<?php

namespace app\controllers;

use Yii;
use app\models\Roles;
use app\models\User;
use app\models\Assignments;
use app\models\RoleChild;
use app\models\RolesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;

use yii\data\Pagination;

/**
 * RolesController implements the CRUD actions for Roles model.
 */
class RolesController extends Controller
{
    
    public function behaviors()
    { 
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    
   
    }

    /**
     * Lists all Roles models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->can('role-list'))
        {
            $pagination=new Pagination([
                    'defaultPageSize'=>10,
                    'totalCount'=>User::find() ->count(),
                ]);

            //ищем все роли
            $roles=Roles::find()->where(['type'=> '1'])->all();
            //выводим юзеров
            $users=User::find()
            ->limit($pagination->limit)
            ->offset($pagination->offset)
            ->all();

            //ищем все доступы
            $assignments=Assignments::find()->all();

            //получаем массив значений из формы
             $post=Yii::$app->request->post('roles');
           if($post){
            Assignments::deleteAll();
            //перезаписываем данные в таблицу
                foreach($post as $key=>$value){
                   
                    $assignment=new Assignments;
                    $assignment->user_id=$key;
                    $assignment->item_name=$value;
                    $assignment->insert();

                    Yii::$app->session->setFlash('success', 'данные успешно изменены');
                    
                }
           $_POST['roles']="";
            return $this->redirect('index');
           }
            //
            return $this->render('index', [
                'roles' =>$roles,
                'users' => $users,
                'assignments'=>$assignments,
                'post'=>$post,
                'pagination' =>$pagination,
                
            ]);
        }
        else
        {
            throw new ForbiddenHttpException;
            
        }
    }

    /**
     * Displays a single Roles model.
     * @param string $id
     * @return mixed
     */
    /*public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }*/

    /**
     * Creates a new Roles model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        if(Yii::$app->user->can('role-create')){
            $model = new Roles();

            if ($model->load(Yii::$app->request->post())) {
                if($id==='role'){
                    $model->type='1';
                }
                else{
                    $model->type = '2';
                }
                $model->save();
                return $this->redirect(['index']);
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'id'=>$id
                ]);
            }
         }
         else{
            throw new ForbiddenHttpException;
         }
    }

    /**
     * Updates an existing Roles model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if(Yii::$app->user->can('role-list'))
        {
                //находим запись по имени
               $model = $this->findModel($id);

               //находим все простые действия
               $functions =Roles::find()->where(['type'=>2])->all();

               //находим все роли
               $roles= Roles::find()->where(['type'=>1])->all();

               //находим все связи между ролями
               $allconn=RoleChild::find()->all();

               //находим всех детей данной роли
               $children=RoleChild::find()->where(['parent'=>$id])-> all();

               //находим всех родителей данной роли
               $parents=RoleChild::find()->where(['child'=>$id])-> all();

                $post=$_POST['functions'];      

             if ($post) {
                RoleChild::deleteAll(['parent'=>$id]);
                foreach ($post as $key => $value) {
                    
                               $function=new RoleChild;
                                 $function->parent=$id;
                                $function->child=$value;
                               $function->insert();
                 }

                 $_POST['functions']=[]; 
                      $this->redirect(['update','id'=>$id]);
                         return 
                        $this->render('update', [
                        'model' => $model,
                        'functions' =>$functions,
                        'parents' =>$parents,
                        'children'=>$children,
                        'roles'=>$roles,
                        'allconn'=>$allconn,
                 ]);
                
                } 
                else {
                    return 
                        $this->render('update', [
                        'model' => $model,
                        'functions' =>$functions,
                        'parents' =>$parents,
                        'children'=>$children,
                        'roles'=>$roles,
                        'allconn'=>$allconn,
                        'post'=>$post,
                    
                    ]);
                }
            }

        else{
            throw new ForbiddenHttpException;
            
        }
    }

    /**
     * Deletes an existing Roles model.
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
     * Finds the Roles model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Roles the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Roles::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
