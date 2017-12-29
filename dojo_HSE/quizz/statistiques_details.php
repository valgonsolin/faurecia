<?php
include_once "../needed.php";
include_once "../../needed.php";
drawheader('dojo_hse');
drawMenu('quizz');

if(empty($_SESSION['login']))
{ ?>
  <h2>Statistiques</h2>
  <h4>Vous devez être connecté pour accéder à cette partie.</h4>
  <a href="/moncompte/identification.php?redirection=dojo_HSE/quizz/statistiques.php"><button class="btn btn-default">Se connecter</button></a>
  <a href="index.php" class="btn btn-default">Quiz</a>
<?php
}
else
{
  if(!$_SESSION['hse']){
    echo "<h2>Statistiques</h2>";
    echo "<p>Vous n'avez pas les droits pour accéder à cette partie. <a href='".$url."' class='btn btn-default pull-right'>Accueil</a></p>";
  }else{?>

<h2> Statistiques liés à la question <?php echo $_GET['id'] ?> </h2>

<table class="table">
<thead class="thead">
<tr>
    <th style="width: 150px;">Question</th>
    <th>Reponse1 </th>
    <th>Reponse2 </th>
    <th>Reponse3 </th>
    <th>Reponse4 </th>

</tr>
</thead>

<tbody>

<?php

$Query = $bdd->prepare('SELECT * FROM qualite_hse_reponse
    JOIN qualite_hse_session ON qualite_hse_reponse.session=qualite_hse_session.id
    JOIN profil ON qualite_hse_session.personne=profil.id
    WHERE profil.supprime = 0 AND qualite_hse_reponse.question = ? ');
$Query->execute(array($_GET["id"]));

$Query2 = $bdd->prepare('SELECT * FROM qualite_hse_question
    WHERE qualite_hse_question.id = ? ');
$Query2->execute(array($_GET["id"]));

$total_rep = 0;
$rep1=0;
$rep2=0;
$rep3=0;
$rep4=0;
$v1=0;
$v2=0;
$v3=0;
$v4=0;

while ($Data2 = $Query2->fetch()) {
    $q1=$Data2['reponse_1'];
    $q2=$Data2['reponse_2'];
    $q3=$Data2['reponse_3'];
    $q4=$Data2['reponse_4'];
    $question=$Data2['question'];
    if($Data2['corrige_1']==1) {
      $v1=1;
    }
    if($Data2['corrige_2']==1) {
      $v2=1;
    }
    if($Data2['corrige_3']==1) {
      $v3=1;
    }
    if($Data2['corrige_4']==1) {
      $v4=1;
    }
}

while ($Data = $Query->fetch()) {
  if ($Data['vrai_1']==1) {
    $rep1=$rep1 + 1;
  }
  if ($Data['vrai_2']==1) {
    $rep2=$rep2 + 1;
  }
  if ($Data['vrai_3']==1) {
    $rep3=$rep3 + 1;
  }
  if ($Data['vrai_4']==1) {
    $rep4=$rep4 + 1;
  }
  $total_rep=$total_rep +1;

}

?>

<tr>
  <td><?php echo $question; ?></td>
  <td><?php echo $q1 ?>  </td>
  <td><?php echo $q2 ?>  </td>
  <td><?php echo $q3 ?> </td>
  <td><?php echo $q4 ?> </td>
</tr>

<tr>
  <td> Pourcentage des participants qui ont choisi cette reponse:  </td>
  <td><?php if ($total_rep>0) { echo (floatval($rep1)/$total_rep)*100; echo "%"; }
            else { echo "0%" ; }  ?> </td>
  <td><?php if ($total_rep>0) { echo (floatval($rep2)/$total_rep)*100; echo "%"; }
            else { echo "0%" ; }  ?> </td>
  <td><?php if ($total_rep>0) { echo (floatval($rep3)/$total_rep)*100; echo "%"; }
            else { echo "0%" ; }  ?> </td>
  <td><?php if ($total_rep>0) { echo (floatval($rep4)/$total_rep)*100; echo "%"; }
            else { echo "0%" ; }  ?> </td>
</tr>


<tr>
  <td> Reponse attendue:  </td>
  <td> <?php  if ($v1==1){?>
                <img src="ressources/checked.png" style="height: 40px; margin: 20px auto;" class="center-block">
      <?php    }else{?> <img src="ressources/cancel.png" style="height: 40px; margin: 20px auto;" class="center-block"> <?php } ?>

  </td>
  <td> <?php  if ($v2==1){?>
                <img src="ressources/checked.png" style="height: 40px; margin: 20px auto;" class="center-block">
      <?php    }else{?> <img src="ressources/cancel.png" style="height: 40px; margin: 20px auto;" class="center-block"> <?php } ?>

  </td>
  <td> <?php  if ($v3==1){?>
                <img src="ressources/checked.png" style="height: 40px; margin: 20px auto;" class="center-block">
      <?php    }else{?> <img src="ressources/cancel.png" style="height: 40px; margin: 20px auto;" class="center-block"> <?php } ?>

  </td>
  <td> <?php  if ($v4==1){?>
                <img src="ressources/checked.png" style="height: 40px; margin: 20px auto;" class="center-block">
      <?php    }else{?> <img src="ressources/cancel.png" style="height: 40px; margin: 20px auto;" class="center-block"> <?php } ?>

  </td>
</tr>

</tbody>
</table>

<h4> Total Reponses: <?php echo $total_rep ?></h4><br/>
<a href="statistiques.php" class="btn btn-default">Retour</a>

<?php
} }
drawFooter();
?>
