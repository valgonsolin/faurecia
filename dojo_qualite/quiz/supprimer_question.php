<?php
include_once "../needed.php";
include_once "../../needed.php";

drawHeader('dojo_qualite');
drawMenu('quiz');

if(empty($_SESSION['login']))
{ ?>
  <h2>Quiz</h2>
  <h4>Vous devez être connecté en tant qu'administrateur pour accéder à cette partie.</h4>
  <a href="/identification.php?redirection=dojo_qualite/quiz/ajout.php"><button class="btn btn-default">Se connecter</button></a>
  <a href="index.php"> Retourner au quizz</a>
<?php
}
else
{?>
  <h2>Quiz</h2>
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
    $query = $bdd -> prepare('SELECT * FROM qualite_quiz_question WHERE id = ?');
    $query -> execute(array($_GET['id']));
    $Data=$query -> fetch(); ?>

    <form action="suppression.php" method="post" style="margin-top:20px;">
      <div class="form-group">
        <label>Question n°</label>
        <input class="form-control" type="number" name="ordre2" value="<?php echo $Data['ordre']; ?>">
        <input type="hidden" name="ordre1" value="<?php echo $Data['ordre']; ?>" >
      </div>
    	<div class="form-group">
      <label>Type</label>
  	  <select name="type" class="form-control" value="<?php echo $Data['type']; ?>">
      <option value="0" selected="selected">MOD</option>
      <option value="1">Autre</option>
      </select>
    	<label>Titre</label>
    	<input class="form-control" name="titre" type="text" value="<?php echo $Data['titre']; ?>">
    	</div>
    	<div class="form-group">
    	<label>Question</label>
    	<input class="form-control" name="question" type="text" value="<?php echo $Data['question']; ?>">
    	</div>
    	<div class="form-group">
    		<label>Réponse 1 :     </label><label style="margin-left:20px"><input name="vrai1" type="checkbox" <?php if($Data['corrige_1']){echo "checked" ;}?>> Vrai</label>
    		<input name="reponse1" class="form-control" type="text" value="<?php echo $Data['reponse_1']; ?>">
    	</div>
    	<div class="form-group">
    		<label>Réponse 2 :     </label><label style="margin-left:20px"><input name="vrai2" type="checkbox" <?php if($Data['corrige_2']){echo "checked" ;}?>> Vrai</label>
    		<input name="reponse2" class="form-control" type="text" value="<?php echo $Data['reponse_2']; ?>">
    	</div>
    	<div class="form-group">
    		<label>Réponse 3 :     </label><label style="margin-left:20px"><input name="vrai3" type="checkbox" <?php if($Data['corrige_3']){echo "checked" ;}?>> Vrai</label>
    		<input name="reponse3" class="form-control" type="text" value="<?php echo $Data['reponse_3']; ?>">
    	</div>
    	<div class="form-group">
    		<label>Réponse 4 :     </label><label style="margin-left:20px"><input name="vrai4" type="checkbox" <?php if($Data['corrige_4']){echo "checked" ;}?>> Vrai</label>
    		<input name="reponse4" class="form-control" type="text" value="<?php echo $Data['reponse_4']; ?>">
    	</div>
      <input type="hidden" name="id" value="<?php echo $Data["id"] ?>" >
      <button type="submit" name="modifier" class="btn btn-default">Modifier</button>
      <button type="submit" name="supprimer" class="btn btn-default">Supprimer</button>


    </form>




<?php
}
}
?>





<?php
drawFooter();
