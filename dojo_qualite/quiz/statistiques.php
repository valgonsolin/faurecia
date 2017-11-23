<?php
include_once "../needed.php";
include_once "../../needed.php";
drawheader('dojo_qualite');
drawMenu('quiz');
?>

<h2> Statistiques </h2>

<table class="table">
<thead class="thead">
<tr>
    <th style="width: 150px;">Numéro de question</th>
    <th>Titre</th>
    <th>Question</th>
    <th>Total réponses</th>
    <th>Taux de réussite</th>
</tr>
</thead>
<tbody>


<?php

$Query = $bdd->query('SELECT id,titre,question FROM qualite_quiz_question ');
$proportion_bonne_reponse_id = [];
while ($Data = $Query->fetch()) {
  $identifiant = $Data['id'];
  $ancien_titre = $Data['titre'];
  $question=$Data['question'];
  $tot_reponse_id = 0;
  $bonne_reponse_id = 0;

  $Query2 = $bdd->prepare('SELECT * FROM qualite_quiz_reponse
  LEFT JOIN qualite_quiz_question ON qualite_quiz_question.id = qualite_quiz_reponse.question
  WHERE qualite_quiz_question.id = ? ');
  $Query2->execute(array($identifiant));

  while ($Data2 = $Query2->fetch()) {
      $valide =   $Data2['vrai_1']==$Data2['corrige_1'] &&
        $Data2['vrai_2']==$Data2['corrige_2'] &&
        $Data2['vrai_3']==$Data2['corrige_3'] &&
        $Data2['vrai_4']==$Data2['corrige_4'];

      if ($valide){
        $bonne_reponse_id += 1;
      }
      $tot_reponse_id +=1;
    }

  array_push($proportion_bonne_reponse_id, array ($identifiant, $ancien_titre, $question, $bonne_reponse_id, $tot_reponse_id));
}
?>

<?php
foreach ($proportion_bonne_reponse_id as $element){
?>

<tr>
    <td><?php echo $element[0];?></td>
    <td><?php echo $element[1];?></td>
    <td><?php echo $element[2];?></td>
    <td><?php echo $element[4];?></td>
    <td><?php echo (floatval($element[3])/$element[4])*100; echo "%"; ?></td>
</tr>
<?php
}
?>
</tbody>
</table>


<?php
drawFooter(); ?>
