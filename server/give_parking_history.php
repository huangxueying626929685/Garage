<?php require_once('Connections/mygarage.php'); ?>
<?php

header("Content-Type:text/html;charset=utf8");
mysql_select_db("garage",$mygarage);
mysql_query("SET NAMES utf8",$mygarage);

$get_id = $_POST['username'];
//$get_id = 18795968928;
//$get_id = "admin";

$sql_1 = mysql_query("SELECT * FROM parking_history where username = '$get_id' ");
$result1 = mysql_fetch_assoc($sql_1);
if(!empty($result1)){
    $back['status'] = '1';
    $back['id'] = $result1['id'];
    $back['car_num'] = $result1['car_num'];
    $back['garage_num'] = $result1['garage_num'];
    $back['cp_num'] = $result1['cp_num'];
    $back['start_time'] = $result1['start_time'];
    $back['leave_time'] = $result1['leave_time'];
    $back['money'] = $result1['money'];
}else{
    $back['status'] = '-1';
}
echo json_encode($back, JSON_UNESCAPED_UNICODE);
?>