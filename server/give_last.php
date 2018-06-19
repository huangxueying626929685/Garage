<?php require_once('Connections/mygarage.php'); ?>
<?php

header("Content-Type:text/html;charset=utf8");
$get_id = $_POST['username'];
//$get_id = 18795968928;
mysql_select_db("garage",$mygarage);
mysql_query("SET NAMES utf8",$mygarage);
$sql_1 = mysql_query("SELECT * FROM parking_info where username = '$get_id' ");
$result1 = mysql_fetch_assoc($sql_1);
if(!empty($result1)) {
    //取得单价
    $garage_num = $result1['garage_num'];
    $sql_2 = mysql_query("SELECT * FROM garage_info where id = $garage_num");
    $result2 = mysql_fetch_assoc($sql_2);
    $price_per_hour = $result2['price_per_hour'];
    //真实开始停车时间
    $start_time = $result1['start_time'];
    //用户进行预约操作的时间
    $action_time = $result1['action_time'];
    //用户预约时间
    $order_time = $result1['order_time'];
    //求用户从操作预约到进入车库的这段时间
    $order_minute = floor((strtotime($start_time)-strtotime($action_time))/86400/60);
    //求预约过程的费用
    $extra_cost = ($order_minute/60) * ($price_per_hour/2);



    //获取当前时间作为结束时间
    date_default_timezone_set('PRC'); //设置中国时区
    $leave_time = date("Y-m-d H:i");
    //计算停车时间
    $minute = floor((strtotime($leave_time)-strtotime($start_time))%86400/60);
//    echo $date;

    //计算正常停车费用
    //$parking_money = ($minute/60) * $price_per_hour;
    //总费用 = 预约期间费用 + 停车费用
    //$money = $parking_money + $extra_cost;

    $money = "0.01";

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

    //返回给app端
    echo json_encode($back, JSON_UNESCAPED_UNICODE );//不会自动把中文编码
}else{
    $back['status']="-1";
    echo json_encode($back, JSON_UNESCAPED_UNICODE );//不会自动把中文编码
}

?>