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
      if(! isset($_GET['id'])){
        ?>
        <h4>Oups.. Votre session est inconnue.</h4>
        <a href="gestion.php" class="btn btn-default">Retour</a>
        <a href="<?php echo $url; ?>" class="btn btn-default pull-right">Accueil</a>
        <?php
      }else{
        $query = $bdd -> prepare('SELECT * FROM codir_kamishibai WHERE id= ?');
        $query -> execute(array($_GET['id']));
        $Data = $query -> fetch();
      ?>
      <form method="post" action="gestion.php">
        <div class="form-group">
          <label>Titre :</label>
          <input type="text" class="form-control" name="titre" value="<?php echo $Data['titre']; ?>">
        </div>
        <div class="form-group">
          <label>Question 1 :</label>
          <input type="text" class="form-control" name="question1" value="<?php echo $Data['question1']; ?>">
        </div>
        <div class="form-group">
          <label>Question 2 :</label>
          <input type="text" class="form-control" name="question2" value="<?php echo $Data['question2']; ?>">
        </div>
        <div class="form-group">
          <label>Question 3 :</label>
          <input type="text" class="form-control" name="question3" value="<?php echo $Data['question3']; ?>">
        </div>
        <div class="form-group">
          <label>Question 4 :</label>
          <input type="text" class="form-control" name="question4" value="<?php echo $Data['question4']; ?>">
        </div>
        <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
        <input type="submit" value="Modifier" name="update" class="btn btn-default" onclick="return confirm('Modifier la question ?');">
        <input type="submit" value="Supprimer" name="delete" class="btn btn-default" onclick="return confirm('Supprimer la question ?');">
        <a href="gestion.php" class="btn btn-default pull-right">Retour</a>
      </form>

<?php    }}
?>


<?php
}
drawFooter();
