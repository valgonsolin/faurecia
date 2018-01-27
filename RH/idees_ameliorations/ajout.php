<?php
include_once "needed.php";
include_once "../../needed.php";

drawHeader('RH');
drawMenu('ajouter');


if(empty($_SESSION['login']))
{ ?>
  <h2>Idées</h2>
  <h4>Vous devez être connecté pour accéder à cette partie.</h4>
  <a href="/moncompte/identification.php?redirection=dojo_HSE/quizz/ajout.php"><button class="btn btn-default">Se connecter</button></a>
  <a href="index.php" class="btn btn-default">Idées du mois</a>
<?php
}
else
{
  echo "<h2>Votre idée</h2>";
  if(!$_SESSION['idees']){
    echo "<p>Vous n'avez pas les droits pour accéder à cette partie. <a href='".$url."' class='btn btn-default pull-right'>Accueil</a></p>";
  }else{


  if(!empty($_POST)){


    $datetime = date("Y-m-d");

  $query = $bdd -> prepare('INSERT INTO idees_ameliorations(emmetteur, manager,type,transversalisation,retenue,respo_rea,date_rea,situation_actuelle,situation_proposee) VALUES (:emmetteur, :manager,:type,:transversalisation,:retenue,:respo_rea,:date_rea ,:situation_actuelle,:situation_proposee)');
    $query -> execute(array(
      'emmetteur' => $_SESSION['id'],
      'manager'=>$_SESSION['manager'],
      'type' => $_POST['type'],
      'transversalisation' => $_POST['transversalisation'],
      'retenue' =>$_POST['retenue'],
      'respo_rea' => $_POST['respo_rea'],
      'date_rea'=>$datetime,
      'situation_actuelle' => $_POST['situation_actuelle'],
      'situation_proposee' => $_POST['situation_proposee']
    ));

    if($query ==false){
      warning('Erreur','Les données entrées ne sont pas conformes.');
    }else{
      success('Ajouté','La question a bien été ajoutée.');

    }
  }
  ?>
  <div class="boutons_nav" style="display: flex; justify-content: center;">
    <a href="ajout.php" class="bouton_menu bouton_nav_selected" style="margin-right:20%">Ajout</a>
    <a href="suppression.php" class="bouton_menu">Modification/Suppression</a>
  </div>

  <form method="post" style="margin-top:20px;" enctype="multipart/form-data">

  	<div class="form-group">
  	<label>Type</label>
  	<select name="type" class="form-control">
  		<option value="Communication" selected="selected">Communication</option>
  		<option value="Qualite">Qualite</option>
      <option value="Rangement">Rangement/5S</option>
      <option value="Organisation">Organisation</option>
      <option value="Securite">Securite</option>
      <option value="Production/Rendement">Production/Rendement</option>
      <option value="Ergonomie">Ergonomie</option>
      <option value="Autre">Autre</option>
      <option value="Challenge">Challenge</option>

  </select>
  </div>
  <div class="form-group">
    <label>Transversalisation :     </label><label style="margin-left:20px">
      <input type="hidden" value="0" name="transversalisation">
      <input name="vrai1" type="checkbox" value="1"> Oui</label>

  </div>
  <div class="form-group">
    <label>Retenue :     </label><label style="margin-left:20px">
      <input type="hidden" value="0" name="retenue">
      <input name="vrai1" type="checkbox" value="1"> Oui</label>

  </div>
  <div class="form-group">
    <label>Responsable réalisation</label>
    <select class="form-control" name="respo_rea" >
      <?php
      $profil = $bdd -> query('SELECT * FROM profil');
      while($personne = $profil -> fetch()){ ?>
        <option value="<?php echo $personne['id']; ?>" ><?php echo $personne['nom']." ".$personne['prenom']; ?></option>
    <?php  } ?>
    </select>
  </div>
  	<div class="form-group">
  		<label>Situation actuelle :     </label>
  		<input name="situation_actuelle" class="form-control" type="text">
  	</div>
    <div class="form-group">
      <label>Situation proposée :     </label>
      <input name="situation_proposee" class="form-control" type="text">
    </div>


  	<input value="Ajouter" class="btn btn-default" type="submit">


  </form>


<?php
} }
?>



<?php
drawFooter();
 ?>
