<?php require_once('Connections/mygarage.php'); ?>
<?php

header("Content-Type:text/html;charset=utf8");
//
$get_id = $_POST['username'];

//$get_id = 18795968928;
//$get_id = "admin";
mysql_select_db("garage",$mygarage);
mysql_query("SET NAMES utf8",$mygarage);
$sql_1 = mysql_query("SELECT * FROM parking_info where username = '$get_id' ");
$result1 = mysql_fetch_assoc($sql_1);
if(!empty($result1)){
    if($result1['confirm_out'] == 1){
        $result['status'] = '-1';
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
}
echo json_encode($back, JSON_UNESCAPED_UNICODE);
?>