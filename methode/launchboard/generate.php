<?php
ob_start();
include_once "../../needed.php";
require '../../php/spreadsheet/vendor/autoload.php';
$file = 'base.xlsx';

$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
$spreadsheet = $reader->load($file);


$spreadsheet->setActiveSheetIndex(0)
 ->setCellValue('AI7', '15%');
$spreadsheet->setActiveSheetIndex(0)
 ->setCellValue('B30', '1')
 ->setCellValue('S30', '18/09/1897')
 ->setCellValue('C30', 'titre');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
$writer->save('temp.xlsx');
header('Content-Description: File Transfer');
header('Content-Type: application/ms-excel');
header("Content-Disposition: attachment; filename=launchroom.xlsx");
ob_clean();
flush();
readfile('temp.xlsx');
exit;
