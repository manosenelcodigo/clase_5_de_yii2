<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

use app\models\Profesion;

class SiteController extends Controller
{
    public function behaviors()
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
    }

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
        return $this->render('index');
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
    
    public function actionInsertar()
    {
        $profesion = new Profesion();
        $profesion->profesion = 'Ingeniero Civil';
        $profesion->created_by = 1;
        $profesion->created_at = date('Y-m-d H:i:s');
        $profesion->updated_by = 1;
        $profesion->updated_at = date('Y-m-d H:i:s');
        
        //echo '<pre>';
        //print_r($profesion);
        //exit;
        //$profesion->save();
        if ( $profesion->insert() ) {
            echo 'registro guardado';
        } else {
            echo 'error al guardar el registro';
        }
    }
    
    public function actionListar()
    {
        $profesiones = Profesion::find()
        //->select(['profesion'])
        //->where(['profesion' => null])
        //->orderBy('profesion asc')
        ->all();
        
        return $this->render(
            'listar',
            [
                'profesiones'   => $profesiones,
                'titulo'        => 'Listar registros',
            ]
        );
    }
    
    public function actionEliminar($id)
    {
        $profesion = Profesion::findOne($id);
        $profesion->delete();
    }
    
    public function actionEditar($id)
    {
        $profesion = Profesion::findOne($id);
        $profesion->profesion = 'Médico general';
        $profesion->updated_at = date('Y-m-d H:i:s');
        $profesion->update();
        //$profesion->save();
    }
}
