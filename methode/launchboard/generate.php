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

function get_nb_open_days($date_start, $date_stop) {	
	$arr_bank_holidays = array(); // Tableau des jours feriés	
	
	// On boucle dans le cas où l'année de départ serait différente de l'année d'arrivée
	$diff_year = date('Y', $date_stop) - date('Y', $date_start);
	for ($i = 0; $i <= $diff_year; $i++) {			
		$year = (int)date('Y', $date_start) + $i;
		// Liste des jours feriés
		$arr_bank_holidays[] = '1_1_'.$year; // Jour de l'an
		$arr_bank_holidays[] = '1_5_'.$year; // Fete du travail
		$arr_bank_holidays[] = '8_5_'.$year; // Victoire 1945
		$arr_bank_holidays[] = '14_7_'.$year; // Fete nationale
		$arr_bank_holidays[] = '15_8_'.$year; // Assomption
		$arr_bank_holidays[] = '1_11_'.$year; // Toussaint
		$arr_bank_holidays[] = '11_11_'.$year; // Armistice 1918
		$arr_bank_holidays[] = '25_12_'.$year; // Noel
				
		// Récupération de paques. Permet ensuite d'obtenir le jour de l'ascension et celui de la pentecote	
		$easter = easter_date($year);
		$arr_bank_holidays[] = date('j_n_'.$year, $easter + 86400); // Paques
		$arr_bank_holidays[] = date('j_n_'.$year, $easter + (86400*39)); // Ascension
		$arr_bank_holidays[] = date('j_n_'.$year, $easter + (86400*50)); // Pentecote	
	}
	//print_r($arr_bank_holidays);
	$nb_days_open = 0;
	// Mettre <= si on souhaite prendre en compte le dernier jour dans le décompte	
	while ($date_start < $date_stop) {
		// Si le jour suivant n'est ni un dimanche (0) ou un samedi (6), ni un jour férié, on incrémente les jours ouvrés	
		if (!in_array(date('w', $date_start), array(0, 6)) 
		&& !in_array(date('j_n_'.date('Y', $date_start), $date_start), $arr_bank_holidays)) {
			$nb_days_open++;		
		}
		$date_start = mktime(date('H', $date_start), date('i', $date_start), date('s', $date_start), date('m', $date_start), date('d', $date_start) + 1, date('Y', $date_start));			
	}		
	return $nb_days_open;
}

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
      $sheet -> setCellValue($lettre[2*$index].strval($row),date('j/m/y', strtotime(str_replace('/', '-',$array[$category."_f"]))));
    }
    if(! is_null($array[$category."_r"]) && $array[$category]){
      $sheet -> setCellValue($lettre[2*$index+1].strval($row),date('j/m/y', strtotime(str_replace('/', '-',$array[$category."_r"]))));
      if(strtotime(str_replace('/', '-',$array[$category."_r"])) > strtotime(str_replace('/', '-',$array[$category."_f"]))){
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
  if(strtotime(str_replace('/', '-',$array['3pt_f'])) >= time() ){
    $sheet->setCellValue('AT'.strval($row),'0');
  }else{
    $sheet->setCellValue('AT'.strval($row),'1');
  }
  if($sheet->getCell('AW'.strval($row))->getValue() == "" && ($sheet->getCell('AT'.strval($row))->getValue()) && ($sheet->getCell('AS'.strval($row))->getValue())){
    $sheet->setCellValue('AU'.strval($row),strval(get_nb_open_days(strtotime(str_replace('/', '-',$array['3pt_f'])),time())));
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
  if(strtotime(str_replace('/', '-',$array['3mpt_f'])) >= time() ){
    $sheet->setCellValue('BB'.strval($row),'0');
  }else{
    $sheet->setCellValue('BB'.strval($row),'1');
  }
  if($sheet->getCell('BE'.strval($row))->getValue() == "" && ($sheet->getCell('BA'.strval($row))->getValue()) && ($sheet->getCell('BB'.strval($row))->getValue())){
    $sheet->setCellValue('BC'.strval($row),strval(get_nb_open_days(strtotime(str_replace('/', '-',$array['3mpt_f'])),time())));
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
  if(strtotime(str_replace('/', '-',$array['4empt_f'])) >= time() ){
    $sheet->setCellValue('BM'.strval($row),'0');
  }else{
    $sheet->setCellValue('BM'.strval($row),'1');
  }
  if($sheet->getCell('BP'.strval($row))->getValue() == "" && ($sheet->getCell('BL'.strval($row))->getValue()) && ($sheet->getCell('BM'.strval($row))->getValue())){
    $sheet->setCellValue('BN'.strval($row),strval(get_nb_open_days(strtotime(str_replace('/', '-',$array["4empt_f"])),time())));
  }else{
    $sheet->setCellValue('BN'.strval($row),'0');
  }
  if($sheet->getCell('BN'.strval($row))->getValue()  == ""){
    $sheet->setCellValue('BO'.strval($row),'0');
  }else{
    $sheet->setCellValue('BO'.strval($row),'1');
  }
  
}

$sheet->setCellValue('BH7',date("j/m/y"));
//PSA
$tabPSA=array("totalPT" =>0,"ttpPT"=>0,"totalMPT"=>0,"ttpMPT"=>0,"totalEMPT"=>0,"ttpEMPT"=>0);
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
  if(! is_null($value['3pt_f']) && ! $value['3pt']){
		$tabPSA['ttpPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($value['3pt_f']))),time());
		$tabPSA['totalPT']+=1;
	}
	if(! is_null($value['3mpt_f']) && ! $value['3mpt']){
		$tabPSA['ttpMPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($value['3mpt_f']))),time());
		$tabPSA['totalMPT']+=1;
	}
	if(! is_null($value['4empt_f']) && ! $value['4empt']){
		$tabPSA['ttpEMPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($value['4empt_f']))),time());
		$tabPSA['totalEMPT']+=1;
	}
}

//JLR
$tabJLR=array("totalPT" =>0,"ttpPT"=>0,"totalMPT"=>0,"ttpMPT"=>0,"totalEMPT"=>0,"ttpEMPT"=>0);
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
  if(! is_null($value['3pt_f']) && ! $value['3pt']){
		$tabJLR['ttpPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($value['3pt_f']))),time());
		$tabJLR['totalPT']+=1;
	}
	if(! is_null($value['3mpt_f']) && ! $value['3mpt']){
		$tabJLR['ttpMPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($value['3mpt_f']))),time());
		$tabJLR['totalMPT']+=1;
	}
	if(! is_null($value['4empt_f']) && ! $value['4empt']){
		$tabJLR['ttpEMPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($value['4empt_f']))),time());
		$tabJLR['totalEMPT']+=1;
	}
}

//Toyota
$tabTOY=array("totalPT" =>0,"ttpPT"=>0,"totalMPT"=>0,"ttpMPT"=>0,"totalEMPT"=>0,"ttpEMPT"=>0);
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
  if(! is_null($value['3pt_f']) && ! $value['3pt']){
		$tabTOY['ttpPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($value['3pt_f']))),time());
		$tabTOY['totalPT']+=1;
	}
	if(! is_null($value['3mpt_f']) && ! $value['3mpt']){
		$tabTOY['ttpMPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($value['3mpt_f']))),time());
		$tabTOY['totalMPT']+=1;
	}
	if(! is_null($value['4empt_f']) && ! $value['4empt']){
		$tabTOY['ttpEMPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($value['4empt_f']))),time());
		$tabTOY['totalEMPT']+=1;
	}
}

//TTP
if($tabPSA['totalPT'] == 0){
  $sheet->setCellValue('AR17',0);
}else{
  $sheet->setCellValue('AR17',(int) $tabPSA['ttpPT']/$tabPSA['totalPT']);
}
if($tabJLR['totalPT'] == 0){
  $sheet->setCellValue('AR19',0);
}else{
  $sheet->setCellValue('AR19',(int) $tabJLR['ttpPT']/$tabJLR['totalPT']);
}
if($tabTOY['totalPT'] == 0){
  $sheet->setCellValue('AR21',0);
}else{
  $sheet->setCellValue('AR21',(int) $tabTOY['ttpPT']/$tabTOY['totalPT']);
}

if($tabPSA['totalMPT'] == 0){
  $sheet->setCellValue('AZ17',0);
}else{
  $sheet->setCellValue('AZ17',(int) $tabPSA['ttpMPT']/$tabPSA['totalMPT']);
}
if($tabJLR['totalMPT'] == 0){
  $sheet->setCellValue('AZ19',0);
}else{
  $sheet->setCellValue('AZ19',(int) $tabJLR['ttpMPT']/$tabJLR['totalMPT']);
}
if($tabTOY['totalMPT'] == 0){
  $sheet->setCellValue('AZ21',0);
}else{
  $sheet->setCellValue('AZ21',(int) $tabTOY['ttpMPT']/$tabTOY['totalMPT']);
}

if($tabPSA['totalEMPT'] == 0){
  $sheet->setCellValue('BK17',0);
}else{
  $sheet->setCellValue('BK17',(int) $tabPSA['ttpEMPT']/$tabPSA['totalEMPT']);
}
if($tabJLR['totalEMPT'] == 0){
  $sheet->setCellValue('BK19',0);
}else{
  $sheet->setCellValue('BK19',(int) $tabJLR['ttpEMPT']/$tabJLR['totalEMPT']);
}
if($tabTOY['totalEMPT'] == 0){
  $sheet->setCellValue('BK21',0);
}else{
  $sheet->setCellValue('BK21',(int) $tabTOY['ttpEMPT']/$tabTOY['totalEMPT']);
}

if($tabPSA['totalPT']+$tabJLR['totalPT']+$totalToy['totalPT'] == 0){
  $sheet->setCellValue('P17',0);
}else{
  $sheet->setCellValue('P17',(int) (($tabPSA['ttpPT']+$tabJLR['ttpPT']+$totalToy['ttpPT'])/($tabPSA['totalPT']+$tabJLR['totalPT']+$totalToy['totalPT'])));
}
if($tabPSA['totalMPT']+$tabJLR['totalMPT']+$totalToy['totalMPT'] == 0){
  $sheet->setCellValue('P19',0);
}else{
  $sheet->setCellValue('P19',(int) (($tabPSA['ttpMPT']+$tabJLR['ttpMPT']+$totalToy['ttpMPT'])/($tabPSA['totalMPT']+$tabJLR['totalMPT']+$totalToy['totalMPT'])));
}
if($tabPSA['totalEMPT']+$tabJLR['totalEMPT']+$totalToy['totalEMPT'] == 0){
  $sheet->setCellValue('P21',0);
}else{
  $sheet->setCellValue('P21',(int) (($tabPSA['ttpEMPT']+$tabJLR['ttpEMPT']+$totalToy['ttpEMPT'])/($tabPSA['totalEMPT']+$tabJLR['totalEMPT']+$totalToy['totalEMPT'])));
}

$tot= $tabPSA['totalPT'] + $tabPSA['totalMPT'] + $tabPSA['totalEMPT'] + $tabTOY['totalPT'] + $tabTOY['totalMPT'] + $tabTOY['totalEMPT'] + $tabJLR['totalPT'] + $tabJLR['totalMPT'] + $tabJLR['totalEMPT'];
$ttp = $tabPSA['ttpPT'] + $tabPSA['ttpMPT'] + $tabPSA['ttpEMPT'] + $tabTOY['ttpPT'] + $tabTOY['ttpMPT'] + $tabTOY['ttpEMPT'] + $tabJLR['ttpPT'] + $tabJLR['ttpMPT'] + $tabJLR['ttpEMPT'];
if($tot){
  $sheet ->setCellValue('AI7',(int) $ttp/$tot);
}else{
  $sheet->setCellValue('AI7', 0);
}
$writer = new Xlsx($spreadsheet);
header('Content-Description: File Transfer');
header('Content-Type: application/ms-excel');
header("Content-Disposition: attachment; filename=launchroom.xlsx");
ob_clean();
flush();
$writer->save('php://output');
exit;
