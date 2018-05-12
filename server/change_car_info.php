<?php require_once('Connections/mygarage.php'); ?>
<?php
mysql_select_db("garage",$mygarage);

$get_id = $_POST['userid'];//客户端post过来的手机号
$get_type = $_POST['type'];
$get_num = $_POST['number'];
//$get_id = 18795968928;//客户端post过来的手机号
//$get_type = "中文";
//$get_num = 542543245;
$sql = mysql_query("SELECT * FROM member WHERE username='$get_id'");
$found_id = mysql_fetch_assoc($sql);

if(!empty($found_id)) {
//  echo($found_tel);
    if(mysql_query("UPDATE member SET car_type= '$get_type',car_num='$get_num'WHERE username = '$get_id'")){
        $back['status'] = "1";
        echo(json_encode($back));
    }
}
else{
    $back['status']="-1";
    echo(json_encode($back));
}
