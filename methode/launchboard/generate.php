<?php
ini_set('memory_limit', '1024M');
ob_start();
include_once "../../needed.php";
require '../../php/spreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell;
$input = 'base2.xlsx';

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
  $lettre = array('S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AW','AX','AY','AZ','BE','BF','BG','BI','BJ','BK','BP');
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
  //partie calcul ttp
  if($sheet->getCell('AR'.strval($row))->getValue()  == ""){
    $sheet->setCellValue('AS'.strval($row),'0');
  }else{
    $sheet->setCellValue('AS'.strval($row),'1');
  }
  if(strtotime($sheet->getCell('AR'.strval($row))->getValue()) >= time() ){
    $sheet->setCellValue('AT'.strval($row),'0');
  }else{
    $sheet->setCellValue('AT'.strval($row),'1');
  }
  if($sheet->getCell('AW'.strval($row))->getValue() == "" && ($sheet->getCell('AT'.strval($row))->getValue()) && ($sheet->getCell('AS'.strval($row))->getValue())){
    $sheet->setCellValue('AU'.strval($row),'BH$7-AR'.strval($row));
  }else{
    $sheet->setCellValue('AU'.strval($row),'0');
  }
  if($sheet->getCell('AU'.strval($row))->getValue()  == ""){
    $sheet->setCellValue('AV'.strval($row),'0');
  }else{
    $sheet->setCellValue('AV'.strval($row),'1');
  }

  if($sheet->getCell('AZ'.strval($row))->getValue()  == ""){
    $sheet->setCellValue('BA'.strval($row),'0');
  }else{
    $sheet->setCellValue('BA'.strval($row),'1');
  }
  if(strtotime($sheet->getCell('AZ'.strval($row))->getValue()) >= time() ){
    $sheet->setCellValue('BB'.strval($row),'0');
  }else{
    $sheet->setCellValue('BB'.strval($row),'1');
  }
  if($sheet->getCell('BE'.strval($row))->getValue() == "" && ($sheet->getCell('BA'.strval($row))->getValue()) && ($sheet->getCell('BB'.strval($row))->getValue())){
    $sheet->setCellValue('BC'.strval($row),'BH$7-AZ'.strval($row));
  }else{
    $sheet->setCellValue('BC'.strval($row),'0');
  }
  if($sheet->getCell('BC'.strval($row))->getValue()  == ""){
    $sheet->setCellValue('BD'.strval($row),'0');
  }else{
    $sheet->setCellValue('BD'.strval($row),'1');
  }

  if($sheet->getCell('BK'.strval($row))->getValue()  == ""){
    $sheet->setCellValue('BL'.strval($row),'0');
  }else{
    $sheet->setCellValue('BL'.strval($row),'1');
  }
  if(strtotime($sheet->getCell('BK'.strval($row))->getValue()) >= time() ){
    $sheet->setCellValue('BM'.strval($row),'0');
  }else{
    $sheet->setCellValue('BM'.strval($row),'1');
  }
  if($sheet->getCell('BP'.strval($row))->getValue() == "" && ($sheet->getCell('BL'.strval($row))->getValue()) && ($sheet->getCell('BM'.strval($row))->getValue())){
    $sheet->setCellValue('BN'.strval($row),'=BH$7-BK'.strval($row));
    $sheet->getCell('BN'.strval($row))->getCalculatedValue();
  }else{
    $sheet->setCellValue('BN'.strval($row),'0');
  }
  if($sheet->getCell('BN'.strval($row))->getValue()  == ""){
    $sheet->setCellValue('BO'.strval($row),'0');
  }else{
    $sheet->setCellValue('BO'.strval($row),'1');
  }
  
}
//PSA
$psa = $bdd -> query('SELECT * FROM launchboard WHERE client = "PSA" AND archive=0');
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
    copyrange($sheet,'A'.strval($row+($i-1)*2).':BP'.strval($row+1+($i-1)*2),'A'.strval($row+$i*2));
    complete($sheet,$value,$row+($i-1)*2,$i);
  }
  $i+=1;
}

//JLR
$jlr = $bdd -> query('SELECT * FROM launchboard WHERE client = "JLR" AND archive=0');
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
    copyrange($sheet,'A'.strval($row+($i-1)*2).':BP'.strval($row+1+($i-1)*2),'A'.strval($row+$i*2));
    complete($sheet,$value,$row+($i-1)*2,$i);
  }
  $i+=1;
}

//Toyota
$toy = $bdd -> query('SELECT * FROM launchboard WHERE client = "TOY/RENAULT" AND archive=0');
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
    copyrange($sheet,'A'.strval($row+($i-1)*2).':BP'.strval($row+1+($i-1)*2),'A'.strval($row+$i*2));
    complete($sheet,$value,$row+($i-1)*2,$i);
  }
  $i+=1;
}

$writer = new Xlsx($spreadsheet);
header('Content-Description: File Transfer');
header('Content-Type: application/ms-excel');
header("Content-Disposition: attachment; filename=launchroom.xlsx");
ob_clean();
flush();
$writer->save('php://output');
exit;
