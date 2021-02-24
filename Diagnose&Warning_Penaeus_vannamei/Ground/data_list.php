<?php
include_once "mysql.php";
function info($link,$pageNum = 1, $pageSize = 8)
{
    $Head=($pageNum - 1) * $pageSize;
    if($_POST['id'])
    {
        $sql = "select * from diagnose where category_id='{$_POST['id']}' limit $Head,$pageSize";
    }
    else
    $sql = "select * from diagnose limit $Head,$pageSize";
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
function get_Max_Page($link)
{
    if($_POST['id'])
    {
        $sql = "select * from diagnose where category_id='{$_POST['id']}'";
        $sql_count = "select count(*) as amount from diagnose where category_id='{$_POST['id']}' ";
    }
    else
    {
        $sql = "select * from diagnose where 1 ";
        $sql_count = "select count(*) as amount from diagnose where 1 ";
    }
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
$sql = "select * from diagnose ";
$result = mysqli_query($link, $sql);
$info_category = array();
while($row = mysqli_fetch_array($result)) {
    $info_category[] = $row;
}
$info_category_value = array();
foreach($info_category as $val ){
    $info_category_value[$val['id']] = $val['id'];
}
@$max_page=get_Max_Page($link);
@$pageNum = empty($_GET["pageNum"])?1:$_GET["pageNum"];
@$pageSize =8;
@$arr_info = info($link,$pageNum,$pageSize);

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
</head>
<body>
<form method="post" action="" id="listform">
    <div class="panel admin-panel">
        <div class="panel-head"><strong class="icon-reorder"> 内容列表</strong> <a href="" style="float:right; display:none;">添加字段</a></div>
        <div class="padding border-bottom">
            <ul class="search" style="padding-left:10px;">
                <li> <a class="button border-red icon-plus-square-o" href="add_data.php"> 添加内容</a> </li>

            </ul>
        </div>
        <table class="table table-hover text-center">
            <tr style="width: 1000px">
                <td>pH</td>
                <td>含氧量</td>
                <td>温度</td>
                <td>水色</td>
                <td>氨氮</td>
                <td>结果</td>
                <td width="310">操作</td>
            </tr>
            <tr>
                <td colspan="7">注：0为正常范围，1为偏离正常范围，2为偏离正常范围严重</td>
            </tr>
            <?php
            if($arr_info){
                foreach ($arr_info as $val){
                    echo "<tr>";
                    echo "<td>{$val['pH']}</td>";
                    echo "<td>{$val['DO']}</td>";
                    echo "<td>{$val['temperature']}</td>";
                    echo "<td>{$val['color']}</td>";
                    echo "<td>{$val['NO2']}</td>";
                    echo "<td>{$val['res']}</td>";
                    ?>
                    <td>
                        <div class='button-group'>
                            <a class='button border-blue' href='edit_data.php?id=<?php echo $val['id'];?>'>
                                <span class='icon-edit'></span> 修改</a>
                            <a class='button border-red' href='javascript:;' onclick='return del(<?php echo $val['id']?>)'>
                                <span class='icon-trash-o'></span> 删除</a>
                        </div>
                    </td>
                    <?
                    echo "</tr>";
                }
            }
            ?>
            <tr>

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
                            <a href="?pageNum=<?php echo $pageNum==$max_page?$max_page:($pageNum+1)?>">下一页</a>
                            <?
                        }
                        ?>
                        <a href="?pageNum=<?php echo $max_page?>">末页</a>
                        / 总页码 <font color="red"><?php echo $max_page;?></font>页 当前页码 <font color="red"><?php echo $pageNum;?></font>页
                    </div>
                </td>
            </tr>
        </table>
    </div>
</form>
<script type="text/javascript">

    //搜索
    function changesearch(){

    }

    //单个删除
    function del(id){
        if(confirm("您确定要删除吗?")){
            document.location.href = "delete_data.php?id=" + id ;
        }
    }

    //全选
    $("#checkall").click(function(){
        $("input[name='id[]']").each(function(){
            if (this.checked) {
                this.checked = false;
            }
            else {
                this.checked = true;
            }
        });
    })

    //批量删除
    function DelSelect(){
        var Checkbox=false;
        $("input[name='id[]']").each(function(){
            if (this.checked==true) {
                Checkbox=true;
            }
        });

        //单个删除
        if (Checkbox){
            var t=confirm("您确认要删除选中的内容吗？");
            if (t==false) return false;
            $("#listform").submit();
        }
        else{
            alert("请选择您要删除的内容!");
            return false;
        }
    }


</script>
</body>
</html>
