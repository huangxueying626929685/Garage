<?php require_once('Connections/mygarage.php'); ?>
<?php
header("Content-Type:text/html;charset=utf8");
mysql_select_db("garage", $mygarage);
mysql_query("SET NAMES utf8", $mygarage);

//$garage_id = $_POST['garage_id'];
$garage_id = 1;

$sql = mysql_query("SELECT * FROM fault_warning WHERE id = $garage_id");
$result = mysql_fetch_assoc($sql);

echo json_encode($result, JSON_UNESCAPED_UNICODE);//不会自动把中文编码
?>