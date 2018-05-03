<?php require_once('Connections/mygarage.php'); ?>
<?php
header("Content-Type:text/html;charset=utf8");
mysql_select_db("garage",$mygarage);
//$json = $_GET['data'];//接收单片机数据
mysql_query("SET NAMES utf8",$mygarage);
$json = '{"id":"1","car":"B8D3F16712","f_1":"011001","fall":"1","all":"00101010110"}';
$json1 = json_decode($json,true);
$floor_1 = $json1['f_1'];

$temp = "1";
$sql5 = mysql_query("select * from parking_info 
      where finish_parking = '$temp'");
//echo json_encode($result5);
$result5 = mysql_fetch_assoc($sql5);
$parking_id = $result5['parking_id'];
$pre_cp_num = $result5['cp_num'];
$confirm_parking = $result5['confirm_parking'];//上一次是否完成停车

echo ("上一次的确认停车标志：".$confirm_parking);
echo '<br>';

//取出上一次车位的第一位
$first = substr($pre_cp_num, 0, 1);

//确定上一次分配的车位是否是第一层的
if ($first != "1") {//不是一层的，存入第一层的高层车架板：high_floor_cp_num，确定第一层的高层板具体的车位号
    $high_floor_cp_num = $pre_cp_num;
    if (!(mysql_query("update floor_1 set high_floor_cp_num = $high_floor_cp_num"))) {
        die(mysql_error());
    }
}
$true_cp_num = ensure_cp_num($floor_1);
echo $true_cp_num;
function ensure_cp_num($floor_1){
    $floor_1_length = strlen($floor_1);
    //取出限位开关状态信息
    $floor_1_place = substr($floor_1, 0, $floor_1_length / 2);
//    echo $floor_1_place;
//    echo '<br>';
    //取出有否有车状态信息
    $floor_1_status = substr($floor_1, $floor_1_length / 2, $floor_1_length);
//    echo $floor_1_status;
//    echo '<br>';

    //取出第一层车位位置和车位状态的信息存入数组
    for ($i = 0; $i < strlen($floor_1_status); $i++) {
        $place[$i] = substr($floor_1_place, $i, 1);
        $status[$i] = substr($floor_1_status, $i, 1);
    }
    //echo json_encode($status);
    $k = 101; //第一层车位
    $get_cp_num = 0; //是否获得最终停车的位置标志

    //判断出上一次停车的车位号
    for ($i = 0; $i < strlen($floor_1_status); $i++) {
        if ($place[$i] == "1") {//限位开关为1，表示当前位置是第一层的车位
            $sql8 = mysql_query("select * from no1_car_place 
                    where cp_num = $k");
            $result8 = mysql_fetch_assoc($sql8);
            $cp_k_status = $result8['status'];//取出数据库中的车位k的状态
            if ($status[$i] != $cp_k_status) {//如果单片机传来的当前位置信息与数据库不匹配，则表示车停到了该车位
                //            echo $k;
               /* mysql_query("update parking_info set cp_num = $k
                 where parking_id = $parking_id");//更新停车信息数据库中的车位号
                mysql_query("update no1_car_place set status = 1 where cp_num = $k");*/
                $get_cp_num = 1;
                $true_cp_num = $k;
            }
            $k++;
        } else {
            $j = $i;//获得高层车位的位置
//                   echo $j;
        }
    }
    if ($get_cp_num == 0) {//若没有停在第一层，则停在了高层车架上
        $sql10 = mysql_query("select * from floor_1 where id = 1");
        $result10 = mysql_fetch_assoc($sql10);
        $high_floor_cp_num1 = $result10['high_floor_cp_num'];//取出第一层高层车架的车位号
        $sql9 = mysql_query("select * from no1_car_place where cp_num = $high_floor_cp_num1");
        $result9 = mysql_fetch_assoc($sql9);
        $cp_high_status = $result9['status'];//取出数据库中的高层车位的状态

        if ($status[$j] != $cp_high_status) {
            /*mysql_query("update parking_info set cp_num = $high_floor_cp_num1
                 where parking_id = $parking_id");
            mysql_query("update no1_car_place set status = 1 where cp_num = $high_floor_cp_num1");*/
            $true_cp_num = $high_floor_cp_num1;//此时表示用户停进去的车位号
        }
    }
return $true_cp_num;
    /*if(!(mysql_query("update no1_car_place set status = 1
         where cp_num = $true_cp_num"))){
        die(mysql_error());
    }*/
}

//更新停车信息数据库中的车位号
mysql_query("update parking_info set cp_num = $true_cp_num
                 where parking_id = $parking_id");

//将对应的车位状态置1
mysql_query("update no1_car_place set status = 1 where cp_num = $true_cp_num");

?>