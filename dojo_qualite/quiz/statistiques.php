<?php
include_once "../needed.php";
include_once "../../needed.php";
drawheader('dojo_qualite');
drawMenu('quiz');


if(empty($_SESSION['login']))
{ ?>
  <h2>Statistiques</h2>
  <h4>Vous devez être connecté pour accéder à cette partie.</h4>
  <a href="/moncompte/identification.php?redirection=dojo_qualite/quiz/statistiques.php"><button class="btn btn-default">Se connecter</button></a>
  <a href="index.php" class="btn btn-default">Quiz</a>
<?php
}
else
{
  echo "<h2> Statistiques </h2>";
  if(!$_SESSION['qualite']){
    echo "<p>Vous n'avez pas les droits pour accéder à cette partie. <a href='".$url."' class='btn btn-default pull-right'>Accueil</a></p>";
  }else{?>
<div id="lien_page">
<div class="boutons_nav" style="display: flex; justify-content: center;">
  <a href="#lien_page" class="bouton_menu bouton_nav_selected" style="margin-right:20%">Statistiques par questions</a>
  <a href="statistiques_personne.php" class="bouton_menu">Statistiques par personnes</a>
</div>
</div>
<br/>

<?php
$Qy = $bdd->query('SELECT COUNT(*) as nombre_total FROM profil WHERE supprime = 0');
$n=$Qy->fetch();
?>

<?php
$Qy2 = $bdd->query('SELECT DISTINCT personne FROM qualite_quiz_session WHERE valide=1 ');
$nombre_valide=0;
while($n2 = $Qy2->fetch()){
  $nombre_valide+=1; }
?>
<h3>Sur un total de <?php echo $n['nombre_total']; ?> personnes <?php echo $nombre_valide; ?> ont validé le quiz, soit <?php echo(floatval($nombre_valide)/$n['nombre_total'])*100; echo "%"; ?> de taux de réussite </h3>

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
  JOIN qualite_quiz_session ON qualite_quiz_reponse.session=qualite_quiz_session.id
  JOIN profil ON qualite_quiz_session.personne=profil.id
  WHERE profil.supprime = 0 AND qualite_quiz_reponse.question = ? ');

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

  <tr class="clickable" onclick="window.location='statistiques_details.php?id=<?php echo $element[0]; ?>';" title="Cliquez ici pour accéder aux statistiques de la question">
    <td><?php echo $element[0];?></td>
    <td><?php echo $element[1];?></td>
    <td><?php echo $element[2];?></td>
    <td><?php echo $element[4];?></td>
    <td><?php if ($element[4]>0) { echo round(((floatval($element[3])/$element[4])*100),2); echo "%"; }
              else { echo "0%" ; }  ?> </td>
</tr>
<?php
}
?>
</tbody>
</table>


<?php
}
}
drawFooter(); ?>
