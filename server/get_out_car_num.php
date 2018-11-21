<?php require_once('Connections/mygarage.php'); ?>
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/16
 * Time: 10:26
 */
mysql_select_db("garage",$mygarage);

//$json = $_GET['data'];
$json = '{"id":"1","car":"B8D3F16712"}';
//解析json数据
$json1 = json_decode($json, true);
//取出设备id，判断车库号
$device_id = $json1['id'];

//判断是否有车牌信息
$car_num1 = $json1['car'];//取出单片机传来的车牌数据以待解析
$car_num = resolve_car_num($car_num1);//解析车牌

$sql = mysql_query("SELECT * FROM parking_info WHERE car_num = '$car_num'");
$found = mysql_fetch_assoc($sql);
if(!empty($found))
{
    $temp = "1";
//    mysql_query("DELETE FROM parking_info  WHERE username = $get_username");
    if(!(mysql_query("update parking_info set confirm_out = '$temp' where car_num = '$car_num'"))){
        die(mysql_error());
    }
    $back['status'] = "1";
}else{
    $back['status'] = "-1";
}
echo(json_encode($back));
mysql_close();

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

?>