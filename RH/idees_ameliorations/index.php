
<?php
include_once "needed.php";
include_once "../../needed.php";

drawHeader('RH');
drawMenu('les_idees');



$recherche = -1;
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

  <h2>Idées</h2>
  <h4>Vous devez être connecté pour accéder à cette partie.</h4>
  <a href="/moncompte/identification.php?redirection=RH/idees_ameliorations"><button class="btn btn-default">Se connecter</button></a>
  <a href="<?php echo $url; ?>" class="btn btn-default">Accueil</a>
<?php
}
else
{
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

<h2>Idées du mois</h2>

  <form class="form-inline">
  <div class="form-group">
    <label>Recherche</label>
    <select class="form-control" name="recherche" >
      <?php
      $profil = $bdd -> query('SELECT * FROM profil');
      while($personne = $profil -> fetch()){ ?>
        <option value="<?php echo $personne['id']; ?>" ><?php echo $personne['nom']." ".$personne['prenom']; ?></option>
    <?php  } ?>
    </select>
  </div>
  <button type="submit" class="btn btn-default">Rechercher</button>
  <a href="ajout.php" class="btn btn-default pull-right">Espace administration</a>
</form>





<div class="conteneur_alerte">
<?php

if($recherche>0){
$Query = $bdd->prepare('SELECT situation_actuelle,situation_proposee,nom,prenom,type,date_rea,vote,idees_ameliorations.id AS id1 FROM idees_ameliorations LEFT JOIN profil  ON idees_ameliorations.emmetteur = profil.id  WHERE profil.id= ? and supprime = 0 and idees_ameliorations.id >= ? and MONTH(idees_ameliorations.date_rea)= ?  ORDER BY vote DESC LIMIT 40  ') ;
$Query->execute(array($recherche,$debut,$mois));}
else{$Query = $bdd->prepare('SELECT situation_actuelle,situation_proposee,nom,prenom,type,date_rea,vote,idees_ameliorations.id AS id1 FROM idees_ameliorations LEFT JOIN profil  ON idees_ameliorations.emmetteur = profil.id  WHERE  supprime = 0 and idees_ameliorations.id >= ? and MONTH(idees_ameliorations.date_rea)= ?  ORDER BY vote DESC LIMIT 40  ') ;
$Query->execute(array($debut,$mois));}


while ($Data = $Query->fetch()) {

  $c=0;
  $Qy = $bdd->prepare('SELECT * FROM votes_idees WHERE personne= ? AND idee= ?');
  $Qy->execute(array($_SESSION['id'],  $Data['id1']));
  if($Qy->fetch()){$c=1;}
  if($c){ ?>

    <a href="details.php?idee2= <?php echo $Data['id1'] ; ?>" ><div class="alerte" >

        <div class="info_alerte">
            <div class="date_et_titre">
                <h4 style="margin-top: 0px; font-size: 40px;">
                  <?php echo $Data['nom']; ?>
                  </h4>
            </div>

            <p><b>Type : </b><?php echo $Data['type'];?><br>
                <b>Date création: </b><?php echo date('d/m/y ',strtotime($Data['date_rea']));?><br>
                <b>Nombre de vote : </b><?php echo $Data['vote'];?><br>
                <b>situation_actuelle :</b><?php echo $Data['situation_actuelle'];?><br>
                <b>situation_proposee :</b><?php echo $Data['situation_proposee'];?><br><br><br>
                <b><?php echo "Cliquez pour retirer vote";?></b><br></p>


        </div>

    </div></a>
<?php
}else{ ?>
  <a href="details.php?idee= <?php echo $Data['id1'] ; ?>" ><div class="alerte" >

      <div class="info_alerte">
          <div class="date_et_titre">
              <h4 style="margin-top: 0px; font-size: 40px;">
                <?php echo $Data['nom']; ?>
                </h4>
          </div>

          <p><b>Type : </b><?php echo $Data['type'];?><br>
              <b>Date : </b><?php echo date('d/m/y ',strtotime($Data['date_rea']));?><br>
              <b>Nombre de vote : </b><?php echo $Data['vote'];?><br>
              <b>situation_actuelle : </b><?php echo $Data['situation_actuelle'];?><br>
              <b>situation_proposee :</b><?php echo $Data['situation_proposee'];?><br><br><br>
              <b><?php echo "Cliquez pour voter";?></b><br></p>

        </div>


  </div></a>

<?php } }


?>
</div>

<?php
if($debut > 39){
  ?>
  <a href="index.php?recherche=<?php echo $recherche;?>&amp;nb=<?php echo $debut-40;?>" class="btn btn-default">Elements précédents</a>
<?php
}
$test = $bdd->prepare('SELECT * FROM profil LEFT JOIN idees_ameliorations
    ON idees_ameliorations.emmetteur = profil.id
    WHERE (nom LIKE ? or prenom LIKE ?) and supprime = 0 and idees_ameliorations.id >= ? and ((MONTH(idees_ameliorations.date_rea)= ?)&&(YEAR(idees_ameliorations.date_rea)=?)) LIMIT 40 ');
$test->execute(array('%'.$recherche.'%', '%'.$recherche.'%', ($debut+40), $mois, $annee));
if($test -> fetch()){ ?>
  <a href="index.php?recherche=<?php echo $recherche;?>&amp;nb=<?php echo $debut+40;?>" class="btn btn-default">Elements suivants</a>
<?php
}
}
drawFooter();
 ?>
