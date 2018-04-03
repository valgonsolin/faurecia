<?php
include_once "needed.php";
include_once "../../needed.php";

drawHeader('RH');
drawMenu('collab');

$datetime = date("Y-m-d");

    $date = date_parse($datetime);
    $jour = $date['day'];
    $mois = $date['month'];
    $annee = $date['year'];

if(!empty($_POST)){
    $dateoj = date_parse($_POST['date_deb']);
    $jour = $dateoj['day'];
    $mois = $dateoj['month'];
    $annee = $dateoj['year'];

    if($mois<10){

  $dateDepart =$annee."-"."0".$mois."-".$jour;}
  else{$dateDepart =$annee."-".$mois."-".$jour;}

  //durée à rajouter : 6 mois;
  $duree = 6;

  //la première étape est de transformer cette date en timestamp
  $dateDepartTimestamp = date("Y-m-d",strtotime($_POST['date_deb']));
  $dateDepartTimestamp = strtotime($dateDepart);

  //on calcule la date de fin
  $dateFin = date("Y-m-d", strtotime("+".$duree." month", $dateDepartTimestamp));

  $query = $bdd -> prepare('INSERT INTO formations_dispo(trainingtitle,date_deb,date_fin,date_ajout) VALUES (:tt,:dd,:df,:da)');
  if($query -> execute(array(
    'tt' => $_POST['title'],
    'dd' => $dateDepart,
    'df' => $dateFin,
    'da' =>$datetime,
  ))){
  }else{
    warning('Erreur','Les données entrées ne sont pas conformes.');
    print_r($query->errorInfo());
  }
  $qq=$bdd->query('SELECT MAX(id) as m FROM formations_dispo');
  $last=$qq->fetch();
  $query2= $bdd-> prepare('INSERT INTO demande_formations(formation,demandeur,origine) VALUES (:f,:d,:o)');
  if($query2 -> execute(array(
    'f' => $last['m'],
    'd' => $_SESSION['id'],
    'o' => $_POST['origine'],
  ))){
      success('Succés','La demande de formation à bien été efétuée');
  }else{
    warning('Erreur','Les données entrées ne sont pas conformes.');
    print_r($query->errorInfo());
  }
}

?>
<br>
  <a href="attval.php" class="btn btn-default pull-right">Formations en attendes de validation</a>
  <a href="mesval.php" class="btn btn-default pull-right">Voir mes formations validées</a>
<br>

<h2>Demande de formations</h2>

<?php
if(empty($_SESSION['login'])){
  ?>
    <h2>Formations</h2>
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
     <form method="post" style="margin-top:20px;" enctype="multipart/form-data">

    <div class="row">
      <div class="col-md-6 col-md-offset-3">
        <div class="form-group">

          <label>Training Title</label>
            <select name="title" class="form-control">
              <option value="Recyclage CACES 2 et 3n" selected="selected">Recyclage CACES 2 et 3</option>
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
      </div>
      <div class="col-md-3">
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-md-6 col-md-offset-3">
        <p>La date de fin de formation pendra automatiquement la valeur date de début +6mois</p>
      </div>
      <div class="col-md-3">
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-md-6 col-md-offset-3">
        <div class="form-group"><label>Date de début de la formation </label>
          <input type="date"  name="date_deb" class="form-control" required>
        </div>
      </div>
      <div class="col-md-3">
      </div>
    </div>
    <div class="row">
    <div class="col-md-6 col-md-offset-3">
    <div class="form-group">
          	<label>Origine du besoin :     </label>
            <select name="origine" class="form-control">
            <option value="Plan formation" selected="selected">Plan formation</option>
            <option value="Entretien individuel" >Entretien individuel</option>
            <option value="Staffing review">Staffing review</option>
            <option value="Autre">Autre</option>
            </select>
    </div>
    </div>
    </div>   
    <div class="row"> 
    <div class="col-md-6 col-md-offset-3">
    <input value="Demander la formation" class="btn btn-default" type="submit">
    </div>
    </div>
    </form>

<?php

}
drawFooter();
?>
