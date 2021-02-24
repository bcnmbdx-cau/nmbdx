<?php
include_once "mysql.php";
$id = $_GET['id'];
if( !is_numeric($id) ){
    echo "ERROR!";
    exit;
}
$sql = "delete from member where id = $id";
$result = mysqli_query($link, $sql);

if($result){
    echo "删除成功！";
    header("Location: user_management.php");
} else {
    echo "删除失败！";
}
