<?php
include_once "../../needed.php";

include_once "../needed.php";

drawHeader('methode');
drawMenu('launchboard');

if(!isset($_GET['id'])){ ?>
  <h2>LaunchBoard</h2>
  <h4>OUPS... Votre session est inconnu.</h4>
  <a class="btn btn-default" href="<?php echo $url; ?>/methode/launchboard"> Retourner au LaunchBoard</a>
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
  <a href="statistiques.php?id=<?php echo $_GET['id']; ?>" class="bouton_menu bouton_nav_selected" >Statistiques</a>
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
if((! is_null($Data['3pt_f'])) && (! is_null($Data['3pt_r'])) && (strtotime($Data['3pt_r']) > strtotime('+30 days',strtotime(str_replace('/', '-', $Data['3pt_f']))))){
  $color1="#FF002A";
}
if((! is_null($Data['3mpt_f'])) && (! is_null($Data['3mpt_r'])) && (strtotime($Data['3mpt_r']) > strtotime('+30 days',strtotime(str_replace('/', '-', $Data['3mpt_f']))))){
  $color2="#FF002A";
}
if((! is_null($Data['4empt_f'])) && (! is_null($Data['4empt_r'])) && (strtotime($Data['4empt_r']) > strtotime('+30 days',strtotime(str_replace('/', '-', $Data['4empt_f']))))){
  $color3="#FF002A";
}

?>
<div class="row">
  <h4>Time to Pass :</h4>
  <div class="col-md-4">
    <b>PT</b>
    <p>Date prévue : <span  style="background-color:<?php echo $color1; ?>; border-radius:3px;"><?php if(! is_null($Data['3pt_f'])){echo date('d/m/y',strtotime($Data['3pt_f'])); }?></span></p>
    <p>Date prévue + 30 jours : <?php if(! is_null($Data['3pt_f'])){echo date('d/m/y',strtotime('+30 days',strtotime(str_replace('/', '-', $Data['3pt_f'])))); } ?></p>
    <p>Date réalisée : <?php if(! is_null($Data['3pt_r'])){echo date('d/m/y',strtotime($Data['3pt_r'])); } ?></p>
  </div>
  <div class="col-md-4">
    <b>MPT</b>
    <p>Date prévue : <span style="background-color:<?php echo $color2; ?>; border-radius:3px;"><?php if(! is_null($Data['3mpt_f'])){echo date('d/m/y',strtotime($Data['3mpt_f'])); } ?></span></p>
    <p>Date prévue + 30 jours : <?php if(! is_null($Data['3pt_f'])){echo date('d/m/y',strtotime('+30 days',strtotime(str_replace('/', '-', $Data['3mpt_f'])))); } ?></p>
    <p>Date réalisée : <?php if(! is_null($Data['3mpt_r'])){echo date('d/m/y',strtotime($Data['3mpt_r'])); } ?></p>
  </div>
  <div class="col-md-4">
    <b>EMPT</b>
    <p>Date prévue : <span   style="background-color:<?php echo $color3; ?>; border-radius:3px;"> <?php if(! is_null($Data['4empt_f'])){echo date('d/m/y',strtotime($Data['4empt_f'])); } ?> </span></p>
    <p>Date prévue + 30 jours : <?php if(! is_null($Data['3pt_f'])){echo date('d/m/y',strtotime('+30 days',strtotime(str_replace('/', '-', $Data['4empt_f'])))); } ?></p>
    <p>Date réalisée : <?php if(! is_null($Data['4empt_r'])){echo date('d/m/y',strtotime($Data['4empt_r'])); } ?></p>
  </div>
</div>

<script src="../../js/moment.min.js"></script>
<script src="../../js/Chart.js"></script>

<canvas id="myChart" style="width=400px; height:400px;"></canvas>
<script>
  function newDate(days)
  {
    return moment(days,'DD-MM-YYYY').toDate();
  }
  <?php
  $query = $bdd -> prepare('SELECT * FROM evolution_projet WHERE id_projet = ? ORDER BY date ASC');
  $query -> execute(array($_GET['id']));
  ?>
  var data1 = [
  <?php
  while($date = $query -> fetch()){
    echo "{x : newDate('".date('d/m/Y',strtotime($date['date']))."'), y: ".$date['pourcentage']."},";
  } ?>];
  var config = {
    type: 'line',
    data: {
      datasets: [{
        fill: false,
        data: data1,
      }]
    },
    options: {
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



<a href="generate.php" class="btn btn-default">Fichier</a>

<?php
}
drawFooter();
 ?>
