<?php

use app\models\PropertiesDetail;
use app\models\Photo;
use yii\helpers\Html;
use yii\helpers\Url;
$this->registerJsFile('@web/js/jquery1.9.js', ['position' => \yii\web\View::POS_HEAD]);
$this->registerJsFile('@web/js/loadmoredata.js', ['position' => \yii\web\View::POS_END]);

foreach ($models as $model) {
    $detail = PropertiesDetail::find()->where(['properties_id' => $model->id])->one();
    $photo = Photo::find()->where(['properties_detail_id' => $detail->id])->one(); ?>
        <div class="post-id" id="<?php echo $model->id; ?>">
            <div class="row">
                <div class='col-xs-12'>
                  <p>
                    <?php
                    if (!empty($photo)) {
                        echo Html::a('<img src="'.Yii::$app->urlManager->baseUrl.'/images/'.$photo->name.'" class="img-responsive " id="img_left"/>', '#', [
                            'onclick' => "
                                $.ajax({
                                type:'GET',
                                cache:false,
                                url:'".Url::to(['site/viewhouse', 'id' => $model->id])."',
                                'beforeSend': function(){
                                    document.getElementById('loader').style.display = 'block'; 
                                    },
                                success  : function(response) {
                                    $('#output').html(response);
                                    document.getElementById('loader').style.display = 'none'; 

                                },
                                error: function(XMLHttpRequest, textStatus, errorThrown) {
                                        $('#noconnection').modal('show');
                                        document.getElementById('loader').style.display = 'none'; 
                                    }
                                });return false;",
                        ]);
                        ?>
                    <?php
                    } else {
                         echo Html::a('<img src="'.Yii::$app->urlManager->baseUrl.'/images/nohouse.jpg" class="img-responsive " id="img_left"/>', '#', [
                            'onclick' => "
                                $.ajax({
                                type:'GET',
                                cache:false,
                                url:'".Url::to(['site/viewhouse', 'id' => $model->id])."',
                                'beforeSend': function(){
                                    document.getElementById('loader').style.display = 'block'; 
                                    },
                                success  : function(response) {
                                    $('#output').html(response);
                                    document.getElementById('loader').style.display = 'none'; 

                                },
                                error: function(XMLHttpRequest, textStatus, errorThrown) {
                                        $('#noconnection').modal('show');
                                        document.getElementById('loader').style.display = 'none'; 
                                    }
                                });return false;",
                        ]);
                        ?>
                        <?php
                    } ?>
                    
                    <?php echo nl2br($detail->details); ?>
                    <br/>
                    <?php
                    if ($detail->per == 'm') {
                        $m_y = Yii::t('models', 'ເດືອນ');
                    } else {
                        $m_y = Yii::t('models', 'ປີ');
                    } ?>
                    <?= Yii::t('models', 'ລະ​ຄາ:').'<span style="color:red">'.number_format($detail->fee, 2).'</span> '.Yii::t('app', 'ກີບ').'/'.$m_y; ?>
                    <br/>
                    <?php
                    if ($detail->per == 0) {
                        echo Yii::t('app', 'ສະ​ຖາ​ນະ');
                        echo " <span style='color:green'>".Yii::t('models', 'ຫວ່າງ').'</span>';
                    } else {
                        echo Yii::t('app', 'ສະ​ຖາ​ນະ');
                        echo " <span style='color:red'>".Yii::t('models', '​ບໍ່​ຫວ່າງ').'</span>';
                    } ?>
                    <div class="col-xs-2">
                        <span id="likeunlike<?=$model->id; ?>">
                        <?=$this->render('likeunlike', ['model' => $model]); ?>
                      </span>
                    </div>
                    <div class="col-xs-2">
                        <span id="comment<?=$model->id; ?>">
                            <?php echo Html::a('<i class="fa fa-commenting-o" aria-hidden="true"></i>', '#', [
                                    'onclick' => "
                                        $.ajax({
                                        type:'GET',
                                        cache:false,
                                        url:'".Url::to(['site/comment', 'id' => $model->id])."',
                                        'beforeSend': function(){
                                            document.getElementById('loader').style.display = 'none'; 
                                            },
                                        success  : function(response) {
                                            $('#comment".$model->id."').html(response);
                                            document.getElementById('loader').style.display = 'none'; 

                                        },
                                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                                                $('#noconnection').modal('show');
                                                document.getElementById('loader').style.display = 'none'; 
                                            }
                                        });return false;",
                                        'class' => 'btn btn-link',
                                ]);
                                ?>
                        </span>
                    </div>
                    <div class="col-xs-4">
                      <span id="timetable<?=$model->id; ?>">
                            <?php echo Html::a('<i class="fa fa-calendar" aria-hidden="true"></i>', '#', [
                                    'onclick' => "
                                        $.ajax({
                                        type:'GET',
                                        cache:false,
                                        url:'".Url::to(['site/timetable', 'id' => $model->id])."',
                                        'beforeSend': function(){
                                            document.getElementById('loader').style.display = 'none'; 
                                            },
                                        success  : function(response) {
                                            $('#timetable".$model->id."').html(response);
                                            document.getElementById('loader').style.display = 'none'; 

                                        },
                                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                                                $('#noconnection').modal('show');
                                                document.getElementById('loader').style.display = 'none'; 
                                            }
                                        });return false;",
                                        'class' => 'btn btn-link',
                                ]);
                                ?>
                        </span>
                    </div>
                  </p>
                    
                </div>
              </div>
           
            <hr style="margin-top:5px;">
        </div>
        <?php
}
      ?>