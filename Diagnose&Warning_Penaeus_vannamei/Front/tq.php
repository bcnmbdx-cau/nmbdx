<?php
$url="http://wthrcdn.etouch.cn/weather_mini?city=北京";//
$str = file_get_contents($url);
$result= gzdecode($str);   //解压
$arr = json_decode($result,true); //获取到数组格式的数据。
print_r($arr);
?>