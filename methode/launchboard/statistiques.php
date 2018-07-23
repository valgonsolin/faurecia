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

function get_nb_open_days_alg($date_start, $date_stop){
	if($date_start<$date_stop){
		return get_nb_open_days($date_start,$date_stop);
	}else{
		return (-1) * get_nb_open_days($date_stop,$date_start);
	}
}

$total=0;
//PSA
$tabPSA=array("totalPT" =>0,"ttpPT"=>0,"totalMPT"=>0,"ttpMPT"=>0,"totalEMPT"=>0,"ttpEMPT"=>0);
$psa = $bdd -> query('SELECT * FROM launchboard WHERE client = "PSA" AND archive = 0');
While($projet = $psa ->fetch()){
	if((! is_null($projet['3pt_f'])) && ( is_null($projet['3pt_r']) || (! $projet['3pt'])) && (get_nb_open_days(strtotime(str_replace('/', '-',($projet['3pt_f']))),time()) > 0) ){
		$tabPSA['ttpPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($projet['3pt_f']))),time());
		$tabPSA['totalPT']+=1;
	}elseif((! is_null($projet['3pt_f'])) && ! is_null($projet['3pt_r']) && $projet['3pt']){
		$tabPSA['ttpPT']+=get_nb_open_days_alg(strtotime(str_replace('/', '-',($projet['3pt_f']))),strtotime(str_replace('/', '-',($projet['3pt_r']))));
		$tabPSA['totalPT']+=1;
	}
	if((! is_null($projet['3mpt_f'])) && ( is_null($projet['3mpt_r']) || (! $projet['3mpt'])) && (get_nb_open_days(strtotime(str_replace('/', '-',($projet['3mpt_f']))),time()) > 0)){
		$tabPSA['ttpMPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($projet['3mpt_f']))),time());
		$tabPSA['totalMPT']+=1;
	}elseif((! is_null($projet['3mpt_f'])) && ! is_null($projet['3mpt_r']) && $projet['3mpt']){
		$tabPSA['ttpMPT']+=get_nb_open_days_alg(strtotime(str_replace('/', '-',($projet['3mpt_f']))),strtotime(str_replace('/', '-',($projet['3mpt_r']))));
		$tabPSA['totalMPT']+=1;
	}
	if((! is_null($projet['4empt_f'])) && ( is_null($projet['4empt_r']) || (! $projet['4empt'])) && (get_nb_open_days(strtotime(str_replace('/', '-',($projet['4empt_f']))),time()) > 0)){
		$tabPSA['ttpEMPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($projet['4empt_f']))),time());
		$tabPSA['totalEMPT']+=1;
	}elseif((! is_null($projet['4empt_f'])) && ! is_null($projet['4empt_r']) && $projet['4empt']){
		$tabPSA['ttpEMPT']+=get_nb_open_days_alg(strtotime(str_replace('/', '-',($projet['4empt_f']))),strtotime(str_replace('/', '-',($projet['4empt_r']))));
		$tabPSA['totalEMPT']+=1;
	}
}

//JLR
$tabJLR=array("totalPT" =>0,"ttpPT"=>0,"totalMPT"=>0,"ttpMPT"=>0,"totalEMPT"=>0,"ttpEMPT"=>0);
$jlr = $bdd -> query('SELECT * FROM launchboard WHERE client = "JLR" AND archive =0');
While($projet = $jlr ->fetch()){

	if((! is_null($projet['3pt_f'])) && ( is_null($projet['3pt_r']) || (! $projet['3pt'])) && (get_nb_open_days(strtotime(str_replace('/', '-',($projet['3pt_f']))),time()) > 0)){
		$tabJLR['ttpPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($projet['3pt_f']))),time());
		$tabJLR['totalPT']+=1;
	}elseif((! is_null($projet['3pt_f'])) && ! is_null($projet['3pt_r']) && $projet['3pt']){
		$tabJLR['ttpPT']+=get_nb_open_days_alg(strtotime(str_replace('/', '-',($projet['3pt_f']))),strtotime(str_replace('/', '-',($projet['3pt_r']))));
		$tabJLR['totalPT']+=1;
	}
	if((! is_null($projet['3mpt_f'])) && ( is_null($projet['3mpt_r']) || (! $projet['3mpt'])) && (get_nb_open_days(strtotime(str_replace('/', '-',($projet['3mpt_f']))),time()) > 0)){
		$tabJLR['ttpMPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($projet['3mpt_f']))),time());
		$tabJLR['totalMPT']+=1;
	}elseif((! is_null($projet['3mpt_f'])) && ! is_null($projet['3mpt_r']) && $projet['3mpt']){
		$tabJLR['ttpMPT']+=get_nb_open_days_alg(strtotime(str_replace('/', '-',($projet['3mpt_f']))),strtotime(str_replace('/', '-',($projet['3mpt_r']))));
		$tabJLR['totalMPT']+=1;
	}
	if((! is_null($projet['4empt_f'])) && ( is_null($projet['4empt_r']) || (! $projet['4empt'])) && (get_nb_open_days(strtotime(str_replace('/', '-',($projet['4empt_f']))),time()) > 0)){
		$tabJLR['ttpEMPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($projet['4empt_f']))),time());
		$tabJLR['totalEMPT']+=1;
	}elseif((! is_null($projet['4empt_f'])) && ! is_null($projet['4empt_r']) && $projet['4empt']){
		$tabJLR['ttpEMPT']+=get_nb_open_days_alg(strtotime(str_replace('/', '-',($projet['4empt_f']))),strtotime(str_replace('/', '-',($projet['4empt_r']))));
		$tabJLR['totalEMPT']+=1;
	}
}

//TOY/RENAULT
$tabTOY=array("totalPT" =>0,"ttpPT"=>0,"totalMPT"=>0,"ttpMPT"=>0,"totalEMPT"=>0,"ttpEMPT"=>0);
$toy = $bdd -> query('SELECT * FROM launchboard WHERE client = "TOY/RENAULT" AND archive = 0');
While($projet = $toy ->fetch()){
	if(! is_null($projet['3pt_f']) && ( is_null($projet['3pt_r']) || (! $projet['3pt'])) && (get_nb_open_days(strtotime(str_replace('/', '-',($projet['3pt_f']))),time()) > 0 )){
		$tabTOY['ttpPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($projet['3pt_f']))),time());
		$tabTOY['totalPT']+=1;
	}elseif(! is_null($projet['3pt_f']) && ! is_null($projet['3pt_r']) && $projet['3pt']){
		$tabTOY['ttpPT']+=get_nb_open_days_alg(strtotime(str_replace('/', '-',($projet['3pt_f']))),strtotime(str_replace('/', '-',($projet['3pt_r']))));
		$tabTOY['totalPT']+=1;
	}
	if(! is_null($projet['3mpt_f']) && ( is_null($projet['3mpt_r']) || (! $projet['3mpt'])) && (get_nb_open_days(strtotime(str_replace('/', '-',($projet['3mpt_f']))),time()) > 0)){
		$tabTOY['ttpMPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($projet['3mpt_f']))),time());
		$tabTOY['totalMPT']+=1;
	}elseif(! is_null($projet['3mpt_f']) && ! is_null($projet['3mpt_r']) && $projet['3mpt']){
		$tabTOY['ttpMPT']+=get_nb_open_days_alg(strtotime(str_replace('/', '-',($projet['3mpt_f']))),strtotime(str_replace('/', '-',($projet['3mpt_r']))));
		$tabTOY['totalMPT']+=1;
	}
	if(! is_null($projet['4empt_f']) && ( is_null($projet['4empt_r']) || (! $projet['4empt'])) && (get_nb_open_days(strtotime(str_replace('/', '-',($projet['4empt_f']))),time()) > 0)){
		$tabTOY['ttpEMPT']+=get_nb_open_days(strtotime(str_replace('/', '-',($projet['4empt_f']))),time());
		$tabTOY['totalEMPT']+=1;
	}elseif(! is_null($projet['4empt_f']) && ! is_null($projet['4empt_r']) && $projet['4empt']){
		$tabTOY['ttpEMPT']+=get_nb_open_days_alg(strtotime(str_replace('/', '-',($projet['4empt_f']))),strtotime(str_replace('/', '-',($projet['4empt_r']))));
		$tabTOY['totalEMPT']+=1;
	}	
}

//calcul des moyennes
$total_division=0;

if($tabPSA['totalPT']){
	$moyPT_PSA = (int) ($tabPSA['ttpPT']/$tabPSA['totalPT']);
	$total_division+=1;
}else{
	$moyPT_PSA = 0;
}
if($tabPSA['totalMPT']){
	$moyMPT_PSA = (int) ($tabPSA['ttpMPT']/$tabPSA['totalMPT']);
	$total_division+=1;
}else{
	$moyMPT_PSA = 0;
}
if($tabPSA['totalEMPT']){
	$moyEMPT_PSA = (int) ($tabPSA['ttpEMPT']/$tabPSA['totalEMPT']);
	$total_division+=1;
}else{
	$moyEMPT_PSA = 0;
}

if($tabTOY['totalPT']){
	$moyPT_TOY = (int) ($tabTOY['ttpPT']/$tabTOY['totalPT']);
	$total_division+=1;
}else{
	$moyPT_TOY = 0;
}
if($tabTOY['totalMPT']){
	$moyMPT_TOY = (int) ($tabTOY['ttpMPT']/$tabTOY['totalMPT']);
	$total_division+=1;
}else{
	$moyMPT_TOY = 0;
}
if($tabTOY['totalEMPT']){
	$moyEMPT_TOY = (int) ($tabTOY['ttpEMPT']/$tabTOY['totalEMPT']);
	$total_division+=1;
}else{
	$moyEMPT_TOY = 0;
}

if($tabJLR['totalPT']){
	$moyPT_JLR = (int) ($tabJLR['ttpPT']/$tabJLR['totalPT']);
	$total_division+=1;
}else{
	$moyPT_JLR = 0;
}
if($tabJLR['totalMPT']){
	$moyMPT_JLR = (int) ($tabJLR['ttpMPT']/$tabJLR['totalMPT']);
	$total_division+=1;
}else{
	$moyMPT_JLR = 0;
}
if($tabJLR['totalEMPT']){
	$moyEMPT_JLR = (int) ($tabJLR['ttpEMPT']/$tabJLR['totalEMPT']);
	$total_division+=1;
}else{
	$moyEMPT_JLR = 0;
}


if($total_division){
 	$score = (int) (($moyEMPT_JLR + $moyEMPT_PSA + $moyEMPT_TOY + $moyMPT_JLR + $moyMPT_PSA + $moyMPT_TOY + $moyPT_JLR + $moyPT_PSA + $moyPT_TOY )/$total_division);
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
			PT : <?php echo $moyPT_PSA; ?></b><br>
			<b> MPT : <?php echo $moyMPT_PSA; ?></b><br>
			<b> EMPT : <?php echo $moyEMPT_PSA; ?></b>
		</div>
	</div>
	<div class="col-md-4">
		<div class="conteneur">
			<b>JLR<br>
			PT : <?php echo $moyPT_JLR; ?></b><br>
			<b> MPT : <?php echo $moyMPT_JLR; ?></b><br>
			<b> EMPT : <?php echo $moyEMPT_JLR; ?></b>
		</div>
	</div>
	<div class="col-md-4 ">
		<div class="conteneur">
			<b>TOYOTA / RENAULT<br>
			PT : <?php echo $moyPT_TOY; ?></b><br>
			<b> MPT : <?php echo $moyMPT_TOY; ?></b><br>
			<b> EMPT : <?php echo $moyEMPT_TOY; ?></b>
		</div>
	</div>
</div>
  <?php 
$query = $bdd -> query('SELECT * FROM score_ttp 

WHERE date BETWEEN (CURRENT_DATE() - INTERVAL 6 MONTH) AND CURRENT_DATE() ORDER BY date ASC');
$dates = $query ->fetchAll();
if(sizeof($dates) >0){
  ?>
<script src="../../js/moment.min.js"></script>
<script src="../../js/Chart.js"></script>

<canvas id="myChart" style="width=400px; height:400px;"></canvas>
<script>
  function newDate(days)
  {
    return moment(days,'DD/MM/YYYY').toDate();
  }
  var data1 = [
  <?php
  foreach ($dates as $date) {
    echo "{x : newDate('".date('d/m/Y',strtotime($date['date']))."'), y: ".$date['score']."},";
  }?>];
  var config = {
    type: 'line',
    data: {
      datasets: [{
        fill: false,
        lineTension: 0.1,
        data: data1,
      }]
    },
    options: {
      color: [ 'red'],
      responsive: true,
	  tooltips: {enabled: false},
      hover: {mode: null},
      legend :{
        display : false
      },
      title:{
        display:true,
        text:"Évolution du Scoring"
      },
      scales: {
        xAxes: [{
          type: 'time',
		  time : {
			  unit : 'day'
		  },
          display: true,
          scaleLabel: {
            display: true,
            labelString: 'Date'
          }
        }],
        yAxes: [{
          display: true,
          scaleLabel: {
            display: true,
            labelString: 'Scoring TTP'
          }
        }]
      }
    }
  };

		window.onload = function() {
      var ctx = document.getElementById("myChart");
      var myChart = new Chart(ctx, config);
		};
</script>

<?php
}
drawFooter();
 ?>
