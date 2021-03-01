<?php
session_start();
if($_GET['act']=="loginout"){
    $_SESSION['username']='';
    ?>
    <script>
        location.href="?";
    </script>
    <?
    exit;
}
header("Content-type:text/html;charset=utf-8");
include_once "../Front/fmysql.php";
function consult($link,$pageNum = 1, $pageSize = 6)
{
    $Head=($pageNum - 1) * $pageSize;
    $sql = "select * from consulting order by id desc limit $Head,$pageSize";
    $result = mysqli_query($link, $sql);
    if (!$result) {
        printf("Error: %s\n", mysqli_error($link));
        exit();
    }
    return $result;
}
function get_Max_Page($link)
{
    $sql_count = "select count(*) as amount from consulting";
    $result_amount = mysqli_query($link, $sql_count);
    if (!$result_amount)
    {
        return 0;
    } else
    {
        $arr_amount = mysqli_fetch_assoc($result_amount);
        $amount = $arr_amount['amount'];
        $page_size = 8;
        $max_page = ceil($amount / $page_size);
        return $max_page;
    }
}
@$max_page=get_Max_Page($link);
@$pageNum = empty($_GET["pageNum"])?1:$_GET["pageNum"];
@$pageSize =8;
@$arr = consult($link,$pageNum,$pageSize);
//地图数据库
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
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>白对虾</title>
    <link href="../css/slimize.css" rel="stylesheet">
    <link href="../css/tsd-ui.css" rel="stylesheet">
    <link href="../css/common.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/table.css">
    <script src="../js/jquery.1.71.min.js"></script>
    <script src="../js/jquery.SuperSlide.2.1.1.js"></script>


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


    <script src="../js/jquery.1.11.3.min.js"></script>
    <style>
        table,tbody,tfoot,thead,tr,th,td {
            margin:0;
            padding:0;
            outline:0;
            font-size:100%;
            vertical-align:baseline;
            background:transparent;
            border-collapse:collapse;
            border-spacing:0;
            border:0px;
        }
        .tablebox {
            width:300px;
            height:400px;
            overflow:hidden;
            margin:50px auto;
        }
        .tablebox table {
            width:100%;
        }
        .tablebox table th,.tablebox table td {
            font-size:12px;
            text-align:center;
            line-height:36px;
        }
        .tablebox table th {
            color:#2584e3;
            background-color:#f6f6f6;
        }
        .tablebox table td img {
            display:inline-block;
            vertical-align:middle;
        }
        .tablebox table tbody tr:nth-child(even) {
            background-color:#f6f6f6;
        }
        .tablebox.table_md table td,.tablebox.table_md table th {
            line-height:40px;
        }
    </style>
</head>
<style type="text/css">
    .slideBox .prev,.slideBox .next{
        position: absolute;
        display: block;
    }
    .slideBox .prev:hover,.slideBox .next:hover{
        filter: alpha(opacity=80);
        opacity: 0.8;
    }
    .slideBox .prevStop{
        display: none;
    }
    .slideBox .nextStop{
        display: none;
    }
    .slideBox1 .prev,.slideBox1 .next{
        left: 0;
        top: 50%;
        width: 32px;
        height: 64px;
        margin-top: -32px;
        background-image: url(../images/mb3-slider-arrow1.png);
        background-repeat: no-repeat;
    }
    .slideBox1 .next{
        left: auto;
        right: 0;
        background-image: url(../images/mb3-slider-arrow3.png);
    }
    .slideBox1 .prev:hover{
        background-image: url(../images/mb3-slider-arrow2.png);
    }
    .slideBox1 .next:hover{
        background-image: url(../images/mb3-slider-arrow4.png);
    }
    .news-list .date {
        color: #999;
    }
    img:hover {
        filter: alpha(opacity=100) !important;
        opacity: 1 !important;
    }
    .com-sel {
        line-height: 1.1rem;
        cursor: pointer;        /*鼠标上移变成小手*/
    }

    .com-opt {
        color: #afbac0;
        font-size: 1.0rem;
        border: none;
        outline: none;
        /*去掉默认的下拉三角*/
        appearance:none;
        -moz-appearance:none;
        -webkit-appearance:none;
        /*添加下拉三角图标*/
        background: url("../images/select.jpg") no-repeat right center transparent;
        width: 100px;

    }
</style>
<style>
    .slideBox-common { width:1200px !important;}
    .slideBox-common .bd li{height:120px !important;width:1200px !important;}
    .slideBox-common li img{width:1200px !important;}
    .slideBox-common .hd ul { height:12px !important;}
    .slideBox-common .hd { bottom:15px !important; }
    .pagelist {padding:10px 0; text-align:center;}
    .pagelist span,.pagelist a{ border-radius:3px; border:1px solid #dfdfdf;display:inline-block; padding:5px 12px;}
    .pagelist a{ margin:0 3px;}
    .pagelist span.current{ background:#09F; color:#FFF; border-color:#09F; margin:0 2px;}
    .pagelist a:hover{background:red; color:#FFF; border-color:red; }
    .pagelist label{ padding-left:15px; color:#999;}
    .pagelist label b{color:red; font-weight:normal; margin:0 3px;}
</style>
<body>
<!-- 通用页头 -->
<div id="disappare" style="display:none;z-index:999;">
    <p  style="margin-top: -80px;">正在获取您当前坐标...</p>
</div>

<header class="header" >
    <nav class="nav-center clear">

        <nav class="weather">
            <iframe name="WeatherTool" src="http://i.tianqi.com/index.php?c=code&amp;id=34&amp;icon=1&amp;num=3&amp;py=beijing" width="260" height="28" marginwidth="0" marginheight="0" hspace="0" vspace="0" frameborder="0" scrolling="no" allowtransparency="true" style="padding-top:7px"></iframe>
        </nav>
        <?php
        if($_SESSION['username']){
            ?>
            <a class="buttonm"
                <?php if($_SESSION['category_id']==1)
                {?>
                    href="../Ground/G_index.html" title="点此前往后台"
                <?php }
                else
                {?>
                    href="index.php" title="点此回到首页"
                <?php } ?> > 欢迎您！<? echo $_SESSION['username']?></a>
            <input  class="buttonr" type="button" name="button" id="button" value="退出" onClick="window.location.href = '?act=loginout'">
            <?php
            if ($_SESSION['category_id'] != 3 && $_SESSION['username']) {
                echo "<input  class=\"buttonp\" type=\"button\" name=\"button\" id=\"button\" value=\"发布新闻\" onClick=\"window.location.href = 'publish_news.php'\">";
            }
        }
        else{
            ?>
            <input  class="buttonl" type="button" name="button" id="button" value="登陆" onClick="window.location.href = 'login.html'">
            <a class="buttonm">|</a>
            <input  class="buttonr" type="button" name="button" id="button" value="注册" onClick="window.location.href = 'reg.html'">
            <?php
        }
        ?>
    </nav>
</header>

<figure class="banner">
    <figure class="center">
        <img src="../images/titleOfWeb.jpg" width="1200" height="108">
    </figure>

</figure>

<nav class="nav">
    <ul class="center clear" style="margin-bottom: auto">
        <li>
            <a href="#""><img src="../images/mb23-nav-icon-1.png" class="icon-off" /></a><a href="#""><img src="../images/mb23-nav-icon-1-2.png" class="icon-on" /></a>
            <a href="index.php">首页</a>
        </li>
        <li>
            <a href="#zsdt/"><img src="../images/mb23-nav-icon-2.png" class="icon-off" /></a><a href="#zsdt/"><img src="../images/mb23-nav-icon-2-2.png" class="icon-on" /></a>
            <a href="cultivation.php">养殖技术</a>
        </li>

        <li>
            <a href="#tzzn/"><img src="../images/mb23-nav-icon-4.png" class="icon-off" /></a><a href="#tzzn/"><img src="../images/mb23-nav-icon-4-2.png" class="icon-on" /></a>
            <a href="disease.php">病害知识</a>
        </li>
        <li class="on">
            <a href="#qyxz/"><img src="../images/mb23-nav-icon-5.png" class="icon-off" /></a><a href="#qyxz/"><img src="../images/mb23-nav-icon-5-2.png" class="icon-on" /></a>
            <a href="consulting.php">在线诊断</a>
        </li>
        <li>
            <a href="#zwgk/"><img src="../images/mb23-nav-icon-3.png" class="icon-off" /></a><a href="#zwgk/"><img src="../images/mb23-nav-icon-3-2.png" class="icon-on" /></a>
            <a href="news.php">预警信息</a>
        </li>
    </ul>
</nav>

<style>
    .w1 li a {
        width: 448px !important;
    }
    .w2 li a {
        width: 192px !important;
    }
    .w3 li a {
        width: 298px  !important;
    }
    .nav{    margin-bottom: 0px;
    }</style><!-- main-news end -->

<article class="main-col clear">
    <div class="lp">
        <article class="col-body mb20">
            <article class="col-main clear">
                <article class="bd">
                    <table width="1000px" class="table table-hover" style="margin-top:50px">
                        <tr class="title">
                            <td colspan="4" style="text-align: center;border: none"><h1 style="font-size: 35px">病害诊断</h1><a href="addconsult.php" style="text-decoration: none;color: #ff8181">点此向专家问诊</a></td>
                        </tr>
                        <tr>
                            <td width="270px" style="font-size: 20px;">问题</td>
                            <td width="120px" style="font-size: 20px;">用户</td>
                            <td width="150px"style="font-size: 20px;">时间</td>
                            <td width="60px" style="font-size: 20px;">问题状态</td>
                        </tr>
                        <?php
                        if($pageNum>0) {
                        while($row=mysqli_fetch_array($arr)) {
                        ?>
                        <tr >
                            <td width="270px"><div><a href="details.php?id=<?php echo $row['id']?>"</a><?php echo $row['title']?></div> </td>
                            <td width="120px"><?php echo $row['author'] ?></td>
                            <td width="150px"><?php echo $row['last_post_time']?></td>
                            <td width="60px"><?php if($row['reply_time']) echo "已解答";else echo "未解答"?></td>
                        </tr>
                        <tr>
                            <p></p>
                            <?php }
                            }
                            else{
                                echo "<tr><td colspan='5'>暂无人问诊.....</td></tr>";
                            } ?>

                        </tr>
                        <tr>
                            <td colspan="5">
                                <div class="pagelist" style="text-align: center">
                                    <a href="?pageNum=1">首页</a>
                                    <?php
                                    if( $pageNum > 1 ){
                                        ?>
                                        <a href="?pageNum=<?php echo $pageNum==1?1:($pageNum-1)?>">上一页</a>
                                        <?
                                    }
                                    if( $pageNum < $max_page ){
                                        ?>
                                        <a href=?pageNum=<?php echo $pageNum==$max_page?$max_page:($pageNum+1)?>">下一页</a>
                                        <?
                                    }
                                    ?>
                                    <a href="?pageNum=<?php echo $max_page?>">末页</a>
                                    / 总页码 <font color="red"><?php echo $max_page;?></font>页 当前页码 <font color="red"><?php echo $pageNum;?></font>页
                                </div>
                            </td>
                        </tr>
                    </table>
                </article><!-- bd end -->			  <!-- slideTxtBox end --><!-- slideTxtBox end --><!-- slideTxtBox end --><!-- slideTxtBox end -->
            </article>
        </article>
    </div>
</article>

<footer class="footer">

</footer>
<hr size="500"/>

<!---->
<!--地图功能实现 margin:0 auto -->
<!--<p> </p>-->
<!--<div class="pagelist"  style="width:400px;height:400px;margin-left:200px;" id="allmap"></div>-->
<!--<p style="margin-left:200px;" id="demo">点击按钮获取您当前坐标（可能需要比较长的时间获取）：</p>-->
<!--<p> </p>-->
<!--<button style="margin-left:200px;" onclick="tipsWindow();getLocation();">获取你的位置</button>-->
<!--<script type="text/javascript">-->
<!--    //百度地图API功能-->
<!--    var map = new BMap.Map("allmap");-->
<!--    map.enableScrollWheelZoom(true);-->
<!--    map.addControl(new BMap.NavigationControl());   //平移缩放控件-->
<!--    map.addControl(new BMap.ScaleControl());        //比例尺-->
<!--    map.addControl(new BMap.OverviewMapControl());  //缩略地图-->
<!--    map.addControl(new BMap.MapTypeControl());      //地图类型-->
<!--    function initMap()-->
<!--    {-->
<!--        map.centerAndZoom("宁夏", 3);      // 初始化地图,用城市名设置地图中心点-->
<!--    }-->
<!--    function tipsWindow()-->
<!--    {-->
<!--        $("#disappare").show().delay(3000).hide(300);//延时-->
<!--    }-->
<!--    function showLocation(point)-->
<!--    {-->
<!--        var marker1 = new BMap.Marker(point);        // 创建标注-->
<!--        map.addOverlay(marker1);                     // 将标注添加到地图中-->
<!--        map.centerAndZoom(point,15);//根据坐标初始化地图-->
<!--    }-->
<!--    function httpGetAsync(url)-->
<!--    {-->
<!--        //window.open(url);-->
<!--        var xmlHttp = new XMLHttpRequest();-->
<!--        xmlHttp.open("GET", url, true); // true 为异步-->
<!--        xmlHttp.send();-->
<!--    }-->
<!--    function getLocation()-->
<!--    {-->
<!--        if (navigator.geolocation)-->
<!--        {-->
<!--            navigator.geolocation.getCurrentPosition((position) =>-->
<!--            {-->
<!--                let lat = position.coords.latitude;-->
<!--                let lng = position.coords.longitude;-->
<!--                const pointBak = new BMap.Point(lng, lat);-->
<!--                const convertor = new BMap.Convertor();-->
<!--                convertor.translate([pointBak], 1, 5,function(resPoint) {-->
<!--                    if(resPoint && resPoint.points && resPoint.points.length>0){-->
<!--                        lng = resPoint.points[0].lng;-->
<!--                        lat = resPoint.points[0].lat;-->
<!--                    }-->
<!--                    const point = new BMap.Point(lng, lat);-->
<!--                    const geo = new BMap.Geocoder();-->
<!--                    geo.getLocation(point, (res) => {-->
<!--                        httpGetAsync("../Ground/saveLocation.php?location_x="+lng+"&location_y="+lat+"&user_id="+"--><?php //echo $_SESSION['id']?>
<!--//");-->
<!--//                        showLocation(point);-->
<!--//                    });-->
<!--//                });-->
<!--//            });-->
<!--//        }-->
<!--//        else{x.innerHTML="Geolocation is not supported by this browser.";}-->
<!--//    }-->
<!--//</script>-->
<!--//-->
<!--//<head>-->
<!--//    <meta http-equiv="content-type" content="text/html; charset=utf-8" />-->
<!--//    <script src="http://libs.baidu.com/jquery/1.9.0/jquery.js"></script>-->
<!--//    <script src="http://libs.baidu.com/bootstrap/2.0.4/js/bootstrap.min.js"></script>-->
<!--//    <link href="http://libs.baidu.com/bootstrap/2.0.4/css/bootstrap.min.css" rel="stylesheet"/>-->
<!--//    <title>天气查询简单版</title>-->
<!--//</head>-->
<?php
//if($row['location_x']&&$row['location_y'])
//{
//    echo "<script type='text/javascript'>showLocation(new BMap.Point({$row['location_x']},{$row['location_y']}))</script>";
//}
//else
//{
//    echo "<script type='text/javascript'>initMap()</script>";
//}
//
////function getWeather() {
////    $wcity = file_get_contents("city.txt");
////    $pattern = '/([0-9]+)=' . $city . '/';
////    preg_match($pattern, $wcity, $matches, PREG_OFFSET_CAPTURE);
////    if ($matches == null) {
////        return null;
////    }
////    $cityID = $matches[1][0];
////    $data = file_get_contents('http://www.weather.com.cn/data/sk/' . $cityID . '.html');
////    $datat=json_decode($data, true);
////print_r($datat);
////    return $datat;
//
////这里需要更新经纬度坐标
//$jd=$row['location_x'];
//$wd=$row['location_y'];
////$jd ="123.2111";
////$wd = "30.2111";
//print_r($jd);
//print_r($wd);
//$url = "https://api.caiyunapp.com/v2.5/SqbtWNg8TqBq6ikN/" . "" . $jd.",".$wd . "" . "/realtime.json";//
//$str = file_get_contents($url);
////$result = gzdecode($str);   //解压
//$arr = json_decode($str, true); //获取到数组格式的数据。
////print_r($arr);
////    return $arr;
//
////}
//
////if ($_POST != null && $_POST["city"] != null) {
////$arr = getWeather();
//if ($arr == null) {?>
<!--    <div class="alert alert-block" style="margin: 20px">-->
<!--        <button type="button" class="close" data-dismiss="alert">-->
<!--            &times;-->
<!--        </button>-->
<!--        <h4>警告!</h4>-->
<!--        发生错误了亲，您输入的城市好像没有找到哦！-->
<!--    </div>-->
<!--    --><?php //return;
//}
//$info = $arr["result"]["realtime"];
//?>
<!---->
<!---->
<!--<hr size="500"/>-->
<!---->
<!---->
<!--<table align="center" class="table table-striped table-bordered"  style="width: 75%;margin-left:200px;"";>-->
<!--    <thead>-->
<!--    <th>实时天气信息</th>-->
<!--    </thead>-->
<!--    <tbody>-->
<!--    --><?php
//    //echo "<tr><td>经纬度：</td><td>".$arr["result"]["realtime"]["location"]."</td></tr>";
//    echo "<tr><td>温度：</td><td>".$info["temperature"]."</td></tr>";
//    echo "<tr><td>体感温度：</td><td>".$info["apparent_temperature"]."</td></tr>";
//    echo "<tr><td>气压：</td><td>".$info["pressure"]."pska</td></tr>";
//    echo "<tr><td>相对湿度：</td><td>".$info["humidity"]."</td></tr>";
//    echo "<tr><td>风速：</td><td>".$info["wind"]["speed"]."</td></tr>";
//    echo "<tr><td>能见度：</td><td>".$info["visibility"]."</td></tr>";
//    echo "<tr><td>pm2.5：</td><td>".$info["air_quality"]["pm25"]."</td></tr>";
//    ?>
<!--    </tbody>-->
<!--</table>-->



<?php
//session_start();
////header("Content-type:text/html;charset=utf-8"); //设置编码
//if(empty($_SESSION['username']))
//{
//    echo "<script>alert('请先登录');location.href='login.html';</script>";
//}
//include_once "fmysql.php";
//$sql = "select * from water_quality_data where user_id={$_SESSION['id']} order by 采集时间 desc limit 1000";
//$result = mysqli_query($link, $sql);
//if (!$result) {
//    printf("Error: %s\n", mysqli_error($link));
//    exit();
//}
//?>
<!---->
<!--<div class="tablebox" style="border: 1px solid #000">-->
<!--    <table id="tableId" border="0" cellspacing="0" cellpadding="0">-->
<!--        <thead>-->
<!--        <tr>-->
<!--            <th>采集时间</th>-->
<!--            <th>监测点位</th>-->
<!--            <th>溶解氧</th>-->
<!--            <th>水温</th>-->
<!--            <th>PH</th>-->
<!--        </tr>-->
<!--        </thead>-->
<!--        <tbody>-->
<!--        --><?php
//        while($row=mysqli_fetch_array($result)) {
//            ?>
<!---->
<!--            <tr>-->
<!--                <td>--><?php //echo $row['采集时间']?><!--</td>-->
<!--                <td>--><?php //echo $row['监测点位']?><!--</td>-->
<!--                <td>--><?php //echo $row['溶解氧']?><!--</td>-->
<!--                <td>--><?php //echo $row['水温']?><!--</td>-->
<!--                <td>--><?php //echo $row['PH']?><!--</td>-->
<!--            </tr>-->
<!---->
<!--            --><?php
//        }
//        ?>
<!--        </tbody>-->
<!--    </table>-->
<!--</div>-->
<!---->
<!--<script>-->
<!--    // 参数1 tableID,参数2 div高度，参数3 速度，参数4 tbody中tr几条以上滚动-->
<!--    tableScroll('tableId', 400, 30, 10)-->
<!--    var MyMarhq;-->
<!---->
<!--    function tableScroll(tableid, hei, speed, len) {-->
<!--        clearTimeout(MyMarhq);-->
<!--        $('#' + tableid).parent().find('.tableid_').remove()-->
<!--        $('#' + tableid).parent().prepend(-->
<!--            '<table class="tableid_"><thead>' + $('#' + tableid + ' thead').html() + '</thead></table>'-->
<!--        ).css({-->
<!--            'position': 'relative',-->
<!--            'overflow': 'hidden',-->
<!--            'height': hei + 'px'-->
<!--        })-->
<!--        $(".tableid_").find('th').each(function(i) {-->
<!--            $(this).css('width', $('#' + tableid).find('th:eq(' + i + ')').width());-->
<!--        });-->
<!--        $(".tableid_").css({-->
<!--            'position': 'absolute',-->
<!--            'top': 0,-->
<!--            'left': 0,-->
<!--            'z-index': 9-->
<!--        })-->
<!--        $('#' + tableid).css({-->
<!--            'position': 'absolute',-->
<!--            'top': 0,-->
<!--            'left': 0,-->
<!--            'z-index': 1-->
<!--        })-->
<!---->
<!--        if ($('#' + tableid).find('tbody tr').length > len) {-->
<!--            $('#' + tableid).find('tbody').html($('#' + tableid).find('tbody').html() + $('#' + tableid).find('tbody').html());-->
<!--            $(".tableid_").css('top', 0);-->
<!--            $('#' + tableid).css('top', 0);-->
<!--            var tblTop = 0;-->
<!--            var outerHeight = $('#' + tableid).find('tbody').find("tr").outerHeight();-->
<!---->
<!--            function Marqueehq() {-->
<!--                if (tblTop <= -outerHeight * $('#' + tableid).find('tbody').find("tr").length) {-->
<!--                    tblTop = 0;-->
<!--                } else {-->
<!--                    tblTop -= 1;-->
<!--                }-->
<!--                $('#' + tableid).css('margin-top', tblTop + 'px');-->
<!--                clearTimeout(MyMarhq);-->
<!--                MyMarhq = setTimeout(function() {-->
<!--                    Marqueehq()-->
<!--                }, speed);-->
<!--            }-->
<!---->
<!--            MyMarhq = setTimeout(Marqueehq, speed);-->
<!--            $('#' + tableid).find('tbody').hover(function() {-->
<!--                clearTimeout(MyMarhq);-->
<!--            }, function() {-->
<!--                clearTimeout(MyMarhq);-->
<!--                if ($('#' + tableid).find('tbody tr').length > len) {-->
<!--                    MyMarhq = setTimeout(Marqueehq, speed);-->
<!--                }-->
<!--            })-->
<!--        }-->
<!---->
<!--    }-->
<!--</script>-->

<script src="../js/jquery.1.11.3.min.js"></script>
<style>
    table,tbody,tfoot,thead,tr,th,td {
        margin:0;
        padding:0;
        outline:0;
        font-size:100%;
        vertical-align:baseline;
        background:transparent;
        border-collapse:collapse;
        border-spacing:0;
        border:0px;
    }
    .tablebox {
        width:300px;
        height:400px;
        overflow:hidden;
        margin:50px auto;
    }
    .tablebox table {
        width:100%;
    }
    .tablebox table th,.tablebox table td {
        font-size:12px;
        text-align:center;
        line-height:36px;
    }
    .tablebox table th {
        color:#2584e3;
        background-color:#f6f6f6;
    }
    .tablebox table td img {
        display:inline-block;
        vertical-align:middle;
    }
    .tablebox table tbody tr:nth-child(even) {
        background-color:#f6f6f6;
    }
    .tablebox.table_md table td,.tablebox.table_md table th {
        line-height:40px;
    }
</style>
<article class="main-col clear">
    <div class="lp">
        <article class="col-body mb20">
            <article class="col-main clear">
                <article class="slideTxtBox slideTxtBox1 w100 fl">
                    <article class="hd">
                        <ul class="clear">
                            <li style="margin-left:200px; margin-right:10px">
                                水质实时数据
                            </li>
                        </ul>
                        <?php
                        session_start();
                        //header("Content-type:text/html;charset=utf-8"); //设置编码
                        if(empty($_SESSION['username']))
                        {
                            echo "<script>alert('请先登录');location.href='login.html';</script>";
                        }
                        include_once "fmysql.php";
                        $sql = "select * from water_quality_data where user_id={$_SESSION['id']} order by 采集时间 desc limit 1000";
                        $result = mysqli_query($link, $sql);
                        if (!$result) {
                            printf("Error: %s\n", mysqli_error($link));
                            exit();
                        }
                        ?>

                        <div class="tablebox" style="border: 1px solid #000">
                            <table id="tableId" border="0" cellspacing="0" cellpadding="0">
                                <thead>
                                <tr>
                                    <th>采集时间</th>
                                    <th>监测点位</th>
                                    <th>溶解氧</th>
                                    <th>水温</th>
                                    <th>PH</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                while($row=mysqli_fetch_array($result)) {
                                    ?>

                                    <tr>
                                        <td><?php echo $row['采集时间']?></td>
                                        <td><?php echo $row['监测点位']?></td>
                                        <td><?php echo $row['溶解氧']?></td>
                                        <td><?php echo $row['水温']?></td>
                                        <td><?php echo $row['PH']?></td>
                                    </tr>

                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>

                        <script>
                            // 参数1 tableID,参数2 div高度，参数3 速度，参数4 tbody中tr几条以上滚动
                            tableScroll('tableId', 400, 30, 10)
                            var MyMarhq;

                            function tableScroll(tableid, hei, speed, len) {
                                clearTimeout(MyMarhq);
                                $('#' + tableid).parent().find('.tableid_').remove()
                                $('#' + tableid).parent().prepend(
                                    '<table class="tableid_"><thead>' + $('#' + tableid + ' thead').html() + '</thead></table>'
                                ).css({
                                    'position': 'relative',
                                    'overflow': 'hidden',
                                    'height': hei + 'px'
                                })
                                $(".tableid_").find('th').each(function(i) {
                                    $(this).css('width', $('#' + tableid).find('th:eq(' + i + ')').width());
                                });
                                $(".tableid_").css({
                                    'position': 'absolute',
                                    'top': 0,
                                    'left': 0,
                                    'z-index': 9
                                })
                                $('#' + tableid).css({
                                    'position': 'absolute',
                                    'top': 0,
                                    'left': 0,
                                    'z-index': 1
                                })

                                if ($('#' + tableid).find('tbody tr').length > len) {
                                    $('#' + tableid).find('tbody').html($('#' + tableid).find('tbody').html() + $('#' + tableid).find('tbody').html());
                                    $(".tableid_").css('top', 0);
                                    $('#' + tableid).css('top', 0);
                                    var tblTop = 0;
                                    var outerHeight = $('#' + tableid).find('tbody').find("tr").outerHeight();

                                    function Marqueehq() {
                                        if (tblTop <= -outerHeight * $('#' + tableid).find('tbody').find("tr").length) {
                                            tblTop = 0;
                                        } else {
                                            tblTop -= 1;
                                        }
                                        $('#' + tableid).css('margin-top', tblTop + 'px');
                                        clearTimeout(MyMarhq);
                                        MyMarhq = setTimeout(function() {
                                            Marqueehq()
                                        }, speed);
                                    }

                                    MyMarhq = setTimeout(Marqueehq, speed);
                                    $('#' + tableid).find('tbody').hover(function() {
                                        clearTimeout(MyMarhq);
                                    }, function() {
                                        clearTimeout(MyMarhq);
                                        if ($('#' + tableid).find('tbody tr').length > len) {
                                            MyMarhq = setTimeout(Marqueehq, speed);
                                        }
                                    })
                                }

                            }
                        </script>
                    </article>
                </article><!-- slideTxtBox end -->

                <article class="slideTxtBox slideTxtBox1 w100 fl">
                    <article class="hd">
                        <ul class="clear" >
                            <li style="margin-left:200px; margin-right:10px">
                                地理位置信息
                            </li>
                        </ul>

                        <?php
                        session_start();
                        //header("Content-type:text/html;charset=utf-8"); //设置编码
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
                        <!-- 地图功能实现 margin:0 auto -->
                        <p> </p>
                        <div class="pagelist"  style="width:400px;height:400px;margin-left:100px;margin-top: 50px" id="allmap"></div>
                        <p style="margin-left:200px;" id="demo">点击按钮获取您当前坐标（可能需要比较长的时间获取）：</p>
                        <p> </p>
                        <button style="margin-left:200px;" onclick="tipsWindow();getLocation();">获取你的位置</button>
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
                        if ($arr == null) {
                            ?>
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


                        <hr size="500"/>

                    </article>
                </article><!-- slideTxtBox end -->


                <table align="center" class="table table-striped table-bordered"  style="width: 75%;margin-left:200px;"";>
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
                <article class="slideTxtBox slideTxtBox1 w100 fl">
                    <article class="hd">
                        <ul class="clear">
                            <li>专家问诊</li>
                        </ul>

                    </article>
                </article><!-- slideTxtBox end -->
                <article class="slideTxtBox slideTxtBox1 w100 fl">
                    <article class="hd">
                        <ul class="clear">
                            <li>在线诊断</li>
                        </ul>

                    </article>
                </article><!-- slideTxtBox end -->
            </article>
        </article>
    </div>
</article>
<footer class="footer">
    <address>

        <p>小组信息</p>

    </address>
</footer>




</body>
</html>