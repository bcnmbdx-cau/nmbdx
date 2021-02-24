<?php
include_once "./mysql.php";
$username=$_POST['username'];
$password=$_POST['password'];
echo $password;
$sql1="select * from member where username='{$username}'";
$que=mysqli_query($link,$sql1);
if(!$que){
    echo"<script>alert('用户名已经被注册');location.href='../Front/reg.html';</script>";
}else{
    $category_id=3;
    $sql="insert into member(username,password,category_id)VALUES
    ('$username','$password','$category_id')";
    $que=mysqli_query($link,$sql);
    $_SESSION['username']=$username;
    echo "<script>alert('注册成功');location.href='../Front/index.php';</script>";
}
?>