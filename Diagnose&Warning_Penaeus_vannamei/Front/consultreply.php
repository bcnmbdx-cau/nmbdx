<?php
session_start();
header("Content-type:text/html;charset=utf-8");    //设置编码
include_once "fmysql.php";
$id=$_GET['id'];
$reply_author=$_SESSION['username'];
$reply=$_POST['reply'];
$reply_time=date("Y-m-d H:i:s");
$reply_member_category=$_GET['reply_member_category'];

if($reply_member_category==1)
{
    $sql="update consulting set reply_author='$reply_author',reply='$reply',reply_time='$reply_time' WHERE id='$id'";
}
else
{
    $sql="insert into consulting_reply values ({$id},'$reply','$reply_author','$reply_time')";
}
$arr=mysqli_query($link,$sql);
if($arr){
    echo "<script>alert('回复成功');location.href='details.php?id={$id}';</script>";
}else{
    echo "<script>alert('你的回复好像有点小问题.....');location.href='details.php';</script>";
}
?>