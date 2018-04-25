<?php require_once('Connections/mygarage.php'); ?>
<?php
mysql_select_db("garage",$mygarage);

$get_username='18959204245';//客户端post过来的用户名
$get_car_num='341895';
$get_garage_num='2';//客户端post过来的车库号
$get_start_time='345325';//客户端post过来的开始时间
$get_leave_time='345325';//客户端post过来的开始时间
$get_money='0.01';//客户端post过来的开始时间

//mysql_query("INSERT INTO `member`.`member` (`username`, `password`, `car_num`, `car_type`, `tel`, `status`) VALUES ('1', '4', '1', '4', '1', '0')");
if(mysql_query("INSERT INTO parking_info(username,car_num, garage_num, start_time,leave_time,money) VALUES('$get_username','$get_car_num','$get_garage_num','$get_start_time','$get_leave_time','$get_money')"))
{
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