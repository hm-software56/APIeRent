<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Register */

$this->title = Yii::t('models', 'Create Register');
$this->params['breadcrumbs'][] = ['label' => Yii::t('models', 'Registers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="register-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
