<?php
include_once "mysql.php";
function info($link,$pageNum = 1, $pageSize = 4)
{
    $Head=($pageNum - 1) * $pageSize;
    if($_POST['category_id'])
    {
        $sql = "select * from consulting limit $Head,$pageSize";
    }
    else
        $sql = "select * from consulting limit $Head,$pageSize";
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
        $sql = "select * from consulting";
        $sql_count = "select count(*) as amount from consulting";
    $sql .= "order by id asc";
    $result_amount = mysqli_query($link, $sql_count);
    if (!$result_amount) {
        return 0;
    } else {
        $arr_amount = mysqli_fetch_assoc($result_amount);
        $amount = $arr_amount['amount'];
        $page_size = 4;
        $max_page = ceil($amount / $page_size);
        return $max_page;
    }
}
@$max_page=get_Max_Page($link);
@$pageNum = empty($_GET["pageNum"])?1:$_GET["pageNum"];
@$pageSize =4;
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
        </div>
        <table class="table table-hover text-center">
            <tr>
                <th width="100" style="text-align:left; padding-left:20px;">ID</th>
                <th>问题</th>
                <th>提问者</th>
                <th>提问时间</th>
                <th>回答者</th>
                <th width="10%">更新时间</th>
                <th width="310">操作</th>
            </tr>
            <?php
            if($arr_info){
                foreach ($arr_info as $val){
                    echo "<tr>";
                    echo " <td style='text-align:left; padding-left:20px;'>
 <input type='checkbox' name='id[]' value='' />{$val['id']}</td>";
                    echo "<td>". mb_substr($val['title'], 0,15,"utf-8")."</td>";
                    echo "<td>{$val['author']}</td>";
                    echo "<td>{$val['last_post_time']}</td>";
                    echo "<td>{$val['reply_author']}</td>";
                    echo "<td>{$val['reply_time']}</td>";
                    ?>
                    <td>
                        <div class='button-group'>
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
    </div>
</form>
<script type="text/javascript">

    //搜索
    function changesearch(){

    }

    //单个删除
    function del(id){
        if(confirm("您确定要删除吗?")){
            document.location.href = "delete_cos.php?id=" + id ;
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
