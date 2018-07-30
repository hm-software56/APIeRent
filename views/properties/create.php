<?php
use yii\helpers\Url;

$this->title = Yii::t('models', 'ເພີ່ມເຮືອນເຂົ້າ');
echo \Yii::$app->controller->renderPartial('../site/navdetail', ['title' => $this->title, 'url' => Url::to(['properties/list'])]);
?>
<br/>
<div class="properties-create">
    <?= $this->render('_form', [
        'model' => $model,
        'detail' => $detail,
    ]); ?>

</div>
