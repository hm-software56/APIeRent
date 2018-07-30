<?php
use yii\helpers\Url;

$this->title = Yii::t('models', 'ແກ້​ໄຂ​ເຮືອ​ນ');
echo \Yii::$app->controller->renderPartial('../site/navdetail', ['title' => $this->title, 'url' => Url::to(['properties/view', 'id' => $model->id])]);

?>
<div class="properties-update">

    <?= $this->render('_form', [
        'model' => $model,
        'detail' => $detail,
    ]); ?>

</div>
