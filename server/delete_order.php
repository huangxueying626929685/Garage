<?php require_once('Connections/mygarage.php'); ?>
<?php
mysql_select_db("garage",$mygarage);

$get_id = $_POST['order_id'];//客户端post过来的预约单号
//$get_id = 140;
//$get_id = 1;

$sql = mysql_query("SELECT * FROM order_info WHERE order_id=$get_id");
$result = mysql_fetch_assoc($sql);
if(!empty($result))
{
//  echo($found_tel);
    //删除预约信息
    mysql_query("DELETE FROM order_info  WHERE order_id = $get_id");
    $garage_num = $result['garage_num'];
    $sql_1 = mysql_query("SELECT * FROM garage_info where id = $garage_num");
    $result1 = mysql_fetch_assoc($sql_1);
//对应车库空闲车位加1
    $free_num1 = $result1['free_num'];
    $free_num = $free_num1 + 1;
    mysql_query("update garage_info set free_num = $free_num where id = $garage_num");
    $back['status']="1";
    echo(json_encode($back));
}
else{
    $back['status']="-1";
    echo(json_encode($back));
}

mysql_close();
?>