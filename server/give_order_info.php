<?php require_once('Connections/mygarage.php'); ?>
<?php

    header("Content-Type:text/html;charset=utf8");
    $get_id = $_POST['username'];
    //$get_id = 18959204245;
    mysql_select_db("garage",$mygarage);
    mysql_query("SET NAMES utf8",$mygarage);
    $sql_1 = mysql_query("SELECT * FROM order_info where username = '$get_id' ");
        while($result1 = mysql_fetch_assoc($sql_1)) {
            $garage_num = $result1['garage_num'];
            $sql_2 = mysql_query("SELECT * FROM garage_info where id = $garage_num");
            $result2 = mysql_fetch_assoc($sql_2);
            $back['order_id'] = $result1['order_id'];
            $back['address'] = $result2['address'];
            $back['price_per_hour'] = $result2['price_per_hour'];
            $back['start_time'] = $result1['start_time'];
            echo json_encode($back, JSON_UNESCAPED_UNICODE);//不会自动把中文编码
            echo '<br>';
        }
?>