<?php
include_once "../needed.php";
include_once "../../needed.php";
drawheader('dojo_qualite');
drawMenu('RR');

if(empty($_SESSION['login']))
{ ?>
  <h2>Statistiques</h2>
  <h4>Vous devez être connecté en tant qu'administrateur pour accéder à cette partie.</h4>
  <a href="/identification.php?redirection=dojo_qualite/RR/statistiques.php"><button class="btn btn-default">Se connecter</button></a>
  <a href="index.php" class="btn btn-default">R&amp;R</a>
<?php
}
else
{
  $Query = $bdd->prepare('SELECT * FROM qualite_RR_reponse
    JOIN qualite_RR_session ON qualite_RR_reponse.session=qualite_RR_session.id
    JOIN profil ON qualite_RR_session.personne=profil.id
    WHERE profil.supprime = 0 AND qualite_RR_reponse.question = ? ');
    $Query->execute(array($_GET["id"]));

    $Query2 = $bdd->prepare('SELECT * FROM qualite_RR_question
      WHERE qualite_RR_question.id = ? ');
      $Query2->execute(array($_GET["id"]));

      $total_rep = 0;
      $rep1=0;
      $v1=0;
      $img=NULL;
      $ordre=0;

      while ($Data2 = $Query2->fetch()) {
        $question=$Data2['question'];
        if($Data2['valide']==1) {
          $v1=1;
        }
        $queryimg = $bdd -> prepare('SELECT * FROM files WHERE id= ?');
        $queryimg -> execute(array($Data2['image']));
        $img = $queryimg -> fetch();
        $ordre=$Data2['ordre'];

      }

      while ($Data = $Query->fetch()) {
        if ($Data['vrai_1']==1) {
          $rep1=$rep1 + 1;
        }
        $total_rep=$total_rep +1;

      }
      ?>
<h2> Statistiques liés à la question <?php echo $ordre; ?> </h2>

<table class="table">
<thead class="thead">
<tr>
    <th style="width: 150px;">Question</th>
    <th>Image</th>
    <th>Pourcentage de gens ayant eu juste</th>
    <th>Réponse attendue</th>

</tr>
</thead>

<tbody>


<tr>
  <td><?php echo $question; ?></td>
  <td><img src="<?php echo $img['chemin']; ?>" alt="Image" width=200px;></td>
  <td><?php if ($total_rep>0) { echo (floatval($rep1)/$total_rep)*100; echo "%"; }
            else { echo "0%" ; }  ?></td>
  <td> <?php  if ($v1==1){?>
                <img src="ressources/checked.png" style="height: 40px; margin: 20px auto;" class="center-block">
      <?php    }else{?> <img src="ressources/cancel.png" style="height: 40px; margin: 20px auto;" class="center-block"> <?php } ?></td>
</tr>

</tbody>
</table>

<h4> Total Reponses: <?php echo $total_rep ?></h4><br/>
<a href="statistiques.php" class="btn btn-default">Retour</a>

<?php
}
drawFooter();
?>
