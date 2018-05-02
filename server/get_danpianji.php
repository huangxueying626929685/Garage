<?php require_once('Connections/mygarage.php'); ?>
<?php
header("Content-Type:text/html;charset=utf8");
mysql_select_db("garage",$mygarage);
$json = $_GET['data'];//接收单片机数据
mysql_query("SET NAMES utf8",$mygarage);
//$json = '{"id":"1","car":"0","f_1":"0","fall":"1","all":"00101010110"}';
//floor_1表示一层的车位状态信息，前三个数字表示限位开关状态，1表示一楼的车位，0表示高层车位
//floor_1后三个数字表示是否有车状态，1表示有车，0表示没车
//all_floor表示除一层之外的所有车位状态信息

$len = strlen($json);
//echo $json;
$json1 = json_decode($json,true);

//取出上一次分配的车位
/*$sql5 = mysql_query("select * from parking_info
    where parking_id=(select max(parking_id) from parking_info)");
$result5 = mysql_fetch_assoc($sql5);*/
$temp = "1";
$sql5 = mysql_query("select * from parking_info 
      where finish_parking = '$temp'");
//echo json_encode($result5);
$result5 = mysql_fetch_assoc($sql5);
$parking_id = $result5['parking_id'];
$pre_cp_num = $result5['cp_num'];
$confirm_parking = $result5['confirm_parking'];//上一次是否完成停车

//取出设备id
$device_id = $json1['id'];

//echo $device_id;
//解析出第一层车位的状态
$floor_1 = $json1['f_1'];
//echo $floor_1;

//如果收到的floor_1不等于0，且完成停车标志为1，判断上一次车停在了哪一个车位上
if(($confirm_parking == "1")&&($floor_1 != "0")) {
    $confirm_parking == "0";
    mysql_query("update parking_info set confirm_parking = 0 where confirm_parking = 1");
    $floor_1_length = strlen($floor_1);
    //取出限位开关状态信息
    $floor_1_place = substr($floor_1, 0, $floor_1_length / 2);
//    echo $floor_1_place;
//    echo '<br>';
    //取出有否有车状态信息
    $floor_1_status = substr($floor_1, $floor_1_length / 2, $floor_1_length);
//    echo $floor_1_status;
//    echo '<br>';

    //取出上一次车位的第一位
    $first = substr($pre_cp_num, 0, 1);

    //确定上一次分配的车位是否是第一层的
    if ($first != "1") {//不是一层的，存入第一层的高层车架板：high_floor_cp_num，确定第一层的高层板具体的车位号
        $high_floor_cp_num = $pre_cp_num;
        if (!(mysql_query("update floor_1 set high_floor_cp_num = $high_floor_cp_num"))) {
            die(mysql_error());
        }
    }

    //取出第一层车位位置和车位状态的信息存入数组
    for ($i = 0; $i < strlen($floor_1_status); $i++) {
        $place[$i] = substr($floor_1_place, $i, 1);
        $status[$i] = substr($floor_1_status, $i, 1);
    }
    //echo json_encode($status);
    $k = 101; //第一层车位
    $get_cp_num = 0; //是否获得最终停车的位置标志

    //得出上一次停车的车位号
    for ($i = 0; $i < strlen($floor_1_status); $i++) {
        if ($place[$i] == "1") {//限位开关为1，表示当前位置是第一层的车位
            $sql8 = mysql_query("select * from no1_car_place 
                    where cp_num = $k");
            $result8 = mysql_fetch_assoc($sql8);
            $cp_k_status = $result8['status'];//取出数据库中的车位k的状态
            if ($status[$i] != $cp_k_status) {//如果单片机传来的当前位置信息与数据库不匹配，则表示车停到了该车位
                //            echo $k;
                mysql_query("update parking_info set cp_num = $k
                 where parking_id = $parking_id");//更新停车信息数据库中的车位号
                mysql_query("update no1_car_place set status = 1 where cp_num = $k");
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
        $high_floor_cp_num1 = $result10['high_floor_cp_num'];//取出高层车架的车位号
        $true_cp_num = $high_floor_cp_num1;
        $sql9 = mysql_query("select * from no1_car_place where cp_num = $high_floor_cp_num1");
        $result9 = mysql_fetch_assoc($sql9);
        $cp_high_status = $result9['status'];//取出数据库中的车位102的状态
        if ($status[$j] != $cp_high_status) {
            mysql_query("update parking_info set cp_num = $high_floor_cp_num1
                 where parking_id = $parking_id");
            mysql_query("update no1_car_place set status = 1 where cp_num = $high_floor_cp_num1");
        }
    }
//    echo $true_cp_num;
    if(!(mysql_query("update no1_car_place set status = 1 
         where cp_num = $true_cp_num"))){
        die(mysql_error());
    }
}

//总体车位状态判断
$all_floor = $json1['all'];

//存车流程
//判断是否有车牌信息
$car_num1 = $json1['car'];
$in_cp_num = "0";
if($car_num1 != "0") {//有车牌信息
    $car_first = substr($car_num1, 0, 4);//收到的车牌的前4位，准备转化为省份

//将前四位装换成省份，解析出车牌号存入$car_num
    switch ($car_first)//解析协议
    {
        case 'BEA9':
            //将接受到的车牌的前4位字符替换成相应的汉字；下同
            $car_num = substr_replace($car_num1, '京', 0, 4);
            break;
        case 'BDF2':
            $car_num = substr_replace($car_num1, '津', 0, 4);
            break;
        case 'BCBD':
            $car_num = substr_replace($car_num1, '冀', 0, 4);
            break;
        case 'BDFA':
            $car_num = substr_replace($car_num1, '晋', 0, 4);
            break;
        case 'C3C9':
            $car_num = substr_replace($car_num1, '蒙', 0, 4);
            break;
        case 'C1C9':
            $car_num = substr_replace($car_num1, '辽', 0, 4);
            break;
        case 'BCAA':
            $car_num = substr_replace($car_num1, '吉', 0, 4);
            break;
        case 'BADA':
            $car_num = substr_replace($car_num1, '黑', 0, 4);
            break;
        case 'BBA6':
            $car_num = substr_replace($car_num1, '沪', 0, 4);
            break;
        case 'CBD5':
            $car_num = substr_replace($car_num1, '苏', 0, 4);
            break;
        case 'D5E3':
            $car_num = substr_replace($car_num1, '浙', 0, 4);
            break;
        case 'CDEE':
            $car_num = substr_replace($car_num1, '皖', 0, 4);
            break;
        case 'C3F6':
            $car_num = substr_replace($car_num1, '闽', 0, 4);
            break;
        case 'B8D3':
            $car_num = substr_replace($car_num1, '赣', 0, 4);
            break;
        case 'C2B3':
            $car_num = substr_replace($car_num1, '鲁', 0, 4);
            break;
        case 'D4A5':
            $car_num = substr_replace($car_num1, '豫', 0, 4);
            break;
        case 'B6F5':
            $car_num = substr_replace($car_num1, '鄂', 0, 4);
            break;
        case 'CFE6':
            $car_num = substr_replace($car_num1, '湘', 0, 4);
            break;
        case 'D4C1':
            $car_num = substr_replace($car_num1, '粤', 0, 4);
            break;
        case 'B9F0':
            $car_num = substr_replace($car_num1, '桂', 0, 4);
            break;
        case 'C7ED':
            $car_num = substr_replace($car_num1, '琼', 0, 4);
            break;
        case 'D3E5':
            $car_num = substr_replace($car_num1, '渝', 0, 4);
            break;
        case 'B4A8':
            $car_num = substr_replace($car_num1, '川', 0, 4);
            break;
        case 'B9F3':
            $car_num = substr_replace($car_num1, '贵', 0, 4);
            break;
        case 'D4C6':
            $car_num = substr_replace($car_num1, '云', 0, 4);
            break;
        case 'B2D8':
            $car_num = substr_replace($car_num1, '藏', 0, 4);
            break;
        case 'C9C2':
            $car_num = substr_replace($car_num1, '陕', 0, 4);
            break;
        case 'B8CA':
            $car_num = substr_replace($car_num1, '甘', 0, 4);
            break;
        case 'C7E0':
            $car_num = substr_replace($car_num1, '青', 0, 4);
            break;
        case 'C4FE':
            $car_num = substr_replace($car_num1, '宁', 0, 4);
            break;
        case 'D0C2':
            $car_num = substr_replace($car_num1, '新', 0, 4);
            break;
        case '0x31':
            $car_num = substr_replace($car_num1, '粤Z', 0, 4);
            break;
        case '0x32':
            $car_num = substr_replace($car_num1, '粤Z', 0, 4);
            break;
        case '0x33':
            $car_num = substr_replace($car_num1, '粤Z', 0, 4);
            break;
        default:
            $car_num = 0;
            break;
    }
//echo $car_num;

    mysql_query("update danpianji set device_id = '$device_id',car_num = '$car_num',
 floor_1 = $floor_1,all_floor = $all_floor,datas = '$json', len = '$len' where id = 1");

    $sql = mysql_query("select * from order_info where car_num = '$car_num'");//从数据表order_info（手机端订单数据库）取车牌号
    $result = mysql_fetch_assoc($sql);
//echo $finish_parking;
   // if ($finish_parking == "1") {//如果上一个用户完成停车，则进行下一次车位分配
    if (!empty($result)) {//如果车牌匹配，则将数据存入单片机数据表
        //分配车位
        $sql4 = mysql_query("select * from parking_info 
             where car_num = '$car_num'");
        $result4 = mysql_fetch_assoc($sql4);
//        echo $result4['car_num'];
        if (empty($result4)) {

            $sql1 = mysql_query("select * from no1_car_place where status = 0 ");
            $result1 = mysql_fetch_assoc($sql1);
            $in_cp_num = $result1['cp_num'];
//    echo $in_cp_num;
//            mysql_query("update no1_car_place set status = 1 where cp_num = $in_cp_num");
            //获取当前时间
            date_default_timezone_set('PRC'); //设置中国时区
            $start_time = date("Y-m-d H:i");
            //    echo $start_time;
            $sql2 = mysql_query("select * from order_info where car_num = '$car_num'");
            $result2 = mysql_fetch_assoc($sql2);
            $username = $result2['username'];
            $garage_num = $result2['garage_num'];
            mysql_query("insert into parking_info(username,car_num,garage_num,cp_num,finish_parking,start_time)
                      value ('$username','$car_num','$garage_num','$in_cp_num',0,'$start_time')");
        }
    }

    }else{//无车牌信息
    $in_cp_num = "0";
}

//取车流程
//取出支付状态为1的车位号
$sql3 = mysql_query("select * from parking_info where pay_status = 1");
$result3 = mysql_fetch_assoc($sql3);
if(!empty($result3)){
    $out_cp_num = $result3['cp_num'];
//    $fall_io_point1 = $result3['fall_io_point'];

}else{
    $out_cp_num = "0";
//    $fall_io_point1 = "0";
}

//判断是否取车成功
//如果下限位为1；则判断是否取车成功
//$get_fall = $json1['fall'];
//if($get_fall == "1"){
//    $get_floor_1 = "1";
//    if($floor_1 != "0"){
//        $floor_1_length = strlen($floor_1);
//        //取出限位开关状态信息
//        $floor_1_place = substr($floor_1, 0, $floor_1_length / 2);
////    echo $floor_1_place;
////    echo '<br>';
//        //取出有否有车状态信息
//        $floor_1_status = substr($floor_1, $floor_1_length / 2, $floor_1_length);
////    echo $floor_1_status;
////    echo '<br>';
//        //取出第一层车位位置和车位状态的信息存入数组
//        for ($i = 0; $i < strlen($floor_1_status); $i++) {
//            $place[$i] = substr($floor_1_place, $i, 1);
//            $status[$i] = substr($floor_1_status, $i, 1);
//        }
//
//        for($i = 0;$i < strlen($floor_1_status); $i++){
//
//        }
//    }
////    echo $get_floor_1;
//}
//故障标志位
//取出车位数据库中车位状态数据


/*
$cp_status ="";
$sql4 = mysql_query("select status from no1_car_place");
while($result4 = mysql_fetch_assoc($sql4)){
    $cp_status = $cp_status.$result4['status'];
}
//echo($cp_status);
//判断现场立体车库车位状态是否与数据库状态一致
$plc_cp_status = $json1['plc'];//取出单片机发送的车位数据
//故障标志位
$error= strcmp($cp_status,$plc_cp_status);
if($error == 0){//若一致则表明无故障，将车位发送给单片机端
//返回单片机数据
    $back['device_id'] = $device_id;
    if($in_cp_num != 0){
        $back['cp_num'] = "201";
    }else{
        $back['cp_num'] = $out_cp_num;
    }
    $back['error'] = $error;
}else{//若不一致，则表示有故障，或者还在存取车操作当中，返回0，不让plc工作
    $back['device_id'] = $device_id;
    $back['cp_num'] = 0;
    $back['error'] = $error;
}*/

$error = "0";

if($confirm_parking == "1"){
    $get_floor_1 = "1";
}else{
    $get_floor_1 = "0";
}

if($in_cp_num != "0"){
    $give_cp_num = $in_cp_num;
//    $fall_io_point = "0";
}else{
    $give_cp_num = $out_cp_num;
//    echo $out_cp_num;
    if($out_cp_num != "0"){
        mysql_query("update no1_car_place set status = 0 where cp_num = $out_cp_num");
        mysql_query("delete from parking_info where cp_num = $out_cp_num");
    }
//    $fall_io_point = $fall_io_point1;
}


$back['device_id'] = $device_id;
$back['cp_num'] = $give_cp_num;
//$back['fall_io_point'] = $fall_io_point;
$back['error'] = $error;
$back['get_floor_1'] = $get_floor_1;

echo json_encode($back);
mysql_close(); //关闭MySQL连接*/

?>