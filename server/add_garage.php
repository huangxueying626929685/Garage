<?php require_once('Connections/mygarage.php'); ?>
<?php
mysql_select_db("garage", $mygarage);

function randomFloat($min = 0, $max = 1) {
    return $min + mt_rand() / mt_getrandmax() * ($max - $min);
}


for($i=10;$i<309;$i++){
//    $latitude = randomFloat(32.001,32.037);
//    $longtitude = randomFloat(118.742,118.856);
//    $address = "江苏省南京市".($i+59)."号车库";
//    $total_num = $i + 30;
//    $free_num = $i + 20;
//    $price_per_hour = $i * 0.1 + 10;
//    mysql_query("insert into garage_info(latitude,longtitude,address,total_num,free_num,price_per_hour)
//values('$latitude','$longtitude','$address','$total_num','$free_num',$price_per_hour)");
    mysql_query("update garage_info set price_per_hour = floor(price_per_hour) where id = '$i'");
}

?>