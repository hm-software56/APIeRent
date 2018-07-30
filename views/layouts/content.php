<?php
use dmstr\widgets\Alert;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="content-wrapper">
    <section class="content">
        <?= Alert::widget(); ?>
        <?= $content; ?>
    </section>
    
</div>

<footer class="main-footer">
    <div class="row" >
   <div class="col-xs-6">
      <?php
        echo Html::a('<i class="fa fa-home fa-2x" aria-hidden="true"></i>', '#', [
            'onclick' => "
                        $.ajax({
                       type:'GET',
                       cache:false,
                       url:'".Url::to(['site/index'])."',
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
            'style' => 'color:#fff !important;',
           // 'class' => 'btn btn-danger btn-sm bnt-fixed',
        ]); ?>
  </div>
  <div class="col-xs-6" align="right">
</div>
                    </div>
</footer>