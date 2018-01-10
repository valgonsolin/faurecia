<?php
include_once "../../needed.php";

include_once "../needed.php";

drawheader('codir');
drawMenu("gestion");

if(empty($_SESSION['login']))
{ ?>
  <h2>Gestion Kamishibai</h2>
  <h4>Vous devez être connecté pour accéder à cette partie.</h4>
  <a href="/moncompte/identification.php?redirection=codir/kamishibai"><button class="btn btn-default">Se connecter</button></a>
  <a href="<?php echo $url; ?>" class="btn btn-default">Accueil</a>
<?php
}
else
{
    echo "<h2>Gestion kamishibai</h2>";
    if(!$_SESSION['kamishibai']){
        echo "<p>Vous n'avez pas les droits pour accéder à cette partie. <a href='".$url."' class='btn btn-default pull-right'>Accueil</a></p>";
    }else{
      if(! empty($_POST)){
        $query = $bdd -> prepare('INSERT INTO codir_kamishibai(titre , question1, question2, question3, question4) VALUES (:titre, :question1, :question2, :question3, :question4)');
        if($query -> execute(array(
          'titre' => $_POST['titre'],
          'question1' => $_POST['question1'],
          'question2' => $_POST['question2'],
          'question3' => $_POST['question3'],
          'question4' => $_POST['question4']
        ))){
          success('Ajoutée','La carte a bien été ajoutée.');
        }else{
          warning('Erreur','Les données ne sont pas conformes.');
        }
      }
      ?>
      <form method="post">
        <div class="form-group">
          <label>Titre :</label>
          <input type="text" class="form-control" name="titre">
        </div>
        <div class="form-group">
          <label>Question 1 :</label>
          <input type="text" class="form-control" name="question1">
        </div>
        <div class="form-group">
          <label>Question 2 :</label>
          <input type="text" class="form-control" name="question2">
        </div>
        <div class="form-group">
          <label>Question 3 :</label>
          <input type="text" class="form-control" name="question3">
        </div>
        <div class="form-group">
          <label>Question 4 :</label>
          <input type="text" class="form-control" name="question4">
        </div>
        <input type="submit" value="Ajouter" class="btn btn-default">
        <a href="gestion.php" class="btn btn-default pull-right">Retour</a>
      </form>

<?php    }
?>


<?php
}
drawFooter();
