<?php require_once('Connections/mygarage.php'); ?>
<?php
header("Content-Type:text/html;charset=utf8");
mysql_select_db("garage", $mygarage);
mysql_query("SET NAMES utf8", $mygarage);

/******************接收解析单片机发来的数据*******************/
//接收单片机数据
$json = $_GET['data'];
//$json = '{"id":"1","car":"B8D3F16712","free_num":"7","f_1":"011011","fall":"1","all":"00101010110"}';
//$json = '{"id":"1","car":"D4C1CKC236","free_num":"1","f_1":"0","fall":"1","all":"00101010110"}';
//$json = '{"id":"1","car":"CBD5E9E5F9","free_num":"1","f_1":"011011","fall":"1","all":"00101010110"}';
//$json = '{"id":"1","car":"D4A5FSQ818","free_num":"1","f_1":"0","fall":"0","all":"00101010110"}';
//$json = '{"id":"1","car":"BEA9NH1N10","free_num":"1","f_1":"011010","fall":"0","all":"00101010110"}';
//$json = '{"id":"1","car":"0","free_num":"1","f_1":"011010","fall":"0","all":"00101010110"}';
//floor_1表示一层的车位状态信息，前三个数字表示限位开关状态，1表示一楼的车位，0表示高层车位
//floor_1后三个数字表示是否有车状态，1表示有车，0表示没车
//all_floor表示除一层之外的所有车位状态信息

//判断长度
$len = strlen($json);
//解析json数据
$json1 = json_decode($json, true);
//取出设备id，判断车库号
$device_id = $json1['id'];
$garage_id = "no" . $device_id . "_car_place";
$floor_1_id = "no" . $device_id . "_floor_1";
//取出空闲车位数量
$free_num = hexdec($json1['free_num']);
//更新数据库空闲车位数量
if (!(mysql_query("update garage_info set free_num = '$free_num' where id = '$device_id'"))) {
    die(mysql_error());
}
/******************END*******************/

/******************取出已停好车用户的分配车位*******************/
//查找parking_info表中confirm_parking为1的订单，判断确认停车的用户
$temp = "1";
$sql5 = mysql_query("select * from parking_info 
      where confirm_parking = '$temp'");
$result5 = mysql_fetch_assoc($sql5);
$parking_id = $result5['parking_id'];
$pre_car_num = $result5['car_num'];
$pre_cp_num = $result5['cp_num'];
$confirm_parking = $result5['confirm_parking'];//上一次是否完成停车
/******************END*******************/

/******************判断已停好车的用户的真实停车车位*******************/
//解析出第一层车位的状态
$floor_1 = $json1['f_1'];
//如果收到的floor_1不等于0，且完成停车标志为1，判断上一次车停在了哪一个车位上
get_true_cp_num($pre_cp_num, $confirm_parking, $floor_1, $parking_id, $floor_1_id, $garage_id);
/******************END*******************/

/******************确认取车离开的用户订单处理*******************/
//查找是否有确认取车的订单，存入历史订单信息表，并在停车信息表中删除该订单，
deal_with_out_order($garage_id);
/******************END*******************/

/******************整体车位状态判断*******************/
//总体车位状态判断
$all_floor = $json1['all'];
/******************END*******************/

/******************判断是否有用户正在取车或存车*******************/
//判断parking_info数据表中是否有正在停车的用户订单
$sql6 = mysql_query("select * from parking_info where finish_parking = '0'");
$result6 = mysql_fetch_assoc($sql6);
//判断parking_info数据表中是否有正在取车的用户订单 正在取车的confirm_out为 -1
$sql7 = mysql_query("select * from parking_info where pay_status = '1' and confirm_out = '-1'");
$result7 = mysql_fetch_assoc($sql7);
/******************END*******************/

/******************存车流程*******************/
//判断是否有车牌信息
$car_num1 = $json1['car'];//取出单片机传来的车牌数据以待解析
$car_num = resolve_car_num($car_num1);//解析车牌
$in_cp_num = in_car_process($car_num, $garage_id, $result6, $result7);
/******************END*******************/

/******************存入单片机数据表*******************/
store_danpianji_data($json, $len, $device_id, $car_num, $free_num, $floor_1, $all_floor);
/******************END*******************/

/******************取车流程*******************/
//取出支付状态为1,且未开始取车的车位号
$out_cp_num = out_car_process($result6, $result7);
/******************END*******************/

/******************返回单片机数据*******************/
$back = back_to_danpianji($confirm_parking, $in_cp_num, $out_cp_num, $device_id, $floor_1_id);
echo json_encode($back);

//返回单片机的数据存入back_danpianji数据表
//获取当前时间
date_default_timezone_set('PRC'); //设置中国时区
$now_time = date("Y-m-d H:i:s");
$back1 = json_encode($back);
mysql_query("update back_danpianji set now_time = '$now_time',back = '$back1' where id = 1");
/******************END*******************/

mysql_close(); //关闭MySQL连接*/

//判断确认停车的用户真实停车车位函数
function get_true_cp_num($pre_cp_num, $confirm_parking, $floor_1, $parking_id, $floor_1_id, $garage_id)
{
    if (($confirm_parking == "1") && ($floor_1 != "0")) {
        $confirm_parking = "0";
        mysql_query("update parking_info set confirm_parking = $confirm_parking where confirm_parking = 1");

        //取出上一次车位的第一位
        $first = substr($pre_cp_num, 0, 1);

        //确定上一次分配的车位是否是第一层的
        //记录下一层的高层车架的车位号，存入floor_1数据表
        if ($first != "1") {//不是一层的，存入第一层的高层车架板：high_floor_cp_num，确定第一层的高层板具体的车位号
            $high_floor_cp_num = $pre_cp_num;
            if (!(mysql_query("update $floor_1_id set high_floor_cp_num = $high_floor_cp_num"))) {
                die(mysql_error());
            }
        }
        //确定停在那个车位
        $true_cp_num = ensure_cp_num($floor_1, $pre_cp_num, $garage_id, $floor_1_id);
        //更新停车信息数据库中的车位号
        mysql_query("update parking_info set cp_num = $true_cp_num
                 where parking_id = $parking_id");

        //将对应的车位状态置1
        mysql_query("update $garage_id set status = 1 where cp_num = $true_cp_num");
        //删除预约信息
        // mysql_query("delete from order_info where car_num = $pre_car_num");

    }
}

function ensure_cp_num($floor_1, $pre_cp_num, $garage_id, $floor_1_id)
{
    $floor_1_length = strlen($floor_1);
    //取出限位开关状态信息
    $floor_1_place = substr($floor_1, 0, $floor_1_length / 2);
//    echo $floor_1_place;
//    echo '<br>';
    //取出有否有车状态信息
    $floor_1_status = substr($floor_1, $floor_1_length / 2, $floor_1_length);
//    echo $floor_1_status;
//    echo '<br>';
    $place = array();
    $status = array();
    //取出第一层车位位置和车位状态的信息存入数组
    for ($i = 0; $i < strlen($floor_1_status); $i++) {
        $place[$i] = substr($floor_1_place, $i, 1);
        $status[$i] = substr($floor_1_status, $i, 1);
//        echo $place[$i];
//        echo '<br>';
//        echo $status[$i];
//        echo '<br>';
    }
    //echo json_encode($status);
    $k = 101; //第一层车位
    $get_cp_num = 0; //是否获得最终停车的位置标志
    $true_cp_num = $pre_cp_num;
    $j = 0;
    //判断出上一次停车的车位号
    for ($i = 0; $i < strlen($floor_1_status); $i++) {
        if ($place[$i] == "1") {//限位开关为1，表示当前位置是第一层的车位
            $sql8 = mysql_query("select * from $garage_id 
                    where cp_num = $k");
            $result8 = mysql_fetch_assoc($sql8);
            $cp_k_status = $result8['status'];//取出数据库中的车位k的状态
            if ($status[$i] != $cp_k_status) {//如果单片机传来的当前位置信息与数据库不匹配，则表示车停到了该车位
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
        $sql10 = mysql_query("select * from $floor_1_id where id = 1");
        $result10 = mysql_fetch_assoc($sql10);
        $high_floor_cp_num1 = $result10['high_floor_cp_num'];//取出第一层高层车架的车位号
        $sql9 = mysql_query("select * from $garage_id where cp_num = $high_floor_cp_num1");
        $result9 = mysql_fetch_assoc($sql9);
        $cp_high_status = $result9['status'];//取出数据库中的高层车位的状态

        if ($status[$j] != $cp_high_status) {
            $true_cp_num = $high_floor_cp_num1;//此时表示用户停进去的车位号
        }
    }
    return $true_cp_num;
}

//确认取车离开的订单处理
function deal_with_out_order($garage_id)
{
    $sql8 = mysql_query("select * from parking_info where confirm_out = '1'");
    $result8 = mysql_fetch_assoc($sql8);
    if (!empty($result8)) {
        $username_1 = $result8['username'];
        $car_num_1 = $result8['car_num'];
        $garage_num_1 = $result8['garage_num'];
        $cp_num_1 = $result8['cp_num'];
        $start_time_1 = $result8['start_time'];
        $leave_time_1 = $result8['leave_time'];
        $money_1 = $result8['money'];
//存入历史订单
        mysql_query("insert into parking_history(username,car_num,garage_num,cp_num,start_time,leave_time,money) 
values('$username_1','$car_num_1','$garage_num_1','$cp_num_1','$start_time_1','$leave_time_1','$money_1')");
//删除订单信息表中的订单
        mysql_query("delete from parking_info where cp_num = $cp_num_1");

//确认取车则将车位数据表的车位状态置0
        mysql_query("update $garage_id set status = 0 where cp_num = $cp_num_1");

    }
}

//解析车牌函数
function resolve_car_num($car_num1)
{
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
    return $car_num;
}

//存入单片机数据表
function store_danpianji_data($json, $len, $device_id, $car_num, $free_num, $floor_1, $all_floor)
{
    //获取当前时间
    date_default_timezone_set('PRC'); //设置中国时区
    $time = date("Y-m-d H:i:s");
//存入单片机数据表
    mysql_query("update danpianji set time1 = '$time',device_id = '$device_id',car_num = '$car_num',
free_num = '$free_num',floor_1 = '$floor_1',all_floor = '$all_floor',datas = '$json', len = '$len' where id = 1");
}

//存车流程函数
function in_car_process($car_num, $garage_id, $result6, $result7)
{
    $in_cp_num = "0";

    if ($car_num != "0") {
        //搜索order_info数据表是否有该车牌号
        $sql = mysql_query("select * from order_info where car_num = '$car_num'");//从数据表order_info（手机端订单数据库）取车牌号
        $result = mysql_fetch_assoc($sql);

        //判断用户是否迟到
        $username = $result['username'];
        $late_car_num = $result['car_num'];
        $action_time = $result['action_time'];
        $order_time = $result['start_time'];
        //获取当前时间
        date_default_timezone_set('PRC'); //设置中国时区
        $start_time = date("Y-m-d H:i");
        //判断是否迟到
        if (strtotime($order_time) < strtotime($start_time)) {
            //预约超时，删除预约信息
            mysql_query("delete from order_info where car_num = '$late_car_num'");
            $money = "0.01";
            //存入用户迟到订单表
            if(!empty($username)) {
                mysql_query("insert into late_order(username,car_num,action_time,order_time,start_time,money)
        values('$username','$late_car_num','$action_time','$order_time','$start_time','$money') ");
            }
            //超时费用
//        $late_minute = floor((strtotime($order_time)-strtotime($action_time))/86400/60);
//        $extra_cost = $late_minute * ($price_per_hour/2);
        }
        //查询是否有空闲车位

        //车位分配
        //如果订单信息中没有正在停车和取车的用户订单，且车牌号已预约，在预约时间前进入车库，则进行车位分配
        if ((!empty($result)) && (empty($result6)) && (empty($result7)) && (strtotime($order_time) >= strtotime($start_time))) {
            //分配车位
            $sql4 = mysql_query("select * from parking_info 
             where car_num = '$car_num'");
            $result4 = mysql_fetch_assoc($sql4);

//        echo $result4['car_num'];
            //如果订单信息中没有该车牌号信息，才存入，若已有则不存入
            if (empty($result4)) {
                $sql1 = mysql_query("select * from $garage_id where status = 0 ");
                $result1 = mysql_fetch_assoc($sql1);
                //分配车位
                $in_cp_num = $result1['cp_num'];
                //mysql_query("update $garage_id set status = 1 where cp_num = $in_cp_num");

                //    echo $start_time;
                $sql2 = mysql_query("select * from order_info where car_num = '$car_num'");
                $result2 = mysql_fetch_assoc($sql2);
                $username = $result2['username'];
                $garage_num = $result2['garage_num'];
                mysql_query("delete from order_info where username = '$username'");
                //形成订单信息，存入parking_info 数据表
                mysql_query("insert into parking_info(username,car_num,garage_num,cp_num,finish_parking,action_time,order_time,start_time)
                      value ('$username','$car_num','$garage_num','$in_cp_num',0,'$action_time','$order_time','$start_time')");
            }

        } else {//没有预约，或者上一次停车未完成
            $in_cp_num = "0";
        }

    } else {//无车牌信息
        $in_cp_num = "0";
    }
    return $in_cp_num;
}

//取车流程
function out_car_process($result6, $result7)
{
    $sql3 = mysql_query("select * from parking_info where pay_status = 1 and confirm_out = 0");
    $result3 = mysql_fetch_assoc($sql3);
//如果有支付完成的，且保证车库内没有用户正在取车或存车，则将取车的车位号存入out_cp_num
    if ((!empty($result3)) && (empty($result6)) && (empty($result7))) {
        $out_cp_num = $result3['cp_num'];

    } else {//不满足则置为0
        $out_cp_num = "0";
    }
    return $out_cp_num;
}

//返回单片机数据
function back_to_danpianji($confirm_parking, $in_cp_num, $out_cp_num, $device_id, $floor_1_id)
{
    //故障情况
    $error = "0";
//确定是否需要车库第一层的状态数据
    if ($confirm_parking == "1") {
        $get_floor_1 = "1";
    } else {
        $get_floor_1 = "0";
    }
//确定给单片机的车位号，存车还是取车
    if ($in_cp_num != "0") {
        $give_cp_num = $in_cp_num;
//    $fall_io_point = "0";
    } else {
        $give_cp_num = $out_cp_num;;
//    echo $out_cp_num;
        if ($out_cp_num != "0") {
            //若给单片机的是要取车的车位号，则表示将要开始取车，将confirm_out置为-1
            mysql_query("update parking_info set confirm_out = '-1' where cp_num = '$out_cp_num'");
            //更新第一层的高层车架
            mysql_query("update $floor_1_id set high_floor_cp_num = '$out_cp_num' where id = 1");
        }
    }

    $back['device_id'] = $device_id;
    $back['cp_num'] = $give_cp_num;
    $back['error'] = $error;
    $back['get_floor_1'] = $get_floor_1;
    return $back;
}

//调试打印信息
/*echo("单片机传来的json数据：" . $json);
echo '<br>';
echo ("车库号".$garage_id);
echo '<br>';
echo ("车库第一层号".$floor_1_id);
echo '<br>';
echo("空闲车位数量：" . $free_num);
echo '<br>';
echo("车牌号：" . $car_num);
echo '<br>';
echo("上一次的确认停车标志：" . $confirm_parking);
echo '<br>';
echo("第一层车位状况：" . $floor_1);
echo '<br>';
echo ("单片机总体车位状况：".$all_floor);
echo '<br>';
echo("in_cp_num：" . $in_cp_num);
echo '<br>';
echo("out_cp_num：" . $out_cp_num);
echo '<br>';
echo("正在取车车位：" . $result7['cp_num']);
echo '<br>';
echo("正在存车车位：" . $result6['cp_num']);
echo '<br>';*/

/*
$cp_status ="";
$sql4 = mysql_query("select status from $garage_id");
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
?>