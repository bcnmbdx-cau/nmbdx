<?php
include_once "mysql.php";
$id = $_GET['id'];
if( !is_numeric($id) ){
    echo "ERROR!";
    exit;
}
$sql = "delete from diagnose where id = $id";
$result = mysqli_query($link, $sql);

if($result){
    echo "删除成功！";
    header("Location: data_list.php");
} else {
    echo "删除失败！";
}

