<?php require_once('Connections/mygarage.php'); ?>
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/5
 * Time: 13:59
 */
header("Content-Type:text/html;charset=utf8");
mysql_select_db("garage",$mygarage);
mysql_query("SET NAMES utf8",$mygarage);

$get_id = $_POST['username'];
//$get_id = "";
//$get_id = 18795968928;

if(!empty($get_id)){
    //取出预约订单信息
    $sql = mysql_query("SELECT * FROM order_info where username = '$get_id' ");
    $result = mysql_fetch_assoc($sql);

//判断超时订单表中是否有该用户
    $sql_3 = mysql_query("SELECT * FROM late_order where username = $get_id");
    $result3 = mysql_fetch_assoc($sql_3);
}

//若该用户已预约,且超时订单表中没有该用户，判断是否超时，并存入late_order数据表形成未完成订单
if (!empty($result)&&(empty($result3))&&(!empty($get_id))) {
    //获取用户名，车牌号，预约停车时间
    $username = $result['username'];
    $car_num = $result['car_num'];
    $order_time = $result['start_time'];
    //获取进行预约操作的时间
    $action_time = $result['action_time'];
    //获取单价
    $garage_num = $result['garage_num'];
    $sql_2 = mysql_query("SELECT * FROM garage_info where id = $garage_num");
    $result2 = mysql_fetch_assoc($sql_2);
    $price_per_hour = $result2['price_per_hour'];
    //获取当前时间
    date_default_timezone_set('PRC'); //设置中国时区
    $now_time = date("Y-m-d H:i");
    //判断是否预约超时
    $minute = floor((strtotime($now_time) - strtotime($action_time)) % 86400 / 60);
//        echo $now_time;
//        echo '<br>';
//        echo $action_time;
//        echo '<br>';
//        echo $minute;
//        echo '<br>';
    //用户超时未到
    if (strtotime($now_time) > strtotime($order_time)) {//如果超时
        //计算费用
//          $money = number_format(($price_per_hour) * ($minute/60), 2);
        $status = "1";
        $money = "0.01";
        //echo $money;
        //选取相应车库
        $garage_num = $result['garage_num'];
        $sql_1 = mysql_query("SELECT * FROM garage_info where id = $garage_num");
        $result1 = mysql_fetch_assoc($sql_1);
        $address = $result1['address'];
        mysql_query("delete from order_info where username = '$username'");

        if(!mysql_query("insert into late_order(username,car_num,action_time,order_time,money)
    values('$username','$car_num','$action_time','$order_time','$money') ")){
            die(mysql_error());
        }

        //对应车库空闲车位加1
        $free_num1 = $result1['free_num'];
        $free_num = $free_num1 + 1;
        mysql_query("update garage_info set free_num = $free_num where id = $garage_num");

    } else {
        $status = "-1";
        $money = "0";
    }
    //若超时订单表中有该用户，说明未付钱，继续提醒
}else if(!empty($result3)){
    $status = "1";
    $money = $result3['money'];
}else{
    $status = "-1";
    $money = "0";
}

$back['status'] = $status;
$back['money'] = $money;
echo(json_encode($back));

?>