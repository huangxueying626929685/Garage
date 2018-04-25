<?php require_once('Connections/mygarage.php'); ?>
<?php
mysql_select_db("garage",$mygarage);
//
//$get_id='18795968928';//客户端post过来的用户名
//$get_garage_num='2';//客户端post过来的车库号
//$get_start_time='345325';//客户端post过来的开始时间
$get_id=$_POST['userid'];//客户端post过来的用户名
$get_garage_num=$_POST['garage_num'];//客户端post过来的车库号
$get_start_time=$_POST['start_time'];//客户端post过来的开始时间
//取车牌号
$sql = mysql_query("SELECT * FROM member where username = '$get_id'");
$result = mysql_fetch_assoc($sql);
$car_num = $result['car_num'];
//mysql_query("INSERT INTO `member`.`member` (`username`, `password`, `car_num`, `car_type`, `tel`, `status`) VALUES ('1', '4', '1', '4', '1', '0')");
if(mysql_query("INSERT INTO order_info(username, car_num, garage_num, start_time) VALUES('$get_id','$car_num','$get_garage_num','$get_start_time')"))
{
    $sql_1 = mysql_query("SELECT * FROM garage_info where id = $get_garage_num");
    $result1 = mysql_fetch_assoc($sql_1);
//对应车库空闲车位减1
    $free_num1 = $result1['free_num'];
    $free_num = $free_num1 - 1;
    mysql_query("update garage_info set free_num = $free_num where id = $get_garage_num");

    $back['status']="1";
    echo(json_encode($back));
}
else{
    die (mysql_error());//错误原因
    $back['status']="-1";
    echo(json_encode($back));
}

mysql_close();
?>