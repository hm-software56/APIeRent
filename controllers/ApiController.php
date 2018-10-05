<?php

namespace app\controllers;

use app\models\AlertPackage;
use app\models\Comments;
use app\models\LikeProperty;
use app\models\Packages;
use app\models\Photo;
use app\models\Properties;
use app\models\PropertiesDetail;
use app\models\PropertiesType;
use app\models\Register;
use app\models\User;
use Imagine\Image\Box;
use Yii;
use yii\httpclient\Client;
use yii\imagine\Image;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\db\Expression;
use app\models\Currency;
use app\models\AnswerComments;
use app\models\Maps;
use app\models\Province;
use app\models\District;

class ApiController extends \yii\web\Controller
{

    public function actionIndex()
    {
        exit;
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (isset($_POST)) {
            $user = User::find()->Where(['username' => $_POST['email'], 'password' => $_POST['password'], 'status' => 1])->one();
            if (empty($user)) {
                $phone = substr($_POST['email'], -8, 8);
                $register = Register::find()->Where('phone like "%' . $phone . '"')->one();
                if (!empty($register)) {
                    $user = User::find()->Where(['register_id' => $register->id, 'password' => $_POST['password'], 'status' => 1])->one();
                }
            }
            if (!empty($user)) {

                $token = array('first_name' => ucfirst($user->register->first_name),'photo_profile'=>$user->register->photo,'photo_bg'=>$user->register->photo_bg);
                $js = array_merge($user->attributes, $token);
                return json_encode($js);
                //return $user;
            } else {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                $token = array('error' => 'Invalid User name or Password');
                return $token;
            }
        }
    }
    public function actionTest()
    {
        $client = new Client();
        $response = $client->createRequest($content = null, $headers = [])
            ->setMethod('POST')
            ->setUrl('https://start.engagespark.com/api/v1/messages/sms')
            ->addHeaders([
                'content-type' => 'application/json',
                'authorization' => 'Token ecf889f6a53daad7ba397be36da9ac5283ecddf2',
            ])
            ->setContent('{
            "organization_id":5449,
            "recipient_type":"mobile_number",
            "mobile_numbers":["8562055045770"],
            "message":"Test",
            "sender_id":"eRent"
            }')->send();
        echo $response->content;
        exit;
    }
    public function actionRegister()
    {
        //\Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new Register();
        if (isset($_POST)) {
            $model->first_name = $_POST['first_name'];
            $model->last_name = $_POST['last_name'];
            $model->email = $_POST['email'];
            $model->phone = $_POST['phone'];
            $model->address = $_POST['address'];
            $model->register_type = "Landlord";
            if ($model->save()) {
                $user = new User();
                $user->type = $model->register_type;
                $user->username = $model->email;
                $user->password = date('sYm');
                $user->code_verify = rand(00, 99) . date('s');
                $user->register_id = $model->id;
                if (\Yii::$app->session['payerID']) {
                    $user->payer_code = '' . \Yii::$app->session['payerID'] . '';
                }
                if ($user->save()) {
                    $client = new Client();
                    $response = $client->createRequest()
                        ->setMethod('POST')
                        ->setUrl('https://start.engagespark.com/api/v1/messages/sms')
                        ->addHeaders([
                            'content-type' => 'application/json',
                            'authorization' => 'Token ecf889f6a53daad7ba397be36da9ac5283ecddf2',
                        ])
                        ->setContent('{
                        "organization_id":5449,
                        "recipient_type":"mobile_number",
                        "mobile_numbers":["' . $model->phone . '"],
                        "message":"' . \Yii::t('models', 'Verify code: ') . $user->code_verify . '",
                        "sender_id":"eRent"
                        }')->send();
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    return $user;
                } else {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    $token = array('error' => 'Error save Data user');
                    return $token;
                }

            } else {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                //$token=array('error'=>'Error Save Data Register');
                return $model->getErrors();
            }
        } else {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $token = array('error' => 'Invalid Data register');
            return $token;
        }

    }

    public function actionVerifycode()
    {
        if (isset($_POST)) {
            $user = User::find()->Where(['id' => $_POST['registerid'], 'code_verify' => $_POST['codeverify']])->one();
            if (!empty($user)) {
                $user->status = 1;
                $user->save();
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return $user;
            } else {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                $token = array('error' => '​ລະ​ຫັດ​ຢັ້ງ​ຢືນ​ບໍ​ຖືກ');
                return $token;
            }

        }
    }
    public function actionNewpassword()
    {
        if (isset($_POST)) {
            $user = User::find()->Where(['id' => $_POST['registerid']])->one();
            if (!empty($user)) {
                $user->password = $_POST['password'];
                $user->save();
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return $user;
            } else {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                $token = array('error' => '​ລະ​ຫັດ​ຢັ້ງ​ຢືນ​ບໍ​ຖືກ');
                return $token;
            }

        }
    }
    public function actionListhouse()
    {
        $model = Properties::find()
            ->select('properties.id ,
        properties.properties_type_id,
        pd.details,
        pd.id as did,
        pd.status as dstatus,
        pd.fee,
        pd.per,
        type.name as type_name,
        photo.name as photo_name,
        currency.name as currency_name
        ')
            ->joinWith(['propertiesDetails as pd',
             'propertiesType as type', 
             'propertiesDetails.photos as photo',
             'propertiesDetails.currency as currency'
             ])
            ->asArray()
            ->orderBy('id DESC')
            ->all();

        \Yii::$app->response->format = Response::FORMAT_JSON;
        $row = ['rows' => $model];
        return $row;
    }

    public function actionDetailhouse($id)
    {
        $model = Properties::find()
            ->select('properties.* ,
        pd.details,
        pd.id as did,
        pd.status as dstatus,
        pd.fee,
        pd.per,
        type.name as type_name,
        photo.name as photo_name,
        currency.name as currency_name
        ')
            ->joinWith(['propertiesDetails as pd',
             'propertiesType as type', 
             'propertiesDetails.photos as photo',
             'propertiesDetails.currency as currency',
             ])
            ->where(['properties.id' => $id])
            ->asArray()
            ->all();
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $row = ['rows' => $model];
        return $row;
        // return $row;
    }
    public function actionPhotos($did)
    {
        $photos = Photo::find()->select('photo.name as name')->where(['properties_detail_id' => $did])->asArray()->all();
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $row = ['photos' => $photos];
        return $row;
    }

    public function actionListhouseuser($id)
    {
        $model = Properties::find()
            ->select('properties.id ,
        properties.properties_type_id,
        pd.details,
        pd.id as did,
        pd.status as dstatus,
        pd.fee,
        pd.per,
        type.name as type_name,
        photo.name as photo_name,
        currency.name as currency_name
        ')
            ->joinWith(['propertiesDetails as pd',
                'propertiesType as type',
                'propertiesDetails.photos as photo',
                'propertiesDetails.currency as currency',
                'likeProperties as liked','comments'
                 ])
            ->where(['properties.user_id' => $id])
            ->asArray()->all();
        LikeProperty::updateAll(['status' => 0], ['user_id' => $id]);

        \Yii::$app->response->format = Response::FORMAT_JSON;
        $row = ['rows' => $model];
        return $row;
    }

    public function actionListpropertiestype($lang)
    {
        $model = PropertiesType::find()->localized($lang)->all();
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $row = ['rows' => $model];
        return $row;
    }


    public function actionListcurrency($lang)
    {
        $model = Currency::find()->localized($lang)->all();
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $row = ['rows' => $model];
        return $row;
    }

    public function actionListprovince($lang)
    {
        $model =Province::find()->localized($lang)->all();
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $row = ['rows' => $model];
        return $row;
    }

    public function actionListdistrictbyprovince($lang,$province_id)
    {
        $model =District::find()->localized($lang)->all();
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $row = ['rows' => $model];
        return $row;
    }

    public function actionListpackage()
    {
        $model = Packages::find()->all();
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $row = ['rows' => $model];
        return $row;
    }

    public function actionUplaodfile()
    {

        $uploads = UploadedFile::getInstancesByName("upfile");
        if (empty($uploads)) {
            return "Must upload at least 1 file in upfile form-data POST";
        }

        // $uploads now contains 1 or more UploadedFile instances
        $savedfiles = null;
        foreach ($uploads as $file) {
            $realFileName = rand() . date('Ymdhis') . time() . '.' . $file->extension;
            $path = \Yii::$app->basePath . '/web/images/' . $realFileName; //Generate your save file path here;
            if ($file->saveAs($path)) {
                $savedfiles = $realFileName;
                $imagine = Image::getImagine();
                $image = $imagine->open(\Yii::$app->basePath . '/web/images/' . $savedfiles);
                if(isset($_POST['name']) && ($_POST['name']=="profile_img" || $_POST['name']=="profileBg_img"))
                {
                    $image->save(\Yii::$app->basePath . '/web/images/small/' . $savedfiles, ['quality' => 60]);
                }else{
                    $image->resize(new Box(500, 300))->save(\Yii::$app->basePath . '/web/images/small/' . $savedfiles, ['quality' => 70]);
                }
                
            } else {
                $savedfiles = 'Error save file';
            } //Your uploaded file is saved, you can process it further from here
        }

        /*======== Use for update profile profile bg ========*/
        if(isset($_POST['edit']))
        {
            $user=User::find()->where(['id'=>$_POST['userid']])->one();
            if(isset($_POST['name']) && $_POST['name']=='profile_img')
            {
                Register::updateAll(['photo' =>$savedfiles], 'id='.$user->register_id.'');
            }elseif(isset($_POST['name']) && $_POST['name']=='profileBg_img')
            {
                Register::updateAll(['photo_bg' =>$savedfiles], 'id='.$user->register_id.'');
            }
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $savedfiles;

    }

    public function actionCreateproperties()
    {
        $model = new Properties();
        $propertiesdetail = new PropertiesDetail();
        if (isset($_POST)) {
            $model->packages_id = 1;
            $model->date_create = date('Y-m-d H:i:s');
            $model->user_id = $_POST['userID'];
            $propertiesdetail->details = $_POST['details'];
            $propertiesdetail->fee = $_POST['fee'];
            $model->date_start = date('Y-m-d');
            $model->user_id = $_POST['userID'];
            if ($_POST['long']!='null' && $_POST['lat']!='null') {
                $model->longtitude=$_POST['long'];
                $model->lattitude=$_POST['lat'];
            }
            if ($_POST['per'] == 'ເດືອນ') {
                $propertiesdetail->per = 'm';
            } else {
                $propertiesdetail->per = 'y';
            }
            $type = PropertiesType::find()->where(['name' => $_POST['propertye']])->one();
            if (!empty($type)) {
                $model->properties_type_id = $type->id;
            }

            $package = Packages::find()->where(['id' => 1])->one();
            if ($package->m_y == 'm') {
                $enddate = date('Y-m-d', strtotime('+' . $package->number . ' month', strtotime($model->date_start)));
            } else {
                $enddate = date('Y-m-d', strtotime('+' . $package->number . ' year', strtotime($model->date_start)));
            }
            $model->date_end = $enddate;
            $model->status = 1;
            if ($model->save()) {
                $currency = Currency::find()->where(['name' => $_POST['currency']])->one();
                if (!empty($currency)) {
                    $propertiesdetail->currency_id=$currency->id;
                }

                $propertiesdetail->properties_id = $model->id;
                if ($propertiesdetail->save() && !empty($_POST['photos'])) {
                    $re = str_replace("[", "", $_POST['photos']);
                    $re = str_replace("]", "", $re);
                    foreach (explode(",", $re) as $photo) {
                        $dphoto = new Photo();
                        $dphoto->properties_detail_id = $propertiesdetail->id;
                        $dphoto->name = preg_replace('/\s+/', '', $photo);
                        $dphoto->save();
                    }
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    return ['id' => $model->id, 'did' => $propertiesdetail->id];

                }
            }
        }

    }

    public function actionEditproperties($id)
    {
        $model = Properties::find()->where(['id' => $id])->one();
        $propertiesdetail = PropertiesDetail::find()->where(['properties_id' => $id])->one();
        if (isset($_POST)) {
            $model->packages_id = 1;
            //$model->date_create = date('Y-m-d H:i:s');
            //$model->user_id =$_POST['userID'];
            if ($_POST['long']!='null' && $_POST['lat']!='null') {
                $model->longtitude=$_POST['long'];
                $model->lattitude=$_POST['lat'];
            }
            $propertiesdetail->details = $_POST['details'];
            $propertiesdetail->fee = $_POST['fee'];
            //$model->date_start=date('Y-m-d');
            //$model->user_id=$_POST['userID'];
            if ($_POST['per'] == 'ເດືອນ') {
                $propertiesdetail->per = 'm';
            } else {
                $propertiesdetail->per = 'y';
            }
            $type = PropertiesType::find()->where(['name' => $_POST['propertye']])->one();
            if (!empty($type)) {
                $model->properties_type_id = $type->id;
            }
            if ($model->save()) {
                $currency = Currency::find()->where(['name' => $_POST['currency']])->one();
                if (!empty($currency)) {
                    $propertiesdetail->currency_id=$currency->id;
                }
                $propertiesdetail->properties_id = $model->id;
                if ($propertiesdetail->save() && !empty($_POST['photos'])) {
                    Photo::deleteAll(['properties_detail_id' => $propertiesdetail->id]);

                    $re = str_replace("[", "", $_POST['photos']);
                    $re = str_replace("]", "", $re);
                    foreach (explode(",", $re) as $photo) {
                        $dphoto = new Photo();
                        $dphoto->properties_detail_id = $propertiesdetail->id;
                        $dphoto->name = preg_replace('/\s+/', '', $photo);
                        $dphoto->save();
                    }
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    return ['id' =>''.$model->id.'', 'did' =>''.$propertiesdetail->id.''];

                }
            }
        }

    }
    public function actionPropertiesdelet($id)
    {
        $model = Properties::find()->where(['id' => $id])->one();
        $modeldetail = PropertiesDetail::find()->where(['properties_id' => $model->id])->one();
        Photo::deleteAll(['properties_detail_id' => $modeldetail->id]);
        $modeldetail->delete();
        $model->delete();

    }

    public function actionRenewpackage($id)
    {
        $model = Properties::find()->where(['id' => $id])->one();
        $modeldetail = PropertiesDetail::find()->where(['properties_id' => $model->id])->one();

        if (isset($_POST['packageID​'])) {
            $package = Packages::find()->where(['id' => (int) $_POST['packageID​']])->one();
            if ($package->m_y == 'm') {
                $enddate = date('Y-m-d', strtotime('+' . $package->number . ' month', strtotime(date('Y-m-d'))));
            } else {
                $enddate = date('Y-m-d', strtotime('+' . $package->number . ' year', strtotime(date('Y-m-d'))));
            }
            $date_post = date('Y-m-d', \strtotime($_POST['datestart']));
            $date_curent = date_create($date_post);
            $date_priod = date_create($model->date_end);
            $diff = date_diff($date_curent, $date_priod);
            if ($diff->format('%R') == '+') {
                $enddate = date('Y-m-d', strtotime($diff->format('%R%a') . ' days', strtotime($enddate)));
            }
            $model->date_start = $date_post;
            $model->date_end = $enddate;
            if ($model->save()) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['id' => $model->id, 'did' => $modeldetail->id];
            }
        }
    }

    public function actionUserpayer($id)
    {
        if (isset($_POST['payerID']) && !empty($_POST['payerID'])) {
            $model = User::find()->where(['id' => $id])->one();
            $model->payer_code = $_POST['payerID'];
            if ($model->save()) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return $model->id;
            } else {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return 'Errors Save PayerID';
            }

        }
    }

    public function actionFavorite($id, $userid, $like)
    {
        $model = Properties::findOne($id);
        $likep = new LikeProperty();
        if ($like == 1) {
            $likep->properties_id = $id;
            $likep->user_id = $userid;
            $liked = LikeProperty::find()->where(['properties_id' => $id, 'user_id' => $userid])->one();
            if ($liked == 0 && $likep->save()) {
                $player_id = [$model->user->payer_code];
                $name = $likep->user->register->first_name . '. ';
                $sms = $name . Yii::t('app', 'ຕິດ​ຕາມ​ ແລະ ​ກົດ Like ເຮືອນ​ທ່ານ');
                AlertPackage::onesignalnotification($sms, $player_id);
                $l = ['like' => 0];
            } else {
                $l = ['like' => 0];
            }
        } else {
            LikeProperty::deleteAll(['user_id' => $userid, 'properties_id' => $id]);
            $l = ['like' => 1];
        }
        $likcount = LikeProperty::find()->where(['properties_id' => $id])->count();
        $l = array_merge($l, ['nbcount' => $likcount]);
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $l;
    }

    public function actionLikecount($id, $userid)
    {
        $likcount = LikeProperty::find()->where(['properties_id' => $id])->count();
        $liked = LikeProperty::find()->where(['properties_id' => $id, 'user_id' => $userid])->one();
        if (!empty($liked)) {
            $l = ['like' => 0];
        } else {
            $l = ['like' => 1];
        }
        $l = array_merge($l, ['nbcount' => $likcount]);
        $l = array_merge($l, ['nbcount' => $likcount]);
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $l;
    }

    public function actionCountalert($userid)
    {
        $likcount = LikeProperty::find()->joinWith(['properties'])->where(['properties.user_id' => $userid, 'like_property.status' => 1])->count();
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $likcount;
    }

    public function actionCountlikebyone($id, $userid)
    {
        $likcount = LikeProperty::find()->where(['user_id' => $userid, 'properties_id' => $id])->count();
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $likcount;
    }

    public function actionAddcomments()
    {
        $comment = new Comments;
        if (isset($_POST['userID'])) {
            $comment->smg = $_POST['smg'];
            $comment->user_id = $_POST['userID'];
            $comment->properties_id = $_POST['houseID'];
            if ($comment->save()) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return $comment;
            }
        } else {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return 'Errors Add comment';
        }

    }
    public function actionAnwercomments()
    {
        $answercomment = new AnswerComments;
        if (isset($_POST['userID'])) {
            $answercomment->smg = $_POST['smg'];
            $answercomment->user_id = $_POST['userID'];
            $answercomment->comments_id=$_POST['idq'];
            if ($answercomment->save()) {
                $comment = Comments::find()
                    ->joinWith(['user','user.register','answerComments'])
                    ->where(['comments.properties_id' => $_POST['houseID']])
                    ->asArray()->orderBy('comments.id DESC')->all();
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return $comment;
            }
        } else {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return 'Errors Add comment';
        }

    }

    public function actionListcomments($houseID)
    {
        $comment = Comments::find()
        ->joinWith(['user','user.register','answerComments'])
        ->where(['comments.properties_id' => $houseID])->asArray()->orderBy('comments.id DESC')->all();
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $comment;
    }

    public function actionListanswers($idq)
    {
        $comment = Comments::find()
        ->joinWith(['user','user.register'])
        ->where(['answer_id' => $idq])->asArray()->orderBy('id DESC')->all();
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $comment;
    }

    public function actionCountcomments($houseID)
    {
        $comment = Comments::find()->where(['properties_id' => $houseID])->count();
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $comment;
    }

    public function actionShowprofile($id)
    {
        $user=User::find()->joinWith(['register'])->where(['user.id'=>$id])->asArray()->one();
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $user;
    }
    public function actionEditprofile()
    {
        if(isset($_POST['id']))
        {
        $user=User::find()->where(['id'=>$_POST['id']])->one();
        $register=Register::find()->where(['id'=>$user->register_id])->one();
        $register->first_name=$_POST['first_name'];
        $register->last_name=$_POST['last_name'];
        $register->email=$_POST['email'];
        $register->phone=$_POST['phone'];
        $register->address=$_POST['address'];
        $register->photo=$_POST['photo'];
        $register->photo_bg=$_POST['photo_bg'];
        $register->save();
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $register;
    }

    public function actionGetmap($user_id)
    {
        $this->layout='main_map';
        $map=new Maps;
        if($map->load(Yii::$app->request->post()))
        {
            $map->user_id=$user_id;
            $map->save();
        }
        return $this->render('map',['model'=>$map]);
    }
    public function actionGetlatlong($user_id)
    {
        $map=Maps::find()->where(['user_id'=>$user_id])->orderBy('id DESC')->one();
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $map;
    }
    public function actionViewmap($id)
    {
        $this->layout='main_map';
        $model = Properties::find()->where(['id' => $id])->one();
        return $this->render('viewmap',['model'=>$model]);
    }
}
