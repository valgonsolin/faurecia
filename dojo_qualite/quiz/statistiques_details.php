<?php
include_once "../needed.php";
include_once "../../needed.php";
drawheader('dojo_qualite');
drawMenu('quiz');
?>

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

$Query = $bdd->prepare('SELECT * FROM qualite_quiz_reponse
    WHERE qualite_quiz_reponse.question = ? ');
$Query->execute(array($_GET["id"]));

$Query2 = $bdd->prepare('SELECT * FROM qualite_quiz_question
    WHERE qualite_quiz_question.id = ? ');
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
    $total_rep=$total_rep +1;
  }
  if ($Data['vrai_2']==1) {
    $rep2=$rep2 + 1;
    $total_rep=$total_rep +1;
  }
  if ($Data['vrai_3']==1) {
    $rep3=$rep3 + 1;
    $total_rep=$total_rep +1;
  }
  if ($Data['vrai_4']==1) {
    $rep4=$rep4 + 1;
    $total_rep=$total_rep +1;
  }

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
  <td> <?php echo (floatval($rep1)/$total_rep)*100; echo "%"; ?> </td>
  <td> <?php echo (floatval($rep2)/$total_rep)*100; echo "%"; ?> </td>
  <td><?php echo (floatval($rep3)/$total_rep)*100; echo "%"; ?> </td>
  <td> <?php echo (floatval($rep4)/$total_rep)*100; echo "%"; ?></td>
</tr>


<tr>
  <td> Reponse atendue:  </td>
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




<?php
drawFooter();
?>
