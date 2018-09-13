<?php require_once('Connections/mygarage.php'); ?>
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/5
 * Time: 14:56
 */
header("Content-Type:text/html;charset=utf8");
$get_id = $_POST['username'];
//$get_id = 18036134694;
mysql_select_db("garage",$mygarage);
mysql_query("SET NAMES utf8",$mygarage);
$sql_1 = mysql_query("SELECT * FROM late_order where username = '$get_id' ");
$result1 = mysql_fetch_assoc($sql_1);
if(!empty($result1)) {
    $back['id'] = $result1['id'];
    $back['car_num'] = $result1['car_num'];
    $back['action_time'] = $result1['action_time'];
    $back['cancel_time'] = $result1['cancel_time'];
    $back['order_time'] = $result1['order_time'];
    $back['start_time'] = $result1['start_time'];
    $back['money'] = $result1['money'];
    echo json_encode($back,JSON_UNESCAPED_UNICODE);//不会自动把中文编码
}
?>