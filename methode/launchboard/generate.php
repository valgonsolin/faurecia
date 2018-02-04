<?php
ob_start();
include_once "../../needed.php";
require '../../php/spreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell;
$input = 'base.xlsx';

$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
$spreadsheet = $reader->load($input);
// $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($input);


$sheet = $spreadsheet->getActiveSheet();
$final = new Spreadsheet();
$finaleSheet = $final -> getActiveSheet();

function cellColor($sheet, $cells,$color){
    $sheet->getStyle($cells)->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB($color);
}

function copyRange( Worksheet $sheet, $srcRange, $dstCell) {
    // Validate source range. Examples: A2:A3, A2:AB2, A27:B100
    if( !preg_match('/^([A-Z]+)(\d+):([A-Z]+)(\d+)$/', $srcRange, $srcRangeMatch) ) {
        // Wrong source range
        return;
    }
    // Validate destination cell. Examples: A2, AB3, A27
    if( !preg_match('/^([A-Z]+)(\d+)$/', $dstCell, $destCellMatch) ) {
        // Wrong destination cell
        return;
    }

    $srcColumnStart = $srcRangeMatch[1];
    $srcRowStart = $srcRangeMatch[2];
    $srcColumnEnd = $srcRangeMatch[3];
    $srcRowEnd = $srcRangeMatch[4];

    $destColumnStart = $destCellMatch[1];
    $destRowStart = $destCellMatch[2];

    // For looping purposes we need to convert the indexes instead
    // Note: We need to subtract 1 since column are 0-based and not 1-based like this method acts.

    $srcColumnStart = Cell::columnIndexFromString($srcColumnStart) - 1;
    $srcColumnEnd = Cell::columnIndexFromString($srcColumnEnd) - 1;
    $destColumnStart = Cell::columnIndexFromString($destColumnStart) - 1;

    $rowCount = 0;
    for ($row = $srcRowStart; $row <= $srcRowEnd; $row++) {
        $colCount = 0;
        for ($col = $srcColumnStart; $col <= $srcColumnEnd; $col++) {
            $cell = $sheet->getCellByColumnAndRow($col, $row);
            $style = $sheet->getStyleByColumnAndRow($col, $row);
            $dstCell = Cell::stringFromColumnIndex($destColumnStart + $colCount) . (string)($destRowStart + $rowCount);
            $sheet->setCellValue($dstCell, $cell->getValue());
            $sheet->duplicateStyle($style, $dstCell);

            // Set width of column, but only once per row
            if ($rowCount === 0) {
                $w = $sheet->getColumnDimensionByColumn($col)->getWidth();
                $sheet->getColumnDimensionByColumn ($destColumnStart + $colCount)->setAutoSize(false);
                $sheet->getColumnDimensionByColumn ($destColumnStart + $colCount)->setWidth($w);
            }

            $colCount++;
        }

        $h = $sheet->getRowDimension($row)->getRowHeight();
        $sheet->getRowDimension($destRowStart + $rowCount)->setRowHeight($h);

        $rowCount++;
    }

    foreach ($sheet->getMergeCells() as $mergeCell) {
        $mc = explode(":", $mergeCell);
        $mergeColSrcStart = Cell::columnIndexFromString(preg_replace("/[0-9]*/", "", $mc[0])) - 1;
        $mergeColSrcEnd = Cell::columnIndexFromString(preg_replace("/[0-9]*/", "", $mc[1])) - 1;
        $mergeRowSrcStart = ((int)preg_replace("/[A-Z]*/", "", $mc[0]));
        $mergeRowSrcEnd = ((int)preg_replace("/[A-Z]*/", "", $mc[1]));

        $relativeColStart = $mergeColSrcStart - $srcColumnStart;
        $relativeColEnd = $mergeColSrcEnd - $srcColumnStart;
        $relativeRowStart = $mergeRowSrcStart - $srcRowStart;
        $relativeRowEnd = $mergeRowSrcEnd - $srcRowStart;

        if (0 <= $mergeRowSrcStart && $mergeRowSrcStart >= $srcRowStart && $mergeRowSrcEnd <= $srcRowEnd) {
            $targetColStart = Cell::stringFromColumnIndex($destColumnStart + $relativeColStart);
            $targetColEnd = Cell::stringFromColumnIndex($destColumnStart + $relativeColEnd);
            $targetRowStart = $destRowStart + $relativeRowStart;
            $targetRowEnd = $destRowStart + $relativeRowEnd;

            $merge = (string)$targetColStart . (string)($targetRowStart) . ":" . (string)$targetColEnd . (string)($targetRowEnd);
            //Merge target cells
            $sheet->mergeCells($merge);
        }
    }
}

function complete(Worksheet $sheet, $array, $row,$i,$tab){
  $sheet ->setCellValue('B'.$row,$i)
  ->setCellValue('C'.$row,$array['code']." - ".$array['titre']);
  $cat = array('2tct','2capacity','2equip','2pfmea','2mvp','2layout','2master','2pack','3equip','3pack','3supplier','3checklist1','3pt','3checklist2','3mpt','3samples','4checklist','4empt');
  $lettre = array('S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','BA','BB','BC','BD');
  foreach ($cat as $index => $category){
    if(! is_null($array[$category."_f"])){
      $sheet -> setCellValue($lettre[2*$index].strval($row),date('j/m/y', strtotime($array[$category."_f"])));
    }
    if(! is_null($array[$category."_r"])){
      $sheet -> setCellValue($lettre[2*$index+1].strval($row),date('j/m/y', strtotime($array[$category."_r"])));
      if(strtotime($array[$category."_r"]) > strtotime($array[$category."_f"])){
        cellColor($sheet,$lettre[2*$index+1].strval($row),'FF0000');
      }else{
        cellColor($sheet,$lettre[2*$index+1].strval($row),'00B050');
        $tab[$category]+=1;
      }
    }
  }
  return $tab;
}
//PSA
$indice = array('2tct'=> 'S','2capacity'=> 'U','2equip'=> 'W','2pfmea'=> 'Y','2mvp'=> 'AA','2layout'=> 'AC','2master'=> 'AE','2pack'=> 'AG','3equip'=> 'AJ','3pack'=> 'AL','3supplier'=> 'AN','3checklist1'=> 'AP','3pt'=> 'AR','3checklist2'=> 'AT','3mpt'=> 'AV','3samples'=> 'AX','4checklist'=> 'BA','4empt'=> 'BC');
$psa = $bdd -> query('SELECT * FROM launchboard WHERE client = "PSA"');
$projets = $psa -> fetchAll();
$count = sizeof($projets);
$psagreen = array('2tct'=> 0,'2capacity'=> 0,'2equip'=> 0,'2pfmea'=> 0,'2mvp'=> 0,'2layout'=> 0,'2master'=> 0,'2pack'=> 0,'3equip'=> 0,'3pack'=> 0,'3supplier'=> 0,'3checklist1'=> 0,'3pt'=> 0,'3checklist2'=> 0,'3mpt'=> 0,'3samples'=> 0,'4checklist'=> 0,'4empt'=> 0);
$i=1;
$row=30;
foreach ($projets as $key => $value) {
  if($i == 1){
    $psagreen=complete($sheet,$value,$row,1,$psagreen);
  }elseif($i == $count){
    $psagreen=complete($sheet,$value,$row+2*($i-1),$i,$psagreen);
  }elseif($i == $count -1){
    $psagreen=complete($sheet,$value,$row+2*($i-1),$i,$psagreen);
  }else{
    $sheet->insertNewRowBefore($row+$i*2,2);
    copyrange($sheet,'A'.strval($row+($i-1)*2).':BD'.strval($row+1+($i-1)*2),'A'.strval($row+$i*2));
    $psagreen=complete($sheet,$value,$row+($i-1)*2,$i,$psagreen);
  }
  $i+=1;
}
foreach ($psagreen as $key => $value) {
  if($value/($i-1) > 0.75){
    $sheet -> setCellValue($indice[$key].'17','75%');
  }
}

//JLR
$jlr = $bdd -> query('SELECT * FROM launchboard WHERE client = "JLR"');
$projets = $jlr -> fetchAll();
$count = sizeof($projets);
$jlrgreen = array('2tct'=> 0,'2capacity'=> 0,'2equip'=> 0,'2pfmea'=> 0,'2mvp'=> 0,'2layout'=> 0,'2master'=> 0,'2pack'=> 0,'3equip'=> 0,'3pack'=> 0,'3supplier'=> 0,'3checklist1'=> 0,'3pt'=> 0,'3checklist2'=> 0,'3mpt'=> 0,'3samples'=> 0,'4checklist'=> 0,'4empt'=> 0);
$row=40 + ($i-4)*2;
$i=1;
foreach ($projets as $key => $value) {
  if($i == 1){
    $jlrgreen=complete($sheet,$value,$row,1,$jlrgreen);
  }elseif($i == $count){
    $jlrgreen=complete($sheet,$value,$row+2*($i-1),$i,$jlrgreen);
  }elseif($i == $count -1){
    $jlrgreen=complete($sheet,$value,$row+2*($i-1),$i,$jlrgreen);
  }else{
    $sheet->insertNewRowBefore($row+$i*2,2);
    copyrange($sheet,'A'.strval($row+($i-1)*2).':BD'.strval($row+1+($i-1)*2),'A'.strval($row+$i*2));
    $jlrgreen=complete($sheet,$value,$row+($i-1)*2,$i,$jlrgreen);
  }
  $i+=1;
}
foreach ($jlrgreen as $key => $value) {
  if($value/($i-1) > 0.75){
    $sheet -> setCellValue($indice[$key].'19','75%');
  }
}

//Toyota
$toy = $bdd -> query('SELECT * FROM launchboard WHERE client = "TOY/RENAULT"');
$projets = $toy -> fetchAll();
$count = sizeof($projets);
$toygreen = array('2tct'=> 0,'2capacity'=> 0,'2equip'=> 0,'2pfmea'=> 0,'2mvp'=> 0,'2layout'=> 0,'2master'=> 0,'2pack'=> 0,'3equip'=> 0,'3pack'=> 0,'3supplier'=> 0,'3checklist1'=> 0,'3pt'=> 0,'3checklist2'=> 0,'3mpt'=> 0,'3samples'=> 0,'4checklist'=> 0,'4empt'=> 0);
$row=50 + ($i-4)*2 + ($row-40);
$i=1;
foreach ($projets as $key => $value) {
  if($i == 1){
    $toygreen=complete($sheet,$value,$row,1,$toygreen);
  }elseif($i == $count){
    $toygreen=complete($sheet,$value,$row+2*($i-1),$i,$toygreen);
  }elseif($i == $count -1){
    $toygreen=complete($sheet,$value,$row+2*($i-1),$i,$toygreen);
  }else{
    $sheet->insertNewRowBefore($row+$i*2,2);
    copyrange($sheet,'A'.strval($row+($i-1)*2).':BD'.strval($row+1+($i-1)*2),'A'.strval($row+$i*2));
    $toygreen=complete($sheet,$value,$row+($i-1)*2,$i,$toygreen);
  }
  $i+=1;
}

foreach ($toygreen as $key => $value) {
  if($value/($i-1) > 0.75){
    $sheet -> setCellValue($indice[$key].'21','75%');
  }
}

$writer = new Xlsx($spreadsheet);
$output ='temp.xlsx';

header('Content-Description: File Transfer');
header('Content-Type: application/ms-excel');
header("Content-Disposition: attachment; filename=launchroom.xlsx");
ob_clean();
flush();
$writer->save('php://output');
// readfile('temp.xlsx');
exit;
