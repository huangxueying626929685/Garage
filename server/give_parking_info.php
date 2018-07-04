<?php require_once('Connections/mygarage.php'); ?>
<?php

header("Content-Type:text/html;charset=utf8");
mysql_select_db("garage",$mygarage);
mysql_query("SET NAMES utf8",$mygarage);

$get_id = $_POST['username'];
//$get_id = 18959204245;
//$get_id = "admin";

$sql_1 = mysql_query("SELECT * FROM parking_info where username = '$get_id' ");
$result1 = mysql_fetch_assoc($sql_1);
if(!empty($result1)){
    if($result1['confirm_out'] == 1){
        $back['status'] = '-1';
        $back['parking_id'] = 0;
        $back['address'] = 0;
        $back['price_per_hour'] = 0;
        $back['finish_parking'] = 0;
        $back['start_time'] = 0;
        $back['leave_time'] = 0;
        $back['money'] = 0;
        $back['pay_status'] = 0;
        $back['confirm_out'] = 0;
    }else{
        $garage_num = $result1['garage_num'];
        $sql_2 = mysql_query("SELECT * FROM garage_info where id = $garage_num");
        $result2 = mysql_fetch_assoc($sql_2);

        $back['status'] = '1';
        $back['parking_id'] = $result1['parking_id'];
        $back['address'] = $result2['address'];
        $back['price_per_hour'] = $result2['price_per_hour'];
        $back['finish_parking'] = $result1['finish_parking'];
        $back['start_time'] = $result1['start_time'];
        $back['leave_time'] = $result1['leave_time'];
        $back['money'] = $result1['money'];
        $back['pay_status'] = $result1['pay_status'];
        $back['confirm_out'] = $result1['confirm_out'];
    }
}else{
    $back['status'] = '-1';
    $back['parking_id'] = 0;
    $back['address'] = 0;
    $back['price_per_hour'] = 0;
    $back['finish_parking'] = 0;
    $back['start_time'] = 0;
    $back['leave_time'] = 0;
    $back['money'] = 0;
    $back['pay_status'] = 0;
    $back['confirm_out'] = 0;
}
echo json_encode($back, JSON_UNESCAPED_UNICODE);
?>