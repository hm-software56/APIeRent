<?php
if (\Yii::$app->session['payerID']) {
    // echo \Yii::$app->session['payerID'];
} else {
    // echo 'No';
}

?>
<br/>
<div id="output"><br/>
<div class="row">
    <div class="col-md-12" id="post-data">
       <?php
       echo  $this->render('list_view', ['models' => $model]);
        ?>

    </div>
</div>
<div class="ajax-load text-center" style="display:none">
    <p><img src="<?= Yii::$app->urlManager->baseUrl; ?>/images/loading.gif" style="width:20px"> <?= Yii::t('app', 'ກຳ​ລັງ​ໂຫລດ​...'); ?></p>
</div>
