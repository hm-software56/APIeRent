<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\LikeProperty;

$like = LikeProperty::find()->where(['user_id' => Yii::$app->user->id, 'properties_id' => $model->id])->one();
if (!empty($like)) {
    $likeunlike = 0;
    $icon = '<i class="fa fa-heart" aria-hidden="true" style="color:red;"></i>';
} else {
    $likeunlike = 1;
    $icon = '<i class="fa fa-heart-o " aria-hidden="true" ></i>';
}
echo Html::a($icon, '#', [
    'onclick' => "
        $.ajax({
        type:'GET',
        cache:false,
        url:'".Url::to(['site/likeunlike', 'id' => $model->id, 'likeunlike' => $likeunlike])."',
        'beforeSend': function(){
            document.getElementById('loader').style.display = 'none'; 
            },
        success  : function(response) {
            $('#likeunlike".$model->id."').html(response);
            document.getElementById('loader').style.display = 'none'; 

        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
                $('#noconnection').modal('show');
                document.getElementById('loader').style.display = 'none'; 
            }
        });return false;",
        'class' => 'btn btn-link',
]);
