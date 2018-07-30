<?php
use yii\helpers\Html;
use yii\helpers\Url;
if(isset($comment))
{
    echo Html::a('<i class="fa fa-commenting-o" aria-hidden="true"></i>', '#', [
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
}elseif(isset($timetable)){
    echo Html::a('<i class="fa fa-calendar" aria-hidden="true"></i>', '#', [
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
}else{
    echo $this->render('likeunlike', ['model' => $model]);
}                  
?>

<div id="errormodal" class="modal fade">
    <div class="modal-dialog ">
        <div class="modal-content vmodal">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
				<p align='center'>
                <?php
                    if(isset($comment))
                    {
                        ?>
                        <?=\Yii::t('models','Comming soon.....')?>
                        <?php
                    }else{
                ?>
                    <?=\Yii::t('models','ທ່ານຕ້ອງລອກອິນເຂົ້າລະບົບກ່ອນ....')?>
                    <br/>
                    <?php
                    echo Html::a(\Yii::t('app', 'ກົດທີນີ້ລອກອີນ>>'), '#', [
                        'onclick' => "
                                    $.ajax({
                                type:'GET',
                                cache:false,
                                url:'".Url::to(['site/login'])."',
                                'beforeSend': function(){
                                    document.getElementById('loader').style.display = 'block'; 
                                    $('#errormodal').modal('hide'); 
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
                        'class' => 'btn btn-link btn-sm ',
                    ]); ?>
                </p>
                <?php
                    }
                ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$("#errormodal").modal('show');
	});
</script>