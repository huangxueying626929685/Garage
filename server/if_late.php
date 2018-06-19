<?php require_once('Connections/mygarage.php'); ?>
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/5
 * Time: 13:59
 */
header("Content-Type:text/html;charset=utf8");
$get_id = $_POST['username'];
//$get_id = 18959204245;
mysql_select_db("garage",$mygarage);
mysql_query("SET NAMES utf8",$mygarage);

//取出预约订单信息
$sql = mysql_query("SELECT * FROM order_info where username = '$get_id' ");
$result = mysql_fetch_assoc($sql);

//若该用户已预约
if(!empty($result)) {
    //获取用户名，车牌号，预约停车时间
    $username = $result['username'];
    $car_num = $result['car_num'];
    $order_time = $result['start_time'];

    //获取单价
    $garage_num = $result['garage_num'];
    $sql_2 = mysql_query("SELECT * FROM garage_info where id = $garage_num");
    $result2 = mysql_fetch_assoc($sql_2);
    $price_per_hour = $result2['price_per_hour'];

    //获取进行预约操作的时间
    $action_time = $result['action_time'];
    echo $action_time;
    echo '<br>';
    //获取当前时间
    date_default_timezone_set('PRC'); //设置中国时区
    $now_time = date("Y-m-d H:i");
    echo $now_time;
    echo '<br>';

    //判断是否预约超时
    $minute = floor((strtotime($now_time)-strtotime($action_time))%86400/60);
//    echo $hour;
    echo $minute;
    echo '<br>';
    if (strtotime($now_time) > strtotime($order_time)) {
        //用户超时未到

        //计算费用
        $money = ($price_per_hour) * ($minute/60);
        echo $money;
        //存入late_order数据表形成未完成订单
        mysql_query("insert into late_order(username,car_num,action_time,order_time,money)
        values('$username','$car_num','$action_time','$order_time','$money') ");
        //删除预约信息
//        mysql_query("DELETE FROM order_info WHERE username = $get_id");
        //选取相应车库
        $garage_num = $result['garage_num'];
        $sql_1 = mysql_query("SELECT * FROM garage_info where id = $garage_num");
        $result1 = mysql_fetch_assoc($sql_1);
        //对应车库空闲车位加1
        $free_num1 = $result1['free_num'];
        $free_num = $free_num1 + 1;
        mysql_query("update garage_info set free_num = $free_num where id = $garage_num");
        $status = "1";
    }else {
        $money = 0;
        $status = "-1";
    }
}else{
    $money = 0;
    $status = "-1";
}

$back['status'] = $status;
$back['money'] = $money;
echo(json_encode($back));

?>