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
    <link rel="stylesheet" href="../css/table.css">
    <script src="../js/jquery.1.71.min.js"></script>
    <script src="../js/jquery.SuperSlide.2.1.1.js"></script>
    <style type="text/css">
        body, html,#allmap {width: 100%;height: 100%;overflow: hidden;margin:0;font-family:"微软雅黑";}
    </style>
    <!--调用百度api -->
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=16zrDHPLkYrv8e1Tp98hPZ8UUpkKrsdv"></script>
    <title>地图展示</title>
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
<div id="allmap"></div>
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

                    <script type="text/javascript">

                        //百度地图API功能
                        var map = new BMap.Map("allmap");
                        map.centerAndZoom(new BMap.Point(120.378386,30.309756),15);//根据坐标初始化地图
                        map.enableScrollWheelZoom(true);
                        map.addControl(new BMap.NavigationControl());   //平移缩放控件
                        map.addControl(new BMap.ScaleControl());        //比例尺
                        map.addControl(new BMap.OverviewMapControl());  //缩略地图
                        map.addControl(new BMap.MapTypeControl());      //地图类型
                        map.setCurrentCity("杭州"); // 仅当设置城市信息时，MapTypeControl的切换功能才能可用

                        //添加标注
                        var marker = new BMap.Marker(new BMap.Point(120.378386,30.309756));        // 创建标注
                        map.addOverlay(marker);                     // 将标注添加到地图中
                    </script>


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
    <address>

        <p>小组信息</p>

    </address>
</footer>




</body>
</html>