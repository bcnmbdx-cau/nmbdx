<?php
include_once "../Front/fmysql.php";
function info($link,$pageNum = 1, $pageSize = 8,$category)
{
    $Head=($pageNum - 1) * $pageSize;
    $sql = "select * from information where category_id={$category} limit $Head,$pageSize";
    $result = mysqli_query($link, $sql);
    if (!$result) {
        printf("Error: %s\n", mysqli_error($link));
        exit();
    }
    $arr_info=array();
    while($row = mysqli_fetch_array($result)) {
        array_push($arr_info,$row);
    }
    return $arr_info;
}
function get_Max_Page($link,$category)
{
    $sql = "select * from information WHERE category_id = {$category} limit 0,5";
    $sql_count = "select count(*) as amount from information WHERE category_id={$category} ";
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
@$max_page=get_Max_Page($link,$category);
@$pageNum = empty($_GET["pageNum"])?1:$_GET["pageNum"];
@$pageSize =8;
@$arr_info = info($link,$pageNum,$pageSize,$category);

?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
</head>
<body>
<form>
    <div>
        <table>
            <?php
            $no=1;
            if($arr_info){
                foreach ($arr_info as $val)
            {
                ?>
                <table width="1150px">
                <br width="90%" height="25px">
                    <tr>
                    <td >
                        <?php echo "<a href='r.php?id={$val['id']}' style=\"text-align: left;font-size:20px\"> $no.   {$val['title']} </a>" ?>
                    </td>
                    <td style="text-align: right">
                        <?
                        echo "{$val['created_at']}";
                        ?>
                    </td>
                    </tr>
                    <br>
                    <tr>
                    <td style="text-align: left">
                        <?
                        echo "分类：{$val['tag']}";
                        ?>
                    </td>
                    <td style="text-align: right;style=">
                        <?
                        echo "作者：{$val['author']}";
                        ?>
                    </td>
                    </tr>
                </table>
                    <?
                    $no=$no+1;
                    ?>
                    <table border="1px"></table>
                </table>
                </table>

                    <?
                    echo "</br>";
                }
            }
            ?>
            <tr>

                <td colspan="8">
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
    </div>
</form>
</body>
</html>
