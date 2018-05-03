<?php require_once('Connections/mygarage.php'); ?>
<?php
mysql_select_db("garage",$mygarage);

$status = "0000000000000";

$a = substr($status,0,1);
$b = substr($status,1,1);
$c = substr($status,2,1);
$d = substr($status,3,1);
$e = substr($status,4,1);
$f = substr($status,5,1);
$g = substr($status,6,1);
$h = substr($status,7,1);
$i = substr($status,8,1);
$j = substr($status,9,1);
$k = substr($status,10,1);
$l = substr($status,11,1);
$m = substr($status,12,1);
mysql_query("update no1_car_place set status = '$a' where cp_num ='101'");
mysql_query("update no1_car_place set status = '$b' where cp_num ='102'");
mysql_query("update no1_car_place set status = '$c' where cp_num ='201'");
mysql_query("update no1_car_place set status = '$d' where cp_num ='202'");
mysql_query("update no1_car_place set status = '$e' where cp_num ='301'");
mysql_query("update no1_car_place set status = '$f' where cp_num ='302'");
mysql_query("update no1_car_place set status = '$g' where cp_num ='401'");
mysql_query("update no1_car_place set status = '$h' where cp_num ='402'");
mysql_query("update no1_car_place set status = '$i' where cp_num ='501'");
mysql_query("update no1_car_place set status = '$j' where cp_num ='502'");
mysql_query("update no1_car_place set status = '$k' where cp_num ='601'");
mysql_query("update no1_car_place set status = '$l' where cp_num ='602'");
mysql_query("update no1_car_place set status = '$m' where cp_num ='603'");

?>