<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

use yii\base\Security;

use app\models\LoginForm;
use app\models\ContactForm;

use app\models\User;
use app\models\Post;
use app\models\Comments;
use app\models\Assignments;


class SiteController extends Controller
{
    /*public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }*/

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        $comments=Comments::find()->where(['post_id'=>$post->id])->andWhere(['status'=>'publish'])->count();

        $posts=Post::find()->orderBy('publish_date DESC')->where(['publish_status'=>'publish'])->limit(3)->all();

        return $this->render('index', [
            'model' => $model,
            'comments' => $comments,
            'posts'=>$posts,
        ]);
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }


    public function actionSignup()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new User();
        
        $assignments= new Assignments();

        if ($model->load(Yii::$app->request->post())) {
            if(User::find()->where(['username'=>$model->username])->one())
            {
               $this->render('signup', [
                'model' => $model,
            ]); 
            }
            else{
            $model->access_token=$model->tokenGenerator();
            $model->password=md5($model->password);
            $model->validate();
            $model->save();

            $id=User::findIdentityByAccessToken($model->access_token)->id;
            $assignments->user_id=$id;
            $assignments->item_name='user';
            $assignments->save();
            return $this->redirect(['login']);
            }
        }
        return $this->render('signup', [
            'model' => $model,
        ]);
    }


    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
}
