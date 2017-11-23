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
    <a href="ajout.php" class="bouton_menu bouton_nav_selected" style="margin-right:20%">Ajout</a>
    <a href="suppression.php" class="bouton_menu">Suppression</a>
  </div>

  <form method="post" style="margin-top:20px;">
  	<div class="form-group">
  	<label>Type</label>
  	<select name="type" class="form-control">
  		<option value="0" selected="selected">MOD</option>
  		<option value="1">Autre</option>
  	</select>
  	<label>Titre</label>
  	<input class="form-control" name="titre" type="text">
  	</div>
  	<div class="form-group">
  	<label>Question</label>
  	<input class="form-control" name="question" type="text">
  	</div>
  	<div class="form-group">
  		<label>Réponse 1 :     </label><label style="margin-left:20px"><input name="vrai1" type="checkbox"> Vrai</label>
  		<input name="reponse1" class="form-control" type="text">
  	</div>
  	<div class="form-group">
  		<label>Réponse 2 :     </label><label style="margin-left:20px"><input name="vrai2" type="checkbox"> Vrai</label>
  		<input name="reponse2" class="form-control" type="text">
  	</div>
  	<div class="form-group">
  		<label>Réponse 3 :     </label><label style="margin-left:20px"><input name="vrai3" type="checkbox"> Vrai</label>
  		<input name="reponse3" class="form-control" type="text">
  	</div>
  	<div class="form-group">
  		<label>Réponse 4 :     </label><label style="margin-left:20px"><input name="vrai4" type="checkbox"> Vrai</label>
  		<input name="reponse4" class="form-control" type="text">
  	</div>
  	<input value="Ajouter" class="btn btn-default" type="submit">


  </form>


<?php
}
?>





<?php
drawFooter();
 ?>
