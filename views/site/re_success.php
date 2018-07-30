<?php
use yii\helpers\Url;

$this->title = Yii::t('models', 'ສຳ​ເລັດ​ການ​ລົງ​ທະ​ບຽນ');
echo \Yii::$app->controller->renderPartial('navdetail', ['title' => $this->title]);
?>
<div class="vmedle">
    <div class="col-md-12" align="center">
        <?=Yii::t('models', 'ທ່ານ​ສຳ​ເລັດ​ການ​ລົງ​ທະ​ບຽນ​ເພື່ອ​ເຂົ້າ​ຫ​າ​ລະ​ບົບ​ແລ້ວ'); ?>
        <br/><br/> 
        <?php
        echo yii\helpers\Html::a(Yii::t('app', 'ກົດ​ທີ່ນີ້​ລອກ​ອີນ​ເຂົ້າ​ລະ​ບ​ົບ>>'), '#', [
            'onclick' => "
                        $.ajax({
                       type:'GET',
                       cache:false,
                       url:'".Url::to(['site/login'])."',
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
    </div>
</div>