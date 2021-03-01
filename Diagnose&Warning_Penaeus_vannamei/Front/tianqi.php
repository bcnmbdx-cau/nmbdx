<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <script src="http://libs.baidu.com/jquery/1.9.0/jquery.js"></script>
    <script src="http://libs.baidu.com/bootstrap/2.0.4/js/bootstrap.min.js"></script>
    <link href="http://libs.baidu.com/bootstrap/2.0.4/css/bootstrap.min.css" rel="stylesheet"/>
    <title>天气查询简单版</title>
</head>
<?php

function getWeather() {
    $wcity = file_get_contents("city.txt");
    $pattern = '/([0-9]+)=' . $city . '/';
    preg_match($pattern, $wcity, $matches, PREG_OFFSET_CAPTURE);
    if ($matches == null) {
        return null;
    }
    $cityID = $matches[1][0];
    $data = file_get_contents('http://www.weather.com.cn/data/sk/' . $cityID . '.html');
    $datat=json_decode($data, true);
    //print_r($datat);
//    return $datat;

//这里需要更新经纬度坐标
?>
<script type="text/javascript">
    var x=document.getElementById("demo");
    var jd=120.3;
    var wd=30.3;
    function getLocation()
    {
        if (navigator.geolocation)
        {
            navigator.geolocation.getCurrentPosition(showPosition);
        }
        else{x.innerHTML="Geolocation is not supported by this browser.";}
    }

    function showPosition(position)
    {
        x.innerHTML="纬度: " + position.coords.latitude +
            "<br>经度: " + position.coords.longitude;
        wd=position.coords.latitude;
        jd=position.coords.longitude;
        // var marker1 = new BMap.Marker(new BMap.Point(jd,wd));        // 创建标注
        // map.addOverlay(marker1);                     // 将标注添加到地图中
        // map.centerAndZoom(new BMap.Point(jd,wd),15);//根据坐标初始化地图
    }
</script>
    <?php $jd="<script type=text/javascript>document.write(jd)</script>";
          $wd="<script type=text/javascript>document.write(wd)</script>";
    //$jd ="123.2111";
    //$wd = "30.2111";
    print_r($jd);
    print_r($wd);
    $url = "https://api.caiyunapp.com/v2.5/SqbtWNg8TqBq6ikN/" . "" . $jd.",".$wd . "" . "/realtime.json";//
    $str = file_get_contents($url);
//$result = gzdecode($str);   //解压
    $arr = json_decode($str, true); //获取到数组格式的数据。
    //print_r($arr);
    return $arr;

}

//if ($_POST != null && $_POST["city"] != null) {
$arr = getWeather();
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
    <table class="table table-striped table-bordered" style="table-layout:fixed;width: 75%" align="center"  text-align="center";>
        <thead>
        <th>实时天气信息</th>
        </thead>
        <tbody>
        <?php
        //echo "<tr><td>经纬度：</td><td>".$arr["result"]["realtime"]["location"]."</td></tr>";
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
    <?php

?>

