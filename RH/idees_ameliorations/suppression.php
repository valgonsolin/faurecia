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

    $query = $bdd -> prepare('DELETE * FROM idees_ameliorations WHERE id=?');
    $query -> execute(array($_POST['id']));
    success('Supprimé','La question a bien été supprimée.');

}elseif(isset($_POST['modifier'])){

  $query = $bdd -> prepare("UPDATE idees_ameliorations SET type=:type ,transversalisation = :transversalisation,retenue= :retenue,respo_rea,=:respo_rea, situation_actuelle= :situation_actuelle, situation_proposee= :situation_proposee WHERE id = :id ");
  $query->bindValue('type', $_POST['type'],PDO::PARAM_STR);
  $query->bindValue('transversalisation', $_POST['transversalisation'],PDO::PARAM_INT);
 $query->bindValue('retenue', $_POST['retenue'],PDO::PARAM_INT);
 $query->bindValue('respo_rea', $_POST['respo_rea'],PDO::PARAM_INT);
 $query->bindValue('situation_actuelle', $_POST['situation_actuelle'],PDO::PARAM_STR);
 $query->bindValue('situation_proposee', $_POST['situation_proposee'],PDO::PARAM_STR);
 $query->bindValue('id', $_POST['id'],PDO::PARAM_INT);
 $query->execute();
  // $query -> execute(array(
  //     'type' => $_POST['type'],
  //     'transversalisation' => $_POST['transversalisation'],
  //     'retenue' =>$_POST['retenue'],
  //     'respo_rea' => $_POST['respo_rea'],
  //     'situation_actuelle' => $situationA,
  //     'situation_proposee' => $situationP,
  //     'id'=> $_POST['id']
  print_r($query->errorInfo());

  if($query ==false){
    warning('Erreur','Les données entrées ne sont pas conformes.');
  }else{
    success('Modifié','La question a bien été mise à jour.');
  }
}


  ?>
  <h2>Idées</h2>
  <div class="boutons_nav" style="display: flex; justify-content: center;">
    <a href="ajout.php" class="bouton_menu" style="margin-right:20%">Ajout</a>
    <a href="suppression.php" class="bouton_menu bouton_nav_selected">Modification/Suppression</a>
  </div>


  <table class="table">
  <thead class="thead">
  <tr>
      <th>Emmeteur</th>
      <th>Date réalisation</th>
      <th>Situation actuelle</th>
      <th>Score</th>
      <th></th>
  </tr>
  </thead>
  <tbody>

<?php

if(isset($_GET['nb'])){
$nb=$_GET['nb'];
}
  if($droit==1){
  $qyy= $bdd->prepare('SELECT  date_rea,situation_actuelle,vote,idees_ameliorations.id AS id1 FROM idees_ameliorations LEFT JOIN profil ON  profil.id=idees_ameliorations.emmetteur  ORDER BY idees_ameliorations.date_rea DESC LIMIT 20 OFFSET :off ');
  $qyy->bindValue('off', $nb, PDO::PARAM_INT);
  $qyy->execute();

}else{
  $qyy= $bdd->prepare('SELECT  idees_ameliorations.id AS id1, date_rea,situation_actuelle,vote FROM idees_ameliorations  LEFT JOIN profil ON  profil.id=idees_ameliorations.emmetteur  WHERE (profil.id= :a OR profil.manager= :b)  ORDER BY vote LIMIT 20 OFFSET : off');
  $qyy->bindValue('a', $nb, PDO::PARAM_INT);
  $qyy->bindValue('b', $nb, PDO::PARAM_INT);
  $qyy->bindValue('off', $nb, PDO::PARAM_INT);
  $qyy->execute(); }


  while($Data=$qyy->fetch()){

  ?>
    <tr>
      <td><?php echo $Data['id1']; ?></td>
      <td><?php echo $Data['date_rea']; ?></td>
      <td><?php echo $Data['situation_actuelle']?></td>
      <td><?php echo $Data['vote'];?></td>
      <td><a href="supprimer_question.php?id=<?php echo $Data['id1']?>" class="btn btn-default">Modifier</a></td>
    </tr>
  <?php
}
 ?>
</tbody>

</table>
<?php

if($droit==1){
$test = $bdd->prepare('SELECT * FROM idees_ameliorations JOIN profil ON profil.id=idees_ameliorations.emmetteur ORDER BY date_rea LIMIT 1 OFFSET : off ');
$test->bindValue('off',($nb+20),PDO::PARAM_INT );}
else{$test = $bdd->prepare('SELECT * FROM idees_ameliorations JOIN profil ON profil.id=idees_ameliorations.emmetteur ORDER BY WHERE( profil.id= :a OR idees_ameliorations.manager= :b ) LIMIT 1 OFFSET :off ');

$test->bindValue('a',$_SESSION['id'],PDO::PARAM_INT );
$test->bindValue('off',$_SESSION['manager'],PDO::PARAM_INT );
$test->bindValue('off',($nb+20),PDO::PARAM_INT );
$test->execute(); }
 ?>
<form method="post" class="inline-form"> <?php
  if($nb > 19){    ?>
      <a href="suppression.php?nb=<?php echo $nb-20;?>" class="btn btn-default">Elements précédents</a>
    <?php
    }
    if($test -> fetch()){ ?>
    <a href="suppression.php?nb=<?php echo $nb+20;?>" class="btn btn-default">Elements suivants</a>
  <?php } ?>

    <span class="clear" style="clear: both; display: block;"></span>
  </form>
<?php
}

drawFooter();
