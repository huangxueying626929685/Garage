<?php require_once('Connections/mygarage.php'); ?>
<?php
header("Content-Type:text/html;charset=utf8");
mysql_select_db("garage",$mygarage);
mysql_query("SET NAMES utf8",$mygarage);
$sql = mysql_query("SELECT * FROM garage_info");
//$result = mysql_fetch_assoc($sql);
while($result = mysql_fetch_assoc($sql))
{
    $back['id']=$result['id'];
    $back['total_number']=$result['total_num'];
    $back['rest_number']=$result['free_num'];
    echo json_encode($back,JSON_UNESCAPED_UNICODE);//不会自动把中文编码
    echo '<br>';
}

?>