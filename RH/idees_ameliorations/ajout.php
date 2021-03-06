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

  if(!empty($_POST)){

    $file=upload($bdd,'file',"../../ressources","idee",5048576,array( 'jpg' , 'jpeg' , 'gif' , 'png' , 'JPG' , 'JPEG' , 'GIF' , 'PNG' ));
    if($file < 0){$file=NULL;}

    $file2=upload($bdd,'file1',"../../ressources","idee",5048576,array( 'jpg' , 'jpeg' , 'gif' , 'png' , 'JPG' , 'JPEG' , 'GIF' , 'PNG' ));
    if($file2 < 0){$file2=NULL;}

    $datetime = date("Y-m-d");

  $query = $bdd -> prepare('INSERT INTO idees_ameliorations(emmetteur,type,transversalisation,retenue,respo_rea,date_rea,situation_actuelle,situation_proposee,image,nbidees,image2) VALUES (:emmetteur,:type,:transversalisation,:retenue,:respo_rea,:date_rea ,:situation_actuelle,:situation_proposee,:image,:nbidees,:image2)');

    if($query -> execute(array(
      'emmetteur' => $_SESSION['id'],
      'type' => $_POST['type'],
      'transversalisation' => $_POST['transversalisation'],
      'retenue' =>$_POST['retenue'],
      'respo_rea' => $_POST['respo_rea'],
      'date_rea'=>$datetime,
      'situation_actuelle' => $_POST['situation_actuelle'],
      'situation_proposee' => $_POST['situation_proposee'],
      'image'=>$file,
      'nbidees'=>$_POST['nbidees'],
      'image2'=>$file2
    ))){
      success('Ajouté',"L'idée a bien été ajoutée.");
    }else{
      warning('Erreur','Les données entrées ne sont pas conformes.');

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
      <input name="transversalisation" type="checkbox" value="1"> Oui</label>

  </div>
  <div class="form-group">
    <label>Retenue :     </label><label style="margin-left:20px">
      <input type="hidden" value="0" name="retenue">
      <input name="retenue" type="checkbox" value="1"> Oui</label>

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

    <div class="form-group">
      <label>Nombre d'Idees Ameliorations  :     </label>
      <input name="nbidees" value="1" class="form-control" type="int">
    </div>

    <div class="form-group">
      <label>Image Avant  :     </label>
      <input name="file" type="file">
    </div>

    <div class="form-group">
      <label>Image Apres  :     </label>
      <input name="file1" type="file">
    </div>

  	<input value="Ajouter" class="btn btn-default" type="submit">


  </form>


<?php
 }
?>



<?php
drawFooter();
 ?>
