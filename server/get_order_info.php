<?php require_once('Connections/mygarage.php'); ?>
<?php
mysql_select_db("garage", $mygarage);
//
/*$get_id='18795968928';//客户端post过来的用户名
$garage_num='1';//客户端post过来的车库号
$start_time='2018-06-30 17:30';//客户端post过来的开始时间*/
$get_id = $_POST['userid'];//客户端post过来的用户名
$garage_num = $_POST['garage_num'];//客户端post过来的车库号
$start_time = $_POST['start_time'];//客户端post过来的开始时间

//判断超时订单表中是否有该用户
$sql_3 = mysql_query("SELECT * FROM late_order where username = $get_id");
$result3 = mysql_fetch_assoc($sql_3);

if(empty($result3)) {
    //判断是否还有空闲车位
    $sql_1 = mysql_query("SELECT * FROM garage_info where id = $garage_num");
    $result1 = mysql_fetch_assoc($sql_1);
    $free_num1 = $result1['free_num'];
    if ($free_num1 > 0) {
        //取车牌号
        $sql = mysql_query("SELECT * FROM member where username = '$get_id'");
        $result = mysql_fetch_assoc($sql);
        $car_num = $result['car_num'];

        //获取当前时间作为进行预约操作的时间
        date_default_timezone_set('PRC'); //设置中国时区
        $action_time = date("Y-m-d H:i");

        //存入预约数据表
        //mysql_query("INSERT INTO `member`.`member` (`username`, `password`, `car_num`, `car_type`, `tel`, `status`) VALUES ('1', '4', '1', '4', '1', '0')");
        if(!mysql_query("INSERT INTO order_info(username, car_num, garage_num, action_time, start_time) 
    VALUES('$get_id','$car_num','$garage_num','$action_time','$start_time')")){
            die(mysql_error());
        }

        //对应车库空闲车位减1
        $free_num = $free_num1 - 1;
        mysql_query("update garage_info set free_num = $free_num where id = $garage_num");

        $back['status'] = "1";
    } else {
        $back['status'] = "-1";//预约失败，没有空闲车位
        }
}else{
    $back['status'] = "-1";//预约失败，有超时订单未完成
}


echo(json_encode($back));
mysql_close();
?>