<?php
header("Content-type:text/html;charset=utf-8");    //设置编码
include_once "fmysql.php";
$category_id=$_POST['category_id'];
$author=$_POST['author'];
$title=$_POST['title'];
$tag=$_POST['tag'];
$content=$_POST['content'];
$created_at=date("Y-m-d H:i:s");
$sql="insert into information(category_id,author,title,tag,content,created_at) values('$category_id','$author','$title','$tag','$content','$created_at')";
$arr=mysqli_query($link,$sql);
if($arr){
    echo "<script>alert('发布成功');location.href='index.php';</script>";
}else{
    echo "<script>alert('好像有点小问题......');location.href='publish_news.php';</script>";
}
?>