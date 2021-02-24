<?php
$contents=$_POST['contents'];
$i=0;
for($i;$i<10;$i++)
{
    $pH=$pH.$contents[$i].",";
}
for($i;$i<20;$i++)
{
    $DO=$DO.$contents[$i].",";
}
for($i;$i<10;$i++)
{
    $temperature=$temperature.$contents[$i].",";
}

$DO = rtrim($DO, ",");
$pH = rtrim($pH, ",");
$temperature = rtrim($temperature, ",");


exec("python ../Algorithm/main.py {$DO} {$pH} {$temperature}",$out);
var_dump($DO);
var_dump($pH);
var_dump($temperature);
var_dump($out);
?>