<?php
session_start();
header("Content-type:text/html;charset=utf-8"); //设置编码
if(empty($_SESSION['username']))
{
    echo "<script>alert('请先登录');location.href='login.html';</script>";
}
include_once "fmysql.php";
$sql = "select * from member where id={$_SESSION['id']}";
$result = mysqli_query($link, $sql);
if (!$result) {
    printf("Error: %s\n", mysqli_error($link));
    exit();
}
else {
    $row=mysqli_fetch_array($result);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script type="text/javascript" src="../js/jquery.1.71.min.js"></script>
    <style type="text/css">
        #disappare {
            border: 3px solid #ccc;
            border-radius: 5px;
            background: #fff;
            font-size: 20px;
            width: 190px;
            height: 67px;
            position: fixed;
            top: 30%;
            left: 50%;
            background-color: yellow;
            color: red;
        }
    </style>
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=16zrDHPLkYrv8e1Tp98hPZ8UUpkKrsdv"></script>
    <script type="text/javascript" src="../js/jquery.1.71.min.js"></script>

</head>

<body>

<div id="disappare" style="display:none;z-index:999;">
    <p  style="margin-top: -80px;">正在获取您当前坐标...</p>
</div>

<div class="pagelist"  style="width:400px;height:400px;margin:0 auto" id="allmap"></div>
<p id="demo">点击按钮获取您当前坐标（可能需要比较长的时间获取）：</p>
<button onclick="tipsWindow();getLocation();">点我</button>
<script type="text/javascript">
    //百度地图API功能
    var map = new BMap.Map("allmap");
    map.enableScrollWheelZoom(true);
    map.addControl(new BMap.NavigationControl());   //平移缩放控件
    map.addControl(new BMap.ScaleControl());        //比例尺
    map.addControl(new BMap.OverviewMapControl());  //缩略地图
    map.addControl(new BMap.MapTypeControl());      //地图类型
    function initMap()
    {
        map.centerAndZoom("宁夏", 3);      // 初始化地图,用城市名设置地图中心点
    }
    function tipsWindow()
    {
        $("#disappare").show().delay(3000).hide(300);//延时
    }
    function showLocation(point)
    {
        var marker1 = new BMap.Marker(point);        // 创建标注
        map.addOverlay(marker1);                     // 将标注添加到地图中
        map.centerAndZoom(point,15);//根据坐标初始化地图
    }
    function httpGetAsync(url)
    {
        //window.open(url);
        var xmlHttp = new XMLHttpRequest();
        xmlHttp.open("GET", url, true); // true 为异步
        xmlHttp.send();
    }
    function getLocation()
    {
        if (navigator.geolocation)
        {
            navigator.geolocation.getCurrentPosition((position) =>
            {
                let lat = position.coords.latitude;
                let lng = position.coords.longitude;
                const pointBak = new BMap.Point(lng, lat);
                const convertor = new BMap.Convertor();
                convertor.translate([pointBak], 1, 5,function(resPoint) {
                    if(resPoint && resPoint.points && resPoint.points.length>0){
                        lng = resPoint.points[0].lng;
                        lat = resPoint.points[0].lat;
                    }
                    const point = new BMap.Point(lng, lat);
                    const geo = new BMap.Geocoder();
                    geo.getLocation(point, (res) => {
                        httpGetAsync("../Ground/saveLocation.php?location_x="+lng+"&location_y="+lat+"&user_id="+"<?php echo $_SESSION['id']?>");
                        showLocation(point);
                    });
                });
            });
        }
        else{x.innerHTML="Geolocation is not supported by this browser.";}
    }
</script>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <script src="http://libs.baidu.com/jquery/1.9.0/jquery.js"></script>
    <script src="http://libs.baidu.com/bootstrap/2.0.4/js/bootstrap.min.js"></script>
    <link href="http://libs.baidu.com/bootstrap/2.0.4/css/bootstrap.min.css" rel="stylesheet"/>
    <title>天气查询简单版</title>
</head>
<?php
if($row['location_x']&&$row['location_y'])
{
    echo "<script type='text/javascript'>showLocation(new BMap.Point({$row['location_x']},{$row['location_y']}))</script>";
}
else
{
    echo "<script type='text/javascript'>initMap()</script>";
}

//function getWeather() {
//    $wcity = file_get_contents("city.txt");
//    $pattern = '/([0-9]+)=' . $city . '/';
//    preg_match($pattern, $wcity, $matches, PREG_OFFSET_CAPTURE);
//    if ($matches == null) {
//        return null;
//    }
//    $cityID = $matches[1][0];
//    $data = file_get_contents('http://www.weather.com.cn/data/sk/' . $cityID . '.html');
//    $datat=json_decode($data, true);
    //print_r($datat);
    //    return $datat;

    //这里需要更新经纬度坐标
        $jd=$row['location_x'];
        $wd=$row['location_y'];
    //$jd ="123.2111";
    //$wd = "30.2111";
    print_r($jd);
    print_r($wd);
    $url = "https://api.caiyunapp.com/v2.5/SqbtWNg8TqBq6ikN/" . "" . $jd.",".$wd . "" . "/realtime.json";//
    $str = file_get_contents($url);
//$result = gzdecode($str);   //解压
    $arr = json_decode($str, true); //获取到数组格式的数据。
    //print_r($arr);
//    return $arr;

//}

//if ($_POST != null && $_POST["city"] != null) {
//$arr = getWeather();
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
</body>