<?php
require_once('Classes/PHPExcel.php');
require_once('Classes/PHPExcel/Writer/Excel2007.php');
require_once('Classes/PHPExcel/IOFactory.php');

//设置缓存
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array(' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);


//将表转换成数组
$headarr = array();
$bodyarr = array();
$filename = './uploads/b.xls';
$objPHPExcelReader = PHPExcel_IOFactory::load($filename);  //加载excel文件

foreach ($objPHPExcelReader->getWorksheetIterator() as $sheet)  //循环读取sheet
{
    $i = 0;
    foreach ($sheet->getRowIterator() as $row)  //逐行处理
    {
        $j = 0;
        //获取标题字段
        if ($row->getRowIndex() < 2)  //确定从哪一行开始读取
        {
            foreach ($row->getCellIterator() as $cell)  //逐列读取
            {
                $head = $cell->getValue(); //获取cell中数据
                $headarr[0][] = $head;
            }
            continue;
        }

        foreach ($row->getCellIterator() as $cell)  //逐列读取
        {
            $data = $cell->getValue(); //获取cell中数据
            $bodyarr[$i][$j] = $data;
            $j += 1;
        }
        $i += 1;
    }
}
?>

<!--读取的excel写入数据库-->
<?php
$headstr=implode(',',$headarr[0]);
echo $headstr;
foreach ($bodyarr as $row){
    echo "<div>";
    echo $bodystr=implode(',',$row);
    echo "</div>";
}
$host = 'localhost';
$username = 'root';
$password = 'root';
$con = mysql_connect($host, $username, $password);
mysql_select_db('ak');
$sql = 'set names utf8';
mysql_query($sql);
mysql_close($con);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <style>
        td {
            border: 1px solid #666666;
            cursor: crosshair;
        }
    </style>
</head>
<body>
<table>
    <!--标题单元-->
    <?php
    foreach ($headarr as $row) {
        echo '<tr>';
        for ($i = 0; $i < count($row); $i++) {
            echo "<td style='background-color: #cccccc'>$row[$i]</td>";
        }
        echo '</tr>';
    }
    ?>
    <!--内容单元-->
    <?php
    foreach ($bodyarr as $row) {
        echo '<tr>';
        for ($i = 0; $i < count($row); $i++) {
            echo "<td>$row[$i]</td>";
        }
        echo '</tr>';
    }
    ?>
</table>
<script src="./public/js/jquery.min.js"></script>
<script>
    $(function () {
        $('td').click(function () {
            $(this).attr('contenteditable', 'true');
            $(this).mouseleave(function () {
                var text = $(this).text();
                $(this).attr('contenteditable', 'false');
            })
        })
    })
</script>
</body>
</html>

