<?php require_once('Connections/mygarage.php'); ?>
<?php
mysql_select_db("garage",$mygarage);

$get_id = $_POST['userid'];//客户端post过来的手机号
$get_pwd = $_POST['passwd'];
/*$get_id = 1;//客户端post过来的手机号
$get_pwd = 654321;*/
//$get_phone = 18795968928;//客户端post过来的手机号
$sql = mysql_query("SELECT * FROM member WHERE username=$get_id");
$found_id = mysql_fetch_assoc($sql);
if(!empty($found_id))
{
//  echo($found_tel);
    mysql_query("UPDATE member SET password= '$get_pwd' WHERE username = '$get_id'");
    $back['status']="1";
    echo(json_encode($back));
}
else{
    $back['status']="-1";
    echo(json_encode($back));
}

mysql_close();
?>