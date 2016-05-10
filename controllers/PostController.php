<?php

namespace app\controllers;

use Yii;
use app\models\Post;
use app\models\PostSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;

use yii\data\ActiveDataProvider;

use yii\data\Pagination;
use app\models\Comments;
use app\models\Category;
use app\models\User;
use yii\helpers\StringHelper;
use yii\base\Security;


/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
{
   

    //вывод всех постов  - для администратора
     public function actionIndex()
    {   
        

            if(Yii::$app->user->can('confirm-post') ||Post::isAuthor($id) && !Yii::$app->user->isGuest)
            {
                if(Yii::$app->request->get('id')){
                    $dataProvider=new ActiveDataProvider([
                        'query' => Post::find()->where(['author_id'=>Yii::$app->request->get('id')]),
                        'pagination' => [
                            'pageSize' => 10,
                        ],
                    ]);
                }
                else{
                        $dataProvider=new ActiveDataProvider([
                        'query' => Post::find(),
                        'pagination' => [
                            'pageSize' => 10,
                        ],
                        ]);
                 }
              }
            else{
                if(Yii::$app->request->get('id')){
                    $dataProvider=new ActiveDataProvider([
                        'query' => Post::isPublished()->andWhere(['author_id'=>Yii::$app->request->get('id')]),
                        'pagination' => [
                            'pageSize' => 10,
                        ],
                    ]);
                }
                else{
                    $dataProvider=new ActiveDataProvider([
                    'query' => Post::isPublished(),
                    'pagination' => [
                        'pageSize' => 10,
                    ],
                ]);
               }
            }
        
            $user=User::findOne([ 'id'=> Yii::$app->request->get('id') ])->username;
            

            return $this->render('index', [
                'dataProvider' => $dataProvider, 
                'category'=>Category::find()->all(),
                'user'=>$user,
                'id'=>Yii::$app->request->get('id'),
                            
            ]);
       
    }


    /**
     Просмотр данного конкретного поста
     */
   public function actionView($id)
    {
        $post= new Post;
        $model=$this->findModel($id);
     
        if(Yii::$app->user->can('post-draft-view') || $post->isAuthor($id) || $model->publish_status==='publish')
        {
           
            $pagination=new Pagination([
                'defaultPageSize'=>5,
                'totalCount'=>Comments::find()
                            ->where(['post_id'=>$id, 'status'=>'publish'])
                            ->count(),
            ]);

            $comments=Comments::find()
            ->orderBy(['id'=>SORT_DESC])
            ->where(['post_id'=>$id])
            ->andWhere(['status'=>'publish'])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

            $author=User::find()->where(['id'=>$model->author_id])->one();


            return $this->render('view', [
                'model' => $model,
                'comments'=>$comments,
                'pagination'=>$pagination,
                'ok'=>Yii::$app->request->get('ok'),
                'author'=>$author->username,
                

            ]);
         }
         else
            {
                 throw new ForbiddenHttpException('Недостаточно прав для совершения этого действия');
            }

    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        //ищем, кто наш юзер
        $user=Yii::$app->user->identity;

        //ищем список категорий
        

        if(Yii::$app->user->can('create-post')){
               
                $model = new Post();
                $model->author_id = $user->id;
                if ($model->load(Yii::$app->request->post()))
                {
                    //$model->anons=StringHelper::truncateWords(strip_tags($model->content, 50));
                    $model->save();
                    return $this->redirect(['view', 'id' => $model->id, 'category' => $category ]);
                } 
                else {
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }
        }
         else{
          throw new ForbiddenHttpException('Недостаточно прав для создания поста');
        }
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)

    {
        $post=new Post;
        
        if(Yii::$app->user->can('update-post') || $post->isAuthor($id))
        {
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post())  && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
        else
        {
            throw new ForbiddenHttpException('Недостаточно прав для обновления поста');
            
        }
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)

    {
        $post=new Post;


        if(Yii::$app->user->can('delete-post') || $post->isAuthor($id) )
        {
            $this->findModel($id)->delete();

            return $this->redirect(['index']);
        }
        else
        {

            throw new ForbiddenHttpException('Недостаточно прав для удаления поста');
            
        }
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {

        if (($model = Post::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемая страница не существует');
        }
    }
}
