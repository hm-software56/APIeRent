<?php
use yii\helpers\Html;

if (!isset($urledit) && !isset($urlremove)) {
    $urledit = '#';
    $urlremove = '#';
}

?>
<footer class="main-footer navbar-fixed-bottom">
  <div class="col-xs-6">
      <?php
        echo Html::a('<i class="fa fa-pencil-square-o fa-2x" aria-hidden="true"></i>', '#', [
            'onclick' => "
                        $.ajax({
                       type:'GET',
                       cache:false,
                       url:'".$urledit."',
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
      <?php
        echo Html::a('<i class="fa fa-trash fa-2x" aria-hidden="true"></i>', '#', [
            'onclick' => "
                        $.ajax({
                       type:'POST',
                       cache:false,
                       url:'".$urlremove."',
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
</footer>