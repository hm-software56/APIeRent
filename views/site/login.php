<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\bootstrap\ActiveForm;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\helpers\Url;

$this->title = Yii::t('models', 'ລອກ​ອີນ​ເຂົ້າ​ລະ​ບ​ົບ');
if (Yii::$app->controller->id == 'site') {
    echo \Yii::$app->controller->renderPartial('navdetail', ['title' => $this->title]);
} else {
    echo \Yii::$app->controller->renderPartial('../site/navdetail', ['title' => $this->title]);
}
?>
<div class="site-login vmedle">
    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-12\">{input}</div>\n<div class=\"col-lg-12\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-12 control-label'],
        ],
    ]);

    ?>
    <div class="login-box-body">
        <div class="form-group has-feedback">
            <div class="line_bottom"><?= Yii::t('app', 'ປ້ອນຊື່ເຂົ້າ​ລະ​ບົບ ແລະ ລະ​ຫັດ​ຜ່ານ'); ?></div>

        </div>
        <div class="form-group has-feedback">
            <?= $form->field($model, 'username')->textInput(['placeholder' => $model->getAttributeLabel('username')])->label(false); ?>
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <?= $form->field($model, 'password')->passwordInput(['placeholder' => $model->getAttributeLabel('password')])->label(false); ?>   
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        
        <div class="row">
            <?php AjaxSubmitButton::begin([
                'label' => Yii::t('models', 'ເຂົ້າ​ລະ​ບົບ'),
                'useWithActiveForm' => 'login-form',
                'icon' => 'fa fa-lock',
                'ajaxOptions' => [
                    'type' => 'POST',
                    'url' => Url::to(['site/login']),
                    'beforeSend' => new \yii\web\JsExpression('function(html){
                            document.getElementById("loader").style.display ="block";
                        }'),
                    'success' => new \yii\web\JsExpression('function(html){
                            $("#output").html(html);
                            document.getElementById("loader").style.display ="none"; 
                        }'),
                    'error' => new \yii\web\JsExpression('function(XMLHttpRequest, textStatus, errorThrown){
                            document.getElementById("loader").style.display ="none";
                        }'),
                ],
                'options' => ['class' => 'btn btn-primary btn-md col-md-12  col-xs-12', 'type' => 'submit'],
            ]);
            AjaxSubmitButton::end();
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6">
            <?php AjaxSubmitButton::begin([
                'label' => Yii::t('models', 'ລົງ​ທະ​ບຽນ​ລະ​ບົບ'),
               // 'useWithActiveForm' => 'login-form',
                'icon' => 'fa fa-registered',
                'ajaxOptions' => [
                    'type' => 'get',
                    'url' => Url::to(['site/register']),
                    'beforeSend' => new \yii\web\JsExpression('function(html){
                            document.getElementById("loader").style.display ="block";
                        }'),
                    'success' => new \yii\web\JsExpression('function(html){
                            $("#output").html(html);
                            document.getElementById("loader").style.display ="none"; 
                        }'),
                    'error' => new \yii\web\JsExpression('function(XMLHttpRequest, textStatus, errorThrown){
                            document.getElementById("loader").style.display ="none";
                        }'),
                ],
                'options' => ['class' => 'btn btn-link btn-md col-md-12  col-xs-12', 'type' => 'submit'],
            ]);
            AjaxSubmitButton::end();
            ?>
        </div>
        <div class="col-xs-6">
            <?php AjaxSubmitButton::begin([
                'label' => Yii::t('models', 'ລືມ​ລະ​ຫັດ​ຜ່ານ'),
                //'useWithActiveForm' => 'login-form',
                'icon' => 'fa fa-arrow-circle-o-right',
                'ajaxOptions' => [
                    'type' => 'GET',
                    'url' => Url::to(['site/forgetpw']),
                    'beforeSend' => new \yii\web\JsExpression('function(html){
                            document.getElementById("loader").style.display ="block";
                        }'),
                    'success' => new \yii\web\JsExpression('function(html){
                            $("#output").html(html);
                            document.getElementById("loader").style.display ="none"; 
                        }'),
                    'error' => new \yii\web\JsExpression('function(XMLHttpRequest, textStatus, errorThrown){
                            document.getElementById("loader").style.display ="none";
                        }'),
                ],
                'options' => ['class' => 'btn btn-link btn-md col-md-12  col-xs-12', 'type' => 'submit'],
            ]);
            AjaxSubmitButton::end();
            ?>
        </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>
