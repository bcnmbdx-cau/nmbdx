<?php
session_start();
include_once "./mysql.php";
// 设置mysql字符集 为 utf8
$link->query("set names utf8");
    $username = $_POST['username'];//获取用户名
    $password = $_POST['password'];//获取密码
    if($username==''){
        echo "<script>console.log('请输入用户名');location='" . $_SERVER['HTTP_REFERER'] . "'</script>";
        exit;
    }
    if($password==''){

        echo "<script>console.log('请输入密码');location='" . $_SERVER['HTTP_REFERER'] . "'</script>";
        exit;

    }
    $sql="select id,username,password,category_id from member where username= '{$username}'";
    $que=mysqli_query($link,$sql);
    $row=mysqli_fetch_array($que);
if($row){

    if($password !=($row['password']) || $username !=$row['username']){

        echo "<script>alert('密码错误，请重新输入');location='../Front/login.html'</script>";
        exit;
    }
    else{
        $_SESSION['username']=$row['username'];
        $_SESSION['id']=$row['id'];
        $_SESSION['category_id']=$row['category_id'];
        if($row['category_id']==1)
            echo "<script>console.log('登录成功');location='G_index.html'</script>";
        else
            echo "<script>console.log('登录成功');location='../Front/index.php'</script>";
    }

}else{
    echo "<script>alert('您输入的用户名不存在');location='../Front/login.html'</script>";
    exit;
}
?>