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
        $("#disappare").show().delay(3000).hide(300);
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
<?php
if($row['location_x']&&$row['location_y'])
{
    echo "<script type='text/javascript'>showLocation(new BMap.Point({$row['location_x']},{$row['location_y']}))</script>";
}
else
{
    echo "<script type='text/javascript'>initMap()</script>";
}
?>
</body>