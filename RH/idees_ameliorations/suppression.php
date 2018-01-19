<?php
include_once "needed.php";
include_once "../../needed.php";

drawHeader('RH');
drawMenu('');

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
  if(!$_SESSION['idees']){
    echo "<h2>Quiz</h2>";
    echo "<p>Vous n'avez pas les droits pour accéder à cette partie. <a href='".$url."' class='btn btn-default pull-right'>Accueil</a></p>";
  }else{
  if(isset($_POST['supprimer'])){

    $query = $bdd -> prepare('DELETE * FROM idees_ameliorations WHERE id=?');
    $query -> execute(array($_POST['id']));
    success('Supprimé','La question a bien été supprimée.');

}elseif(isset($_POST['modifier'])){
  $datetime = date("Y-m-d");
  $queryy=$bdd->prepare('SELECT * FROM profil WHERE id= ?');
  $queryy->execute(array($_SESSION['id']));
  $Data=$queryy->fetch();
  $query = $bdd -> prepare('UPDATE idees_ameliorations SET superviseur= :superviseur ,type=;:type ,transversalisation = :transversalisation,retune= :retenue,respo_rea,=:respo_rea,date_rea=:date_rea,situation_actuelle= :situation_actuelle,situation_proposee= :situation_proposee WHERE id = :id');
  $query -> execute(array(

    'superviseur' => $_POST['superviseur'],
    'type' => $_POST['type'],
    'transversalisation' => $_POST['transversalisation'],
    'retenue' => $_POST['retenue'],
    'respo_rea' => $datetime,
    'date_rea' => $_POST['date_rea'],
    'situation_actuelle' => $_POST['situation_actuelle'],
    'situation_proposee' => $_POST['situation_proposee']
  ));

  if($query ==false){
    warning('Erreur','Les données entrées ne sont pas conformes.');
  }else{
    success('Modifié','La question a bien été mise à jour.');
  }
}



  $recherche = "";
  if (isset($_GET["recherche"])){
      $recherche = $_GET["recherche"];
  }
  ?>
  <h2>Idées</h2>
  <div class="boutons_nav" style="display: flex; justify-content: center;">
    <a href="ajout.php" class="bouton_menu" style="margin-right:20%">Ajout</a>
    <a href="suppression.php" class="bouton_menu bouton_nav_selected">Modification/Suppression</a>
  </div>

  <form class="form-inline">
    <div class="form-group">
      <label for="recherche">Recherche :</label>
      <select class="form-control" name="recherche" >
        <?php
        $profil = $bdd -> query('SELECT * FROM profil');
        while($personne = $profil -> fetch()){ ?>
          <option value="<?php echo $personne['id']; ?>" ><?php echo $personne['nom']." ".$personne['prenom']; ?></option>
      <?php  } ?>
      </select>
    </div>
    <button type="submit" class="btn btn-default">Rechercher</button>
  </form>
  <table class="table">
  <thead class="thead">
  <tr>
      <th>N°</th>
      <th>Date réalisation</th>
      <th>Situation actuelle</th>
      <th>Score</th>
      <th></th>
  </tr>
  </thead>
  <tbody>

<?php
$nb=0;
if(isset($_GET['nb'])){
$nb=$_GET['nb'];
}
  $qyy= $bdd->prepare('SELECT * FROM profil JOIN idees_ameliorations ON  profil.id=idees_ameliorations.emmetteur  ORDER BY vote LIMIT 20 OFFSET ?');
  $qyy->execute(array($nb));

  while($Dat=$qyy->fetch()){

  ?>
    <tr>
      <td><?php echo $Dat['idees_ameliorations.id']; ?></td>
      <td><?php echo $Dat['date_rea']; ?></td>
      <td><?php echo $Dat['situation_actuelle']?></td>
      <td><?php echo $Dat['vote'];?></td>
      <td><a href="supprimer_question.php?id=<?php echo $Dat['idees_ameliorations.id']?>" class="btn btn-default">Modifier</a></td>
    </tr>
  <?php
}
 ?>
</tbody>

</table>
<?php


$test = $bdd->prepare('SELECT * FROM idees_ameliorations WHERE id= ? LIMIT 1 OFFSET ?');
$test->execute(array($Dat['profil.id'],$nb+20) );

 ?>
<form method="post" class="inline-form"> <?php
  if($nb > 19){    ?>
      <a href="suppression.php?recherche=<?php echo $recherche;?>&amp;nb=<?php echo $nb-20;?>" class="btn btn-default">Elements précédents</a>
    <?php
    }
    if($test -> fetch()){ ?>
    <a href="suppression.php?recherche=<?php echo $recherche;?>&amp;nb=<?php echo $nb+20;?>" class="btn btn-default">Elements suivants</a>
  <?php } ?>

    <span class="clear" style="clear: both; display: block;"></span>
  </form>
<?php
}}

drawFooter();
