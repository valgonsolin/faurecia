<?php
include_once "../needed.php";
include_once "../../needed.php";
drawheader('dojo_qualite');
drawMenu('RR');

if(empty($_SESSION['login']))
{ ?>
  <h2>Statistiques</h2>
  <h4>Vous devez être connecté pour accéder à cette partie.</h4>
  <a href="/moncompte/identification.php?redirection=dojo_qualite/RR/statistiques.php"><button class="btn btn-default">Se connecter</button></a>
  <a href="index.php" class="btn btn-default">Quiz</a>
<?php
}
else
{
  echo "<h2>Statistiques</h2>";
  if(!$_SESSION['rr']){
    echo "<p>Vous n'avez pas les droits pour accéder à cette partie.<a href='".$url."' class='btn btn-default pull-right'>Accueil</a></p>";
  }else{ ?>
<div id="lien_page">
<div class="boutons_nav" style="display: flex; justify-content: center;">
  <a href="statistiques.php" class="bouton_menu " style="margin-right:20%">Statistiques par questions</a>
  <a href="statistiques_personne.php" class="bouton_menu bouton_nav_selected">Statistiques par personnes</a>
</div>
</div>

<table class="table">
<thead class="thead">
<tr>
    <th style="width: 150px;">Identifiant</th>
    <th>Nom</th>
    <th>Prénom</th>
    <th>Total questions traitées</th>
    <th>Taux de bonne reponses</th>
    <th>R&amp;R valide</th>
</tr>
</thead>

<?php
$Query = $bdd->query('SELECT id,nom,prenom FROM profil ');
$proportion_bonne_reponse_id = [];
while ($Data = $Query->fetch()) {
  $identifiant = $Data['id'];
  $nom = $Data['nom'];
  $prenom=$Data['prenom'];
  $tot_reponse_id = 0;
  $bonne_reponse_id = 0;
  $validation=false;

  $Query2 = $bdd->prepare('SELECT * FROM qualite_RR_reponse
  JOIN qualite_RR_question ON qualite_RR_question.id = qualite_RR_reponse.question
  JOIN qualite_RR_session ON qualite_RR_reponse.session = qualite_RR_session.id
  JOIN profil ON qualite_RR_session.personne = profil.id
  WHERE profil.id = ? ');

  $Query2->execute(array($identifiant));

  while ($Data2 = $Query2->fetch()) {
      $valide =   $Data2['vrai_1']==$Data2['valide'];

      if ($valide){ $bonne_reponse_id =$bonne_reponse_id + 1; }
      $tot_reponse_id +=1;
    }
    $Query3 = $bdd->prepare('SELECT * FROM qualite_RR_session WHERE qualite_RR_session.personne = ? ');
    $Query3->execute(array($identifiant));
    while ($Data3 = $Query3->fetch()) {
      $validation= $validation || ($Data3['succes']==1);
    }

    array_push($proportion_bonne_reponse_id, array ($identifiant, $nom, $prenom, $bonne_reponse_id, $tot_reponse_id, $validation));
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
    <td><?php if ($element[4]>0) { echo round(((floatval($element[3])/$element[4])*100),2); echo "%"; }
              else { echo "0%" ; }  ?> </td>
    <td> <?php  if ($element[5]){?>
                  <img src="ressources/checked.png" style="height: 30px; margin: 20px auto;" class="center-block">
        <?php    }else{?> <img src="ressources/cancel.png" style="height: 30px; margin: 20px auto;" class="center-block"> <?php } ?>

    </td>
</tr>
<?php
}
?>
</tbody>
</table>

<?php
} }
drawFooter() ?>
