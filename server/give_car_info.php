<?php require_once('Connections/mygarage.php'); ?>
<?php

header("Content-Type:text/html;charset=utf8");
$get_id = $_POST['username'];
//   $get_id = 18959204245;
mysql_select_db("garage",$mygarage);
mysql_query("SET NAMES utf8",$mygarage);
$sql = mysql_query("SELECT * FROM member where username = $get_id ");

    $result1 = mysql_fetch_assoc($sql);

    $result['car_type'] = $result1['car_type'];
    $result['car_num'] = $result1['car_num'];

    echo json_encode($result,JSON_UNESCAPED_UNICODE);//不会自动把中文编码


?>