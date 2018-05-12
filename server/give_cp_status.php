<?php require_once('Connections/mygarage.php'); ?>
<?php

header("Content-Type:text/html;charset=utf8");
//   $get_id = 18959204245;
mysql_select_db("garage",$mygarage);
mysql_query("SET NAMES utf8",$mygarage);
$cp_status ="";
$sql = mysql_query("select status from no1_car_place");
while($result = mysql_fetch_assoc($sql)){
    $cp_status = $cp_status.$result['status'];
}

echo json_encode($cp_status,JSON_UNESCAPED_UNICODE);//不会自动把中文编码


?>