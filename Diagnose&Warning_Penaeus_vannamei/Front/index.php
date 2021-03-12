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
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>南美白对虾病害诊断与预警系统</title>
<link href="../css/slimize.css" rel="stylesheet">
<link href="../css/tsd-ui.css" rel="stylesheet">
<link href="../css/common.css" rel="stylesheet">
<link href="../css/style.css" rel="stylesheet">
<script src="../js/jquery.1.71.min.js"></script>
<script src="../js/jquery.SuperSlide.2.1.1.js"></script>
</head>
<style type="text/css">
    /*p {*/
    /*    text-align: center;*/
    /*    font:bold 60px helvetica, arial, sans-serif;*/
    /*    color: red;        !*字体设为红色*!*/
    /*    text-shadow: 0 0 4px white,    !*第一层阴影为白色*!*/
    /*    0 -5px 4px #ff3,     !*第二层阴影为黄色*!*/
    /*    2px -10px 6px #fd3,     !*第三层阴影为浅橙色*!*/
    /*    -2px -15px 11px #f80,     !*第四层阴影为深橙色*!*/
    /*    2px -25px 18px #f20;    !*最后一层阴影为红色，扩散效果设置较明显*!*/
    /*}*/
    #float_banner{
        position:absolute;
        top:20px;
        left:50%;
        width:900px;
        margin-left:-450px;
        height:30px;
        line-height:30px;
        text-align:center;
        background-color:transparent;
        font-size:20px;
        font-weight:bold;
        color: red;
        z-index:2;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        border-color: red;
    }

    /*侧栏跟随*/
    #box{float:left;position:relative;width:250px;}.div1{width:250px;}.div2{position:fixed;_position:absolute;top:0;z-index:250;}
    .box{
        width: 339px;
        margin: 0px auto;
        font-size: 18px;
		float:right;
    }
	.in{
		width: 260px;
        border: 1px solid #e2e2e2;
        height: 40px;
        float: left;
        background-repeat: no-repeat;
        background-size: 25px;
        background-position:5px center;
        padding:0 0 0 40px;
	}
    .search{
        width: 78px;
        height: 40px;
        float: right;
        background: orangered;
        color: white;
        border:none;
        text-align: center;
        line-height: 32px;
        cursor: pointer;
    }
 
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
  filter: alpha(opacity=100) ;
  opacity: 1 ;
}
</style>
<style>
.slideBox-common { width:1200px ;}
.slideBox-common .bd li{height:120px ;width:1200px ;}
.slideBox-common li img{width:1200px ;}
.slideBox-common .hd ul { height:12px ;}
.slideBox-common .hd { bottom:15px ; }
</style>
<body>
<header class="header" >

    <div id="float_banner" style="border: 2px whitesmoke solid ;">
        防止“转水”和“倒藻”。在“转水”发生前，虾池水体表面暗淡，此时排水30-40厘米，施用活性黑土（腐植酸钠），再加水至原水位。养殖前期出现“倒藻”，主要原因是水体缺乏营养盐类，应施用单细胞藻类生长素，保持水体透明度在25-30厘米；养殖后期出现“倒藻”，是因为水质老化，应先排水30-40厘米，施用益水宝，然后加水至原水位。
    </div>
    <script language="javascript">
        var speed = 100;
        var scrollTop = null;
        var hold = 0;
        var float_banner;
        var pos = null;
        var timer = null;
        var moveHeight = null;
        float_banner = document.getElementById("float_banner");
        window.onscroll=scroll_ad;
        function scroll_ad(){
            scrollTop = document.documentElement.scrollTop+document.body.scrollTop;
            pos = scrollTop - float_banner.offsetTop;
            pos = pos/10
            moveHeight = pos>0?Math.ceil(pos):Math.floor(pos);
            if(moveHeight!=0){
                float_banner.style.top = float_banner.offsetTop+moveHeight+3+"px";
                setTimeout(scroll_ad,speed);
            }
//alert(scrollTop);
        }
        function f() {
            //根据id获取所对应的div,document意思是去整个html文档中找，getElementById是根据ID
            var tag = document.getElementById('float_banner')
            //拿到div中的文本，注意innertext方法没有括弧
            var content = tag.innerText
            //获取它这个字符串的第一个文字
            var z =content.charAt(0)
            //获取这个文本除第一个字以外的文本，用substring给它起始位置
            var  l = content.substring(1,content.length)
            //把第一个字符放到最后一位拼接起来
            var new_content = l+z
            //用新生成new_content把div中的文本替换掉
            tag.innerText = new_content
        }
        //setInterval是个定时器，第一个参数是要执行的内容，第二个参数是执行间隔，单位为毫秒
        setInterval('f()',500)
    </script>

    <!-- 浮动广告代码开始 -->

    <div id="left_layer" style="position:fixed; top:350px; right:0px;">
        <img src="../images/kefu.jpg"width="200px" height="100px"><br>

        <a href="javascript:;" onclick="javascript:document.getElementById('left_layer').style.display='none';">关闭</a>

    </div>

    <!-- 浮动广告代码结束 -->

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
  <p>
    <img src="../images/titleOfWeb.jpg" width="1200" height="108">
  </p>
	<div class="box">
        <form action="search.php" method="post">
            <input  class="in" type="search" name="searchwords" placeholder="请输入标题关键字" required>
        <div ><input type="submit" value="搜索" class="search" ></div>
    </div>
	<p>&nbsp;</p>
  	<p>&nbsp;</p>
</figure>
	
</figure>

<nav class="nav">
	<ul class="center clear">
		<li class="on">
			<a href="#"><img src="../images/mb23-nav-icon-1.png" class="icon-off" /></a><a href="#""><img src="../images/mb23-nav-icon-1-2.png" class="icon-on" /></a>
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
    <li>
			<a href="#zwgk/"><img src="../images/mb23-nav-icon-3.png" class="icon-off" /></a><a href="#zwgk/"><img src="../images/mb23-nav-icon-3-2.png" class="icon-on" /></a>
	        <a href="news.php">预警信息</a>
      </li>
  </ul>
</nav>

<style>
.w1 li a {
    width: 448px ;
}
.w2 li a {
    width: 192px ;
}
.w3 li a {
    width: 298px  ;
}
.nav{    margin-bottom: 0px;
}</style>
<article class="main-news clear" style="margin-top: 15px;">
	<figure id="slideBox" class="slideBox slideBox1" style="border: 2px red solid ;">
	    <div class="hd">
	        <ul class="clear"><li></li><li></li><li></li><li></li><li></li></ul>
	    </div>
	    <figure class="bd"  >
	        <ul>
	            <li class="pic-box pic-box1">
	                <a><img src="../images/1.jpg" height='320' width='500' border='0'/></a>

	            </li>
	     
	            <li class="pic-box pic-box1">
	                <a><img src="../images/2.jpeg" height='320' width='500' border='0'/></a>

	            </li>
	     
	            <li class="pic-box pic-box1">
	                <a><img src="../images/3.jpg" height='320' width='500' border='0'/></a>

	            </li>
	     
	            <li class="pic-box pic-box1">
	                <a><img src="../images/4.jpg" height='320' width='500' border='0'/></a>

	            </li>
	     
	            <li class="pic-box pic-box1">
	                <a><img src="../images/5.jpg" height='320' width='500' border='0'/></a>

	            </li>
	         
	        </ul>
	    </figure>
	    <a class="prev" href="javascript:void(0)"></a>
	    <a class="next" href="javascript:void(0)"></a>
	</figure><!-- slideBox end -->

	<script>
	    jQuery(".slideBox1").slide({mainCell:".bd ul",effect:"left",autoPlay:true,easing:"swing",delayTime:500,mouseOverStop:true,pnLoop:true});
	</script>

	<article class="slideTxtBox slideTxtBox1">
        <a class="prev" href="javascript:void(0)"></a>
        <a class="next" href="javascript:void(0)"></a>
	    <article class="hd">
<!--	        <ul class="clear">-->
                    <li class="on">预警信息</li>
<!--	        </ul>-->
            <?
            $category=3;
            include"show_index.php";
            ?>
	    </article>
	    </article><!-- bd end -->
	</article><!-- slideTxtBox end -->
</article><!-- main-news end -->

<article class="main-col clear">
	<div class="lp">
		<article class="col-body mb20">
			<article class="col-main clear" >
				<article class="slideTxtBox slideTxtBox1 w100 fl" >
				    <article class="hd">
<!--				        <ul class="clear">-->
				            <li>养殖技术</li>
<!--				        </ul>-->
                        <?
                        $category=1;
                        include"show_index.php";
                        ?>
				    </article>
				</article><!-- slideTxtBox end -->

				<article class="slideTxtBox slideTxtBox1 w100 fl">
				    <article class="hd">
<!--				        <ul class="clear">-->
				            <li>病害知识</li>
<!--				        </ul>-->
                        <?
                        $category=2;
                        include"show_index.php";
                        ?>
				    </article>
				</article><!-- slideTxtBox end -->
				<article class="slideTxtBox slideTxtBox1 w100 fl">
				    <article class="hd">
<!--				        <ul class="clear">-->
				            <li>专家问诊</li>
<!--				        </ul>-->
                        <?
                        include"show_exdiagnose_index.php";
                        ?>
				    </article>
				</article><!-- slideTxtBox end -->
				<article class="slideTxtBox slideTxtBox1 w100 fl">
				    <article class="hd">
<!--				        <ul class="clear" >-->
				            <li>在线诊断</li>
<!--				        </ul>-->
                        <form method="post" action="diagnose_p.php">
                            <table width="90%" style="font-size: 14px ;">
                                <tr style="height: 50px ;" >
                                    <td width="50%" location="center">pH值</td>
                                    <td>
                                        <div class="com-sel">
                                            <select name="pH" class="com-opt" style="width: 200px">
                                                <option value="2">波动大</option>
                                                <option value="1">有波动</option>
                                                <option value="0" selected>正常</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="height: 50px ;">
                                    <td width="50%" location="center">溶解氧</td>
                                    <td>
                                        <div class="com-sel">
                                            <select name="DO"  class="com-opt" style="width: 200px">
                                                <option value="2">波动大</option>
                                                <option value="1">有波动</option>
                                                <option value="0" selected>正常</option>
                                            </select>
                                        </div>
                                    </td>

                                </tr>
                                <tr style="height: 50px ;">
                                    <td width="50%" location="center">温度</td>
                                    <td>
                                        <div class="com-sel">
                                            <select  name="temperature" class="com-opt" style="width: 200px">
                                                <option value="1">波动</option>
                                                <option value="0" selected>正常</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="height: 50px">
                                    <td width="50%" location="center">水色</td>
                                    <td>
                                        <div class="com-sel">
                                            <select name="color" class="com-opt" style="width: 200px">
                                                <option value="brown">褐色</option>
                                                <option value="cgreen">淡、暗绿色</option>
                                                <option value="green" selected>正常</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>

                                <tr style="height: 50px ;">
                                    <td width="50%" location="center">氨氮</td>
                                    <td>
                                        <div class="com-sel">
                                            <select name="NO2" class="com-opt" style="width: 200px">
                                                <option value="2">波动大</option>
                                                <option value="1">有波动</option>
                                                <option value="0" selected>正常</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>

                                <tr>

                                    <td style="text-align: right ">
                                        <input type="submit" value="提交">
                                    <td style="text-align: left">
                                        <input type="reset" value="重置">
                                    </td>

                                </tr>
                            </table>
                        </form>
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
