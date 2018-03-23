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
  <a href="index.php" class="btn btn-default"> Retourner à l'acceuil IA</a>
<?php
}
else
{
  echo "<h2>Idées</h2>";
   ?>
  <div class="boutons_nav" style="display: flex; justify-content: center;">
    <a href="ajout.php" class="bouton_menu" style="margin-right:20%">Ajout</a>
    <a href="suppression.php" class="bouton_menu bouton_nav_selected">Modification/Suppression</a>
  </div>
  <?php
  if(!(isset($_GET['id']))){?>
    <h2>Idées</h2>
    <h4>Erreur: Votre session est inconnu.</h4>
    <a href="index.php"> Acceuil</a>
  <?php }else{
    $query = $bdd -> prepare('SELECT * FROM idees_ameliorations WHERE id = ?');
    $query -> execute(array($_GET['id']));
    $Data=$query -> fetch(); ?>

    <form action="suppression.php" method="post" style="margin-top:20px;" enctype="multipart/form-data">



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
      <select class="form-control" name="respo_rea" <?php echo $Data['respo_rea']; ?>>
        <?php
        $profil = $bdd -> query('SELECT * FROM profil ');
        while($personne = $profil -> fetch()){ ?>
          <option value="<?php echo $personne['id']; ?>" <?php if($Data['respo_rea'] == $personne['id']){echo "selected";} ?>><?php echo $personne['nom']." ".$personne['prenom']; ?></option>
      <?php  } ?>
      </select>
    </div>
    	<div class="form-group">
    		<label>Situation actuelle :     </label>
    		<input name="situation_actuelle" class="form-control" type="text" value="<?php echo $Data['situation_actuelle']; ?>">
    	</div>
      <div class="form-group">
        <label>Situation proposée :     </label>
        <input name="situation_proposee" class="form-control" type="text" value="<?php echo $Data['situation_proposee']; ?>">
      </div>

      <div class="form-group">
        <label>Nombre d'Idees Ameliorations  :     </label>
        <input name="nbidees" value="<?php echo $Data['nbidees'] ?>" class="form-control" type="int">
      </div>

      <div class"form-group">
        <div class="row">
          <div class="col-md-7">
            <label>Image Avant:</label>
            <input type="file" name="fichier"><br/><br/>
            <?php if($Data['image'] != NULL){ ?>
            <input type="submit" name="img-reset" value="Supprimer l'image" class="btn btn-default">
          <?php } ?>
          </div>
          <div class="col-md-5">
        <?php
          if($Data['image'] != NULL){
            $query= $bdd -> prepare('SELECT * FROM files WHERE id= ?');
            $query -> execute(array($Data['image']));
            $img= $query -> fetch(); ?>
            <img src="<?php echo $img['chemin']; ?>" style="max-width:100%; max-height:200px; " alt="Image Avant">
          <?php } ?>
          </div>
        </div>
      </div>

      <div class"form-group">
        <div class="row">
          <div class="col-md-7">
            <label>Image Apres:</label>
            <input type="file" name="fichier2"><br/><br/>
            <?php if($Data['image2'] != NULL){ ?>
            <input type="submit" name="img-reset2" value="Supprimer l'image" class="btn btn-default">
          <?php } ?>
          </div>
          <div class="col-md-5">
        <?php
          if($Data['image2'] != NULL){
            $query= $bdd -> prepare('SELECT * FROM files WHERE id= ?');
            $query -> execute(array($Data['image2']));
            $img2= $query -> fetch(); ?>
            <img src="<?php echo $img2['chemin']; ?>" style="max-width:100%; max-height:200px; " alt="Image Apres">
          <?php } ?>
          </div>
        </div>
      </div>



      <input type="hidden" name="id" value="<?php echo $Data["id"] ?>" >
      <button type="submit" name="modifier" class="btn btn-default" onclick="return confirm('Modifier la question ?');">Modifier</button>
      <button type="submit" name="supprimer" class="btn btn-default" onclick="return confirm('Supprimer la question ?');">Supprimer</button>
      <a href="suppression.php" class="btn btn-default pull-right" >Retour</a>

    </form>




<?php
}

}
?>


<?php
drawFooter();
