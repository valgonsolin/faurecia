<?php
include_once "../../needed.php";

include_once "../needed.php";

drawHeader('methode');
drawMenu('launchboard');

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

if(!isset($_GET['id'])){ ?>
  <h2>LaunchBoard</h2>
  <h4>OUPS... Votre session est inconnu.</h4>
  <a class="btn btn-default" href="<?php echo $url; ?>/methode/launchboard"> Retourner au LaunchBoard</a>
<?php }else{
   if(! isset($_SESSION['login'])){ ?>
        <h2>LaunchBoard</h2>
  <h4>Vous devez etre connecté pour accéder à cette partie.</h4>
  <a class="btn btn-default" href="<?php echo $url; ?>/methode/launchboard">Retourner au LaunchBoard</a>
  <a class="btn btn-default" href="<?php echo $url; ?>/moncompte/identification.php">Connexion</a>
   <?php }else{
  $query = $bdd -> prepare('SELECT * FROM launchboard JOIN profil ON profil.id=launchboard.profil WHERE launchboard.id = ?');
  $query -> execute(array($_GET['id']));
  $Data = $query -> fetch();
?>
<style>
.conteneur{
  background-color: #efefef;
  margin-bottom:20px;
  padding: 10px;
  border-radius: 6px;
}
</style>
<h2 style="margin-bottom:10px;">Projet</h2>
<div class="boutons_nav" style="display: flex; justify-content: center;">
  <a href="projet.php?id=<?php echo $_GET['id']; ?>" class="bouton_menu" style="margin-right:20%">Projet</a>
  <a href="statistiques_projet.php?id=<?php echo $_GET['id']; ?>" class="bouton_menu bouton_nav_selected" >Statistiques</a>
</div>
<div class="row conteneur">
  <div class="col-md-6">
    <h4>PPTL : <?php echo $Data['nom']; ?> <?php echo $Data['prenom']; ?></h4>
    <h4>Code : <?php echo $Data['code']; ?></h4>
  </div>
  <div class="col-md-6">
    <h4>Description :</h4>
    <p><?php echo $Data['description']; ?></p>
  </div>
</div>
<?php
$color1="";
$color2="";
$color3="";
if(! is_null($Data['3pt_f']) && ! is_null($Data['3pt_r']) && (get_nb_open_days(strtotime($Data['3pt_f']),strtotime($Data['3pt_r'])) > 30)){
  $color1='#FF002A';
}
if(! is_null($Data['3mpt_f']) && ! is_null($Data['3mpt_r']) && (get_nb_open_days(strtotime($Data['3mpt_f']),strtotime($Data['3mpt_r'])) > 30)){
  $color2='#FF002A';
}
if(! is_null($Data['4empt_f']) && ! is_null($Data['4empt_r']) && (get_nb_open_days(strtotime($Data['4empt_f']),strtotime($Data['4empt_r'])) > 30)){
  $color3='#FF002A';
}

if((! is_null($Data['3pt_f']) && ! is_null($Data['3pt_r'])) || (! is_null($Data['3mpt_f']) && ! is_null($Data['3mpt_r'])) || (! is_null($Data['4empt_f']) && ! is_null($Data['4empt_r']))){
?>
<div class="row" style="font-size:140%;">
  <h4>Time to Pass :</h4>
  <?php if(! is_null($Data['3pt_f']) && ! is_null($Data['3pt_r'])){ ?>
  <div class="col-md-4">
    <b>PT : <span  style="background-color:<?php echo $color1; ?>; border-radius:3px;"> <?php 
    if(! is_null($Data['3pt_f']) && ! is_null($Data['3pt_r'])){
      printf("%+d jours",get_nb_open_days(strtotime($Data['3pt_f']),strtotime($Data['3pt_r'])));
    }
    ?></span></b>
  </div>
  <?php }
  if(! is_null($Data['3mpt_f']) && ! is_null($Data['3mpt_r'])){ ?>
  <div class="col-md-4">
      <b>MPT : <span  style="background-color:<?php echo $color2; ?>; border-radius:3px;"> <?php 
    if(! is_null($Data['3mpt_f']) && ! is_null($Data['3mpt_r'])){
      printf("%+d jours",get_nb_open_days(strtotime($Data['3mpt_f']),strtotime($Data['3mpt_r'])));
    }
    ?></span></b>
  </div>
  <?php }
  if(! is_null($Data['4empt_f']) && ! is_null($Data['4empt_r'])){ ?>
  <div class="col-md-4">
      <b>EMPT : <span  style="background-color:<?php echo $color3; ?>; border-radius:3px;"> <?php 
    if(! is_null($Data['4empt_f']) && ! is_null($Data['4empt_r'])){
      printf("%+d jours",get_nb_open_days(strtotime($Data['4empt_f']),strtotime($Data['4empt_r'])));
    }
    ?></span></b>
  </div>
  <?php } ?>
</div>
<br>
  <?php }
$query = $bdd -> prepare('SELECT * FROM evolution_projet WHERE id_projet = ? ORDER BY date ASC');
$query -> execute(array($_GET['id']));
$dates = $query ->fetchAll();
if(sizeof($dates) >0){
  ?>

<script src="../../js/moment.min.js"></script>
<script src="../../js/Chart.js"></script>

<canvas id="myChart" style="width=400px; height:400px;"></canvas>
<script>
  function newDate(days)
  {
    return moment(days,'DD/MM/YYYY-HH:mm').toDate();
  }
  var data1 = [
  <?php
  foreach ($dates as $date) {
    echo "{x : newDate('".date('d/m/Y-H:i',strtotime($date['date']))."'), y: ".$date['pourcentage']."},";
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
      legend :{
        display : false
      },
      title:{
        display:true,
        text:"Évolution du Scoring"
      },
      scales: {
        xAxes: [{
          type: "time",
          display: true,
          scaleLabel: {
            display: true,
            labelString: 'Date'
          }
        }],
        yAxes: [{
          display: true,
          ticks: {
                                beginAtZero: true,
                                steps: 10,
                                stepValue: 5,
                                max: 100
                            },
          scaleLabel: {
            display: true,
            labelString: 'Scoring'
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
<?php }
}}
drawFooter();
 ?>
