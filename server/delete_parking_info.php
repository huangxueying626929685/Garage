<?php require_once('Connections/mygarage.php'); ?>
<?php
mysql_select_db("garage",$mygarage);

$get_id = $_POST['parking_id'];//客户端post过来的预约单号
//$get_id = 328;
//$get_id = 1;

$sql = mysql_query("SELECT * FROM parking_info WHERE parking_id=$get_id");
$result = mysql_fetch_assoc($sql);
if(!empty($result)) {
    //删除订单信息
    mysql_query("DELETE FROM parking_info  WHERE parking_id = $get_id");
    $back['status'] = "1";
}else{
    $back['status'] = "-1";
}

echo(json_encode($back));

?>