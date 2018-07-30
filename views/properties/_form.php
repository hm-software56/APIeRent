<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Packages;
use kartik\file\FileInput;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\helpers\Url;
use app\models\PropertiesType;
use yii\widgets\MaskedInput;
use app\models\Photo;

?>

<div class="row properties-form">
    <div class="col-xs-12">
        <?php $form = ActiveForm::begin([
            'id' => 'property_form',
            'enableClientValidation' => true,
        ]); ?>
        <?php 
        echo $form->field($model, 'properties_type_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(PropertiesType::find()->all(), 'id', 'name'),
            'language' => 'en',
            'hideSearch' => true,
            'options' => ['placeholder' => Yii::t('models', 'properties')],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ]);
        ?>
        <?=$form->field($detail, 'details')->textarea(['rows' => 8]); ?>
        <div class="row">
            <div class="col-xs-6">
            <?php
            echo $form->field($detail, 'fee')->widget(MaskedInput::className(), [
                'options' => ['class' => 'form-control'],
                'mask' => '9',
                'clientOptions' => ['repeat' => 10, 'greedy' => false],
            ]);
            ?>
            </div>
            <div class="col-xs-6">
                <?php
                $data = array('m' => Yii::t('models', 'ເດືອນ'), 'y' => Yii::t('models', 'ປີ'));
                echo $form->field($detail, 'per')->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'hideSearch' => true,
                    'options' => ['placeholder' => Yii::t('models', 'properties')],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]);
                ?>
            </div>
        </div>
        <?php
        echo FileInput::widget([
            'name' => 'files[]',
            'language' => 'en',
            'options' => ['multiple' => true, 'accept' => 'image/*'],
            'pluginOptions' => [
                'showPreview' => true,
                'showRemove' => false,
                'showUpload' => true,
                'browseClass' => 'btn btn-success',
                'uploadClass' => 'btn btn-primary',
                'removeClass' => 'btn btn-danger',
                'removeIcon' => '<i class="glyphicon glyphicon-trash"></i>',
                'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                'browseLabel' => \Yii::t('app', 'ເລືອກ​ໄຟ​ຣ'),
                'uploadLabel' => \Yii::t('app', 'ອັບ​ໂຫຼດ​ໄຟ​ຣ'),
                'removeLabel' => \Yii::t('app', 'ລຶບ​ໄຟ​ຣ'),

                'previewFileType' => 'any',
                'overwriteInitial' => false,
                'initialPreviewShowDelete' => true,
                'showPreview ' => false,
                'maxFileCount' => 5,
                'uploadUrl' => \yii\helpers\Url::to(['/properties/uploadfile']),
                'initialPreview' => Photo::PreviewfiledocURL($detail->id),
                'initialPreviewConfig' => Photo::Previewfiledocdelete($detail->id),
            ],
        ]);
        ?>
        
        <?= $form->field($model, 'longtitude')->textInput(); ?>
        <?= $form->field($model, 'lattitude')->textInput(); ?>
        <?php
        if (empty($model->id)) {
            ?>
        <label>Choose Pagckage</label>
        <div class="funkyradio">
            <?php
            $packages = Packages::find()->where(['status' => 1])->all();
            foreach ($packages as $package) {
                ?>
            <div class="funkyradio-primary">
                <input type="radio" name="packages_id" id="<?=$package->id; ?>" value="<?= $package->id; ?>" />
                <label for="<?= $package->id; ?>"><?=$package->name; ?> <?=Yii::t('models', 'Price:'); ?> <?=number_format($package->fee, 2); ?> <?= Yii::t('models', 'Kip'); ?></label>
            </div>
            <?php
            } ?>
        </div>
        <?= $form->field($model, 'date_start')->widget(\andrew72ru\datepicker\DatePicker::className(), [
            'options' => [], // Html tag options
            'pluginOptions' => [
                'date-start-view' => 'day',
                'date-format' => 'yyyy-mm-dd',
                'date' => \Yii::$app->formatter->asDate(time(), 'MM-dd-yy'),
            ],
        ])->label(Yii::t('models', 'Date Publish')); ?>
        <?php
        }
        ?>
        <div class="form-group">
            <?php 
            if (!empty($model->id)) {
                AjaxSubmitButton::begin([
                    'label' => Yii::t('models', 'ບັນ​ທື​ກ'),
                    'useWithActiveForm' => 'property_form',
                    'icon' => '<i class="fa fa-registered"></i>',
                    'ajaxOptions' => [
                        'type' => 'POST',
                        'url' => Url::to(['properties/update', 'id' => $model->id]),
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
            } else {
                AjaxSubmitButton::begin([
                    'label' => Yii::t('models', 'ບັນ​ທື​ກ'),
                    'useWithActiveForm' => 'property_form',
                    'icon' => '<i class="fa fa-registered"></i>',
                    'ajaxOptions' => [
                        'type' => 'POST',
                        'url' => Url::to(['properties/create']),
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
            }
            ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
