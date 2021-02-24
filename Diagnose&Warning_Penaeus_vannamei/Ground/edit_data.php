<?php
include_once "mysql.php";
$id = isset($_GET["id"])?$_GET["id"]:"";
$sql="select * from diagnose where id = '$id'";
$rel = mysqli_query($link,$sql);//执行sql语句
$arr= mysqli_fetch_array($rel); //获取一条新闻的所有信息
$sql = "select * from diagnose";
$result = mysqli_query($link, $sql);
$arr_info_category = array();
while($row = mysqli_fetch_array($result)) {
    $arr_info_category[] = $row;
}
if(count($_POST)>0){
    $sql = "update diagnose set pH='{$_POST['pH']}',DO='{$_POST['DO']}',temperature='{$_POST['temperature']}',color='{$_POST['color']}',NO2='{$_POST['NO2']}',res='$res' where id = '$id';";
    $result = mysqli_query($link,$sql);
    if($result){
        header("Location: ./data_list.php");
        exit;
    }else{
        echo "添加失败！";
        echo mysqli_error($link);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
    <title></title>
    <link rel="stylesheet" href="../css/pintuer.css">
    <link rel="stylesheet" href="../css/admin.css">
    <script src="../js/jquery.js"></script>
    <script src="../js/pintuer.js"></script>
    <script type="text/javascript" charset="utf-8" src="../umeditor/umeditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="../umeditor/umeditor.min.js"> </script>
    <script type="text/javascript" charset="utf-8" src="../umeditor/lang/zh-cn/zh-cn.js"></script>
</head>
<body>
<div class="panel admin-panel">
    <div class="panel-head" id="add"><strong><span class="icon-pencil-square-o"></span>增加内容</strong></div>
    <div class="body-content">
        <form method="post" class="form-x" action="" enctype="multipart/form-data">
            <div class="form-group">
                <div class="label">
                    <label>pH</label>
                </div>
                <div class="field">
                    <input type="text" class="input w50" value="" name="pH" />
                    <div class="tips"></div>
                </div>
            </div>
            <div class="clear"></div>
            <div class="form-group">
                <div class="label">
                    <label>含氧量</label>
                </div>
                <div class="field">
                    <input type="text" class="input w50" name="DO" value="" />
                </div>
            </div>

            <div class="form-group">
                <div class="label">
                    <label>温度</label>
                </div>
                <div class="field">
                    <input type="text" class="input w50" name="temperature" value="" />
                    <div class="tips"></div>
                </div>
            </div>

            <div class="form-group">
                <div class="label">
                    <label>水色</label>
                </div>
                <div class="field">
                    <input type="text" class="input w50" name="color" value="" />
                    <div class="tips"></div>
                </div>
            </div>

            <div class="form-group">
                <div class="label">
                    <label>氨氮</label>
                </div>
                <div class="field">
                    <input type="text" class="input w50" name="NO2" value="" />
                    <div class="tips"></div>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>结果</label>
                </div>
                <div class="field">
                    <input type="text" class="input w50" name="res" value="" />
                    <div class="tips"></div>
                </div>
            </div>

            <div class="form-group">
                <div class="label">
                    <label></label>
                </div>
                <div class="field">
                    <button class="button bg-red icon-check-square-o" type="submit"> 提交</button>
                </div>
            </div>
        </form>
    </div>
</div>

</body>
</html>

