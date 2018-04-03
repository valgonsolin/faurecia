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


if(empty($_SESSION['login']))
{ ?>
  <h2>Formationss</h2>
  <h4>Vous devez être connecté pour accéder à cette partie.</h4>
  <a href="/moncompte/identification.php?redirection=RH/formations/index.php"><button class="btn btn-default">Se connecter</button></a>
<?php
}else{ ?>
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
        opacity:1;
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

  <h2>Toutes mes demandes de formations</h2>
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
  <a href="#" class="btn btn-default pull-right">Demande de formations validées</a>
  <a href="index.php" class="btn btn-default pull-right">Effectuer une demande de formations</a>
  </form>
<br>

<div class="conteneur_alerte">
<?php

  $query= $bdd->prepare('SELECT trainingtitle,date_deb,date_fin,origine,date_ajout FROM demande_formations JOIN formations_dispo  ON demande_formations.formation=formations_dispo.id
                        WHERE demandeur= :d and valide = 0 AND trainingtitle LIKE :tt  AND DATEDIFF(date_deb, :d1 )>0 ORDER BY formations_dispo.date_deb LIMIT 20 OFFSET :nb ');
                        $query->bindValue(':d', $_SESSION['id'], PDO::PARAM_INT);
                        $query->bindValue(':tt','%'.$recherche.'%', PDO::PARAM_STR);
                        $query->bindValue(':d1',$datetime, PDO::PARAM_STR);
                        $query ->bindValue(':nb',(int) $debut, PDO::PARAM_INT);
  $query->execute();

  while ($Data=$query->fetch()) { ?>
    <div class="alerte" >

        <div class="info_alerte">
            <div class="date_et_titre">
                <h4 style="margin-top: 0px; font-size: 40px;">
                  <?php echo $Data['trainingtitle']; ?>
                  </h4>
            </div>

            <p><b>Date de début : </b><?php echo $Data['date_deb'];?><br>
                <b>Date de fin: </b><?php echo $Data['date_fin'];?><br>
                <b>Date de demande: </b><?php echo $Data['date_ajout'];?><br>
                <b>Origine du besoin :</b><?php echo $Data['origine']; ?><br>
                <?php
                    $qyy=$bdd->prepare('SELECT * FROM profil WHERE id = ? ');
                    $qyy->execute(array($_SESSION['manager']));
                    $mana=$qyy->fetch();
                ?>
                <b>Manager en charge :</b><?php echo $mana['nom']."  ".$mana['prenom'] ; ?><br></p>


        </div>

    </div>

<?php
  }
?>

</div>

<?php


if($debut > 19){
  ?>
  <a href="attval.php?recherche=<?php echo $recherche;?>&amp;nb=<?php echo $debut-20;?>" class="btn btn-default">Elements précédents</a>
<?php
}

$test = $bdd->prepare('SELECT * FROM demande_formations JOIN formations_dispo  ON demande_formations.formation=formations_dispo.id
                      WHERE demandeur= :d and valide = 0 AND trainingtitle LIKE :tt AND DATEDIFF(date_deb, :d1 )>0 ORDER BY formations_dispo.date_deb LIMIT 1 OFFSET :nb');
$test->bindValue(':d', $_SESSION['id'], PDO::PARAM_INT);
$test->bindValue(':tt','%'.$recherche.'%', PDO::PARAM_STR);
$test->bindValue(':d1',$datetime, PDO::PARAM_STR);
$test ->bindValue(':nb',(int) $debut+20, PDO::PARAM_INT);
$test->execute();

if($test -> fetch()){ ?>
  <a href="attval.php?recherche=<?php echo $recherche;?>&amp;nb=<?php echo $debut+20;?>" class="btn btn-default">Elements suivants</a>

<?php
}
}
drawFooter();
?>
