<?php
use  yii\helpers\Html;
use yii\helpers\Url;
use app\models\AlertPackage;
use app\models\PropertiesDetail;
use app\models\Photo;
use yii\helpers\StringHelper;

$this->title = Yii::t('models', 'ລາຍ​ການ​ແຈ້ງ​ຕືນ');

echo \Yii::$app->controller->renderPartial('navdetail', ['title' => $this->title]);
$alertpackages = AlertPackage::find()->where(['status' => 0, 'user_id' => \Yii::$app->user->id])->orderBy(['id' => SORT_DESC])->groupBy(['properties_id'])->all();
?>
<div class="card">
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active">
      <a href="#home" aria-controls="home" role="tab" data-toggle="tab">ແພັກເກັດໝົດ
      <span class="label label-danger">
        <?=count($alertpackages); ?>
      </span>
      </a>
  </li>
    <li  role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Messages</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="home">
      <div class="row">
    <?php
    foreach ($alertpackages as $alertpackage) {
        $detail = PropertiesDetail::find()->where(['properties_id' => $alertpackage->properties_id])->one();
        $photo = Photo::find()->where(['properties_detail_id' => $detail->id])->one(); ?>
    <div class="col-xs-12">
        <div class="row line">
            <div class="col-xs-4">
              <?php
              if (!empty($photo)) {
                  ?>
              <img src="<?= Yii::$app->urlManager->baseUrl.'/images/'.$photo->name; ?>"class="img-responsive thumbnail">
              <?php
              } else {
                  ?>
                  <img src="<?= Yii::$app->urlManager->baseUrl.'/images/nohouse.jpg'; ?>"class="img-responsive thumbnail">
                  <?php
              } ?>
            </div>
              <div class="col-xs-8">
                  <div class="caption">
                    <?=$alertpackage->date_sms; ?>
                      <div><b> <?= $alertpackage->properties->propertiesType->name; ?></b></div>
                      <?php
                      echo StringHelper::truncateWords($detail->details, 5, $suffix = '...', $asHtml = true); ?>
                       <br/>
                      <?php
                      echo Yii::t('app', 'ສະ​ຖາ​ນະ');
        if ($detail->per == 0) {
            echo " <span style='color:green'>".Yii::t('models', 'ຫວ່າງ').'</span>';
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
                        url:'".Url::to(['properties/viewpackage', 'id' => $alertpackage->properties_id])."',
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
    <div role="tabpanel" class="tab-pane" id="messages">
      Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
      It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently
    </div>
    
</div>