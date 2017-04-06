<?php
header("content-type:text/html; charset=utf-8");
if (isset($_POST)) {
    $result=$_POST['data'];
}else{
    exit();
}

require_once('Classes/PHPExcel.php');
require_once('Classes/PHPExcel/Writer/Excel2007.php');
require_once('Classes/PHPExcel/IOFactory.php');

//列单元
$b1 = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
$b2 = array();
for ($i = 0; $i < count($b1); $i++) {
    for ($j = 0; $j < count($b1); $j++) {
        $b2[] = $b1[$i] . $b1[$j];
    }
}
$b = array_merge($b1, $b2);


//设置缓存
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array(' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);


$objPHPExcel = new PHPExcel();
//标题
$objPHPExcel->getProperties()->setTitle("php合并excel");


//获取列数
$column_number=count($result[0]);


//设置列字段名
for($i=0;$i<$column_number;$i++){
    $objPHPExcel->getActiveSheet()->setCellValue($b[$i].'1', $result[0][$i]);
}

$i = 1;
foreach ($result as $row) {
    for($j=0;$j<$column_number;$j++){
        $objPHPExcel->getActiveSheet()->setCellValue("$b[$j]$i", $result[$i][$j]);
    }
    $i += 1;
}


//清除乱码
ob_end_clean();
// 输出Excel表格到浏览器下载
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="demo.xls"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');
// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0
$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
$objWriter->save('php://output');
?>