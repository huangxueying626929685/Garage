<?php require_once('Connections/mygarage.php'); ?>
<?php
mysql_select_db("garage",$mygarage);

$get_phone = $_POST['PhoneNumber'];//客户端post过来的手机号
//$get_phone = 18795968928;//客户端post过来的手机号
$sql = mysql_query("SELECT username FROM member WHERE username=$get_phone");
$found_tel = mysql_num_rows($sql);

if($found_tel)
{
//  echo($found_tel);
    $back['status']="-1";
    echo(json_encode($back));
}
else{
    $back['status']="1";
    echo(json_encode($back));
}

mysql_close();
?>