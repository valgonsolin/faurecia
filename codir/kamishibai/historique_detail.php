<?php
include_once "../../needed.php";

include_once "../needed.php";

drawheader('codir');
drawMenu("kamishibai");

if(empty($_SESSION['login']))
{ ?>
  <h2>Kamishibai</h2>
  <h4>Vous devez être connecté pour accéder à cette partie.</h4>
  <a href="/moncompte/identification.php?redirection=codir/kamishibai"><button class="btn btn-default">Se connecter</button></a>
  <a href="<?php echo $url; ?>" class="btn btn-default">Accueil</a>
<?php
}
else
{
    echo "<h2>kamishibai</h2>";
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
        $query = $bdd -> prepare('SELECT * FROM codir_kamishibai LEFT JOIN codir_kamishibai_reponse ON codir_kamishibai_reponse.kamishibai = codir_kamishibai.id WHERE codir_kamishibai.id = ? ORDER BY codir_kamishibai_reponse.date_cloture');
        $query -> execute(array($_GET['id']));
        $Data = $query -> fetch();
        echo "<h4>".$Data['titre']."</h4>";
        if(is_null($Data['kamishibai'])){
          echo "<p>Il n'y a aucune carte.</p>";
        }else{ ?>
          <style>
          .card{
            background-color: rgb(239, 239, 239);
            border-radius: 6px;
            margin-bottom: 15px;
            box-shadow: rgb(128, 128, 128) 2px 2px 4px 0px;
          }
          </style>
          <p>Dernière réponse :</p>
          <div class="row">
            <div class="col-md-6">
              <div class="col-md-12 card">
                <h4>Question 1 :</h4>
                <p><?php echo $Data['question1']; ?></p>
                <p>Réponse : <?php if($Data['reponse1']){echo "Oui";}else{echo "Non";} ?></p>
                <p>Commentaire :</p>
                <p><?php echo $Data['commentaire1']; ?></p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="col-md-12 card">
                <h4>Question 2 :</h4>
                <p><?php echo $Data['question2']; ?></p>
                <p>Réponse : <?php if($Data['reponse2']){echo "Oui";}else{echo "Non";} ?></p>
                <p>Commentaire :</p>
                <p><?php echo $Data['commentaire2']; ?></p>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="col-md-12 card">
                <h4>Question 3 :</h4>
                <p><?php echo $Data['question3']; ?></p>
                <p>Réponse : <?php if($Data['reponse3']){echo "Oui";}else{echo "Non";} ?></p>
                <p>Commentaire :</p>
                <p><?php echo $Data['commentaire3']; ?></p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="col-md-12 card">
                <h4>Question 4 :</h4>
                <p><?php echo $Data['question4']; ?></p>
                <p>Réponse : <?php if($Data['reponse4']){echo "Oui";}else{echo "Non";} ?></p>
                <p>Commentaire :</p>
                <p><?php echo $Data['commentaire4']; ?></p>
              </div>
            </div>
          </div>

        <?php
      }

?>

<?php    }}
?>


<?php
}
drawFooter();
