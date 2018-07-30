<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\bootstrap\ActiveForm;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\helpers\Url;

$this->title = Yii::t('models', 'ປ້ອນ​ລະ​ຫັດ​ຢັ້ງ​ຢືນ');
echo \Yii::$app->controller->renderPartial('navdetail', ['title' => $this->title]);
?>
<div class="row">
    <div class="col-md-12">
        <div class="vmedle">
            <?php $form = ActiveForm::begin([
                'id' => 'verify_register',
                'layout' => 'horizontal',
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-lg-12\">{input}</div>\n<div class=\"col-lg-12\">{error}</div>",
                    'labelOptions' => ['class' => 'col-lg-12 control-label'],
                ],
            ]);

            ?>
            <div class="login-box-body">
                <div class="form-group has-feedback">
                    <div class="line_bottom"><?= Yii::t('models', 'ທ່ານ​ລົງ​ທະ​ບຽນ​ສຳ​ເລັດ​ແລ້ວ ແລະ​ ໄດ້​ສົງ​ລະ​ຫັດ​ຢັ້ງ​ຢືນ​ເຂົ້າ​ຫ​າ​ເບີ​ໂທ​ທ່ານ​ແລ້ວ'); ?></div>

                </div>
                <div class="form-group has-feedback">
                    <?= $form->field($model, 'code_verify')->textInput(['placeholder' => Yii::t('models', 'ປ້ອນ​ລະ​ຫັດ​ຢັ້ງ​ຢືນ')])->label(false); ?>   
                    <span class="glyphicon glyphicon-barcode form-control-feedback"></span>
                </div>
                
                <div class="row">
                <?php AjaxSubmitButton::begin([
                    'label' => Yii::t('models', 'ຢັ້ງ​ຢຶນ​ລະ​ຫັດ'),
                    'useWithActiveForm' => 'verify_register',
                    'icon' => '<i class="fa fa-registered"></i>',
                    'ajaxOptions' => [
                        'type' => 'POST',
                        'url' => Url::to(['site/verify']),
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
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
