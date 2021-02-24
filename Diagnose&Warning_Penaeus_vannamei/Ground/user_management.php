<?php
include_once "mysql.php";
function info($link,$pageNum = 1, $pageSize = 8)
{
    $Head=($pageNum - 1) * $pageSize;
    if($_POST['category_id'])
    {
        $sql = "select * from member where category_id='{$_POST['category_id']}' limit $Head,$pageSize";
    }
    else
        $sql = "select * from member limit $Head,$pageSize";
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
    if($_POST['category_id'])
    {
        $sql = "select * from member where category_id='{$_POST['category_id']}'";
        $sql_count = "select count(*) as amount from member where category_id='{$_POST['category_id']}' ";
    }
    else
    {
        $sql = "select * from member where 1 ";
        $sql_count = "select count(*) as amount from member where 1 ";
    }
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
$sql = "select * from mem_category ";
$result = mysqli_query($link, $sql);
$info_category = array();
while($row = mysqli_fetch_array($result)) {
    $info_category[] = $row;
}
$info_category_value = array();
foreach($info_category as $val ){
    $info_category_value[$val['id']] = $val['name'];
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
        <div class="panel-head"><strong class="icon-reorder"> 用户管理</strong></div>
        <div class="padding border-bottom">
            <ul class="search" style="padding-left:10px;">
                <li> <a class="button border-red icon-plus-square-o" href="add_user.php"> 添加账户</a> </li>
                <if condition="$iscid eq 1">
                    <li>
                        <select name="category_id" class="input" style="width:200px; line-height:17px;">
                            <option value="">-请选择-</option>
                            <?php
                            foreach( $info_category_value as $key => $val ){
                                ?>
                                <option value="<?php echo $key;?>"><?php echo $val;?></option>
                                <?php
                            }
                            ?>
                        </select>
                    <li>
                        <button class="button border-main icon-search" type="submit"> 搜索</button>
                    </li>
                </if>
            </ul>
        </div>
        <table class="table table-hover text-center">
            <tr>
                <th width="100" style="text-align:left; padding-left:20px;">ID</th>
                <th>用户类别</th>
                <th>用户名</th>
                <th>密码</th>
                <th width="10%">更新时间</th>
                <th width="310">操作</th>
            </tr>
            <?php
            if($arr_info){
                foreach ($arr_info as $val){
                    echo "<tr>";
                    echo " <td style='text-align:left; padding-left:20px;'>
 <input type='checkbox' name='id[]' value='' />{$val['id']}</td>";
                    echo "<td>{$info_category_value[$val['category_id']]}</td>";
                    echo "<td>". mb_substr($val['username'], 0,15,"utf-8")."</td>";
                    echo "<td>". mb_substr($val['password'], 0,20,"utf-8")."</td>";
                    echo "<td>{$val['created_at']}</td>";
                    ?>
                    <td>
                        <div class='button-group'>
                            <a class='button border-blue' href='edit_user.php?id=<?php echo $val['id'];?>'>
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
            document.location.href = "delete_user.php?id=" + id ;
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
