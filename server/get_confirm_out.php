<?php require_once('Connections/mygarage.php'); ?>
<?php
mysql_select_db("garage",$mygarage);

$get_username = $_POST['username'];//客户端post

//$get_username = 18959204245;//客户端post
$sql = mysql_query("SELECT * FROM parking_info WHERE username = '$get_username'");
$found_id = mysql_fetch_assoc($sql);
if(!empty($found_id))
{
    $temp = "1";
//    mysql_query("DELETE FROM parking_info  WHERE username = $get_username");
    if(!(mysql_query("update parking_info set confirm_out = '$temp' where username = $get_username"))){
        die(mysql_error());
    }
    $back['status'] = "1";
}else{
    $back['status'] = "-1";
}
echo(json_encode($back));
mysql_close();
?>