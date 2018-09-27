<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
 <?php $form = ActiveForm::begin([
                'enableClientValidation' => true,
            ]); ?>
<br/>
<?php
echo $form->field($model, 'search')->widget(\kalyabin\maplocation\SelectMapLocationWidget::className(), [
    'attributeLatitude' => 'lat',
    'attributeLongitude' => 'long',
    'googleMapApiKey' => 'AIzaSyAJBRZecjOv9GUiuFCbto_UPy1sUykYbQs',
    'draggable' => true,
])->label('ຄົ້ນ​ຫາ​ສະ​ຖານ​ທີ່');
?>
<div class="form-group">
    <?= Html::submitButton(Yii::t('models', 'ບັນ​ທືກ'), ['class' => 'btn btn-success btn btn-danger btn-md mr col-md-12  col-xs-12']) ?>
</div>
<?php ActiveForm::end(); ?>