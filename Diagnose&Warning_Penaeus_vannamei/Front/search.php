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
include_once("fmysql.php");
function info($link,$pageNum = 1, $pageSize = 6,$searchwords)
{
    $Head=($pageNum - 1) * $pageSize;
    $sql = "select * from information where title like '%%$searchwords%%' limit $Head,$pageSize";
    $result = mysqli_query($link, $sql);
    if (!$result) {
        printf("Error: %s\n", mysqli_error($link));
        exit();
    }
    $arr_info=array();
    while($row = mysqli_fetch_array($result)) {
        $arr_info[] = $row;
    }
    return $arr_info;
}
function get_Max_Page($link,$searchwords)
{
    $sql = "select * from information where title like '%%$searchwords%%'";
    $sql_count = "select count(*) as amount from information where title like '%%$searchwords%%' ";
    $sql .= "order by id asc";
    $result_amount = mysqli_query($link, $sql_count);
    if (!$result_amount) {
        return 0;
    } else {
        $arr_amount = mysqli_fetch_assoc($result_amount);
        $amount = $arr_amount['amount'];
        $page_size = 8;
        $max_page = ceil($amount / $page_size);
        return $max_page;
    }
}
$searchwords=$_POST['searchwords'];
@$max_page=get_Max_Page($link,$searchwords);
@$pageNum = empty($_GET["pageNum"])?1:$_GET["pageNum"];
@$pageSize =6;
@$arr_info = info($link,$pageNum,$pageSize,$searchwords);

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>搜索结果</title>
    <link href="../css/slimize.css" rel="stylesheet">
    <link href="../css/tsd-ui.css" rel="stylesheet">
    <link href="../css/common.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <script src="../js/jquery.1.71.min.js"></script>
    <script src="../js/jquery.SuperSlide.2.1.1.js"></script>
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
    .pagelist span.current{ background:#09F; color:#FFF; border-color:#09F; margin:0 2px;}
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
        <h2>搜索结果</h2>
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
                      <?php
                          foreach ($arr_info as $val)
                          {
                              ?>
                              <table width="1000px">
                      <?php
                              echo "<td width='75%'><a href='r.php?id={$val['id']}' style='text-decoration: none'>{$val['title']}</a></td>";
                              echo "<td width='25%'><a style='text-align: right;text-decoration: none;'>{$val['created_at']}</a></td>";
                              ?>
                              </table>
                                  <?
                              echo "</br>";
                              echo "</br>";
                            }
                          ?><tr>
                        <td colspan="8">
                            <div class="pagelist">
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
	                </span>
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