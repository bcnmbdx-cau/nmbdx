<?php
session_start();
header("Content-type:text/html;charset=utf-8"); //设置编码
if(empty($_SESSION['username']))
{
    echo "<script>alert('请先登录');location.href='login.html';</script>";
}
include_once "fmysql.php";
$sql = "select * from water_quality_data where user_id={$_SESSION['id']} order by 采集时间 desc limit 1000";
$result = mysqli_query($link, $sql);
if (!$result) {
    printf("Error: %s\n", mysqli_error($link));
    exit();
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>dataScrollTest</title>
    <script src="../js/jquery.1.11.3.min.js"></script>
    <style>
table,tbody,tfoot,thead,tr,th,td {
    margin:0;
    padding:0;
    outline:0;
    font-size:100%;
            vertical-align:baseline;
            background:transparent;
            border-collapse:collapse;
            border-spacing:0;
            border:0px;
        }
        .tablebox {
    width:300px;
            height:400px;
            overflow:hidden;
            margin:50px auto;
        }
        .tablebox table {
    width:100%;
}
        .tablebox table th,.tablebox table td {
    font-size:12px;
            text-align:center;
            line-height:36px;
        }
        .tablebox table th {
    color:#2584e3;
    background-color:#f6f6f6;
        }
        .tablebox table td img {
    display:inline-block;
    vertical-align:middle;
        }
        .tablebox table tbody tr:nth-child(even) {
background-color:#f6f6f6;
        }
        .tablebox.table_md table td,.tablebox.table_md table th {
    line-height:40px;
        }
    </style>
</head>
<body>
<div class="tablebox" style="border: 1px solid #000">
    <table id="tableId" border="0" cellspacing="0" cellpadding="0">
        <thead>
        <tr>
            <th>采集时间</th>
            <th>监测点位</th>
            <th>溶解氧</th>
            <th>水温</th>
            <th>PH</th>
        </tr>
        </thead>
        <tbody>
            <?php
            while($row=mysqli_fetch_array($result)) {
            ?>

            <tr>
                <td><?php echo $row['采集时间']?></td>
                <td><?php echo $row['监测点位']?></td>
                <td><?php echo $row['溶解氧']?></td>
                <td><?php echo $row['水温']?></td>
                <td><?php echo $row['PH']?></td>
            </tr>

            <?php
            }
            ?>
        </tbody>
        </table>
</div>

<script>
// 参数1 tableID,参数2 div高度，参数3 速度，参数4 tbody中tr几条以上滚动
tableScroll('tableId', 400, 30, 10)
    var MyMarhq;

    function tableScroll(tableid, hei, speed, len) {
        clearTimeout(MyMarhq);
        $('#' + tableid).parent().find('.tableid_').remove()
        $('#' + tableid).parent().prepend(
            '<table class="tableid_"><thead>' + $('#' + tableid + ' thead').html() + '</thead></table>'
        ).css({
            'position': 'relative',
            'overflow': 'hidden',
            'height': hei + 'px'
        })
        $(".tableid_").find('th').each(function(i) {
            $(this).css('width', $('#' + tableid).find('th:eq(' + i + ')').width());
        });
        $(".tableid_").css({
            'position': 'absolute',
            'top': 0,
            'left': 0,
            'z-index': 9
        })
        $('#' + tableid).css({
            'position': 'absolute',
            'top': 0,
            'left': 0,
            'z-index': 1
        })

        if ($('#' + tableid).find('tbody tr').length > len) {
            $('#' + tableid).find('tbody').html($('#' + tableid).find('tbody').html() + $('#' + tableid).find('tbody').html());
            $(".tableid_").css('top', 0);
            $('#' + tableid).css('top', 0);
            var tblTop = 0;
            var outerHeight = $('#' + tableid).find('tbody').find("tr").outerHeight();

            function Marqueehq() {
                if (tblTop <= -outerHeight * $('#' + tableid).find('tbody').find("tr").length) {
                    tblTop = 0;
                } else {
                    tblTop -= 1;
                }
                $('#' + tableid).css('margin-top', tblTop + 'px');
                clearTimeout(MyMarhq);
                MyMarhq = setTimeout(function() {
                    Marqueehq()
                }, speed);
            }

            MyMarhq = setTimeout(Marqueehq, speed);
            $('#' + tableid).find('tbody').hover(function() {
                clearTimeout(MyMarhq);
            }, function() {
                clearTimeout(MyMarhq);
                if ($('#' + tableid).find('tbody tr').length > len) {
                    MyMarhq = setTimeout(Marqueehq, speed);
                }
            })
        }

    }
</script>

</body>
</html>