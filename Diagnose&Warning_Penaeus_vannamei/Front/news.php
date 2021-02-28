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
    <script src="../js/jquery.1.71.min.js"></script>
    <script src="../js/jquery.SuperSlide.2.1.1.js"></script>
    <style type="text/css">
        body, html,#allmap {width: 100%;height: 100%;overflow: scroll;margin:0;font-family:"微软雅黑";}
    </style>
    <!--调用百度api -->
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=16zrDHPLkYrv8e1Tp98hPZ8UUpkKrsdv"></script>
    <title>地图展示</title>

    <!-- tinaqi-->
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <meta http-equiv="U-XA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="X-UA-Compatible" content="IE=9">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <script type="text/javascript" src="/static/js/1.4.3/jquery.min.js"></script>
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
    .pagelist {padding:10px 0; text-align:center;}
    .pagelist span,.pagelist a{ border-radius:3px; border:1px solid #dfdfdf;display:inline-block; padding:5px 12px;}
    .pagelist a{ margin:0 3px;}
    .pagelist span.current{ background:#09F; color:#FFF; border-color:black; margin:0 2px;}
    .pagelist a:hover{background:red; color:#FFF; border-color:red; }
    .pagelist label{ padding-left:15px; color:#999;}
    .pagelist label b{color:red; font-weight:normal; margin:0 3px;}
</style>
<style>
    .slideBox-common { width:1200px !important;}
    .slideBox-common .bd li{height:120px !important;width:1200px !important;}
    .slideBox-common li img{width:1200px !important;}
    .slideBox-common .hd ul { height:12px !important;}
    .slideBox-common .hd { bottom:15px !important; }
</style>
<body>
<!-- 通用页头 -->
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
                <?php } ?> >欢迎您！<? echo $_SESSION['username']?></a>
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
    <ul class="center clear">
        <li>
            <a href="#"><img src="../images/mb23-nav-icon-1.png" class="icon-off" /></a><a href="#"><img src="../images/mb23-nav-icon-1-2.png" class="icon-on" /></a>
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
        <li>
            <a href="#qyxz/"><img src="../images/mb23-nav-icon-5.png" class="icon-off" /></a><a href="#qyxz/"><img src="../images/mb23-nav-icon-5-2.png" class="icon-on" /></a>
            <a href="consulting.php">在线诊断</a>
        </li>
        <li class="on">
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
                <span class="news-list news-list1 w3">
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
	            </span>
                </article><!-- bd end -->			  <!-- slideTxtBox end --><!-- slideTxtBox end --><!-- slideTxtBox end --><!-- slideTxtBox end -->
            </article>
        </article>
    </div>
    <div class="pagelist"  style="width:400px;height:400px;margin:0 auto" id="allmap"></div>
    <p id="demo">点击按钮获取您当前坐标（可能需要比较长的时间获取）：</p>
    <button onclick="getLocation()">点我</button>
<!--    <div class="pagelist"  style="width:400px;height:400px;margin:0 auto" id="chengshi"></div>-->
<!--    <p id="chengshi">点击按钮获取您当前坐标（可能需要比较长的时间获取）：</p>-->
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
            var marker1 = new BMap.Marker(new BMap.Point(jd,wd));        // 创建标注
            map.addOverlay(marker1);                     // 将标注添加到地图中
            map.centerAndZoom(new BMap.Point(jd,wd),15);//根据坐标初始化地图
        }


        // var position_option = {
        //     enableHighAccuracy: true,
        //     maximumAge: 30000,
        //     timeout: 20000
        // };
        // function getLocation(){navigator.geolocation.getCurrentPosition(getPositionSuccess, getPositionError, position_option);}
        // function getPositionSuccess( position ){
        //     var lat = position.coords.latitude;
        //     var lng = position.coords.longitude;
        //     x.innerHTML="纬度: " + lat +
        //              "<br>经度: " + lng;
        //     var marker1 = new BMap.Marker(new BMap.Point(lng,lat));        // 创建标注
        //     map.addOverlay(marker1);                     // 将标注添加到地图中
        //     map.centerAndZoom(new BMap.Point(lng,lat),15);//根据坐标初始化地图
        //     alert( "您所在的位置： 纬度" + lat + "，经度" + lng );
        //     if(typeof position.address !== "undefined"){
        //         var country = position.address.country;
        //         var province = position.address.region;
        //         var city = position.address.city;
        //         alert(' 您位于 ' + country + province + '省' + city +'市');
        //     }
        // }

        //百度地图API功能
        var map = new BMap.Map("allmap");
        map.centerAndZoom(new BMap.Point(jd,wd),15);//根据坐标初始化地图
        //map.centerAndZoom(new BMap.Point(120.378386,30.309756),15);//根据坐标初始化地图
        map.enableScrollWheelZoom(true);
        map.addControl(new BMap.NavigationControl());   //平移缩放控件
        map.addControl(new BMap.ScaleControl());        //比例尺
        map.addControl(new BMap.OverviewMapControl());  //缩略地图
        map.addControl(new BMap.MapTypeControl());      //地图类型
        map.setCurrentCity("北京"); // 仅当设置城市信息时，MapTypeControl的切换功能才能可用

        // var nowCity = new BMap.LocalCity();
        // nowCity.get(bdGetPosition);
        // function bdGetPosition(result){
        //     var cityName = result.name; //当前的城市名
        //     var y=document.getElementById("chengshi");
        //     y.innerHTML= cityName;
        // }


        //添加标注
        var marker = new BMap.Marker(new BMap.Point(120.378386,30.309756));        // 创建标注
        map.addOverlay(marker);                     // 将标注添加到地图中
        // var marker1 = new BMap.Marker(new BMap.Point(jd,wd));        // 创建标注
        // map.addOverlay(marker1);                     // 将标注添加到地图中

    </script>
    <nav>
    <iframe name="WeatherTool" src="http://i.tianqi.com/index.php?c=code&amp;id=34&amp;icon=1&amp;num=3&amp;py=zhejiang" width="260" height="28" marginwidth="0" marginheight="0" hspace="0" vspace="0" frameborder="0" scrolling="no" allowtransparency="true" style="padding-top:7px"></iframe>
    </nav>

    <div class="pagelist"  style="width:400px;height:50px;margin:0 auto" id="chengshi"></div>
        <p id="chengshi">点击按钮获取您当前城市（可能需要比较长的时间获取）：</p>
        <button onclick="GetIPAll()">点我</button>
    <script src="https://pv.sohu.com/cityjson?ie=utf-8"></script>
    <script src="https://ip.ws.126.net/ipquery"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script>
        var iPAddress = "", iPAttach = "",iPcity ="";//IP地址，IP归属地
        //获取IP地址，IP归属地
        function GetIPAll() {
            iPAddress = returnCitySN["cip"];//IP地址:
            iPcity = returnCitySN["cname"];
            iPAttach = localAddress["province"] + localAddress["city"];//IP归属地
            var y=document.getElementById("chengshi");
            y.innerHTML= iPAttach;
            //GetIpLookup(iPAddress)
        }


    </script>
</article>


<!--    --><?php
//    $url = "http://www.weather.com.cn/data/cityinfo/101010100.html";//
//    $str = file_get_contents($url);
//    $arr1=array($str);
    //$result = gzdecode($str);   //解压
    //$arr1 = json_decode($str, true); //获取到数组格式的数据。
//    print_r($arr1);
    //print_r((array)$arr1[0]);
//Array ( [data] => Array ( [yesterday] => Array ( [date] => 25日星期四 [high] => 高温 8℃ [fx] => 东北风 [low] => 低温 -3℃ [fl] => [type] => 霾 ) [city] => 北京
    // [forecast] => Array ( [0] => Array ( [date] => 26日星期五 [high] => 高温 11℃ [fengli] => [low] => 低温 -3℃ [fengxiang] => 南风 [type] => 霾 )
    // [1] => Array ( [date] => 27日星期六 [high] => 高温 13℃ [fengli] => [low] => 低温 -1℃ [fengxiang] => 南风 [type] => 多云 ) [2] => Array ( [date] => 28日星期天 [high] => 高温 6℃ [fengli] => [low] => 低温 0℃ [fengxiang] => 东北风 [type] => 雨夹雪 ) [3] => Array ( [date] => 1日星期一 [high] => 高温 5℃ [fengli] => [low] => 低温 -3℃ [fengxiang] => 东南风 [type] => 多云 ) [4] => Array ( [date] => 2日星期二 [high] => 高温 7℃ [fengli] => [low] => 低温 -2℃ [fengxiang] => 南风 [type] => 晴 ) ) [ganmao] => 感冒高发期，尽量避免外出，外出戴口罩防护。 [wendu] => 4 ) [status] => 1000 [desc] => OK )
//    foreach ($arr1 as $player_data) {
//        // Initialise the steam id to an empty string in case one is not found
//        $player_steam_id = "";
//        // Find the steam id in the identifiers array
//        if (array_key_exists("identifiers", $player_data)) {
//            $steam_identifiers = [];
//            foreach ($player_data["identifiers"] as $identifier_str)
//                if (preg_match("/^steam:/i", $identifier_str, $m))
//                    $steam_identifiers[] = $identifier_str;
//            if (!empty($steam_identifiers)) {
//                $player_steam_id = $steam_identifiers[0];
//            }
//        }
//        $player_id = $player_data["id"];
//        $player_name = $player_data["name"];
//        // Show what we have
//        print( $player_id . " - " . $player_name . " - " . $player_steam_id . "
//    " );
//    }
//
//    foreach($arr1 as $rs){
//    if(is_array($rs)) {
//        echo $rs["city"] . "\t";
//        echo $rs["cityid"] . "\t";
//        echo $rs["temp1"] . "\t";
//        echo $rs["temp2"] . "\t";
//        echo $rs["weather"] . "\t";
//        echo $rs["img1"] . "\t";
//        echo $rs["img2"] . "\t";
//        echo $rs["ptime"] . "\t\r\n";
//    }
//    }

//    $BiaoGe = "";
//
//
//    $BiaoGe = $BiaoGe."<table >";
//    //printf(count($arr1));
//    for ($i=0; $i<count($arr1); $i++)
//    {
//
//    $BiaoGe = $BiaoGe. "<tr>";
//    for ($j=0;$j<count($arr1[$i]);$j++)
//
//    {
//
//        $BiaoGe = $BiaoGe. "<td>".$arr1[$i][$j]."</td>";
//
//    }
//    $BiaoGe = $BiaoGe. "</tr>";
//
//    }
//    $BiaoGe = $BiaoGe. "</table>";
//    echo $BiaoGe;
//    ?>

<!--<html lang="zh">-->
<!--<head>-->
<!--    <meta charset="UTF-8">-->
<!--    <meta name="viewport" content="width=device-width, initial-scale=1.0">-->
<!--    <meta http-equiv="X-UA-Compatible" content="ie=edge">-->
<!--    <title></title>-->
<!--    <link rel="stylesheet" type="text/css" href="../css/tq.css" />-->
<!--    <link rel="stylesheet" type="text/css" href="../css/tq.css" />-->
<!--</head>-->
<!--<body>-->
<!--<fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">-->
<!--    <legend>--><?php //echo $month."有".$day."天";?><!--</legend>-->
<!--    <div class="layui-inline">-->
<!--        <label class="layui-form-label">年月选择器</label>-->
<!--        <div class="layui-input-inline">-->
<!--            <input type="text" class="layui-input" id="test3">-->
<!--        </div>-->
<!--    </div>-->
<!--</fieldset>-->
<!--<div class="layui-row">-->
<!--    <div class="layui-col-md1">-->
<!--        <div class="grid-demo grid-demo-bg1">星期日</div>-->
<!--    </div>-->
<!--    <div class="layui-col-md1">-->
<!--        <div class="grid-demo grid-demo-bg1">星期一</div>-->
<!--    </div>-->
<!--    <div class="layui-col-md1">-->
<!--        <div class="grid-demo grid-demo-bg1">星期二</div>-->
<!--    </div>-->
<!--    <div class="layui-col-md1">-->
<!--        <div class="grid-demo grid-demo-bg1">星期三</div>-->
<!--    </div>-->
<!--    <div class="layui-col-md1">-->
<!--        <div class="grid-demo grid-demo-bg1">星期四</div>-->
<!--    </div>-->
<!--    <div class="layui-col-md1">-->
<!--        <div class="grid-demo grid-demo-bg1">星期五</div>-->
<!--    </div>-->
<!--    <div class="layui-col-md1">-->
<!--        <div class="grid-demo grid-demo-bg1">星期六</div>-->
<!--    </div>-->
<!--</div>-->
<!--<div class="layui-row">-->
<!--    --><?php
//    for($i=1;$i<=$day;$i++){
//        $year_month_day = $month."-".$i;
//        //星期0-6
//        $index = date("w",strtotime($year_month_day));
//        //位移
//        $offset = 0;
//        //i第一次循环而且不是星期天，添加位移
//        if($i==1 && $index!=0){
//            $offset = "layui-col-md-offset".$index;
//        }else if($index==0){
//            echo "<div class='layui-clear'></div>";
//        }
//        ?>
<!--        <div class='layui-col-md1 --><?php //echo $offset ?><!--'>-->
<!--            <div class='grid-demo grid-demo-bg1 grid-hover'>-->
<!--                --><?php //echo $i;?>
<!--                --><?php //if($i==date('j') && $month==date('Y-m')){
//                    echo "<span class='weather'>$today_weather</span>";
//                } ?>
<!--            </div>-->
<!--        </div>-->
<!--        --><?php
//    }
//    ?>
<!---->

<!--<div id="he-plugin-standard" style="text-align:center"></div>-->
<!--<script>-->
<!--    WIDGET = {-->
<!--        CONFIG: {-->
<!--            "layout": 1,-->
<!--            "width": 450,-->
<!--            "height": 150,-->
<!--            "background": 1,-->
<!--            "dataColor": "FFFFFF",-->
<!--            "borderRadius": 5,-->
<!--            "key": "f9b7944a61714eb4baa6cb4fc32169a2"-->
<!--        }-->
<!--    }-->
<!--</script>-->
<!--<script src="https://widget.qweather.net/standard/static/js/he-standard-common.js?v=2.0"></script>-->

<!--<div id="weather-v2-plugin-simple"></div>-->
<!--<script>WIDGET = {CONFIG:{"modules":"20","background":5,"tmpColor":"4A4A4A","tmpSize":16,"cityColor":"4A4A4A","citySize":16,"aqiSize":16,"weatherIconSize":24,"alertIconSize":18,"padding":"10px 10px 10px 10px","shadow":"1","language":"auto","borderRadius":5,"fixed":"false","vertical":"middle","horizontal":"center","key":"aGh9gAMF5m"}}</script>-->
<!--<script src="https://apip.weatherdt.com/simple/static/js/weather-simple-common.js?v=2.0"></script>-->


<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <script src="http://libs.baidu.com/jquery/1.9.0/jquery.js"></script>
    <script src="http://libs.baidu.com/bootstrap/2.0.4/js/bootstrap.min.js"></script>
    <link href="http://libs.baidu.com/bootstrap/2.0.4/css/bootstrap.min.css" rel="stylesheet"/>
    <title>天气查询简单版</title>
</head>
<body>
<form class="form-horizontal" action="" method="post">
    <legend>
        Weather
    </legend>
    <div class="control-group">
        <label class="control-label" for="idCity">城市：</label>
        <div class="controls">
            <input name="city" type="text" name="city" id="idCity" placeholder="请输入城市名字" maxlength="64">
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <button type="submit" class="btn btn-primary" style="margin: 10px">
                确定
            </button>
            <button type="reset" class="btn btn-inverse" style="margin: 10px">
                重置
            </button>
        </div>
    </div>
</form>
<hr/>
<!--<button onclick="getLocation1()">点我</button>-->
<!--<script type="text/javascript">-->
<!--    var x=document.getElementById("demo");-->
<!--    var jd=120.3;-->
<!--    var wd=30.3;-->
<!--    function getLocation1()-->
<!--    {-->
<!--        if (navigator.geolocation)-->
<!--        {-->
<!--            navigator.geolocation.getCurrentPosition(showPosition);-->
<!--        }-->
<!--        else{x.innerHTML="Geolocation is not supported by this browser.";}-->
<!--    }-->
<!---->
<!--    function showPosition(position)-->
<!--    {-->
<!--        x.innerHTML="纬度: " + position.coords.latitude +-->
<!--            "<br>经度: " + position.coords.longitude;-->
<!--        wd=position.coords.latitude;-->
<!--        jd=position.coords.longitude;-->
<!--        var marker1 = new BMap.Marker(new BMap.Point(jd,wd));        // 创建标注-->
<!--        map.addOverlay(marker1);                     // 将标注添加到地图中-->
<!--        map.centerAndZoom(new BMap.Point(jd,wd),15);//根据坐标初始化地图-->
<!--    }-->
<!--</script>-->
<?php
//header("Content-Type: text/html; charset=utf-8");

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

    $jd ="123.2111";
    $wd = "30.2111";
    $url = "https://api.caiyunapp.com/v2.5/SqbtWNg8TqBq6ikN/" . "" . $jd.",".$wd . "" . "/realtime.json";//
    $str = file_get_contents($url);
//$result = gzdecode($str);   //解压
    $arr = json_decode($str, true); //获取到数组格式的数据。
    //print_r($arr);
    return $arr;

}

if ($_POST != null && $_POST["city"] != null) {
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
    <table class="table table-striped table-bordered" style="margin-left: 20px;width: 200px">
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
}
?>
</body>


<footer class="footer">
    <address>

        <p>小组信息</p>

    </address>
</footer>




</body>
</html>
