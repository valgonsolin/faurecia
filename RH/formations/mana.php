<?php
include_once "needed.php";
include_once "../../needed.php";

drawHeader('RH');
drawMenu('mana');

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

if(empty($_SESSION['login'])) { ?>
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
      <a href="#" class="btn btn-default pull-right">Evaluation à chaud </a>
      <a href="#" class="btn btn-default pull-right"> </a>
      </form>
    <br>




<?php
}
drawFooter();
?>
