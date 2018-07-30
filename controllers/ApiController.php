<?php

namespace app\controllers;
use app\models\User;
use app\models\Register;
use yii\web\Response;
use yii\httpclient\Client;
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
}
