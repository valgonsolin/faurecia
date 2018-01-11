<?php
include_once "../../needed.php";

include_once "../needed.php";

drawheader('codir');
drawMenu("kamishibai");

function int_to_vrai_faux($int){
    if($int>0){
        return '<img src="ressources/checked.png" style="height: 20px;" class="center-block">';
    }else{
        return '<img src="ressources/cancel.png" style="height: 20px;" class="center-block">';
    }
}

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
        $query = $bdd -> prepare('SELECT * FROM codir_kamishibai LEFT JOIN codir_kamishibai_reponse ON codir_kamishibai_reponse.kamishibai = codir_kamishibai.id LEFT JOIN profil ON profil.id = codir_kamishibai_reponse.profil WHERE codir_kamishibai.id = ? ORDER BY codir_kamishibai_reponse.date_cloture');
        $query -> execute(array($_GET['id']));
        $Data = $query -> fetch();
        echo "<h4>".$Data['titre']."</h4>";
        if(is_null($Data['kamishibai'])){
          echo "<p>Il n'y a aucune carte.</p>";
        }else{ ?>
          <table class="table">
            <thead class="thead">
              <tr>
                  <th>Nom</th>
                  <th>Prenom</th>
                  <th>Question 1</th>
                  <th>Question 2</th>
                  <th>Question 3</th>
                  <th>Question 4</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><?php echo $Data['nom']; ?></td>
                <td><?php echo $Data['prenom']; ?></td>
                <td><?php echo int_to_vrai_faux($Data['reponse1']); ?></td>
                <td><?php echo int_to_vrai_faux($Data['reponse2']); ?></td>
                <td><?php echo int_to_vrai_faux($Data['reponse3']); ?></td>
                <td><?php echo int_to_vrai_faux($Data['reponse4']); ?></td>
              <tr>
              <?php
              while($Data = $query -> fetch()){

              }
?>
            </tbody>
          </table>

        <?php
      }

?>

<?php    }}
?>


<?php
}
drawFooter();
