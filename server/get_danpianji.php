<?php require_once('Connections/mygarage.php'); ?>
<?php
header("Content-Type:text/html;charset=utf8");
mysql_select_db("garage",$mygarage);
//mysql_query("set names 'utf8'");
//$json = $_GET['data'];//接收单片机数据

$json = '{"device_id":"1","car_num":"B8D3F16712","plc":"0110110110110"}';
//$num=$_GET['data'];
$len = strlen($json);

$json1 = json_decode($json,true);
$device_id = $json1['device_id'];

$car_num1 = $json1['car_num'];
$car_first=substr($car_num1,0,4);//收到的车牌的前4位，准备转化为省份

//将前四位装换成省份，解析出车牌号存入$car_num
switch($car_first)//解析协议
{
    case 'BEA9':
        //将接受到的车牌的前4位字符替换成相应的汉字；下同
        $car_num=substr_replace($car_num1,'京',0,4);
        break;
    case 'BDF2':
        $car_num=substr_replace($car_num1,'津',0,4);
        break;
    case 'BCBD':
        $car_num=substr_replace($car_num1,'冀',0,4);
        break;
    case 'BDFA':
        $car_num=substr_replace($car_num1,'晋',0,4);
        break;
    case 'C3C9':
        $car_num=substr_replace($car_num1,'蒙',0,4);
        break;
    case 'C1C9':
        $car_num=substr_replace($car_num1,'辽',0,4);
        break;
    case 'BCAA':
        $car_num=substr_replace($car_num1,'吉',0,4);
        break;
    case 'BADA':
        $car_num=substr_replace($car_num1,'黑',0,4);
        break;
    case 'BBA6':
        $car_num=substr_replace($car_num1,'沪',0,4);
        break;
    case 'CBD5':
        $car_num=substr_replace($car_num1,'苏',0,4);
        break;
    case 'D5E3':
        $car_num=substr_replace($car_num1,'浙',0,4);
        break;
    case 'CDEE':
        $car_num=substr_replace($car_num1,'皖',0,4);
        break;
    case 'C3F6':
        $car_num=substr_replace($car_num1,'闽',0,4);
        break;
    case 'B8D3':
        $car_num=substr_replace($car_num1,'赣',0,4);
        break;
    case 'C2B3':
        $car_num=substr_replace($car_num1,'鲁',0,4);
        break;
    case 'D4A5':
        $car_num=substr_replace($car_num1,'豫',0,4);
        break;
    case 'B6F5':
        $car_num=substr_replace($car_num1,'鄂',0,4);
        break;
    case 'CFE6':
        $car_num=substr_replace($car_num1,'湘',0,4);
        break;
    case 'D4C1':
        $car_num=substr_replace($car_num1,'粤',0,4);
        break;
    case 'B9F0':
        $car_num=substr_replace($car_num1,'桂',0,4);
        break;
    case 'C7ED':
        $car_num=substr_replace($car_num1,'琼',0,4);
        break;
    case 'D3E5':
        $car_num=substr_replace($car_num1,'渝',0,4);
        break;
    case 'B4A8':
        $car_num=substr_replace($car_num1,'川',0,4);
        break;
    case 'B9F3':
        $car_num=substr_replace($car_num1,'贵',0,4);
        break;
    case 'D4C6':
        $car_num=substr_replace($car_num1,'云',0,4);
        break;
    case 'B2D8':
        $car_num=substr_replace($car_num1,'藏',0,4);
        break;
    case 'C9C2':
        $car_num=substr_replace($car_num1,'陕',0,4);
        break;
    case 'B8CA':
        $car_num=substr_replace($car_num1,'甘',0,4);
        break;
    case 'C7E0':
        $car_num=substr_replace($car_num1,'青',0,4);
        break;
    case 'C4FE':
        $car_num=substr_replace($car_num1,'宁',0,4);
        break;
    case 'D0C2':
        $car_num=substr_replace($car_num1,'新',0,4);
        break;
    case '0x31':
        $car_num=substr_replace($car_num1,'粤Z',0,4);
        break;
    case '0x32':
        $car_num=substr_replace($car_num1,'粤Z',0,4);
        break;
    case '0x33':
        $car_num=substr_replace($car_num1,'粤Z',0,4);
        break;
    default:
        $car_num = 0;
        break;
}
//echo $car_num;

mysql_query("update danpianji set device_id = '$device_id',car_num = '$car_num',
 datas = '$json', len = '$len' where id = 1");

$sql = mysql_query("select * from order_info where car_num = '$car_num'");//从数据表order_info（手机端订单数据库）取车牌号
$result = mysql_fetch_assoc($sql);
if(!empty($result)){//如果车牌匹配，则将数据存入单片机数据表
        //分配车位
        $sql1 = mysql_query("select * from no1_car_place where status = 0 ");
        $result1 = mysql_fetch_assoc($sql1);
        $in_cp_num = $result1['cp_num'];
//        mysql_query("update no1_car_place set status = 1 where cp_num = $in_cp_num");
        //获取当前时间
        date_default_timezone_set('PRC'); //设置中国时区
        $start_time = date("Y-m-d H:i");
    //    echo $start_time;
        $sql2 = mysql_query("select * from order_info where car_num = '$car_num'");
        $result2 = mysql_fetch_assoc($sql2);
        $username = $result2['username'];
        $garage_num = $result2['garage_num'];
/*        if(!(mysql_query("insert into parking_info(username,car_num,garage_num,cp_num,start_time)
            value ('$username','$car_num','$garage_num','$in_cp_num','$start_time')"))) {
            die (mysql_error());//错误原因
        }*/

        }else{
            $in_cp_num = 0;
        }

    $sql3 = mysql_query("select * from parking_info where pay_status = 1");
    $result3 = mysql_fetch_assoc($sql3);
    if(!empty($result3)){
       $out_cp_num = $result3['cp_num'];
    }else{
       $out_cp_num = 0;
    }
    //故障标志位
    $error = 0;

//判断现场立体车库车位状态是否与数据库状态一致
$sql4 = mysql_query("select status from no1_car_place");
$result4 = mysql_fetch_assoc($sql4);

    //返回单片机数据
$back['device_id'] = $device_id;
$back['in_cp_num'] = $in_cp_num;
$back['out_cp_num'] = $out_cp_num;
$back['error'] = $error;

echo json_encode($back);

mysql_close(); //关闭MySQL连接

?>