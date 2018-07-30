<?php
use yii\helpers\Url;

?>
<aside class="main-sidebar sidebar-fixed-top">

    <section class="sidebar">
        <ul class="sidebar-menu tree" data-widget="tree">
        <?php
        if (empty(Yii::$app->user->id)) {
            ?>
            <li>
            <?php
            echo yii\helpers\Html::a('<i class="fa fa-dashboard"></i> '.Yii::t('app', 'ຜົ​ນ​ທີ່​ໄດ້​ຮັບ'), '#', [
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
            ]); ?>
            </li>
            <li>
                <?php
                echo yii\helpers\Html::a('<i class="fa fa-dashboard"></i> '.Yii::t('app', 'ຕິດ​ຕໍ່'), '#', [
                    'onclick' => "
                        $.ajax({
                       type:'GET',
                       cache:false,
                       url:'".Url::to(['site/contact'])."',
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
                ]); ?>
            </li>
            <li>
                <?php
                echo yii\helpers\Html::a('<i class="fa fa-dashboard"></i> '.Yii::t('app', 'ໂຄ​ສະ​ນາ​ເຮືອນ​ເຊົ່າ'), '#', [
                    'onclick' => "
                        $.ajax({
                       type:'GET',
                       cache:false,
                       url:'".Url::to(['properties/list'])."',
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
                ]); ?>
            </li>
            <li>
                <?php
                echo yii\helpers\Html::a('<i class="fa fa-dashboard"></i> '.Yii::t('app', 'ການນຳ​ໃຊ້'), '#', [
                    'onclick' => "
                        $.ajax({
                       type:'GET',
                       cache:false,
                       url:'".Url::to(['site/howuse'])."',
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
                ]); ?>
            </li>
            <li>
                <?php
                echo yii\helpers\Html::a('<i class="fa fa-registered"></i> '.Yii::t('app', 'ລົງ​ທະ​ບຽນ'), '#', [
                    'onclick' => "
                        $.ajax({
                       type:'GET',
                       cache:false,
                       url:'".Url::to(['site/register'])."',
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
                ]); ?>
            </li>
            
            <?php
        }
            if (!empty(Yii::$app->user->id)) {
                ?>
            <li>
                <?php
                echo yii\helpers\Html::a('<i class="fa fa-dashboard"></i> '.Yii::t('app', 'My Properties'), '#', [
                    'onclick' => "
                        $.ajax({
                       type:'GET',
                       cache:false,
                       url:'".Url::to(['properties/list'])."',
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
                ]); ?>
            </li>
            <li>
                <?php
                    echo yii\helpers\Html::a('<i class="fa fa-power-off"></i> '.Yii::t('models', 'ອອກ​ຈາກ​ລະ​ບົບ'), Url::to(['site/logout']), ['data-method' => 'POST']); ?>
            </li>
            <?php
            }
            ?>
        </ul>
    </section>

</aside>
