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
  <?php
  if(isset($_POST['id'])){
    
  }
  if(!(isset($_GET['id']))){?>
    <h2>Quiz</h2>
    <h4>OUPS... Votre session est inconnu.</h4>
    <a href="index.php"> Retourner au quiz</a>
  <?php }else{
    $query = $bdd -> prepare('SELECT * FROM qualite_quiz_question WHERE id = ?');
    $query -> execute(array($_GET['id']));
    $Data=$query -> fetch(); ?>
    <form class="form-horizontal" method="post">
        <div class="form-group" style="margin: 10px;" >
            <label for="code_bar"><?php echo $Data['question']; ?></label>
            <div class="checkbox">
                <label><input type="checkbox" <?php if($Data['corrige_1']){echo "checked" ;}?> ><?php echo $Data['reponse_1']; ?></label>
            </div>
            <div class="checkbox">
                <label><input type="checkbox" <?php if($Data['corrige_2']){echo "checked"; }?> ><?php echo $Data['reponse_2']; ?></label>
            </div>
            <div class="checkbox disabled">
                <label><input type="checkbox" <?php if($Data['corrige_3']){echo "checked"; }?> ><?php echo $Data['reponse_3']; ?></label>
            </div>
            <div class="checkbox disabled">
                <label><input type="checkbox" <?php if($Data['corrige_4']){echo "checked" ;}?> ><?php echo $Data['reponse_4']; ?></label>
            </div>
        </div>
        <input type="hidden" name="id" value="<?php echo $Data["id"] ?>" >

        <button type="submit" class="btn btn-default">Supprimer</button>

    </form>





<?php
}
}
?>





<?php
drawFooter();
