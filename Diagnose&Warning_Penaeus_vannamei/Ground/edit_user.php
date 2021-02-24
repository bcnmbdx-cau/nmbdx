<?php
include_once "mysql.php";
$id = isset($_GET["id"])?$_GET["id"]:"";
$sql="select * from member where id = '$id'";
$rel = mysqli_query($link,$sql);//执行sql语句
$arr= mysqli_fetch_array($rel); //获取一条新闻的所有信息
$sql = "select * from mem_category";
$result = mysqli_query($link, $sql);
$arr_info_category = array();
while($row = mysqli_fetch_array($result)) {
    $arr_info_category[] = $row;
}
if(count($_POST)>0){
    $sql = "update member set category_id='{$_POST['category_id']}',username='{$_POST['username']}',password='{$_POST['password']}'  where id = '$id'";
    $result = mysqli_query($link,$sql);
    if($result){
        header("Location: ./user_management.php");
        exit;
    }else{
        echo "修改失败！";
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
    <script src="../js/jquery.1.71.min.js"></script>
    <!--    <script src="../js/pintuer.js"></script>-->
</head>
<body>
<div class="panel admin-panel">
    <div class="panel-head" id="add"><strong><span class="icon-pencil-square-o"></span>增加用户</strong></div>
    <div class="body-content">
        <form method="post" class="form-x" action="" enctype="multipart/form-data">
            <div class="form-group">
                <div class="label">
                    <label>类别：</label>
                </div>
                <select name="category_id" style="padding:5px 15px; border:1px solid #ddd;">
                    <option value="">-请选择-</option>
                    <?php
                    foreach($arr_info_category as $val){
                        if($val['id']==$arr['category_id'])
                            echo "<option value='{$val['id']}' selected>{$val['name']}</option>";
                        else
                            echo "<option value='{$val['id']}'>{$val['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>用户名：</label>
                </div>
                <div class="field">
                    <input type="text" class="input w50" value="<?php echo $arr['username']?>" name="username" data-validate="required:请输入用户名" />
                    <div class="tips"></div>
                </div>
            </div>
            <div class="clear"></div>
            <div class="form-group">
                <div class="label">
                    <label>密码：</label>
                </div>
                <div class="field">
                    <input type="text" class="input w50" name="password" value="<?php echo $arr['password']?>" data-validate="required:请输入密码"/>
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