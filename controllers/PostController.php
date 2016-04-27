<?php

namespace app\controllers;

use Yii;
use app\models\Post;
use app\models\PostSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;

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
     * Lists all Post models.
     * @return mixed
     */

    //вывод всех опубликованных постов 
    //ДЛЯ ЛИСТА ИСПОЛЬЗУЕТСЯ ШАБЛОН ИНДЕКС!!!!
    public function actionList()
    {
        $pagination=new Pagination([
                    'defaultPageSize'=>10,
                    'totalCount'=>Post::arePublished(3)->count(),
                ]);

      
        
        $posts=Post::arePublished(3)
                    ->offset($pagination->offset)
                    ->limit($pagination->limit)
                    ->orderBy('publish_date DESC')
                    ->all();

         return $this->render('all', [
                    'posts' => $posts,
                    'pagination'=>$pagination,
                    'user' =>$user,
                    'author'=>$author,
                    'time' => $time,
                ]);

    }

    ///все посты конкретного автора
    public function actionAll($id)
    {   $user=new User;
        $isauthor=$user->isAuthor($id);
            //делаем пагинацию
                $pagination=new Pagination([
                    'defaultPageSize'=>15,
                    'totalCount'=>Post::find() ->where(['author_id'=>$id]) ->count(),
                ]);


                //находим все посты пользователя
                //если это автор или c правом редактировать чужие посты, он может видеть и опубликованные, и неопубликованные
                if(Yii::$app->user->can('update-post')|| $user->isAuthor($id))
                {
                    $posts = Post::find()
                    ->where(['author_id'=>$id])
                    ->offset($pagination->offset)
                    ->limit($pagination->limit)
                    ->orderBy('publish_date DESC')
                    ->all();
                }
                //если это кто-то сторонний, он может видеть только опубликованные
                else
                {
                     $posts = Post::find()
                    ->where(['author_id'=>$id, 'publish_status'=>'publish'])
                    ->offset($pagination->offset)
                    ->limit($pagination->limit)
                    ->orderBy('publish_date DESC')
                    ->all();
                }
                

                $user= Yii::$app->user->identity->username;
                $author=User::find()->where(['id'=>$id])->one()->username;

               
                return $this->render('all', [
                    'posts' => $posts,
                    'pagination'=>$pagination,
                    'user' =>$user,
                    'author'=>$author,
                    'isauthor' =>$isauthor,
                ]);
        
       
        

    }

    //вывод всех постов  - для администратора
     public function actionIndex()
    {
        if(Yii::$app->user->can('update-post') || Yii::$app->user->can('delete-post'))
        {

            $searchModel = new PostSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
             $dataProvider->pagination->pageSize=8;

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider, 
                'category'=>Category::find()->all(),
                            
            ]);
        }
        else{
          throw new ForbiddenHttpException;
        }
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
                 throw new ForbiddenHttpException;
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
                    $model->anons=strip_tags(StringHelper::truncateWords($model->content, 50));
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
          throw new ForbiddenHttpException;
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
            throw new ForbiddenHttpException;
            
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

            throw new ForbiddenHttpException;
            
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
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
