<?php
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">

    
    <nav class="navbar navbar-fixed-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">
                <li>
                    <?php
                    echo yii\helpers\Html::a('<i class="fa fa-envelope-o"></i><span class="label label-danger">55</span>', '#', [
                        'onclick' => "
                            $.ajax({
                        type:'POST',
                        cache:false,
                        url:'".Url::to(['site/listpush'])."',
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
                        }
                        });return false;",
                        'class' => 'dropdown-toggle',
                        'data-toggle' => 'dropdown',
                    ]);
                    ?>
                </li>
                <li>
                   <?php
                    echo yii\helpers\Html::a('<i class="fa fa-user"></i>', '#', [
                        'onclick' => "
                        $.ajax({
                       type:'POST',
                       cache:false,
                       url:'".Url::to(['site/login'])."',
                       'beforeSend': function(){
                        document.getElementById('loader').style.display = 'block'; 
                        },
                       success  : function(response) {
                           $('#output').html(response);
                           document.getElementById('loader').style.display = 'none'; 
                           body.classList.remove('sidebar-open');
                       }
                       });return false;",
                    ]);
                    ?>
                </li>
            </ul>
        </div>
    </nav>
</header>
