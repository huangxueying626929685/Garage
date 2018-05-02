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
    if($result1['pay_status'] == 1){
        $result['status'] = '-1';
        $result['parking_id'] = '0';
        $result['address'] = '0';
        $result['price_per_hour'] = '0';
        $result['start_time'] = '0';
    }else{
        $garage_num = $result1['garage_num'];
        $sql_2 = mysql_query("SELECT * FROM garage_info where id = $garage_num");
        $result2 = mysql_fetch_assoc($sql_2);

        $result['status'] = '1';
        $result['parking_id'] = $result1['parking_id'];
        $result['address'] = $result2['address'];
        $result['price_per_hour'] = $result2['price_per_hour'];
        $result['finish_parking'] = $result1['finish_parking'];
        $result['start_time'] = $result1['start_time'];
    }

}else{
    $result['status'] = '-1';
    $result['parking_id'] = '0';
    $result['address'] = '0';
    $result['price_per_hour'] = '0';
    $result['start_time'] = '0';
}
echo json_encode($result, JSON_UNESCAPED_UNICODE);
?>