<?php
session_start();
header("Content-type:text/html;charset=utf-8");    //设置编码
include_once "fmysql.php";
$author=$_POST['author'];
$title=$_POST['title'];
$content=$_POST['content'];
$last_post_time=date("Y-m-d H:i:s");
$sql="insert into consulting(author,title,content,last_post_time) values('$author','$title','$content','$last_post_time')";
$arr=mysqli_query($link,$sql);
if($arr){
    echo "<script>alert('发布成功');location.href='consulting.php';</script>";
}else{
    echo "<script>alert('好像有点小问题......');location.href='addnew.php';</script>";
}
?>