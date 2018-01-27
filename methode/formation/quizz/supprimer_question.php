<?php
include_once "../needed.php";
include_once "../../../needed.php";

drawHeader('methode');
drawMenu('quizz');

if(empty($_SESSION['login']))
{ ?>
  <h2>Quiz</h2>
  <h4>Vous devez être connecté pour accéder à cette partie.</h4>
  <a href="/moncompte/identification.php?redirection=dojo_HSE/quiz/ajout.php"><button class="btn btn-default">Se connecter</button></a>
  <a href="index.php"> Retourner au quiz</a>
<?php
}
else
{
  echo "<h2>Quiz</h2>";
  if(!$_SESSION['launchboard']){
    echo "<p>Vous n'avez pas les droits pour accéder à cette partie. <a href='".$url."' class='btn btn-default pull-right'>Accueil</a></p>";
  }else{ ?>
  <div class="boutons_nav" style="display: flex; justify-content: center;">
    <a href="ajout.php" class="bouton_menu" style="margin-right:20%">Ajout</a>
    <a href="suppression.php" class="bouton_menu bouton_nav_selected">Modification/Suppression</a>
  </div>
  <?php
  if(!(isset($_GET['id']))){?>
    <h2>Quiz</h2>
    <h4>OUPS... Votre session est inconnu.</h4>
    <a href="index.php"> Retourner au quiz</a>
  <?php }else{
    $query = $bdd -> prepare('SELECT * FROM formation_question WHERE id = ?');
    $query -> execute(array($_GET['id']));
    $Data=$query -> fetch(); ?>

    <form action="suppression.php" method="post" style="margin-top:20px;" enctype="multipart/form-data">
      <div class="form-group">
        <label>Question n°</label>
        <input class="form-control" type="number" name="ordre2" value="<?php echo $Data['ordre']; ?>">
        <input type="hidden" name="ordre1" value="<?php echo $Data['ordre']; ?>" >
      </div>
    	<div class="form-group">
      <label>Type</label>
      <select name="type" class="form-control" >
      <option value="0" <?php if(!$Data['type']){echo "selected";} ?>>MOD</option>
      <option value="1" <?php if($Data['type']){echo "selected";} ?>>MOI</option>
      </select>
    	<label>Titre</label>
    	<input class="form-control" name="titre" type="text" value="<?php echo $Data['titre']; ?>">
    	</div>
    	<div class="form-group">
    	<label>Question</label>
    	<input class="form-control" name="question" type="text" value="<?php echo $Data['question']; ?>">
    	</div>
    	<div class="form-group">
    		<label>Réponse 1 :     </label><label style="margin-left:20px">
          <input type="hidden" name="vrai1" value="0">
          <input name="vrai1" type="checkbox" value="1" <?php if($Data['corrige_1']){echo "checked" ;}?>> Vrai</label>
        <?php
        if(preg_match('#^img=#',$Data['reponse_1'])){
          $id=substr($Data['reponse_1'],4);
          $query= $bdd -> prepare('SELECT * FROM files WHERE id= ?');
          $query -> execute(array($id));
          $img= $query -> fetch(); ?>
          <img src="<?php echo $img['chemin']; ?>" style="max-width:60%; max-height: 300px;">
           <?php
        }else{ ?>
          <input name="reponse1" class="form-control" type="text" value="<?php echo $Data['reponse_1']; ?>" >
      <?php  } ?>
    	</div>
    	<div class="form-group">
    		<label>Réponse 2 :     </label><label style="margin-left:20px">
          <input type="hidden" name="vrai2" value="0">
          <input name="vrai2" type="checkbox" value="1" <?php if($Data['corrige_2']){echo "checked" ;}?>> Vrai</label>
          <?php
          if(preg_match('#^img=#',$Data['reponse_2'])){
            $id=substr($Data['reponse_2'],4);
            $query= $bdd -> prepare('SELECT * FROM files WHERE id= ?');
            $query -> execute(array($id));
            $img= $query -> fetch(); ?>
            <img src="<?php echo $img['chemin']; ?>" style="max-width:60%; max-height: 300px;">
             <?php
          }else{ ?>
            <input name="reponse2" class="form-control" type="text" value="<?php echo $Data['reponse_2']; ?>" >
          <?php  } ?>    	</div>
    	<div class="form-group">
    		<label>Réponse 3 :     </label><label style="margin-left:20px">
          <input type="hidden" name="vrai3" value="0">
          <input name="vrai3" type="checkbox" value="1" <?php if($Data['corrige_3']){echo "checked" ;}?>> Vrai</label>
          <?php
          if(preg_match('#^img=#',$Data['reponse_3'])){
            $id=substr($Data['reponse_3'],4);
            $query= $bdd -> prepare('SELECT * FROM files WHERE id= ?');
            $query -> execute(array($id));
            $img= $query -> fetch(); ?>
            <img src="<?php echo $img['chemin']; ?>" style="max-width:60%; max-height: 300px;">
             <?php
          }else{ ?>
            <input name="reponse3" class="form-control" type="text" value="<?php echo $Data['reponse_3']; ?>" >
          <?php  } ?>    	</div>
    	<div class="form-group">
    		<label>Réponse 4 :     </label><label style="margin-left:20px">
          <input type="hidden" name="vrai4" value="0">
          <input name="vrai4" type="checkbox"value="1" <?php if($Data['corrige_4']){echo "checked" ;}?>> Vrai</label>
          <?php
          if(preg_match('#^img=#',$Data['reponse_4'])){
            $id=substr($Data['reponse_4'],4);
            $query= $bdd -> prepare('SELECT * FROM files WHERE id= ?');
            $query -> execute(array($id));
            $img= $query -> fetch(); ?>
            <img src="<?php echo $img['chemin']; ?>" style="max-width:60%; max-height: 300px;">
             <?php
          }else{ ?>
            <input name="reponse4" class="form-control" type="text" value="<?php echo $Data['reponse_4']; ?>" >
          <?php  } ?>    	</div>
      <div class"form-group">
        <div class="row">
          <div class="col-md-7">
            <label>Image de correction</label>
            <input type="file" name="fichier"><br/><br/>
            <?php if($Data['image_correction'] != NULL){ ?>
            <input type="submit" name="img-reset" value="Supprimer l'image" class="btn btn-default">
          <?php } ?>
          </div>
          <div class="col-md-5">
        <?php
          if($Data['image_correction'] != NULL){
            $query= $bdd -> prepare('SELECT * FROM files WHERE id= ?');
            $query -> execute(array($Data['image_correction']));
            $img= $query -> fetch(); ?>
            <img src="<?php echo $img['chemin']; ?>" style="max-width:100%; max-height:200px; " alt="Image de correction">
          <?php } ?>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label>Commentaire</label>
        <input type="text" name="commentaire" class="form-control" value="<?php echo $Data['commentaire']; ?>">
      </div>
      <input type="hidden" name="id" value="<?php echo $Data["id"] ?>" >
      <button type="submit" name="modifier" class="btn btn-default" onclick="return confirm('Modifier la question ?');">Modifier</button>
      <button type="submit" name="supprimer" class="btn btn-default" onclick="return confirm('Supprimer la question ?');">Supprimer</button>
      <a href="suppression.php" class="btn btn-default pull-right" >Retour</a>

    </form>




<?php
}
}}
?>





<?php
drawFooter();
