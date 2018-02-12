
<?php
include_once "needed.php";
include_once "../../needed.php";

drawHeader('RH');



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

<div class="boutons_nav" style="display: flex; justify-content: center;">
  <a href="validation.php" class="bouton_menu bouton_nav_selected" style="margin-right:20%">Valider idee</a>
  <a href="realisation.php" class="bouton_menu">Declarer une idee "realisee"</a>
</div>
<br><br>

<h1>Les idées dont vous etes manager :</h1>
<br><br>



  <form class="form-inline">
  <div class="form-group">
    <label>Recherche</label>
    <select class="form-control" name="recherche" >
      <?php
      $profil = $bdd -> query('SELECT * FROM profil'); ?>
      <option value="">Selectionnez une personne </option>

      <?php
      $profil = $bdd -> query('SELECT * FROM profil');
      while($personne = $profil -> fetch()){ ?>
        <option value="<?php echo $personne['id']; ?>" ><?php echo $personne['nom']." ".$personne['prenom']; ?></option>
    <?php  } ?>
    </select>
  </div>
  <button type="submit" class="btn btn-default">Rechercher</button>
  </form>





<div class="conteneur_alerte">
<?php

if($recherche>0){
$Query = $bdd->prepare('SELECT *, idees_ameliorations.id AS id1 FROM idees_ameliorations LEFT JOIN profil  ON idees_ameliorations.emmetteur = profil.id  WHERE profil.id= :i and supprime = 0 and profil.manager=:m  ORDER BY id1 DESC LIMIT 20  OFFSET :nb') ;

$Query->bindValue(':i',(int) $recherche,PDO::PARAM_INT);
$Query->bindValue(':m', $_SESSION['id'], PDO::PARAM_INT);
$Query ->bindValue(':nb',(int) $debut, PDO::PARAM_INT);
$Query->execute();}


else{$Query = $bdd->prepare('SELECT *,idees_ameliorations.id AS id1 FROM idees_ameliorations LEFT JOIN profil  ON idees_ameliorations.emmetteur = profil.id  WHERE  supprime = 0 and profil.manager=:m   ORDER BY  id1 DESC LIMIT 20 OFFSET :nb ') ;

  $Query->bindValue(':m', $_SESSION['id'], PDO::PARAM_INT);
  $Query ->bindValue(':nb',(int) $debut, PDO::PARAM_INT);
  $Query->execute();}


while ($Data = $Query->fetch()) {

  $c=0;
  $Qy = $bdd->prepare('SELECT * FROM idees_ameliorations WHERE id= ? and valide= 1');
  $Qy->execute(array( $Data['id1']));
  if($Qy->fetch()){$c=1;}
  if($c){ ?>

    <a href="valide.php?idee2= <?php echo $Data['id1'] ; ?>" ><div class="alerte" >

        <div class="info_alerte">
            <div class="date_et_titre">
                <h4 style="margin-top: 0px; font-size: 40px;">
                  <?php echo $Data['nom']; ?>
                  </h4>
            </div>

            <p><b>Type : </b><?php echo $Data['type'];?><br>
                <b>Date création: </b><?php echo date('d/m/y ',strtotime($Data['date_rea']));?><br>
                <b>Nombre de vote : </b><?php echo $Data['vote'];?><br>
                <b>Situation actuelle :</b><?php echo $Data['situation_actuelle'];?><br>
                <b>Nobre d'idées qu'elle contient : </b><?php echo $Data['nbidees'];?><br>
                <b><?php if($Data['valide']==1){echo "Cette idée a déja été validé par le manager ";}else{echo "L'idée n'a pas été validé par le manager";} ?></b><br>
                <b>Situation proposée :</b><?php echo $Data['situation_proposee'];?><br><br><br>

                <b><?php echo "Cliquez pour retirer validation idée ";?></b><br></p>


        </div>

    </div></a>
<?php
}else{ ?>
  <a href="valide.php?idee= <?php echo $Data['id1'] ; ?>" ><div class="alerte" >

      <div class="info_alerte">
          <div class="date_et_titre">
              <h4 style="margin-top: 0px; font-size: 40px;">
                <?php echo $Data['nom']; ?>
                </h4>
          </div>

          <p><b>Type : </b><?php echo $Data['type'];?><br>
              <b>Date : </b><?php echo date('d/m/y ',strtotime($Data['date_rea']));?><br>
              <b>Nombre de vote : </b><?php echo $Data['vote'];?><br>
              <b>Situation actuelle : </b><?php echo $Data['situation_actuelle'];?><br>
              <b>Nobre d'idées qu'elle contient : </b><?php echo $Data['nbidees'];?><br>
              <b><?php if($Data['valide']==1){echo "Cette idée a déja été validé par le manager ";}else{echo "L'idée n'a pas été validé par le manager";} ?></b><br>
              <b>Situation proposée :</b><?php echo $Data['situation_proposee'];?><br><br><br>
              <b><?php echo "Cliquez pour valider l'idée ";?></b><br></p>

        </div>


  </div></a>

<?php } }


?>
</div>

<?php
if($debut > 19){
  ?>
  <a href="validation.php?recherche=<?php echo $recherche;?>&amp;nb=<?php echo $debut-20;?>" class="btn btn-default">Elements précédents</a>
<?php
}

if($recherche>0){$test = $bdd->prepare('SELECT * FROM profil LEFT JOIN idees_ameliorations
    ON idees_ameliorations.emmetteur = profil.id
    WHERE profil.id= :i and supprime = 0  LIMIT 20 OFFSET :nb');
$test->bindValue(':i',$recherche, PDO::PARAM_INT);
$test ->bindValue(':nb',(int) $debut+20, PDO::PARAM_INT);
$test->execute(); }else{$test = $bdd->prepare('SELECT * FROM profil LEFT JOIN idees_ameliorations
    ON idees_ameliorations.emmetteur = profil.id
    WHERE  supprime = 0 LIMIT 20 OFFSET :nb');
$test ->bindValue(':nb',(int) $debut+20, PDO::PARAM_INT);
$test->execute();  }

if($test -> fetch()){ ?>
  <a href="validation.php?recherche=<?php echo $recherche;?>&amp;nb=<?php echo $debut+20;?>" class="btn btn-default">Elements suivants</a>
<?php
}
}
drawFooter();
 ?>
