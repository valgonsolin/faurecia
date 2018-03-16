<?php
include_once "../../needed.php";

include_once "../needed.php";

drawHeader('methode');
drawMenu('statistiques');

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

$total=0;
//PSA
$tabPSA=array("totalPT" =>0,"ttpPT"=>0,"totalMPT"=>0,"ttpMPT"=>0,"totalEMPT"=>0,"ttpEMPT"=>0);
$psa = $bdd -> query('SELECT * FROM launchboard WHERE client = "PSA"');
While($projet = $psa ->fetch()){
	if(! is_null($projet['3pt_f']) && ( is_null($projet['3pt_r']) || (! $projet['3pt']))){
		$tabPSA['ttpPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($projet['3pt_f']))),time());
		$tabPSA['totalPT']+=1;
	}elseif(! is_null($projet['3pt_f']) && ! is_null($projet['3pt_r']) && $projet['3pt']){
		$tabPSA['ttpPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($projet['3pt_f']))),strtotime(str_replace('/', '-',($projet['3pt_r']))));
		$tabPSA['totalPT']+=1;
	}
	if(! is_null($projet['3mpt_f']) && ( is_null($projet['3mpt_r']) || (! $projet['3mpt']))){
		$tabPSA['ttpMPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($projet['3mpt_f']))),time());
		$tabPSA['totalMPT']+=1;
	}elseif(! is_null($projet['3mpt_f']) && ! is_null($projet['3mpt_r']) && $projet['3mpt']){
		$tabPSA['ttpMPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($projet['3mpt_f']))),strtotime(str_replace('/', '-',($projet['3mpt_r']))));
		$tabPSA['totalMPT']+=1;
	}
	if(! is_null($projet['4empt_f']) && ( is_null($projet['4empt_r']) || (! $projet['4empt']))){
		$tabPSA['ttpEMPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($projet['4empt_f']))),time());
		$tabPSA['totalEMPT']+=1;
	}elseif(! is_null($projet['4empt_f']) && ! is_null($projet['4empt_r']) && $projet['4empt']){
		$tabPSA['ttpEMPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($projet['4empt_f']))),strtotime(str_replace('/', '-',($projet['4empt_r']))));
		$tabPSA['totalEMPT']+=1;
	}
}

//JLR
$tabJLR=array("totalPT" =>0,"ttpPT"=>0,"totalMPT"=>0,"ttpMPT"=>0,"totalEMPT"=>0,"ttpEMPT"=>0);
$jlr = $bdd -> query('SELECT * FROM launchboard WHERE client = "JLR"');
While($projet = $jlr ->fetch()){

	if(! is_null($projet['3pt_f']) && ( is_null($projet['3pt_r']) || (! $projet['3pt']))){
		$tabJLR['ttpPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($projet['3pt_f']))),time());
		$tabJLR['totalPT']+=1;
	}elseif(! is_null($projet['3pt_f']) && ! is_null($projet['3pt_r']) && $projet['3pt']){
		$tabJLR['ttpPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($projet['3pt_f']))),strtotime(str_replace('/', '-',($projet['3pt_r']))));
		$tabJLR['totalPT']+=1;
	}
	if(! is_null($projet['3mpt_f']) && ( is_null($projet['3mpt_r']) || (! $projet['3mpt']))){
		$tabJLR['ttpMPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($projet['3mpt_f']))),time());
		$tabJLR['totalMPT']+=1;
	}elseif(! is_null($projet['3mpt_f']) && ! is_null($projet['3mpt_r']) && $projet['3mpt']){
		$tabJLR['ttpMPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($projet['3mpt_f']))),strtotime(str_replace('/', '-',($projet['3mpt_r']))));
		$tabJLR['totalMPT']+=1;
	}
	if(! is_null($projet['4empt_f']) && ( is_null($projet['4empt_r']) || (! $projet['4empt']))){
		$tabJLR['ttpEMPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($projet['4empt_f']))),time());
		$tabJLR['totalEMPT']+=1;
	}elseif(! is_null($projet['4empt_f']) && ! is_null($projet['4empt_r']) && $projet['4empt']){
		$tabJLR['ttpEMPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($projet['4empt_f']))),strtotime(str_replace('/', '-',($projet['4empt_r']))));
		$tabJLR['totalEMPT']+=1;
	}
}

//TOY/RENAULT
$tabTOY=array("totalPT" =>0,"ttpPT"=>0,"totalMPT"=>0,"ttpMPT"=>0,"totalEMPT"=>0,"ttpEMPT"=>0);
$toy = $bdd -> query('SELECT * FROM launchboard WHERE client = "TOY/RENAULT"');
While($projet = $toy ->fetch()){
	if(! is_null($projet['3pt_f']) && ( is_null($projet['3pt_r']) || (! $projet['3pt']))){
		$tabTOY['ttpPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($projet['3pt_f']))),time());
		$tabTOY['totalPT']+=1;
	}elseif(! is_null($projet['3pt_f']) && ! is_null($projet['3pt_r']) && $projet['3pt']){
		$tabTOY['ttpPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($projet['3pt_f']))),strtotime(str_replace('/', '-',($projet['3pt_r']))));
		$tabTOY['totalPT']+=1;
	}
	if(! is_null($projet['3mpt_f']) && ( is_null($projet['3mpt_r']) || (! $projet['3mpt']))){
		$tabTOY['ttpMPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($projet['3mpt_f']))),time());
		$tabTOY['totalMPT']+=1;
	}elseif(! is_null($projet['3mpt_f']) && ! is_null($projet['3mpt_r']) && $projet['3mpt']){
		$tabTOY['ttpMPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($projet['3mpt_f']))),strtotime(str_replace('/', '-',($projet['3mpt_r']))));
		$tabTOY['totalMPT']+=1;
	}
	if(! is_null($projet['4empt_f']) && ( is_null($projet['4empt_r']) || (! $projet['4empt']))){
		$tabTOY['ttpEMPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($projet['4empt_f']))),time());
		$tabTOY['totalEMPT']+=1;
	}elseif(! is_null($projet['4empt_f']) && ! is_null($projet['4empt_r']) && $projet['4empt']){
		$tabTOY['ttpEMPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($projet['4empt_f']))),strtotime(str_replace('/', '-',($projet['4empt_r']))));
		$tabTOY['totalEMPT']+=1;
	}	
}

$tot= $tabPSA['totalPT'] + $tabPSA['totalMPT'] + $tabPSA['totalEMPT'] + $tabTOY['totalPT'] + $tabTOY['totalMPT'] + $tabTOY['totalEMPT'] + $tabJLR['totalPT'] + $tabJLR['totalMPT'] + $tabJLR['totalEMPT'];
$ttp = $tabPSA['ttpPT'] + $tabPSA['ttpMPT'] + $tabPSA['ttpEMPT'] + $tabTOY['ttpPT'] + $tabTOY['ttpMPT'] + $tabTOY['ttpEMPT'] + $tabJLR['ttpPT'] + $tabJLR['ttpMPT'] + $tabJLR['ttpEMPT'];
if($tot){
 	$score = (int) ($ttp/$tot);
}else{
  	$score =0;
}
?>

<style>
.conteneur{
  background-color: #efefef;
  padding: 10px;
  border-radius: 6px;
  text-align:center;
}
</style>

<h2>LaunchRoom</h2>

<h1 style="text-align:center;"> TTP Score : <?php echo $score; ?> </h1>
<br>

<div class="row" style="font-size:140%;">
	<div class="col-md-4">
		<div class="conteneur">
			<b>PSA<br>
			PT : <?php if($tabPSA['totalPT']){echo (int) ($tabPSA['ttpPT']/$tabPSA['totalPT']);}else{echo "0";} ?></b><br>
			<b> MPT : <?php if($tabPSA['totalMPT']){echo (int) ($tabPSA['ttpMPT']/$tabPSA['totalMPT']);}else{echo "0";} ?></b><br>
			<b> EMPT : <?php if($tabPSA['totalEMPT']){echo (int) ($tabPSA['ttpEMPT']/$tabPSA['totalEMPT']);}else{echo "0";} ?></b>
		</div>
	</div>
	<div class="col-md-4">
		<div class="conteneur">
			<b>JLR<br>
			PT : <?php if($tabJLR['totalPT']){echo (int) ($tabJLR['ttpPT']/$tabJLR['totalPT']);}else{echo "0";} ?></b><br>
			<b> MPT : <?php if($tabJLR['totalMPT']){echo (int) ($tabJLR['ttpMPT']/$tabJLR['totalMPT']);}else{echo "0";} ?></b><br>
			<b> EMPT : <?php if($tabJLR['totalEMPT']){echo (int) ($tabJLR['ttpEMPT']/$tabJLR['totalEMPT']);}else{echo "0";} ?></b>
		</div>
	</div>
	<div class="col-md-4 ">
		<div class="conteneur">
			<b>TOYOTA / RENAULT<br>
			PT : <?php if($tabTOY['totalPT']){echo (int) ($tabTOY['ttpPT']/$tabTOY['totalPT']);}else{echo "0";} ?></b><br>
			<b> MPT : <?php if($tabTOY['totalMPT']){echo (int) ($tabTOY['ttpMPT']/$tabTOY['totalMPT']);}else{echo "0";} ?></b><br>
			<b> EMPT : <?php if($tabTOY['totalEMPT']){echo (int) ($tabTOY['ttpEMPT']/$tabTOY['totalEMPT']);}else{echo "0";} ?></b>
		</div>
	</div>
</div>

<?php
drawFooter();
 ?>
