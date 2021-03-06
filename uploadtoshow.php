<?php
require_once('Classes/PHPExcel.php');
require_once('Classes/PHPExcel/Writer/Excel2007.php');
require_once('Classes/PHPExcel/IOFactory.php');

//设置缓存
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array(' memoryCacheSize ' => '20MB');
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
$filename = './uploads/cx.xls';
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
$exceldata=json_encode($excel);
?>


<?php
/*表格配置存入数组然后放入数据库中*/
$config = array(
    /*true设置字符串，否则获取的值为1*/
    'rowHeaders' => 'true',
    'colHeaders' => 'true',
    'startRows' => 5,
    'startCols' => 5,
    'rowHeaders' => 'true',
    'colHeaders' => 'true',
    'minSpareRows' => 1,
    'contextMenu' => 'true',
    'minRows' => 20,
    'minCols' => 20,
    'autoColumnSize'=>'true',
);

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
<div style="margin-bottom: 50px">
    <button id="save">保存</button>
    <form action="export.php" method="post">
        <input type="hidden" name="id" value="5">
        <input type="submit" value="导出excel">
    </form>
</div>
<div id="example"></div>
<script>
    function sendData() {
        return <?php echo $exceldata ?>;
    }
    var container = document.getElementById('example');
    var ht = new Handsontable(container, {
        data: sendData(),
        //读取配置数组
        <?php
        foreach ($config as $key=>$value){
            echo $key.':'.$value.',';
        }
        ?>
    });
    $('#save').click(function () {

        var data = ht.getData();
        $.ajax({
            type: 'POST',
            url: 'save.php',
            data: {
                data: data,
            },
            success: function (msg) {
                alert(msg);
            }
        })
    })
</script>
</body>
</html>

