<?php require_once('Connections/mygarage.php'); ?>
<?php
mysql_select_db("garage",$mygarage);

$get_username = $_POST['username'];//客户端post

//$get_username = 18795968928;//客户端post

$sql = mysql_query("SELECT * FROM parking_info WHERE username = $get_username");
$found_id = mysql_fetch_assoc($sql);
if(!empty($found_id))
{
//    mysql_query("DELETE FROM parking_info  WHERE username = $get_username");
    mysql_query("update parking_info set pay_status = 1 where username = $get_username");
    $back['status']="1";
    echo(json_encode($back));
}
else{
    $back['status']="-1";
    echo(json_encode($back));
}
mysql_close();
?>