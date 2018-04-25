<?php require_once('Connections/mygarage.php'); ?>
<?php

/*mysql_select_db("member",$mymember);
$sql=mysql_query("SELECT * FROM member WHERE username = 'garage'");
$result=mysql_fetch_assoc($sql);
$getid=$result['username'];
mysql_query("UPDATE member SET status='0' WHERE username = '$getid'");*/

mysql_select_db("garage",$mygarage);
$getid=$_POST['userid'];//客户端post过来的用户名
$getpwd=$_POST['passwd'];//客户端post过来的密码

//$autologin=$_POST['autologin'];

//$getid=18959204245;//客户端post过来的用户名
//$getpwd=123456;//客户端post过来的密码

$sql=mysql_query("SELECT * FROM member WHERE username = '$getid'");
$result=mysql_fetch_assoc($sql);

if(!empty($result)){
    //存在该用户
    if($getpwd==$result['password']){
        //用户名密码匹配正确

        mysql_query("UPDATE member SET status='1' WHERE username = '$getid'");
        $back['status']="1";
//        $back['info']="login success";

    }else{
//密码错误
        $back['status']="-2";
//        $back['info']="password error";
    }

}else{
    //不存在该用户
    $back['status']="-1";
//    $back['info']="user not exist";

}
echo(json_encode($back));

/*if($autologin==1){
    $row=mysql_fetch_assoc($sql);
    setcookie('username',$getid,strtotime('+7 days'));
    $salt='king';
    $auth=md5($getid.$getpwd.$salt).":".$row['id'];//加密
    setcookie('auth',$auth,strtotime('+7 days'));
}else{
    setcookie('username',$getid);
}*/
mysql_close();
?>