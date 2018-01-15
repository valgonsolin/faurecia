<?php
include_once "../../needed.php";

include_once "../needed.php";

drawHeader('methode');
drawMenu('launchboard');
if(empty($_SESSION['login'])){ ?>
  <h2>Launchboard</h2>
  <h4>Vous devez être connecté pour accéder à cette partie.</h4>
  <a href="/moncompte/identification.php?redirection=moncompte/editer_profil.php"><button class="btn btn-default">Se connecter</button></a>
  <a href="/index.php" class="btn btn-default">Accueil</a>
<?php }else{
  if(! $_SESSION['launchboard']){
    echo "<h2>Launchboard</h2>";
    echo "<p>Vous n'avez pas les droits pour accéder à cette page. <a href='".$url."' class='btn btn-default pull-right'>Accueil</a><p>";
  }else{
    echo "<h2>Ajouter un projet</h2>";
    ?>
    <form method="post" action="index.php" enctype="multipart/form-data">
      <div class="row">
        <div class="form-group col-md-12">
          <label>PPTL :</label>
            <select class="form-control" name="profil">
              <?php
              $profil = $bdd -> query('SELECT * FROM profil');
              while($personne = $profil -> fetch()){ ?>
                <option value="<?php echo $personne['id']; ?>"><?php echo $personne['nom']." ".$personne['prenom']; ?></option>
            <?php  } ?>
            </select>
        </div>
      </div>
      <div class="row">
        <div class="form-group col-md-8">
          <label>Titre :</label>
          <input type="text" name="titre" class="form-control">
        </div>
        <div class="form-group col-md-4">
          <label>Code :</label>
          <input type="text" name="code" class="form-control">
        </div>
      </div>
      <div class="row">
        <div class="form-group col-md-8">
          <label>Client :</label>
          <input type="text" name="client" class="form-control">
        </div>
        <div class="form-group col-md-4">
          <label>Image de présentation :</label>
          <input type="file" name="img">
        </div>
      </div>
      <div class="row">
        <div class="form-group col-md-12">
          <label>Description :</label>
          <textarea class="form-control" rows="3" name="description"></textarea>
        </div>
      </div>
      <div class="row">
        <div class="form-group col-md-6">
          <label>Launchbook (xls) :</label>
          <input type="file" name="launchbook">
        </div>
        <div class="form-group col-md-6">
          <label>Kickoff (ppt) :</label>
          <input type="file" name="kickoff">
        </div>
      </div>
      <div class="row">
        <div class="form-group col-md-6">
          <label>Lien Helios :</label>
          <input type="url" name="helios" class="form-control">
        </div>
        <div class="form-group col-md-6">
          <label>Lien PLR :</label>
          <input type="url" name="plr" class="form-control">
        </div>
      </div>



      <input type="submit" class="btn btn-default" name="ajout" value="Ajouter">
      <a href="index.php" class="btn btn-default pull-right">Retour</a>
    </form>
<?php
  }
}
drawFooter();
