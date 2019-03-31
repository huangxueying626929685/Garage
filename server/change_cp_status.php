<?php require_once('Connections/mygarage.php'); ?>
<?php
mysql_select_db("garage",$mygarage);
$status = $_POST['cp_status'];
//$status = 1101110000101;
$free_num = 0;
for($i = 0;$i < 13;$i ++){
    $a[$i] = substr($status,$i,1);
    if($a[$i] == '0'){
        $free_num++;
    }
    mysql_query("update no1_car_place set status = '$a[$i]' where id = ($i+1)");
}
mysql_query("update garage_info set free_num = $free_num where id = 1");
$back['status'] = "1";
echo json_encode($back);

?>