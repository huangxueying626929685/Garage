<?php require_once('Connections/mygarage.php'); ?>
<?php
mysql_select_db("garage",$mygarage);

/*$get_id='';//客户端post过来的用户名
$get_pwd='pawd';//客户端post过来的密码
$get_car_num=152;//客户端post过来的车牌号码
$get_car_type=14;//客户端post过来的车型
$get_tel=1654684;//客户端post过来的手机号
$get_status="1";//客户端post过来的登录状态*/

$get_id=$_POST['userid'];//客户端post过来的用户名
$get_pwd=$_POST['passwd'];//客户端post过来的密码
$get_car_num=$_POST['car_num'];//客户端post过来的车牌号码
$get_car_type=$_POST['car_type'];//客户端post过来的车型
$get_status=1;//客户端post过来的登录状态
//mysql_query("INSERT INTO `member`.`member` (`username`, `password`, `car_num`, `car_type`, `tel`, `status`) VALUES ('1', '4', '1', '4', '1', '0')");
if(mysql_query("INSERT INTO member(username, password, car_num, car_type, status) VALUES('$get_id','$get_pwd','$get_car_num','$get_car_type','$get_status')"))
{
    $back['status']="1";
    echo(json_encode($back));
}

mysql_close();
?>