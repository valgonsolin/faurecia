<?php
include_once "../needed.php";
include_once "../../needed.php";
drawheader('dojo_qualite');
drawMenu('quiz');
?>

<h2> Statistiques </h2>
<?php
$Qy = $bdd->query('SELECT COUNT(*) as nombre_total FROM profil ');
$n=$Qy->fetch();
?>

<?php
$Qy2 = $bdd->query('SELECT DISTINCT personne FROM qualite_quiz_session WHERE valide=1 ');
$nombre_valide=0;
while($n2 = $Qy2->fetch()){
  $nombre_valide+=1; }
?>
<h3>Sur un total de <?php echo $n['nombre_total']; ?> personnes <?php echo $nombre_valide; ?> ont validé le quiz, soit <?php echo(floatval($nombre_valide)/$n['nombre_total'])*100; echo "%"; ?> de taux de réussite </h3>


<h5>Cliquez sur l'identifiant d'une question pour accéder aux statistiques qui lui sont associés  </h5>
<br/>

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
  JOIN qualite_quiz_question ON qualite_quiz_question.id = qualite_quiz_reponse.question
  WHERE qualite_quiz_question.id = ? ');
  $Query2->execute(array($identifiant));

  while ($Data2 = $Query2->fetch()) {
      $valide =   $Data2['vrai_1']==$Data2['corrige_1'] &&
        $Data2['vrai_2']==$Data2['corrige_2'] &&
        $Data2['vrai_3']==$Data2['corrige_3'] &&
        $Data2['vrai_4']==$Data2['corrige_4'];

      if ($valide){ $bonne_reponse_id =$bonne_reponse_id + 1; }
      $tot_reponse_id +=1;
    }

  array_push($proportion_bonne_reponse_id, array ($identifiant, $ancien_titre, $question, $bonne_reponse_id, $tot_reponse_id));
}
?>


<?php
foreach ($proportion_bonne_reponse_id as $element){
?>

<tr>
    <td><a href="statistiques_details.php?id=<?php echo $element[0]; ?>"><?php echo $element[0];?></a></td>
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
