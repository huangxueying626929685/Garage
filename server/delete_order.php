<?php require_once('Connections/mygarage.php'); ?>
<?php
mysql_select_db("garage",$mygarage);

$get_id = $_POST['order_id'];//客户端post过来的预约单号
//$get_id = 188;
//$get_id = 1;

$sql = mysql_query("SELECT * FROM order_info WHERE order_id=$get_id");
$result = mysql_fetch_assoc($sql);
if(!empty($result))
{
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

    //获取当前时间作为取消时间
    date_default_timezone_set('PRC'); //设置中国时区
    $cancel_time = date("Y-m-d H:i");

    //判断是否超过15分钟
    $minute = floor((strtotime($cancel_time)-strtotime($action_time))/86400/60);
    if($minute > 15) {
        //取消预约，计算费用用户在15分钟内取消不计费，超过15分钟的部分计费
        $money = ($price_per_hour) * (($minute-15)/60);
        //存入late_order数据表
        mysql_query("insert into late_order(username,car_num,action_time,cancel_time,order_time,money)
        values('$username','$car_num','$action_time','$cancel_time','$order_time','$money') ");
    }else{
        $money = 0;
    }

    //删除预约信息
    mysql_query("DELETE FROM order_info  WHERE order_id = $get_id");

    //选取相应车库
    $garage_num = $result['garage_num'];
    $sql_1 = mysql_query("SELECT * FROM garage_info where id = $garage_num");
    $result1 = mysql_fetch_assoc($sql_1);
    //对应车库空闲车位加1
    $free_num1 = $result1['free_num'];
    $free_num = $free_num1 + 1;
    mysql_query("update garage_info set free_num = $free_num where id = $garage_num");
    $status = "1";
}
else{
    $money = 0;
    $status = "-1";
}

$back['status'] = $status;
$back['money'] = $money;
echo(json_encode($back));

mysql_close();
?>