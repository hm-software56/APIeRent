<?php
use yii\helpers\Url;

if (!isset($url)) {
    $url = Url::to(['site/index']);
}
?>
<div class="navbardetail">
  <?php
    echo yii\helpers\Html::a('<i class="fa fa-chevron-left" aria-hidden="true"></i>', '#', [
        'onclick' => "
                        $.ajax({
                       type:'GET',
                       cache:false,
                       url:'".$url."',
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
  <div class="navtile"><?=$title; ?></div>
</div>