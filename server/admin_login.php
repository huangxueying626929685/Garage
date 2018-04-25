<?php require_once('Connections/mygarage.php'); ?>
<?php

mysql_select_db("garage",$mygarage);
//$getid='admn';//客户端post过来的管理员用户名
//$getpwd='12345';//客户端post过来的密码
$getid=$_POST['userid'];//客户端post过来的管理员用户名
$getpwd=$_POST['passwd'];//客户端post过来的密码
$sql=mysql_query("SELECT * FROM manager WHERE username = '$getid'");
$result=mysql_fetch_assoc($sql);

if(!empty($result)){
    //存在该用户
    if($getpwd==$result['password']){
        //用户名密码匹配正确

        mysql_query("UPDATE member SET status='1' WHERE username = '$getid'");
        $back['status']="1";
//        $back['info']="login success";

        echo(json_encode($back));
    }else{
//密码错误
        $back['status']="-2";
//        $back['info']="password error";
        echo(json_encode($back));
    }

}else{
    //不存在该用户
    $back['status']="-1";
//    $back['info']="user not exist";
    echo(json_encode($back));
}

mysql_close();
?>