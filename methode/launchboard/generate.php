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

function complete(Worksheet $sheet, $array, $row,$i){
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
      }
    }
  }
}
//PSA
$psa = $bdd -> query('SELECT * FROM launchboard WHERE client = "PSA"');
$projets = $psa -> fetchAll();
$count = sizeof($projets);
$i=1;
$row=30;
foreach ($projets as $key => $value) {
  if($i == 1){
    complete($sheet,$value,$row,1);
  }elseif($i == $count){
    complete($sheet,$value,$row+2*($i-1),$i);
  }elseif($i == $count -1){
    complete($sheet,$value,$row+2*($i-1),$i);
  }else{
    $sheet->insertNewRowBefore($row+$i*2,2);
    copyrange($sheet,'A'.strval($row+($i-1)*2).':BD'.strval($row+1+($i-1)*2),'A'.strval($row+$i*2));
    complete($sheet,$value,$row+($i-1)*2,$i);
  }
  $i+=1;
}

//JLR
$jlr = $bdd -> query('SELECT * FROM launchboard WHERE client = "JLR"');
$projets = $jlr -> fetchAll();
$count = sizeof($projets);
$row=40 + ($i-4)*2;
$i=1;
foreach ($projets as $key => $value) {
  if($i == 1){
    complete($sheet,$value,$row,1);
  }elseif($i == $count){
    complete($sheet,$value,$row+2*($i-1),$i);
  }elseif($i == $count -1){
    complete($sheet,$value,$row+2*($i-1),$i);
  }else{
    $sheet->insertNewRowBefore($row+$i*2,2);
    copyrange($sheet,'A'.strval($row+($i-1)*2).':BD'.strval($row+1+($i-1)*2),'A'.strval($row+$i*2));
    complete($sheet,$value,$row+($i-1)*2,$i);
  }
  $i+=1;
}

//Toyota
$toy = $bdd -> query('SELECT * FROM launchboard WHERE client = "TOY/RENAULT"');
$projets = $toy -> fetchAll();
$count = sizeof($projets);
$row=50 + ($i-4)*2 + ($row-40);
$i=1;
foreach ($projets as $key => $value) {
  if($i == 1){
    complete($sheet,$value,$row,1);
  }elseif($i == $count){
    complete($sheet,$value,$row+2*($i-1),$i);
  }elseif($i == $count -1){
    complete($sheet,$value,$row+2*($i-1),$i);
  }else{
    $sheet->insertNewRowBefore($row+$i*2,2);
    copyrange($sheet,'A'.strval($row+($i-1)*2).':BD'.strval($row+1+($i-1)*2),'A'.strval($row+$i*2));
    complete($sheet,$value,$row+($i-1)*2,$i);
  }
  $i+=1;
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
