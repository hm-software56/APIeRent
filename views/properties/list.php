<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\PropertiesDetail;
use app\models\Photo;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PropertiesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('models', 'My Properties');
echo \Yii::$app->controller->renderPartial('../site/navdetail', ['title' => $this->title]);
?>
<div class="properties-index">
    
    <div class="row" align="right" style="margin-top:-16px;">
        <?php
        echo Html::a('<i class="fa fa-plus"></i> '.Yii::t('app', 'ເພີ່ມເຮືອນເຂົ້າ'), '#', [
            'onclick' => "
                        $.ajax({
                       type:'GET',
                       cache:false,
                       url:'".Url::to(['properties/create'])."',
                       'beforeSend': function(){
                        document.getElementById('loader').style.display = 'block'; 
                        },
                       success  : function(response) {
                           $('#output').html(response);
                           document.getElementById('loader').style.display = 'none'; 
                           body.classList.remove('sidebar-open');
                       },
                       error: function(XMLHttpRequest, textStatus, errorThrown) {
                            $('#noconnection').modal('show');
                            document.getElementById('loader').style.display = 'none'; 
                            body.classList.remove('sidebar-open');
                        }
                       });return false;",
            'class' => 'btn btn-danger btn-sm bnt-fixed',
        ]); ?>
    </div>
    <br/><br/>
<div class="row">
    <?php
    foreach ($dataProvider->models as $model) {
        $detail = PropertiesDetail::find()->where(['properties_id' => $model->id])->one();
        $photo = Photo::find()->where(['properties_detail_id' => $detail->id])->one(); ?>
    <div class="col-xs-12">
        <div class="row line">
            <div class="col-xs-4">
                    <?php
                    if (!empty($photo)) {
                        ?>
                    <img src="<?=Yii::$app->urlManager->baseUrl.'/images/'.$photo->name; ?>"class="img-responsive thumbnail">
                    <?php
                    } else {
                        ?>
                        <img src="<?= Yii::$app->urlManager->baseUrl.'/images/nohouse.jpg'; ?>"class="img-responsive thumbnail">
                        <?php
                    } ?>
            </div>
            <div class="col-xs-8">
                <div class="caption">
                    <div style="font-size:14pt;"><b> <?=$model->propertiesType->name; ?></b></div>
                    <?=nl2br($detail->details); ?>
                    <br/>
                    <?php
                    if ($detail->per == 'm') {
                        $m_y = Yii::t('models', 'ເດືອນ');
                    } else {
                        $m_y = Yii::t('models', 'ປີ');
                    } ?>
                    <?=Yii::t('models', 'ລະ​ຄາ:').'<span style="color:red">'.number_format($detail->fee, 2).'</span> '.Yii::t('app', 'ກີບ').'/'.$m_y; ?>
                    <br/>
                    <?php
                    echo Yii::t('app', 'ສະ​ຖາ​ນະ');
        if ($detail->per == 0) {
            echo" <span style='color:green'>".Yii::t('models', 'ຫວ່າງ').'</span>';
        } else {
            echo " <span style='color:red'>".Yii::t('models', '​ບໍ່​ຫວ່າງ').'</span>';
        } ?>
                    <p align="right">
                        <?php
                        echo Html::a('<i class="fa fa-eye"></i> '.Yii::t('app', 'ລາຍ​ລະ​ອຽດ'), '#', [
                            'onclick' => "
                        $.ajax({
                       type:'GET',
                       cache:false,
                       url:'".Url::to(['properties/view', 'id' => $model->id])."',
                       'beforeSend': function(){
                        document.getElementById('loader').style.display = 'block'; 
                        },
                       success  : function(response) {
                           $('#output').html(response);
                           document.getElementById('loader').style.display = 'none'; 
                           body.classList.remove('sidebar-open');
                       },
                       error: function(XMLHttpRequest, textStatus, errorThrown) {
                            $('#noconnection').modal('show');
                            document.getElementById('loader').style.display = 'none'; 
                            body.classList.remove('sidebar-open');
                        }
                       });return false;",
                            'class' => 'btn btn-primary btn-sm',
                        ]); ?>
                    
                    </p>
                </div>
            </div>
        </div>
    </div>
    <?php
    }
    ?>
</div>
</div>
