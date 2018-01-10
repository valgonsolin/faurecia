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
      if(isset($_POST['update'])){
        $query = $bdd -> prepare('UPDATE codir_kamishibai SET titre = :titre, question1 = :question1, question2 = :question2, question3 = :question3, question4 = :question4 WHERE id = :id');
        if($query -> execute(array(
          'titre' => $_POST['titre'],
          'question1' => $_POST['question1'],
          'question2' => $_POST['question2'],
          'question3' => $_POST['question3'],
          'question4' => $_POST['question4'],
          'id' => $_POST['id']
        ))){
          success('Modifiée','La carte a bie été modifiée.');
        }else{
          warning('Erreur','Les données ne sont pas conformes.');
        }
      }
      if(isset($_POST['delete'])){
        $query = $bdd -> prepare('DELETE FROM codir_kamishibai WHERE id = :id');
        if($query -> execute(array(
          'id' => $_POST['id']
        ))){
          success('Supprimée','La carte a bie été supprimée.');
        }else{
          warning('Erreur','Veuillez réessayer.');
        }
      }

      $recherche = "";

      if (isset($_GET["recherche"])){
          $recherche = $_GET["recherche"];
      } ?>

      <form class="form-inline">
        <div class="form-group">
          <label for="recherche">Recherche :</label>
          <input type="text" class="form-control" name = "recherche" id="recherche" placeholder="Carte" value="<?php echo $recherche;?>">
        </div>
        <button type="submit" class="btn btn-default">Rechercher</button>
        <a class="btn btn-default pull-right" href="ajout.php">Ajouter une carte</a>
      </form>
      <table class="table">
      <thead class="thead">
      <tr>
          <th>Titre</th>
          <th></th>
      </tr>
      </thead>
      <tbody>

    <?php
    $nb=0;
    if(isset($_GET['nb'])){
    $nb=$_GET['nb'];
    }
      $query=$bdd -> prepare('SELECT * FROM codir_kamishibai WHERE (titre LIKE :titre) LIMIT 20 OFFSET :nb');
      $query ->bindValue(':titre','%'.$recherche.'%');
      $query ->bindValue(':nb',(int) $nb, PDO::PARAM_INT);
      $query ->execute();
      while($Data = $query->fetch()){

      ?>
        <tr>
          <td><?php echo $Data['titre']; ?></td>
          <td align="right"><a href="carte.php?id=<?php echo $Data['id']?>" class="btn btn-default">Modifier</a></td>
        </tr>
      <?php
    } ?>
    </tbody>

    </table>
    <?php

    $test = $bdd->prepare('SELECT * FROM codir_kamishibai WHERE (titre LIKE :titre) LIMIT 1 OFFSET :nb');
    $test ->bindValue(':titre','%'.$recherche.'%');
    $test ->bindValue(':nb',(int) $nb+20, PDO::PARAM_INT);
    $test->execute(); ?>
    <form method="post" class="inline-form"> <?php
      if($nb > 19){    ?>
          <a href="gestion.php?recherche=<?php echo $recherche;?>&amp;nb=<?php echo $nb-20;?>" class="btn btn-default">Elements précédents</a>
        <?php
        }
        if($test -> fetch()){ ?>
        <a href="gestion.php?recherche=<?php echo $recherche;?>&amp;nb=<?php echo $nb+20;?>" class="btn btn-default">Elements suivants</a>
      <?php } ?>
        <span class="clear" style="clear: both; display: block;"></span>
      </form>
    <?php


    }
?>


<?php
}
drawFooter();
