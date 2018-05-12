<?php require_once('Connections/mygarage.php'); ?>
<?php
header("Content-Type:text/html;charset=utf8");
mysql_select_db("garage",$mygarage);
mysql_query("SET NAMES utf8",$mygarage);
$sql = mysql_query("SELECT * FROM parking_info");
//$result = mysql_fetch_assoc($sql);

while($result = mysql_fetch_assoc($sql)) {
    echo json_encode($result,JSON_UNESCAPED_UNICODE);//不会自动把中文编码
    echo "<br>";
}

?>