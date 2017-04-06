<?php
require_once('Classes/PHPExcel.php');
require_once('Classes/PHPExcel/Writer/Excel2007.php');
require_once ('db.inc.php');

//设置缓存
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB' );
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

$objPHPExcel = new PHPExcel();
//标题
$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
//宽度
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

//列
$objPHPExcel->getActiveSheet()->setCellValue('A1', '学号');
$objPHPExcel->getActiveSheet()->setCellValue('B1','姓名');
$objPHPExcel->getActiveSheet()->setCellValue('C1','性别');
$objPHPExcel->getActiveSheet()->setCellValue('D1','班级');
$objPHPExcel->getActiveSheet()->setCellValue('E1','学院');
$objPHPExcel->getActiveSheet()->setCellValue('F1','密码');

$i=2;
foreach ($rows as $row){
    $objPHPExcel->getActiveSheet()->setCellValue("A$i", $row['id']);
    $objPHPExcel->getActiveSheet()->setCellValue("B$i", $row['name']);
    $objPHPExcel->getActiveSheet()->setCellValue("C$i", $row['gender']);
    $objPHPExcel->getActiveSheet()->setCellValue("D$i", $row['class']);
    $objPHPExcel->getActiveSheet()->setCellValue("E$i", $row['school']);
    $objPHPExcel->getActiveSheet()->setCellValue("F$i", $row['password']);
    $i+=1;
}


//清除乱码
ob_end_clean();
// 输出Excel表格到浏览器下载
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="abc.xls"');
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