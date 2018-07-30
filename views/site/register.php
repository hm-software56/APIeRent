<?php

use yii\widgets\ActiveForm;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\helpers\Url;
use kartik\select2\Select2;

$this->title = 'ລົງ​ທະ​ບຽນ';
/* @var $this yii\web\View */
/* @var $model app\models\Register */
/* @var $form yii\widgets\ActiveForm */
//$this->renderPartial('navdetail', ['title' => $this->title]);
echo \Yii::$app->controller->renderPartial('navdetail', ['title' => $this->title]);
?>

<br/>
<div class="row">
    <div class="col-md-12">
        <div class="register-form">
            <?php $form = ActiveForm::begin([
                'id' => 'register',
                'enableClientValidation' => true,
            ]); ?>
            <p align='center' style="font-size:26px"><?= Yii::t('app', 'ລົງ​ທະ​ບຽນ​ເພື່ອ​ເຂົ້າ​ລະ​ບົບ'); ?></p>
            <?php 
            echo $form->field($model, 'register_type')->widget(Select2::classname(), [
                'data' => ['Landlord' => Yii::t('models', 'ເຈົ້າ​ຂອງ'), 'Tenant' => Yii::t('models', 'ຜູ້ເຊົ່າເຮືອນ')],
                'language' => 'en',
                'hideSearch' => true,
                'options' => ['placeholder' => Yii::t('models', 'ເລືອກ​ປະ​ເພດ​')],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]);
            ?>
            <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]); ?>

            <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]); ?>

            <?= $form->field($model, 'email')->textInput(['maxlength' => true]); ?>

            <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'placeholder' => '8562055045770']); ?>

            <?= $form->field($model, 'address')->textarea(['rows' => 3]); ?>
            
            <div class="form-group">
                <?php AjaxSubmitButton::begin([
                    'label' => Yii::t('models', 'ລົງ​ທະ​ບຽນ'),
                    'useWithActiveForm' => 'register',
                    'icon' => 'fa fa-registered',
                    'ajaxOptions' => [
                        'type' => 'POST',
                        'url' => Url::to(['site/register']),
                        'beforeSend' => new \yii\web\JsExpression('function(html){
                            document.getElementById("loader").style.display ="block";
                        }'),
                        'success' => new \yii\web\JsExpression('function(html){
                            $("#output").html(html);
                            document.getElementById("loader").style.display ="none"; 
                        }'),
                        'error' => new \yii\web\JsExpression('function(XMLHttpRequest, textStatus, errorThrown){
                            $("#noconnection").modal("show");
                            document.getElementById("loader").style.display ="none";
                        }'),
                    ],
                    'options' => ['class' => 'btn btn-primary btn-md col-md-12  col-xs-12', 'type' => 'submit'],
                ]);
                AjaxSubmitButton::end();
                ?>
            </div>
            <?php ActiveForm::end(); ?>
            <div>
    </div>
</div>
