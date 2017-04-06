<?php
require_once('Classes/PHPExcel.php');
require_once('Classes/PHPExcel/Writer/Excel2007.php');
require_once('Classes/PHPExcel/IOFactory.php');

//设置缓存
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array(' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);


//列单元
$b1 = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
$b2 = array();
for ($i = 0; $i < count($b1); $i++) {
    for ($j = 0; $j < count($b1); $j++) {
        $b2[] = $b1[$i] . $b1[$j];
    }
}
$b = array_merge($b1, $b2);

//将表转换成数组
$excel = array();
$filename = './uploads/b.xls';
$objPHPExcelReader = PHPExcel_IOFactory::load($filename);  //加载excel文件

foreach ($objPHPExcelReader->getWorksheetIterator() as $sheet)  //循环读取sheet
{
    $i = 0;
    foreach ($sheet->getRowIterator() as $row)  //逐行处理
    {
        $j = 0;
        foreach ($row->getCellIterator() as $cell)  //逐列读取
        {
            $data = $cell->getValue(); //获取cell中数据
            $excel[$i][$j] = $data;
            $j += 1;
        }
        $i += 1;
    }
}
?>

<!--读取的excel写入数据库-->
<?php

var_dump($excel);
$host = 'localhost';
$username = 'root';
$password = 'root';
$con = mysql_connect($host, $username, $password);
mysql_select_db('oa');
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
    <!--在网页中显示excel-->
    <?php
    $j=1;
    foreach ($excel as $row) {
        echo '<tr>';
        for ($i = 0; $i < count($row); $i++) {
            echo "<td data-lt=$b[$i]/$j>$b[$i]/$j $row[$i]</td>";
        }
        echo '</tr>';
        $j+=1;
    }
    ?>
</table>
<script src="./public/js/jquery.min.js"></script>
<script>
    $(function () {
        $('td').click(function () {
            //获取当前td坐标;
            var lt=$(this).data('lt');
            $(this).attr('contenteditable', 'true');
            $(this).blur(function () {
                var text = $(this).text();
                $(this).attr('contenteditable', 'false');
                /*$.ajax({
                    type:'GET',
                    url:'updateexcel.php',
                    data:{
                        lt:lt,
                        text:text
                    }
                    ,
                    success:function (msg) {
                        alert(msg);
                    }
                })*/
            })
        })
    })
</script>
</body>
</html>

