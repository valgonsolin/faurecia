<?php
ob_start();
include_once "../needed.php";
$datetime = date("Y-m-d");
$intervalle=30;
drawheader();
if(isset($_POST['intervalle'])){
	$intervalle=$_POST['duree'];
  }
?>
<h2>Pareto alerte</h2>

<br>
<form method="post" style="margin-top:20px;" enctype="multipart/form-data">

  <div class="form-group">
  <label>Choisissez un intervalle de temps </label>
  <select name="duree" class="form-control">
    <option value="30" selected="selected">30 jours</option>
    <option value="90">90 jours</option>

</select>
</div>

<button type="submit" name="intervalle" class="btn btn-default" >Appliquer</button>

</form>
<div id="container" style="width: 75%; height: 20%;">
		<canvas id="canvas"></canvas>
	</div>

<?php 
$query=$bdd->prepare('SELECT logistique_pieces.fournisseur as f,COUNT(*) as count FROM logistique_alerte JOIN logistique_pieces ON logistique_alerte.piece = logistique_pieces.id WHERE DATEDIFF(?,logistique_alerte.date )<= ? GROUP BY fournisseur ORDER BY count DESC LIMIT 30');
$query->execute(array($datetime, $intervalle));
$f=$query->fetchAll();
?>



	  <script src="../js/loader.js"></script>
  	  <script src="../js/moment.min.js"></script>
      <script src="../js/Chart.js"></script>
      <canvas id="myChart" ></canvas>

<script>


var fournisseur =new Array();
var nbfournisseur =new Array();
<?php
foreach ($f as $fou){
   ?>fournisseur.push("<?php echo preg_replace( "/\r|\n/", "", $fou['f'] ); ?>");
    nbfournisseur.push("<?php echo $fou['count']; ?>");

<?php } ?>;


var ctx = document.getElementById("myChart").getContext('2d');
var myChart = new Chart(ctx, {
    type: 'horizontalBar',
    data: {
        labels: fournisseur,
        datasets: [{
            label: 'Pareto par fournisseur',
            data: nbfournisseur,
            borderWidth: 1,
            backgroundColor: "#ff8900",
            borderColor: "#E37C07"
        }]
    },
    options: {
        scales: {
            xAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});
</script>

<?php
drawFooter();
ob_end_flush();
