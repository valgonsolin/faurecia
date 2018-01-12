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
        $Data = $query -> fetchAll();
        echo "<h4>".$Data[0]['titre']."</h4>";
        if(is_null($Data[0]['8'])){
          echo "<p>Il n'y a aucune carte.</p>";
        }else{
          $q1=0;
          $q2=0;
          $q3=0;
          $q4=0;
          $tot=0;
          foreach ($Data as $row) {
            $q1+=$row['reponse1'];
            $q2+=$row['reponse2'];
            $q3+=$row['reponse3'];
            $q4+=$row['reponse4'];
            $tot+=1;
          }

          ?>
          <style>
          .card{
            background-color: rgb(239, 239, 239);
            border-radius: 6px;
            padding: 0px 5px 5px 5px;
            box-shadow: rgb(128, 128, 128) 1px 1px 2px 0px;
          }
          .col-md-3, .col-md-6{
            padding:5px;
          }
          </style>
          <div class="row">
            <div class="col-md-3">
              <div class="card">
                <h4>Question 1 :   </h4>
                <p><?php echo $Data[0]['question1']; ?></p>
                <b>Oui : <?php echo round(((floatval($q1)/$tot)*100),2); ?>%  &emsp; Non : <?php echo round(((1-floatval($q1)/$tot)*100),2); ?>%</b>
              </div>
            </div>
            <div class="col-md-3">
              <div class="card">
                <h4>Question 2 :   </h4>
                <p><?php echo $Data[0]['question2']; ?></p>
                <b>Oui : <?php echo round(((floatval($q2)/$tot)*100),2); ?>%  &emsp; Non : <?php echo round(((1-floatval($q2)/$tot)*100),2); ?>%</b>
              </div>
            </div>
            <div class="col-md-3">
              <div class="card">
                <h4>Question 3 :   </h4>
                <p><?php echo $Data[0]['question3']; ?></p>
                <b>Oui : <?php echo round(((floatval($q3)/$tot)*100),2); ?>%  &emsp; Non : <?php echo round(((1-floatval($q3)/$tot)*100),2); ?>%</b>
              </div>
            </div>
            <div class="col-md-3">
              <div class="card">
                <h4>Question 4 :   </h4>
                <p><?php echo $Data[0]['question4']; ?></p>
                <b>Oui : <?php echo round(((floatval($q4)/$tot)*100),2); ?>%  &emsp; Non : <?php echo round(((1-floatval($q4)/$tot)*100),2); ?>%</b>
              </div>
            </div>
          </div>
          <hr>
          <table class="table">
            <thead class="thead">
              <tr>
                  <th>Nom</th>
                  <th>Prenom</th>
                  <th>Question 1</th>
                  <th>Question 2</th>
                  <th>Question 3</th>
                  <th>Question 4</th>
                  <th width=130px>Date cloture</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($Data as $id => $row) {
                ?>
                <tr class="clickable" data-toggle="modal" data-target="#modal<?php echo $id; ?>" title="Cliquez pour voir les commentaires">
                  <td><?php echo $row['nom']; ?></td>
                  <td><?php echo $row['prenom']; ?></td>
                  <td><?php echo int_to_vrai_faux($row['reponse1']); ?></td>
                  <td><?php echo int_to_vrai_faux($row['reponse2']); ?></td>
                  <td><?php echo int_to_vrai_faux($row['reponse3']); ?></td>
                  <td><?php echo int_to_vrai_faux($row['reponse4']); ?></td>
                  <td><?php echo $row['date_cloture']; ?></td>
                <tr>
                <div id="modal<?php echo $id; ?>" class="modal fade" role="dialog">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header" style="padding-bottom:0;">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title" style="text-align:center;">Carte de <?php echo $row['nom']."  ".$row['prenom']; ?></h4>
                        <h4 style="margin-bottom:5px;"><a target=_blank class="btn btn-default" href="imprimer_fiche.php?id=<?php echo $row['7']; ?>">Imprimer</a><small style=" float:right;"><?php echo $row['date_cloture']; ?></small></h4>
                      </div>
                      <div class="modal-body" style="padding:20px;">
                        <div class="row">
                          <div class="col-md-6">
                            <div class="col-md-12 card">
                              <h4>Question 1 :</h4>
                              <p><?php echo $row['question1']; ?></p>
                              <p>Réponse : <b><?php if($row['reponse1']){echo "Oui";}else{echo "Non";} ?></b></p>
                              <p>Commentaire :</p>
                              <p><?php echo $row['commentaire1']; ?></p>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="col-md-12 card">
                              <h4>Question 2 :</h4>
                              <p><?php echo $row['question2']; ?></p>
                              <p>Réponse : <b><?php if($row['reponse2']){echo "Oui";}else{echo "Non";} ?></b></p>
                              <p>Commentaire :</p>
                              <p><?php echo $row['commentaire2']; ?></p>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="col-md-12 card">
                              <h4>Question 3 :</h4>
                              <p><?php echo $row['question3']; ?></p>
                              <p>Réponse : <b><?php if($row['reponse3']){echo "Oui";}else{echo "Non";} ?></b></p>
                              <p>Commentaire :</p>
                              <p><?php echo $row['commentaire3']; ?></p>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="col-md-12 card">
                              <h4>Question 4 :</h4>
                              <p><?php echo $row['question4']; ?></p>
                              <p>Réponse : <b><?php if($row['reponse4']){echo "Oui";}else{echo "Non";} ?></b></p>
                              <p>Commentaire :</p>
                              <p><?php echo $row['commentaire4']; ?></p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

            <?php  }
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
