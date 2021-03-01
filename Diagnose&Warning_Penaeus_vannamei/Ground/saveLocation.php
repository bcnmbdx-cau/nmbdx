<?php
include_once "mysql.php";
$sql = "update member set location_x={$_GET['location_x']},location_y={$_GET['location_y']} where id={$_GET['user_id']}";
echo $sql;
$result = mysqli_query($link, $sql);
if (!$result) {
    printf("Error: %s\n", mysqli_error($link));
    exit();
}
?>