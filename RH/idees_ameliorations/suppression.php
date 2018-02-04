<?php
include_once "needed.php";
include_once "../../needed.php";

drawHeader('RH');
drawMenu('');

$droit=0;
$nb=0;
if(empty($_SESSION['login']))
{ ?>
  <h2>Idées</h2>
  <h4>Vous devez être connecté pour accéder à cette partie.</h4>
  <a href="/moncompte/identification.php?redirection=RH/idees_ameliorations/ajout.php"><button class="btn btn-default">Se connecter</button></a>
  <a href="index.php" class="btn btn-default"> Idées du mois</a>
<?php
}
else
{
  if($_SESSION['idees']){
    $droit=1;}


  if(isset($_POST['supprimer'])){

    $query = $bdd -> prepare('DELETE FROM idees_ameliorations WHERE id=?');
    $query -> execute(array($_POST['id']));

    success('Supprimé','La question a bien été supprimée.');

}elseif(isset($_POST['modifier'])){

  $query = $bdd -> prepare("UPDATE idees_ameliorations SET type=:type ,transversalisation = :transversalisation,retenue= :retenue,respo_rea=:respo_rea, situation_actuelle= :situation_actuelle, situation_proposee= :situation_proposee WHERE id = :id ");
  $query->bindValue('type', $_POST['type'],PDO::PARAM_STR);
  $query->bindValue('transversalisation', $_POST['transversalisation'],PDO::PARAM_INT);
 $query->bindValue('retenue', $_POST['retenue'],PDO::PARAM_INT);
 $query->bindValue('respo_rea', $_POST['respo_rea'],PDO::PARAM_INT);
 $query->bindValue('situation_actuelle', $_POST['situation_actuelle'],PDO::PARAM_STR);
 $query->bindValue('situation_proposee', $_POST['situation_proposee'],PDO::PARAM_STR);
 $query->bindValue('id', $_POST['id'],PDO::PARAM_INT);
 $query->execute();

  if($query ==false){
    warning('Erreur','Les données entrées ne sont pas conformes.');
  }else{
    success('Modifié','La question a bien été mise à jour.');
  }
}


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


  <h2>Idées</h2>
  <div class="boutons_nav" style="display: flex; justify-content: center;">
    <a href="ajout.php" class="bouton_menu" style="margin-right:20%">Ajout</a>
    <a href="suppression.php" class="bouton_menu bouton_nav_selected">Modification/Suppression</a>
  </div>




<?php

if(isset($_GET['nb'])){
$nb=(int ) $_GET['nb'];
}
  if($droit==1){
  $qyy= $bdd->prepare('SELECT  situation_proposee,type,nom,date_rea,situation_actuelle,vote,idees_ameliorations.id AS id1 FROM idees_ameliorations LEFT JOIN profil ON  profil.id=idees_ameliorations.emmetteur  ORDER BY id1 DESC LIMIT 5 OFFSET :off ');
  $qyy->bindValue(':off', $nb, PDO::PARAM_INT);
  $qyy->execute();

}else{
  $qyy= $bdd->prepare('SELECT  idees_ameliorations.id AS id1, date_rea,situation_actuelle,vote,nom,type,situation_proposee FROM idees_ameliorations  LEFT JOIN profil ON  profil.id=idees_ameliorations.emmetteur  WHERE (profil.id= :a OR profil.manager= :b)  ORDER BY id1 DESC LIMIT 5 OFFSET :off');
  $qyy->bindValue(':a',$_SESSION['id'],PDO::PARAM_INT );
  $qyy->bindValue(':b',$_SESSION['manager'],PDO::PARAM_INT );
  $qyy->bindValue(':off', $nb, PDO::PARAM_INT);
  $qyy->execute();
  }


  while($Data=$qyy->fetch()){

  ?>
<div class="conteneur_alerte">
  <a href="supprimer_question.php?id=<?php echo $Data['id1']?>" class="btn btn-default">
  <div class="alerte" >

      <div class="info_alerte">
          <div class="date_et_titre">
              <h4 style="margin-top: 0px; font-size: 40px;">
                <?php echo $Data['nom']; ?>
                </h4>
          </div>

          <p><b>Type : </b><?php echo $Data['type'];?><br>
              <b>Date création: </b><?php echo date('d/m/y ',strtotime($Data['date_rea']));?><br>
              <b>Nombre de vote : </b><?php echo $Data['vote'];?><br>
              <b>Situation Actuelle :</b><?php echo $Data['situation_actuelle'];?><br>
              <b>Situation Proposee :</b><?php echo $Data['situation_proposee'];?><br><br><br><br>
              <b><?php echo "Cliquez pour modifier/supprimer ";?><b><br></p>


      </div>

  </div></a>
</div>

  <?php
}
 ?>

<?php

if($droit==1){
$test = $bdd->prepare('SELECT * FROM idees_ameliorations LEFT JOIN profil ON profil.id=idees_ameliorations.emmetteur  LIMIT 5 OFFSET :off ');
$test->bindValue(':off',(int) ($nb+5),PDO::PARAM_INT );
$test->execute(); }
else{$test = $bdd->prepare('SELECT * FROM idees_ameliorations JOIN profil ON profil.id=idees_ameliorations.emmetteur  WHERE( profil.id= :a OR profil.manager= :b ) LIMIT 5 OFFSET :off ');

$test->bindValue(':a',($_SESSION['id']),PDO::PARAM_INT );
$test->bindValue(':b', ($_SESSION['manager']),PDO::PARAM_INT );
$test->bindValue(':off',(int) $nb+5,PDO::PARAM_INT );
$test->execute();}
 ?>
 <?php
  if($nb > 4){    ?>
      <a href="suppression.php?nb=<?php echo ($nb-5);?>" class="btn btn-default">Elements précédents</a>
    <?php
    }
    if($test -> fetch()){ ?>
    <a href="suppression.php?nb=<?php echo $nb+5; ?>" class="btn btn-default">Elements suivants</a>
  <?php } ?>


<?php
}

drawFooter();
