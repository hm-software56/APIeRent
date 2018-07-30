<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

if (Yii::$app->controller->action->id === 'login') {
    /**
     * Do not use this code in your template. Remove it.
     * Instead, use the code  $this->layout = '//main-login'; in your controller.
     */
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
} else {
    if (class_exists('backend\assets\AppAsset')) {
        backend\assets\AppAsset::register($this);
    } else {
        app\assets\AppAsset::register($this);
    }

    dmstr\web\AdminLteAsset::register($this);

    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist'); ?>
    <?php $this->beginPage(); ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language; ?>">
    <head>
        <meta charset="<?= Yii::$app->charset; ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags(); ?>
        <title><?= Html::encode($this->title); ?></title>
        <?php $this->head(); ?>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
    <?php $this->beginBody(); ?>
    <div class="wrapper">
        <div id="loader">
            <span id="text-medel"><img src="<?= Yii::$app->urlManager->baseUrl; ?>/images/loading.gif" style="width:50px"></span>
        </div>
        <?= $this->render(
            'header.php',
            ['directoryAsset' => $directoryAsset]
        ); ?>

        <?= $this->render(
            'left.php',
            ['directoryAsset' => $directoryAsset]
        ); ?>

        <?= $this->render(
            'content.php',
            ['content' => $content, 'directoryAsset' => $directoryAsset]
        ); ?>
        <div id="noconnection" class="modal fade " role="dialog">
            <div class="vertical-alignment-helper">
                <div class="modal-dialog vertical-align-center">
                    <div class="modal-content vmodal">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title"><?=Yii::t('models', 'ການເຊື່ອມຕໍ່ລົ້ມເຫຼວ'); ?></h4>
                        </div>
                        <div class="modal-body">
                            <p><?= Yii::t('models', 'ກະລຸນາກວດສອບການເຊື່ອມຕໍ່ເນັດຂອງທ່ານ.!'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php $this->endBody(); ?>
    <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
<script>
  var OneSignal = window.OneSignal || [];
  OneSignal.push(function() {
    OneSignal.init({
      appId: "321b77aa-e8ff-4922-a91f-c6a9ed89bffe",
    });
  });
</script>
    </body>
    </html>
    <?php $this->endPage(); ?>
<?php
} ?>
