<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Register */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin(); ?>
<?=$form->field($model, 'lat')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'long')->textInput(['maxlength' => true]) ?>

<?php ActiveForm::end(); ?>
<?=\ibrarturi\latlngfinder\LatLngFinder::widget([
	'model' => $model,				// model object
	'latAttribute' => 'lat',		// Latitude attribute
	'lngAttribute' => 'long',		// Longitude attribute
	'zoomAttribute' => 'zoom',		// Zoom text attribute
	'mapCanvasId' => 'map',			// Map Canvas id
	'mapWidth' => 450,				// Map Canvas width
	'mapHeight' => 300,				// Map Canvas mapHeight
	'defaultLat' => -34.397,		// Default latitude for the map
	'defaultLng' =>150.644,			// Default Longitude for the map
	'defaultZoom' => 8, 			// Default zoom for the map
	'enableZoomField' => true,		// True: for assigning zoom values to the zoom field, False: Do not assign zoom value to the zoom field
]); ?>