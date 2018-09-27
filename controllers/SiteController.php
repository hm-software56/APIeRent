<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Register;
use app\models\User;
use app\models\Properties;
use app\models\LikeProperty;
use app\models\AlertPackage;
use yii\httpclient\Client;
use app\models\Maps;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
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

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionPayerid($id = null)
    {
        \Yii::$app->session['payerID'] = $id;
        if (!empty($id) && !empty(Yii::$app->user->id)) {
            $user = User::find()->where(['id' => Yii::$app->user->id])->one();
            $key = explode(',', $user->payer_code);
            if (!in_array($id, $key)) {
                if (empty($user->payer_code)) {
                    $user->payer_code = $id;
                } else {
                    $user->payer_code = $user->payer_code.','.$id;
                }
                $user->save();
            }
        }
    }

    public function actionIndex()
    {
        $sql = 'SELECT * FROM properties where status=1 ORDER BY id DESC LIMIT 4 ';
        $model = Properties::findBySql($sql)->all();
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('index', [
                'model' => $model,
            ]);
        } else {
            return $this->render('index', [
                'model' => $model,
            ]);
        }
    }

    public function actionLoadmoredata($id)
    {
        $sql = 'SELECT * FROM properties WHERE id < '.$id.' and status=1 ORDER BY id DESC LIMIT 4';
        $result = Properties::findBySql($sql)->all();

        return $this->renderAjax('list_view', ['models' => $result]);
        // echo json_encode($json);
    }

    public function actionAverthouse()
    {
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('avert_house');
        } else {
            return $this->render('avert_house');
        }
    }

    public function actionHowuse()
    {
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('how_use');
        }
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if (!empty(Yii::$app->session['payerID']) && !empty(Yii::$app->user->id)) {
                $user = User::find()->where(['id' => Yii::$app->user->id])->one();
                $key = explode(',', $user->payer_code);
                if (!in_array(Yii::$app->session['payerID'], $key)) {
                    if (empty($user->payer_code)) {
                        $user->payer_code = Yii::$app->session['payerID'];
                    } else {
                        $user->payer_code = $user->payer_code.','.Yii::$app->session['payerID'];
                    }
                    $user->save();
                }
            }

            return $this->goBack();
        } else {
            return $this->renderAjax('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionForgetpw()
    {
        return $this->renderAjax('forgetpw');
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        $key = \Yii::$app->session['payerID'];
        Yii::$app->user->logout();
        \Yii::$app->session['payerID'] = $key;

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('contact', [
                'model' => $model,
            ]);
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionRegister()
    {
        $model = new Register();
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        if (Yii::$app->request->isAjax) {
            if ($model->load(Yii::$app->request->post())) {
                if ($model->save()) {
                    try {
                        $user = new User();
                        $user->type = $model->register_type;
                        $user->username = $model->email;
                        $user->password = date('sYm');
                        $user->code_verify = rand(00, 99).date('s');
                        $user->register_id = $model->id;
                        if (\Yii::$app->session['payerID']) {
                            $user->payer_code = ''.\Yii::$app->session['payerID'].'';
                        }
                        if ($user->save()) {
                            $transaction->commit();
                            $client = new Client();
                            $response = $client->createRequest()
                                ->setMethod('POST')
                                ->setUrl('https://start.engagespark.com/api/v1/messages/sms')
                                ->addHeaders(array(
                                    'content-type' => 'application/json',
                                    'authorization' => 'Token ecf889f6a53daad7ba397be36da9ac5283ecddf2',
                                ))
                                ->setContent('{
                                "organization_id":5449,
                                "recipient_type":"mobile_number",
                                "mobile_numbers":["'.$model->phone.'"],
                                "message":"'.Yii::t('models', 'Verify code: ').$user->code_verify.'",
                                "sender_id":"eRent"
                                }')->send();

                            return $this->renderAjax('register_view', ['model' => new User()]);
                        }
                        $transaction->commit();
                    } catch (\Exception $e) {
                        $transaction->rollBack();
                        throw $e;
                    } catch (\Throwable $e) {
                        $transaction->rollBack();
                        throw $e;
                    }
                }
            }
        }

        return $this->renderAjax('register', [
            'model' => $model,
        ]);
    }

    public function actionVerify()
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->post()) {
            $user = User::find()->where(['code_verify' => Yii::$app->request->post()['User']['code_verify']])->one();
            if (!empty($user)) {
                $user->status = 1;
                $user->save();
                $user->password = null;
                $user->scenario = 'setnewpassword';

                return $this->renderAjax('newpassword', ['model' => $user]);
            } else {
                return $this->renderAjax('register_view', ['model' => new User()]);
            }
        }
    }

    public function actionSetnewpassword($id)
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->post()) {
            $user = User::find()->where(['id' => $id])->one();
            $user->scenario = 'setnewpassword';
            if (!empty($user)) {
                $user->password = Yii::$app->request->post()['User']['comfirm_password'];
                $user->comfirm_password = Yii::$app->request->post()['User']['comfirm_password'];
                $user->save();

                return $this->renderAjax('re_success', ['model' => $user]);
            //return $this->renderAjax('newpassword', ['model' => $user]);
            } else {
                return $this->renderAjax('newpassword', ['model' => $user]);
            }
        }
    }

    public function actionListpush()
    {
        return $this->renderAjax('listpush');
    }

    public function actionLikeunlike($id, $likeunlike)
    {
        $model = Properties::findOne($id);
        $like = new LikeProperty();
        if ($likeunlike == 1) {
            $like->properties_id = $id;
            $like->user_id = Yii::$app->user->id;
            if($like->save())
            {
                $player_id = [$model->user->payer_code];
                $name = $like->user->register->first_name.'. ';
                $sms = $name.Yii::t('app', 'ຕິດ​ຕາມ​ ແລະ ​ສູນ​ໃຈ​ເຮຶອນ​ເຊົ່າ​ຂອ​ງ​ທ່ານ');
                AlertPackage::onesignalnotification($sms, $player_id);
            }else{
                return $this->renderAjax('modalerror',['model'=>$model]);
            }
            
        } else {
            LikeProperty::deleteAll(['user_id' => Yii::$app->user->id, 'properties_id' => $id]);
        }

        return $this->renderAjax('likeunlike', ['model' => $model]);
    }

    public function actionComment($id)
    {
        $model = Properties::findOne($id);
        return $this->renderAjax('modalerror',['comment'=>true,'model'=>$model]);
    }
    public function actionTimetable($id)
    {
        $model = Properties::findOne($id);
        return $this->renderAjax('modalerror',['timetable'=>true,'model'=>$model]);
    }

    public function actionViewhouse($id)
    {
        $model = Properties::findOne($id);
        return $this->renderAjax('viewhouse', [
            'model' =>$model
        ]);
    }
    public function actionGetmap()
    {
        $map=New Maps();
        $this->render('map',['model'=>$map]);
    }
   

}
