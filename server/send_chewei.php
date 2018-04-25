<?php require_once('Connections/mygarage.php'); ?>
<?php
mysql_select_db("garage",$mygarage);
mysql_query("set names 'utf8'");
//$chewei='0001';
$chewei=$_GET['data'];
$chewei1=mysql_query('select * from danpianji');
while($arr=mysql_fetch_object($chewei1))
{
    if(strcmp($arr->ceshi,$chewei)==0)
    {
        $YESorNO=1;
        break;
    }
    else
    {
        $YESorNO=0;
    }
}
echo ($YESorNO);
//if(strcmp($chewei3,'0001')==0)
//{
//    echo "1";
//}
//else
 //   {echo "0";}
//print_r(mysql_fetch_row($chewei1));
/*if(mysql_query("insert into send_chewei(num,chewei) values('$num','$chewei')"))
{
    echo "1";
}
else
{
    echo "0";
}*/
?>