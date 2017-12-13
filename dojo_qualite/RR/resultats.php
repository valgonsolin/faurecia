<?php
include_once "../../needed.php";

include_once "../needed.php";



function int_to_vrai_faux($int){
    if($int>0){
        return '<img src="ressources/checked.png" style="height: 20px;" class="center-block">';
    }else{
        return '<img src="ressources/cancel.png" style="height: 20px;" class="center-block">';
    }
}



drawHeader('dojo_qualite');
drawMenu('RR');



$Query = $bdd->prepare('UPDATE qualite_RR_session SET fin = NOW() WHERE id = ? and fin is NULL');
$Query->execute(array($_GET["id"]));

?>
<h2>Résultats R&amp;R</h2>
<table class="table">
<h4>Réponse aux questions</h4>
<div style="height: 30px"></div>
<table class="table">
<thead class="thead">
<tr>
    <th>N°</th>
    <th>Question</th>
    <th style="width: 70px;">Résultats</th>
</tr>
</thead>
<tbody>

<?php


$tot_reponse = 0;
$bonne_reponse = 0;

$Query = $bdd->prepare('SELECT * FROM qualite_RR_reponse
  LEFT JOIN qualite_RR_question ON qualite_RR_question.id = qualite_RR_reponse.question
  WHERE qualite_RR_reponse.session = ? ORDER BY qualite_RR_question.ordre ASC');
$Query->execute(array($_GET["id"]));
$i=0;
while ($Data = $Query->fetch()) {
    ?>
    <tr class="clickable" data-toggle="modal" data-target="#modal<?php echo $i; ?>" title="Cliquez pour voir le commentaire">
        <td>
        <?php
        echo $Data['ordre'];
        $query = $bdd -> prepare('SELECT * FROM files WHERE id = ?');
        $query -> execute(array($Data['image']));
        $img=$query -> fetch();

        $valide = $Data['vrai_1']==$Data['valide'];
        if($valide){
            $bonne_reponse += 1;
        }
        $tot_reponse +=1;
        ?></td>

        <td><?php echo $Data['question']; ?></td>
        <td><?php echo int_to_vrai_faux($valide); ?></td>
    </tr>
    <div id="modal<?php echo $i; ?>" class="modal fade" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Image <?php if($Data['valide']){echo "valide";}else{echo "non valide";} ?>, vous avez répondu <?php if($Data['vrai_1']){echo "valide";}else{echo "non valide";} ?></h4>
          </div>
          <div class="modal-body">
            <img src="../../images/<?php echo $img['chemin']; ?>" style="display:block; margin:auto; max-height:400px; max-width:80%;">
          </div>
        </div>

      </div>
    </div>




    <?php
    $i++;
}


?>


</tbody>
</table>


<h4>Statistiques</h4>
    <p>Vous avez obtenu <?php echo $bonne_reponse; ?> bonnes réponses sur <?php echo $tot_reponse; ?> questions.</p>
<?php
$score = floatval($bonne_reponse)/$tot_reponse*100;
?>
<h4>Score</h4>
<p style="text-align: center; font-size: 20px;">Vous avez obtenu un score de <?php echo  number_format($score, 1); ?> %.</p>
<?php

$Query = $bdd->prepare("SELECT * FROM qualite_RR_session WHERE id = ?");
$Query->execute(array($_GET["id"]));

if ($Query->fetch()['type'] == 0){
    $limite = 70;
}else{
    $limite = 60;
}
if ($score>$limite){?>
    <img src="ressources/checked.png" style="height: 128px; margin: 20px auto;" class="center-block">
    <p style="text-align: center;">Vous avez passez le test avec succès.</p>

    <?php
    $Query = $bdd->prepare("UPDATE qualite_RR_session SET succes=1 WHERE id = ?");
    $Query->execute(array($_GET["id"]));

}else{
    ?>
    <img src="ressources/cancel.png" style="height: 128px; margin: 20px auto;" class="center-block">
    <p style="text-align: center;">Vous n'avez pas réussi le test. </p>
    <?php
    $Query = $bdd->prepare("UPDATE qualite_RR_session SET succes=0 WHERE id = ?");
    $Query->execute(array($_GET["id"]));
}

drawFooter();
