<?php require_once('Connections/mygarage.php'); ?>
<?php

header("Content-Type:text/html;charset=utf8");
//$get_id = $_POST['username'];
$get_id = 18795968928;
mysql_select_db("garage",$mygarage);
mysql_query("SET NAMES utf8",$mygarage);
$sql_1 = mysql_query("SELECT * FROM parking_info where username = '$get_id' ");
$result1 = mysql_fetch_assoc($sql_1);
if(!empty($result1)) {
    $start_time = $result1['start_time'];
    //获取当前时间作为结束时间
    date_default_timezone_set('PRC'); //设置中国时区
    $leave_time = date("Y-m-d H:i");
    //计算停车时间
    $date=floor((strtotime($leave_time)-strtotime($start_time))/86400);
    $hour=floor((strtotime($leave_time)-strtotime($start_time))/86400/3600);
    $minute=floor((strtotime($leave_time)-strtotime($start_time))/86400/60);
    echo $date;
    //取得单价
    $garage_num = $result1['garage_num'];
    $sql_2 = mysql_query("SELECT * FROM garage_info where id = $garage_num");
    $result2 = mysql_fetch_assoc($sql_2);
    $price_per_hour = $result2['price_per_hour'];
    //计算停车费用
//    $money = ($minute/60) * $price_per_hour;
    $money = 0.01;

    //存入停车信息表
    if(!(mysql_query("update parking_info set leave_time = '$leave_time',money = '$money',pay_status = 0 where username = '$get_id'")))
    {
        die(mysql_error());
    }
    $back['status']="1";
    $back['parking_id'] = $result1['parking_id'];
    $back['address'] = $result2['address'];
    $back['price_per_hour'] = $price_per_hour;
    $back['start_time'] = $start_time;
    $back['leave_time'] = $leave_time;
    $back['money'] = $money;
    echo json_encode($back, JSON_UNESCAPED_UNICODE );//不会自动把中文编码
}else{
    $back['status']="-1";
    echo json_encode($back, JSON_UNESCAPED_UNICODE );//不会自动把中文编码
}

?>