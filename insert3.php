<?php
require_once('Classes/PHPExcel.php');
require_once('Classes/PHPExcel/Writer/Excel2007.php');
require_once('Classes/PHPExcel/IOFactory.php');

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', FALSE);


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
$filename = './tmp/demo.xls';
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
$excel = json_encode($excel);
?>

<!--读取的excel写入数据库-->
<?php
$host = 'localhost';
$username = 'root';
$password = 'root';
$con = mysql_connect($host, $username, $password);
mysql_select_db('oa');
$sql = 'set names utf8';
mysql_query($sql);
$sql = "insert into work (content,tableid) values($excel,'1')";
/*if (mysql_query($sql)) {
    echo 'success';
} else {
    echo 'error';
}*/
mysql_close($con);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <script src="handsontable/dist/handsontable.full.js"></script>
    <script src="./public/js/jquery.min.js"></script>
    <script src="public/handsontable/handsontable.full.js"></script>
    <link rel="stylesheet" media="screen" href="public/handsontable/handsontable.full.css">
</head>
<body>
<button>保存</button>
<div id="example"></div>
<script>
    function sendData() {
        return <?php echo $excel ?>;
    }
    var container = document.getElementById('example');
    var ht = new Handsontable(container, {
        data: sendData(),
        rowHeaders: true,
        colHeaders: true,
        startRows: 5,
        startCols: 5,
        minRows: 30,
        minCols: 20,
        rowHeaders: true,
        colHeaders: true,
        minSpareRows: 1,
        contextMenu: true,
        colWidths: 150
    });
    $('button').click(function () {
        var data = ht.getData();
        $.ajax({
            type:'POST',
            url:'test.php',
            data:{
                data:data,
            },
            success:function (msg) {
                alert(msg);
            }
        })
    })
</script>
</body>
</html>

