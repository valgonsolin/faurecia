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
    <th style="width: 150px;">Categorie</th>
    <th>Bonne réréponses</th>
    <th>Total réréponses</th>
    <th>Taux de réussite</th>
</tr>
</thead>
<tbody>


<?php
$ancien_titre = "";
$tot_reponse_cat = 0;
$bonne_reponse_cat = 0;
$proportion_bonne_reponse_cat = [];

$Query = $bdd->query('SELECT * FROM qualite_quiz_reponse
  LEFT JOIN qualite_quiz_question ON qualite_quiz_question.id = qualite_quiz_reponse.question
   ORDER BY qualite_quiz_question.id ASC');

while ($Data = $Query->fetch()) {
  if ($ancien_titre != $Data['titre']){
    if ($ancien_titre != "") {
        array_push($proportion_bonne_reponse_cat, array($ancien_titre, $bonne_reponse_cat, $tot_reponse_cat));
        $tot_reponse_cat = 0;
        $bonne_reponse_cat = 0;
      }
      $ancien_titre = $Data['titre'];
  }
  $valide =   $Data['vrai_1']==$Data['corrige_1'] &&
      $Data['vrai_2']==$Data['corrige_2'] &&
      $Data['vrai_3']==$Data['corrige_3'] &&
      $Data['vrai_4']==$Data['corrige_4'];
  if ($valide){
      $bonne_reponse_cat += 1;
  }
  $tot_reponse_cat +=1;
}

array_push($proportion_bonne_reponse_cat, array($ancien_titre, $bonne_reponse_cat, $tot_reponse_cat));
?>

<?php
foreach ($proportion_bonne_reponse_cat as $categorie){
?>

<tr>
    <td><a href="statistiques_details.php?type=<?php echo $categorie[0]; ?>">  <?php echo $categorie[0];  ?> </a></td>
    <td><?php echo $categorie[1];?></td>
    <td><?php echo $categorie[2];?></td>
    <td><?php echo (floatval($categorie[1])/$categorie[2])*100; echo "%"; ?></td>
</tr>
<?php
}
?>
</tbody>
</table>


<?php
drawFooter(); ?>
