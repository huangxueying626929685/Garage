<?php require_once('Connections/mygarage.php'); ?>
<?php
mysql_select_db("garage",$mygarage);
$status = $_POST['cp_status'];
//$status = 1101110000101;
for($i = 0;$i < 13;$i ++){
    $a[$i] = substr($status,$i,1);
    mysql_query("update no1_car_place set status = '$a[$i]' where id = ($i+1)");
}
$back['status'] = "1";
echo json_encode($back);

?>