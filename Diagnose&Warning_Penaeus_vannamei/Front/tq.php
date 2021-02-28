<?php
$jd ="123.2111";
$wd = "30.2111";
$url = "https://api.caiyunapp.com/v2.5/SqbtWNg8TqBq6ikN/" . "" . $jd.",".$wd . "" . "/realtime.json";//
$str = file_get_contents($url);
//$result = gzdecode($str);   //解压
$arr = json_decode($str, true); //获取到数组格式的数据。
print_r($arr);
if ($arr == null) {?>
    <div class="alert alert-block" style="margin: 20px">
        <button type="button" class="close" data-dismiss="alert">
            &times;
        </button>
        <h4>警告!</h4>
        发生错误了亲，您输入的城市好像没有找到哦！
    </div>
    <?php return;
}
$info = $arr["result"]["realtime"];
?>
<table class="table table-striped table-bordered" style="margin-left: 20px;width: 200px">
    <thead>
    <th>实时天气信息</th>
    </thead>
    <tbody>
    <?php
    //echo "<tr><td>经纬度：</td><td>".$arr["result"]["realtime"]["location"][0]."</td></tr>";
    //echo "<tr><td>经纬度：</td><td>".$arr["result"]["realtime"]["location"][1]."</td></tr>";
    echo "<tr><td>温度：</td><td>".$info["temperature"]."</td></tr>";
    echo "<tr><td>体感温度：</td><td>".$info["apparent_temperature"]."</td></tr>";
    echo "<tr><td>气压：</td><td>".$info["pressure"]."pska</td></tr>";
    echo "<tr><td>相对湿度：</td><td>".$info["humidity"]."</td></tr>";
    echo "<tr><td>风速：</td><td>".$info["wind"]["speed"]."</td></tr>";
    echo "<tr><td>能见度：</td><td>".$info["visibility"]."</td></tr>";
    echo "<tr><td>pm2.5：</td><td>".$info["air_quality"]["pm25"]."</td></tr>";
    ?>
    </tbody>
</table>
