<?php

namespace app\controllers;

use Yii;
use app\models\Properties;
use app\models\PropertiesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\PropertiesDetail;
use yii\web\UploadedFile;
use app\models\Packages;
use app\models\Photo;
use kartik\growl\Growl;
use app\models\AlertPackage;
use app\models\User;
use app\models\LoginForm;

/**
 * PropertiesController implements the CRUD actions for Properties model.
 */
class PropertiesController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Properties models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PropertiesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionList()
    {
        if (empty(Yii::$app->user->id)) {
            $model = new LoginForm();

            return $this->renderAjax('../site/login', [
                    'model' => $model,
                ]);
        } else {
            unset(Yii::$app->session['photo']);
            $searchModel = new PropertiesSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->renderAjax('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        }
    }

    /**
     * Displays a single Properties model.
     *
     * @param int $id
     *
     * @return mixed
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionViewpackage($id)
    {
        $model = $this->findModel($id);
        if (isset($_POST['packages_id'])) {
            $package = Packages::find()->where(['id' => (int) $_POST['packages_id']])->one();
            if ($package->m_y == 'm') {
                $enddate = date('Y-m-d', strtotime('+'.$package->number.' month', strtotime(date('Y-m-d'))));
            } else {
                $enddate = date('Y-m-d', strtotime('+'.$package->number.' year', strtotime(date('Y-m-d'))));
            }

            $date_curent = date_create(date('Y-m-d'));
            $date_priod = date_create($model->date_end);
            $diff = date_diff($date_curent, $date_priod);
            if ($diff->format('%R') == '+') {
                $enddate = date('Y-m-d', strtotime($diff->format('%R%a').' days', strtotime($enddate)));
            }
            $model->date_start = date('Y-m-d');
            $model->date_end = $enddate;
            if ($model->save()) {
                $useradmin = User::find()->where(['type' => 'Admin'])->all();
                $player_id = null;
                foreach ($useradmin as $useradmin) {
                    $player_id[] = $useradmin->payer_code;
                }
                $sms = Yii::t('app', 'ມີ​ລູກ​ຄ້າ​ໄດ້​ຕໍ່ແພ​ັກ​ເກັດ​ໂຄ​ສະ​ນາ​ເຮືອນ​ເຊົາ​ຕື່ມ');
                AlertPackage::onesignalnotification($sms, $player_id);
                AlertPackage::deleteAll(['properties_id' => $model->id]);
                echo Growl::widget([
                    'type' => Growl::TYPE_SUCCESS,
                    'title' => Yii::t('models', 'ສຳ​ເລັດ​ແລ້ວ'),
                    'icon' => 'glyphicon glyphicon-ok-sign',
                    'body' => "<div align='center'>".Yii::t('models', 'ທ່ານ​ໄດ້​ຕໍ່ແພັກ​ເກັດ​ນີ້​ສຳ​ເລັດ​ແລ້ວ<br/>ຂໍ​ຂອບ​ໃຈ').'</div>',
                    'showSeparator' => true,
                    'delay' => 0,
                    'pluginOptions' => [
                        'showProgressbar' => true,
                        'placement' => [
                            'from' => 'top',
                            'align' => 'center',
                        ],
                    ],
                ]);
            }
        }

        return $this->renderAjax('viewpackage', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Properties model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Properties();
        $propertiesdetail = new PropertiesDetail();
        if ($model->load(Yii::$app->request->post()) && $propertiesdetail->load(Yii::$app->request->post())) {
            $model->packages_id = $_POST['packages_id'];
            $model->date_create = date('Y-m-d H:i:s');
            $model->user_id = Yii::$app->user->id;
            $package = Packages::find()->where(['id' => $model->packages_id])->one();
            if ($package->m_y == 'm') {
                $enddate = date('Y-m-d', strtotime('+'.$package->number.' month', strtotime($model->date_start)));
            } else {
                $enddate = date('Y-m-d', strtotime('+'.$package->number.' year', strtotime($model->date_start)));
            }
            $model->date_end = $enddate;
            $model->status = 1;
            if ($model->save()) {
                $propertiesdetail->properties_id = $model->id;
                if ($propertiesdetail->save() && !empty(\Yii::$app->session['photo'])) {
                    foreach (\Yii::$app->session['photo'] as $photo) {
                        $dphoto = new Photo();
                        $dphoto->properties_detail_id = $propertiesdetail->id;
                        $dphoto->name = $photo;
                        $dphoto->save();
                    }

                    $searchModel = new PropertiesSearch();
                    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

                    return $this->renderAjax('list', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                    ]);
                }
            }

            return $this->renderAjax('create', [
                'model' => $model, 'detail' => $propertiesdetail,
            ]);
        }
        unset(Yii::$app->session['photo']);

        return $this->renderAjax('create', [
            'model' => $model, 'detail' => $propertiesdetail,
        ]);
    }

    /**
     * Updates an existing Properties model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $id
     *
     * @return mixed
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $propertiesdetail = PropertiesDetail::find()->where(['properties_id' => $id])->one();
        if ($model->load(Yii::$app->request->post()) && $model->save() && $propertiesdetail->load(Yii::$app->request->post())) {
            if ($propertiesdetail->save()) {
                if (!empty(\Yii::$app->session['photo'])) {
                    foreach (\Yii::$app->session['photo'] as $photo) {
                        $dphoto = new Photo();
                        $dphoto->properties_detail_id = $propertiesdetail->id;
                        $dphoto->name = $photo;
                        $dphoto->save();
                    }
                }

                $searchModel = new PropertiesSearch();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

                return $this->renderAjax('list', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }
        }
        unset(Yii::$app->session['photo']);

        return $this->renderAjax('update', [
            'model' => $model, 'detail' => $propertiesdetail,
        ]);
    }

    /**
     * Deletes an existing Properties model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id
     *
     * @return mixed
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = Properties::find()->where(['id' => $id])->one();
        $modeldetail = PropertiesDetail::find()->where(['properties_id' => $model->id])->one();
        Photo::deleteAll(['properties_detail_id' => $modeldetail->id]);
        $modeldetail->delete();
        $model->delete();

        $searchModel = new PropertiesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->renderAjax('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the Properties model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     *
     * @return Properties the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Properties::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('models', 'The requested page does not exist.'));
    }

    public function actionUploadfile()
    {
        if (!empty(\Yii::$app->session['photo'])) {
            $photo = \Yii::$app->session['photo'];
        } else {
            $photo = [];
        }
        if (Yii::$app->request->isPost) {
            $images = UploadedFile::getInstancesByName('files');
            if ($images) {
                foreach ($images as $file) {
                    $realFileName = rand().date('Ymds').time().'.'.$file->extension;
                    $savePath = Yii::$app->basePath.'/web/images/'.$realFileName;
                    if ($file->saveAs($savePath)) {
                        $photo[] = $realFileName;
                        echo json_encode(['success' => 'true']);
                    } else {
                        echo json_encode(['success' => 'false', 'eror' => 'dddddd']);
                    }
                }
                \Yii::$app->session['photo'] = $photo;
            }
        }
    }

    public function actionDeletefile()
    {
        $model = Photo::findOne(Yii::$app->request->post('key'));
        if ($model !== null) {
            $savePath = \Yii::$app->basePath.'/web/images/'.$model->name;
            if ($model->delete()) {
                @unlink($savePath);
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
        } else {
            echo json_encode(['success' => false]);
        }
    }
}
