<?php
include_once "needed.php";
include_once "../../needed.php";

drawHeader('RH');
drawMenu('collab');


$recherche = "";
$debut=0;
$datetime = date("Y-m-d");

    $date = date_parse($datetime);
    $jour = $date['day'];
    $mois = $date['month'];
    $annee = $date['year'];

if (isset($_GET["recherche"])){
        $recherche = $_GET["recherche"];
    }
if(isset($_GET['nb'])){
      $debut=$_GET['nb'];
    }



?>

<h2>Demande de formations</h2>

<?php
if(empty($_SESSION['login'])){
  ?>
    <h2>Formationss</h2>
    <h4>Vous devez être connecté pour accéder à cette partie.</h4>
    <a href="/moncompte/identification.php?redirection=RH/formations/index.php"><button class="btn btn-default">Se connecter</button></a>
  <?php
  }else{
?>

<style>
    .conteneur_alerte{
        margin-top:20px;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
    }
    .alerte{
        color: #000 ;
        font-size: 15px;
        background-color: #e3e3e3;
        border-color: #ccc;
        border-radius:6px;
        border-width: 1px;
        border-style: solid;
        margin: 5px;
    }
    .alerte:hover{
      opacity:0.7;
    }
    .info_alerte{
        margin: 10px;
        width: 320px;
        padding: 10px;
        border-radius:6px;
        background-color: #FFF;
        border-color: #ccc;
        border-width: 1px;
        border-style: solid;
    }

    .couleur{
        margin: 10px;
        width: 320px;
        height: 20px;
        border-radius:3px;
        border-color: #ccc;
        border-width: 1px;
        border-style: solid;
    }
    .date_et_titre{
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }
</style>

<form class="form-inline">
  <div class="form-group">

    <label>Training Title</label>
      <select name="recherche" class="form-control">
        <option value="" selected="selected">Toute catégories</option>
        <option value="Recyclage CACES 2 et 3n" >Recyclage CACES 2 et 3</option>
        <option value="Recyclage habilitations électriques BR et HRs">Recyclage habilitations électriques BR et HR</option>
        <option value="Recyclage BE manœuvre+ initiale H0V">Recyclage BE manœuvre+ initiale H0V</option>
        <option value="Anglais">Anglais</option>
        <option value="Recyclage SST">Recyclage SST(port des EPI)</option>
        <option value="Welding Technology">Welding Technology</option>
        <option value="FES Outils : QRCI 8D">FES Outils : QRCI 8D</option>
        <option value="Programmation cintrage">Programmation cintrage</option>
        <option value="EE Fundamentals">EE Fundamentals</option>
        <option value="Young female Manager Program">Young female Manager Program</option>
      </select>
  </div>

<button type="submit" class="btn btn-default">Rechercher</button>
<a href="mesfor.php" class="btn btn-default pull-right">Formations en attente de validation</a>
<a href="mesval.php" class="btn btn-default pull-right">Formations validées</a>
</form>
<br><br>
<?php echo "Training title recheché: ".$recherche; ?>

<div class="conteneur_alerte">
<?php

$Query = $bdd->prepare('SELECT * FROM formations_dispo WHERE trainingtitle LIKE :tt AND DATEDIFF(date_deb, :d1 )>0  ORDER BY date_deb LIMIT 20  OFFSET :nb') ;

$Query->bindValue(':tt', '%'.$recherche.'%',PDO::PARAM_STR);
$Query->bindValue(':d1', $datetime , PDO::PARAM_STR);
$Query ->bindValue(':nb',(int) $debut, PDO::PARAM_INT);
$Query->execute();


while ($Data = $Query->fetch()) {
  $Query2 = $bdd->prepare('SELECT * FROM demande_formations WHERE demandeur = :d AND formation= :f ') ;

  $Query2->bindValue(':d', $_SESSION['id'] , PDO::PARAM_INT);
  $Query2 ->bindValue(':f',(int) $Data['id'], PDO::PARAM_INT);
  $Query2->execute();
  if(!($Query2->fetch() ) ){
  ?>

  <a href="details_demande.php?demande= <?php echo $Data['id'] ; ?>" ><div class="alerte" >

      <div class="info_alerte">
          <div class="date_et_titre">
              <h4 style="margin-top: 0px; font-size: 40px;">
                <?php echo $Data['trainingtitle']; ?>
                </h4>
          </div>

          <p><b>Date de début : </b><?php echo $Data['date_deb'];?><br>
              <b>Date de fin: </b><?php echo $Data['date_fin'];?><br>
              <b>Date d'ajout : </b><?php echo $Data['date_ajout'];?>
              <br><br><br>

              <b><?php echo "Cliquez pour demander cette formation";?></b><br></p>


      </div>

  </div></a>



<?php
    }
 }
 ?>
</div>


<?php


if($debut > 19){
  ?>
  <a href="index.php?recherche=<?php echo $recherche;?>&amp;nb=<?php echo $debut-20;?>" class="btn btn-default">Elements précédents</a>
<?php
}


$test = $bdd->prepare('SELECT id FROM formations_dispo WHERE id NOT IN
  (SELECT formations_dispo.id as id FROM formations_dispo JOIN demande_formations ON formations_dispo.id=demande_formations.formation WHERE demandeur= :dd)
  AND trainingtitle LIKE :tt AND DATEDIFF(date_deb, :d1 )>0 ORDER BY date_deb LIMIT 1 OFFSET :nb');
$test->bindValue(':dd', $_SESSION['id'], PDO::PARAM_INT);
$test->bindValue(':tt','%'.$recherche.'%', PDO::PARAM_STR);
$test->bindValue(':d1', $datetime, PDO::PARAM_INT);
$test ->bindValue(':nb',(int) $debut+20, PDO::PARAM_INT);
$test->execute();
if($test -> fetch()){ ?>
  <a href="index.php?recherche=<?php echo $recherche;?>&amp;nb=<?php echo $debut+20;?>" class="btn btn-default">Elements suivants</a>
<?php

}
}
drawFooter();
?>
