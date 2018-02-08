<?php
include_once "needed.php";
include_once "../../needed.php";

drawHeader('RH');
drawMenu('stat');

$intervalle=100;

$datetime = date("Y-m-d");

    $dateoj = date_parse($datetime);
    $jour = $dateoj['day'];
    $mois = $dateoj['month'];
    $annee = $dateoj['year'];


if(isset($_POST['intervalle'])){
  $intervalle=$_POST['duree'];
}

?>

<a href="#categories"><button class="btn btn-default">Voir statistiques par categories</button></a> </br></br>

<form method="post" style="margin-top:20px;" enctype="multipart/form-data">

  <div class="form-group">
  <label>Choisissez un intervalle de temps </label>
  <select name="duree" class="form-control">
    <option value="100" selected="selected">3 mois</option>
    <option value="200">6 mois</option>
    <option value="334">1 an</option>

</select>
</div>

<button type="submit" name="intervalle" class="btn btn-default" >Appliquer</button>

</form>



<?php
 $query = $bdd -> prepare('SELECT date_rea FROM idees_ameliorations WHERE DATEDIFF( ? ,date_rea)< ? GROUP BY MONTH(date_rea) ORDER BY idees_ameliorations.id  ');
 $query->execute(array($datetime, $intervalle));
 $dates = $query ->fetchAll();


if(sizeof($dates) >0){


$qy=$bdd->prepare('SELECT date_rea FROM idees_ameliorations WHERE DATEDIFF( ? ,date_rea)< ? ');
$qy->execute(array($datetime,$intervalle));
$toutesdates = $qy ->fetchAll();
  $n=sizeof($dates);
  $i=1;
  $k= strtotime($dates['0']['date_rea']);


  ?>

  <script type="text/javascript" src="../../js/loader.js"></script>
  <script src="../../js/moment.min.js"></script>
  <script src="../../js/Chart.js"></script>
  <script type="text/javascript">





var dates =new Array();
<?php
foreach ($dates as $date){
  ?>dates.push("<?php echo date_parse($date['date_rea'])['month'] ; ?>");
<?php } ?>;

var datesvrai =new Array();
<?php
foreach ($dates as $date){
  ?>datesvrai.push("<?php echo date_parse($date['date_rea'])['month'] ;echo"  -  "; echo date_parse($date['date_rea'])['year']; ?>");
<?php } ?>;


var toutesdates=new Array();
<?php
foreach ($toutesdates as $date){
  ?>toutesdates.push("<?php echo date_parse($date['date_rea'])['month'] ; ?>");
<?php } ?>;



function countOccurences(tab){
	var result = [];
	dates.forEach(function(element1){
    var k= 0;
    tab.forEach(function(element2){
      if(element1==element2){k=k+1;}
    });
    result.push(k);
  });
	return result;
}

  var vote=countOccurences(toutesdates);
  var n= <?php echo $n ; ?> ;

  var grille = new Array();

  grille.push(Array("Date","Nombre d'idees proposees"));
  for(var i=0;i<n;i++){
    grille.push(Array(datesvrai[i],vote[i]))
  }




    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
      var data = google.visualization.arrayToDataTable(grille);

      var options = {
        title: "Nombre d'idees proposees" ,
        curveType: 'function',
        legend: { position: 'bottom' },
        animation:{
        startup: 'true',
        duration: 3000,
        easing: 'out',
      }
      };

      var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

      chart.draw(data, options);
    }
  </script>

<div id="curve_chart" style="width: 900px; height: 500px"></div>

</br></br></br></br>

<div id="categories" ><h1>Répartition des idees par catégories: </h1> </div>
 </br></br>

<canvas id="browsersChart" width="400" height="100"></canvas>

<?php $qyy=$bdd->query('SELECT type,COUNT(*) as nb FROM idees_ameliorations GROUP BY type ');
$tipos=$qyy->fetchAll();
 ?>

<script>


var tipos =new Array();
var nbtipos =new Array();
<?php
foreach ($tipos as $tipo){
  ?>tipos.push("<?php echo $tipo['type']; ?>");
    nbtipos.push("<?php echo $tipo['nb']; ?>");

<?php } ?>;




   var ctx = document.getElementById("browsersChart");
   var myChart = new Chart(ctx, {
       type: 'pie',
       data: {
           datasets: [{
               label: 'Browsers',
               data: nbtipos,
               backgroundColor: [
             '#ff6384',
             '#36a2eb',
             '#cc65fe',
             '#ffce56',
             '#676633',
             '#507925',
             '#892197',
             '#769414',
             '#D3D3D3'

        ]

           }],
           labels: tipos
           },
           options: {
                   responsive: true
           }
       });
   </script>

<?php }else{echo "<h1>Aucune idee soumise pour le moment...</h1>" ;}


drawFooter();
 ?>
