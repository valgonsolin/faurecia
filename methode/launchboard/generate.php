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

function get_nb_open_days_alg($date_start, $date_stop){
	if($date_start<$date_stop){
		return get_nb_open_days($date_start,$date_stop);
	}else{
		return (-1) * get_nb_open_days($date_stop,$date_start);
	}
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

 if((! is_null($value['3pt_f'])) && ( is_null($value['3pt_r']) || (! $value['3pt'])) && (get_nb_open_days(strtotime(str_replace('/', '-',($value['3pt_f']))),time()) > 0) ){
		$tabPSA['ttpPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($value['3pt_f']))),time());
		$tabPSA['totalPT']+=1;
	}elseif((! is_null($value['3pt_f'])) && ! is_null($value['3pt_r']) && $value['3pt']){
		$tabPSA['ttpPT']+=get_nb_open_days_alg(strtotime(str_replace('/', '-',($value['3pt_f']))),strtotime(str_replace('/', '-',($value['3pt_r']))));
		$tabPSA['totalPT']+=1;
	}
	if((! is_null($value['3mpt_f'])) && ( is_null($value['3mpt_r']) || (! $value['3mpt'])) && (get_nb_open_days(strtotime(str_replace('/', '-',($value['3mpt_f']))),time()) > 0)){
		$tabPSA['ttpMPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($value['3mpt_f']))),time());
		$tabPSA['totalMPT']+=1;
	}elseif((! is_null($value['3mpt_f'])) && ! is_null($value['3mpt_r']) && $value['3mpt']){
		$tabPSA['ttpMPT']+=get_nb_open_days_alg(strtotime(str_replace('/', '-',($value['3mpt_f']))),strtotime(str_replace('/', '-',($value['3mpt_r']))));
		$tabPSA['totalMPT']+=1;
	}
	if((! is_null($value['4empt_f'])) && ( is_null($value['4empt_r']) || (! $value['4empt'])) && (get_nb_open_days(strtotime(str_replace('/', '-',($value['4empt_f']))),time()) > 0)){
		$tabPSA['ttpEMPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($value['4empt_f']))),time());
		$tabPSA['totalEMPT']+=1;
	}elseif((! is_null($value['4empt_f'])) && ! is_null($value['4empt_r']) && $value['4empt']){
		$tabPSA['ttpEMPT']+=get_nb_open_days_alg(strtotime(str_replace('/', '-',($value['4empt_f']))),strtotime(str_replace('/', '-',($value['4empt_r']))));
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

	if((! is_null($value['3pt_f'])) && ( is_null($value['3pt_r']) || (! $value['3pt'])) && (get_nb_open_days(strtotime(str_replace('/', '-',($value['3pt_f']))),time()) > 0)){
		$tabJLR['ttpPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($value['3pt_f']))),time());
		$tabJLR['totalPT']+=1;
	}elseif((! is_null($value['3pt_f'])) && ! is_null($value['3pt_r']) && $value['3pt']){
		$tabJLR['ttpPT']+=get_nb_open_days_alg(strtotime(str_replace('/', '-',($value['3pt_f']))),strtotime(str_replace('/', '-',($value['3pt_r']))));
		$tabJLR['totalPT']+=1;
	}
	if((! is_null($value['3mpt_f'])) && ( is_null($value['3mpt_r']) || (! $value['3mpt'])) && (get_nb_open_days(strtotime(str_replace('/', '-',($value['3mpt_f']))),time()) > 0)){
		$tabJLR['ttpMPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($value['3mpt_f']))),time());
		$tabJLR['totalMPT']+=1;
	}elseif((! is_null($value['3mpt_f'])) && ! is_null($value['3mpt_r']) && $value['3mpt']){
		$tabJLR['ttpMPT']+=get_nb_open_days_alg(strtotime(str_replace('/', '-',($value['3mpt_f']))),strtotime(str_replace('/', '-',($value['3mpt_r']))));
		$tabJLR['totalMPT']+=1;
	}
	if((! is_null($value['4empt_f'])) && ( is_null($value['4empt_r']) || (! $value['4empt'])) && (get_nb_open_days(strtotime(str_replace('/', '-',($value['4empt_f']))),time()) > 0)){
		$tabJLR['ttpEMPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($value['4empt_f']))),time());
		$tabJLR['totalEMPT']+=1;
	}elseif((! is_null($value['4empt_f'])) && ! is_null($value['4empt_r']) && $value['4empt']){
		$tabJLR['ttpEMPT']+=get_nb_open_days_alg(strtotime(str_replace('/', '-',($value['4empt_f']))),strtotime(str_replace('/', '-',($value['4empt_r']))));
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

	if(! is_null($value['3pt_f']) && ( is_null($value['3pt_r']) || (! $value['3pt'])) && (get_nb_open_days(strtotime(str_replace('/', '-',($value['3pt_f']))),time()) > 0 )){
		$tabTOY['ttpPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($value['3pt_f']))),time());
		$tabTOY['totalPT']+=1;
	}elseif(! is_null($value['3pt_f']) && ! is_null($value['3pt_r']) && $value['3pt']){
		$tabTOY['ttpPT']+=get_nb_open_days_alg(strtotime(str_replace('/', '-',($value['3pt_f']))),strtotime(str_replace('/', '-',($value['3pt_r']))));
		$tabTOY['totalPT']+=1;
	}
	if(! is_null($value['3mpt_f']) && ( is_null($value['3mpt_r']) || (! $value['3mpt'])) && (get_nb_open_days(strtotime(str_replace('/', '-',($value['3mpt_f']))),time()) > 0)){
		$tabTOY['ttpMPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($value['3mpt_f']))),time());
		$tabTOY['totalMPT']+=1;
	}elseif(! is_null($value['3mpt_f']) && ! is_null($value['3mpt_r']) && $value['3mpt']){
		$tabTOY['ttpMPT']+=get_nb_open_days_alg(strtotime(str_replace('/', '-',($value['3mpt_f']))),strtotime(str_replace('/', '-',($value['3mpt_r']))));
		$tabTOY['totalMPT']+=1;
	}
	if(! is_null($value['4empt_f']) && ( is_null($value['4empt_r']) || (! $value['4empt'])) && (get_nb_open_days(strtotime(str_replace('/', '-',($value['4empt_f']))),time()) > 0)){
		$tabTOY['ttpEMPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($value['4empt_f']))),time());
		$tabTOY['totalEMPT']+=1;
	}elseif(! is_null($value['4empt_f']) && ! is_null($value['4empt_r']) && $value['4empt']){
		$tabTOY['ttpEMPT']+=get_nb_open_days_alg(strtotime(str_replace('/', '-',($value['4empt_f']))),strtotime(str_replace('/', '-',($value['4empt_r']))));
		$tabTOY['totalEMPT']+=1;
	}	
}

//TTP
$total_division=0;
$total_pt=0;
$total_mpt=0;
$total_empt=0;

if($tabPSA['totalPT']){
	$moyPT_PSA = (int) ($tabPSA['ttpPT']/$tabPSA['totalPT']);
  $total_division+=1;
  $total_pt+=1;
}else{
  $moyPT_PSA = 0;
}
if($tabPSA['totalMPT']){
  $moyMPT_PSA = (int) ($tabPSA['ttpMPT']/$tabPSA['totalMPT']);
  $total_mpt+=1;
	$total_division+=1;
}else{
  $moyMPT_PSA = 0;
}
if($tabPSA['totalEMPT']){
  $moyEMPT_PSA = (int) ($tabPSA['ttpEMPT']/$tabPSA['totalEMPT']);
  $total_empt+=1;
	$total_division+=1;
}else{
  $moyEMPT_PSA = 0;
}

if($tabTOY['totalPT']){
  $moyPT_TOY = (int) ($tabTOY['ttpPT']/$tabTOY['totalPT']);
  $total_pt+=1;
	$total_division+=1;
}else{
  $moyPT_TOY = 0;
}
if($tabTOY['totalMPT']){
  $moyMPT_TOY = (int) ($tabTOY['ttpMPT']/$tabTOY['totalMPT']);
  $total_mpt+=1;
	$total_division+=1;
}else{
  $moyMPT_TOY = 0;
}
if($tabTOY['totalEMPT']){
  $moyEMPT_TOY = (int) ($tabTOY['ttpEMPT']/$tabTOY['totalEMPT']);
  $total_empt+=1;
	$total_division+=1;
}else{
  $moyEMPT_TOY = 0;
}

if($tabJLR['totalPT']){
  $moyPT_JLR = (int) ($tabJLR['ttpPT']/$tabJLR['totalPT']);
  $total_pt+=1;
	$total_division+=1;
}else{
  $moyPT_JLR = 0;
}
if($tabJLR['totalMPT']){
  $moyMPT_JLR = (int) ($tabJLR['ttpMPT']/$tabJLR['totalMPT']);
  $total_mpt+=1;
	$total_division+=1;
}else{
  $moyMPT_JLR = 0;
}
if($tabJLR['totalEMPT']){
  $moyEMPT_JLR = (int) ($tabJLR['ttpEMPT']/$tabJLR['totalEMPT']);
  $total_empt+=1;
	$total_division+=1;
}else{
	$moyEMPT_JLR = 0;
}

$sheet->setCellValue('AR17',$moyPT_PSA);
$sheet->setCellValue('AR19',$moyPT_JLR);
$sheet->setCellValue('AR21',$moyPT_TOY);

$sheet->setCellValue('AZ17',$moyMPT_PSA);
$sheet->setCellValue('AZ19',$moyMPT_JLR);
$sheet->setCellValue('AZ21',$moyMPT_TOY);

$sheet->setCellValue('BK17',$moyEMPT_PSA);
$sheet->setCellValue('BK19',$moyEMPT_JLR);
$sheet->setCellValue('BK21',$moyEMPT_TOY);


if(! $total_pt){
  $sheet->setCellValue('P17',0);
}else{
  $sheet->setCellValue('P17',(int) (($moyPT_TOY+$moyPT_PSA +$moyPT_JLR)/$total_pt));
}
if(! $total_mpt){
  $sheet->setCellValue('P19',0);
}else{
  $sheet->setCellValue('P19',(int) (($moyMPT_TOY+$moyMPT_PSA +$moyMPT_JLR)/$total_mpt));
}
if(! $total_empt){
  $sheet->setCellValue('P21',0);
}else{
  $sheet->setCellValue('P21',(int) (($moyEMPT_TOY+$moyEMPT_PSA +$moyEMPT_JLR)/$total_empt));
}

if($total_division){
 	$score = (int) (($moyEMPT_JLR + $moyEMPT_PSA + $moyEMPT_TOY + $moyMPT_JLR + $moyMPT_PSA + $moyMPT_TOY + $moyPT_JLR + $moyPT_PSA + $moyPT_TOY )/$total_division);
}else{
  	$score =0;
}

$sheet ->setCellValue('AI7',$score);

$writer = new Xlsx($spreadsheet);
header('Content-Description: File Transfer');
header('Content-Type: application/ms-excel');
header("Content-Disposition: attachment; filename=launchroom.xlsx");
ob_clean();
flush();
$writer->save('php://output');
exit;
