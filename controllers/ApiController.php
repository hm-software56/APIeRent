<?php

namespace app\controllers;
use app\models\User;
use app\models\Register;
use yii\web\Response;
use yii\httpclient\Client;
use app\models\Properties;
use app\models\Photo;
use app\models\PropertiesType;
use yii\web\UploadedFile;
use app\models\Packages;
use app\models\PropertiesDetail;
use yii\imagine\Image;
use Imagine\Image\Box;

class ApiController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if(isset($_POST))
        {
            $user=User::find()->Where(['username'=>$_POST['email'],'password'=>$_POST['password'],'status'=>1])->one();
            if(!empty($user))
            {
                
                $token=array('first_name'=>ucfirst($user->register->first_name));
                $js=array_merge($user->attributes,$token);
               return json_encode($js);
               //return $user;
            }else{
                \Yii::$app->response->format = Response::FORMAT_JSON;
                $token=array('error'=>'Invalid User name or Password');
               return $token;
            }
        }
    }
    
    public function actionRegister(){
        //\Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new Register();
        if(isset($_POST))
        {
            $model->first_name=$_POST['first_name'];
            $model->last_name=$_POST['last_name'];
            $model->email=$_POST['email'];
            $model->phone=$_POST['phone'];
            $model->address=$_POST['address'];
            $model->register_type="Landlord";
            if($model->save())
            {
                $user = new User();
                $user->type = $model->register_type;
                $user->username = $model->email;
                $user->password = date('sYm');
                $user->code_verify = rand(00, 99).date('s');
                $user->register_id = $model->id;
                if (\Yii::$app->session['payerID']) {
                    $user->payer_code = ''.\Yii::$app->session['payerID'].'';
                }
                if($user->save())
                {
                   /* $client = new Client();
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
                        }')->send();*/

                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    return $user;
                }else{
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    $token=array('error'=>'Error save Data user');
                    return $token;
                }
                
            }else{
                \Yii::$app->response->format = Response::FORMAT_JSON;
                //$token=array('error'=>'Error Save Data Register');
               return $model->getErrors();
            }
        }else{
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $token=array('error'=>'Invalid Data register');
            return $token;
        }
        
    }

    public function actionVerifycode()
    {
        if(isset($_POST))
        {
            $user=User::find()->Where(['id'=>$_POST['registerid'],'code_verify'=>$_POST['codeverify']])->one();
            if(!empty($user))
            {
                $user->status=1;
                $user->save();
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return $user;
            }else{
                \Yii::$app->response->format = Response::FORMAT_JSON;
                $token=array('error'=>'​ລະ​ຫັດ​ຢັ້ງ​ຢືນ​ບໍ​ຖືກ');
                return $token;  
            }
            
        }
    }
    public function actionNewpassword()
    {
        if(isset($_POST))
        {
            $user=User::find()->Where(['id'=>$_POST['registerid']])->one();
            if(!empty($user))
            {
                $user->password=$_POST['password'];
                $user->save();
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return $user;
            }else{
                \Yii::$app->response->format = Response::FORMAT_JSON;
                $token=array('error'=>'​ລະ​ຫັດ​ຢັ້ງ​ຢືນ​ບໍ​ຖືກ');
                return $token;  
            }
            
        }
    }
    public function actionListhouse()
    {
        $model=Properties::find()
        ->select('properties.id ,
        pd.details,
        pd.id as did,
        pd.status as dstatus,
        pd.fee,
        pd.per,
        type.name as type_name,
        photo.name as photo_name
        ')
        ->joinWith(['propertiesDetails as pd','propertiesType as type','propertiesDetails.photos as photo'])->asArray()->all();
        
       \Yii::$app->response->format = Response::FORMAT_JSON;
        $row=['rows'=>$model];
        return $row;
    }

    public function actionDetailhouse($id)
    {
        $model=Properties::find()
        ->select('properties.* ,
        pd.details,
        pd.id as did,
        pd.status as dstatus,
        pd.fee,
        pd.per,
        type.name as type_name,
        photo.name as photo_name
        ')
        ->joinWith(['propertiesDetails as pd','propertiesType as type','propertiesDetails.photos as photo'])
        ->where(['properties.id'=>$id])
        ->asArray()
        ->all();
       \Yii::$app->response->format = Response::FORMAT_JSON;
        $row=['rows'=>$model];
        return $row;
       // return $row;
    }
    public function actionPhotos($did)
    {
        $photos=Photo::find()->select('photo.name as name')->where(['properties_detail_id'=>$did])->asArray()->all();
       \Yii::$app->response->format = Response::FORMAT_JSON;
        $row=['photos'=>$photos];
        return $row;
    }

    public function actionListhouseuser($id)
    {
        $model=Properties::find()
        ->select('properties.id ,
        pd.details,
        pd.id as did,
        pd.status as dstatus,
        pd.fee,
        pd.per,
        type.name as type_name,
        photo.name as photo_name
        ')
        ->joinWith(['propertiesDetails as pd',
        'propertiesType as type',
        'propertiesDetails.photos as photo'])
        ->where(['user_id'=>$id])
        ->asArray()->all();
        
       \Yii::$app->response->format = Response::FORMAT_JSON;
        $row=['rows'=>$model];
        return $row;
    }

    public function actionListpropertiestype(){
        $model=PropertiesType::find()->all();
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $row=['rows'=>$model];
        return $row;
    }

    public function actionListpackage(){
        $model=Packages::find()->all();
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $row=['rows'=>$model];
        return $row;
    }

    public function actionUplaodfile(){

        $uploads = UploadedFile::getInstancesByName("upfile");
            if (empty($uploads)){
                return "Must upload at least 1 file in upfile form-data POST";
            }

            // $uploads now contains 1 or more UploadedFile instances
            $savedfiles =null;
            foreach ($uploads as $file){
                $realFileName = rand().date('Ymdhis').time().'.'.$file->extension;
                $path =\Yii::$app->basePath.'/web/images/'.$realFileName; //Generate your save file path here;
                if($file->saveAs($path)){
                    $savedfiles=$realFileName;
                    $imagine = Image::getImagine();
                    $image = $imagine->open(\Yii::$app->basePath.'/web/images/'.$savedfiles);
                    $image->resize(new Box(500, 300))->save(\Yii::$app->basePath.'/web/images/small/'.$savedfiles, ['quality' => 70]);
                exit;}else{
                    $savedfiles='Error save file';
                } //Your uploaded file is saved, you can process it further from here
            }
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return $savedfiles;

    }

    public function actionCreateproperties()
    {
    
        $model = new Properties();
        $propertiesdetail = new PropertiesDetail();
        if (isset($_POST)) {
            $model->packages_id =1;
            $model->date_create = date('Y-m-d H:i:s');
            $model->user_id =$_POST['userID'];
            $propertiesdetail->details=$_POST['details'];
            $propertiesdetail->fee=$_POST['fee'];
            $model->date_start=date('Y-m-d');
            $model->user_id=$_POST['userID'];
            if($_POST['per']=='ເດືອນ')
            {
                $propertiesdetail->per='m';
            }else{
                $propertiesdetail->per='y';
            }
            $type = PropertiesType::find()->where(['name' =>$_POST['propertye']])->one();
            if(!empty($type))
            {
                $model->properties_type_id=$type->id;
            }

            $package = Packages::find()->where(['id' =>1])->one();
            if ($package->m_y == 'm') {
                $enddate = date('Y-m-d', strtotime('+'.$package->number.' month', strtotime($model->date_start)));
            } else {
                $enddate = date('Y-m-d', strtotime('+'.$package->number.' year', strtotime($model->date_start)));
            }
            $model->date_end = $enddate;
            $model->status = 1;
            if ($model->save()) {
                $propertiesdetail->properties_id = $model->id;
                if ($propertiesdetail->save() && !empty($_POST['photos'])) {
                   
                   $re=str_replace("[","",$_POST['photos']);
                   $re=str_replace("]","",$re);
                    foreach (explode(",",$re) as $photo) {
                        $dphoto = new Photo();
                        $dphoto->properties_detail_id = $propertiesdetail->id;
                        $dphoto->name = preg_replace('/\s+/', '', $photo);
                        $dphoto->save();
                    }
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    return ['id'=>$model->id,'did'=>$propertiesdetail->id];
                }
            }
        }
        
    }

}
