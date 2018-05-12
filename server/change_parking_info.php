<?php require_once('Connections/mygarage.php'); ?>
<?php
mysql_select_db("garage",$mygarage);

$get_id = $_POST['parking_id'];//客户端post过来的手机号
$cp_num = $_POST['cp_num'];
$finish_parking = $_POST['finish_parking'];
$confirm_parking = $_POST['confirm_parking'];
$pay_status = $_POST['pay_status'];
$confirm_out = $_POST['confirm_out'];

//$get_id = 343;//客户端post过来的手机号
//$cp_num = 1;
//$finish_parking = 1;
//$confirm_parking = 1;
//$pay_status = 1;
//$confirm_out = 1;

$sql = mysql_query("SELECT * FROM parking_info WHERE parking_id = '$get_id'");
$found_id = mysql_fetch_assoc($sql);


if(!empty($found_id)) {
//  echo($found_tel);
    if(mysql_query("UPDATE parking_info SET cp_num = '$cp_num',finish_parking = '$finish_parking',
confirm_parking = '$confirm_parking',pay_status = '$pay_status',confirm_out = '$confirm_out' WHERE parking_id = '$get_id'")){
        $back['status'] = "1";
        echo(json_encode($back));
    }
}
else{
    $back['status']="-1";
    echo(json_encode($back));
}
