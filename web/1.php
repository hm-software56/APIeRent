<?php
if($_POST['email']==123 )
{
    $token=array('token'=>$_POST['email']);
    echo json_encode($token);
}else{
    $token=array('error'=>'Errors');
    echo json_encode($token);
}
?>