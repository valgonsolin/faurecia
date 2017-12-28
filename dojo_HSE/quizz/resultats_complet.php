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



drawHeader();
drawMenu('quizz');



$Query = $bdd->prepare('UPDATE qualite_hse_session SET fin = NOW() WHERE id = ? and fin is NULL');
$Query->execute(array($_GET["id"]));

?>

<h2>Résultats Quiz détaillés</h2>


<?php

$ancien_titre = "";

$Query = $bdd->prepare('SELECT * FROM qualite_hse_reponse
  LEFT JOIN qualite_hse_question ON qualite_hse_question.id = qualite_hse_reponse.question
  WHERE qualite_hse_reponse.session = ? ORDER BY qualite_hse_question.id ASC');
$Query->execute(array($_GET["id"]));
while ($Data = $Query->fetch()) {

    if ($ancien_titre != $Data['titre']){
        ?>
        <h3><?php echo $Data['titre'] ?></h3>
    <?php
    $ancien_titre = $Data['titre'];
    }
    ?>

    <h4><?php echo $Data['question'] ?></h4>
    <?php

    ?>

    <table class="table"
    <thead class="thead">
    <tr>
        <th>Reponses possibles</th>
        <th style="width: 150px;">Vous avez répondu</th>
        <th style="width: 70px;">Attendu</th>
    </tr>
    </thead>
    <tbody>

    <tr>
        <td><?php echo $Data['reponse_1']; ?></td>
        <td><?php echo int_to_vrai_faux($Data['vrai_1']); ?></td>
        <td><?php echo int_to_vrai_faux($Data['corrige_1']); ?></td>
    </tr>

    <tr>
        <td><?php echo $Data['reponse_2']; ?></td>
        <td><?php echo int_to_vrai_faux($Data['vrai_2']); ?></td>
        <td><?php echo int_to_vrai_faux($Data['corrige_2']); ?></td>
    </tr>


    <tr>
        <td><?php echo $Data['reponse_3']; ?></td>
        <td><?php echo int_to_vrai_faux($Data['vrai_3']); ?></td>
        <td><?php echo int_to_vrai_faux($Data['corrige_3']); ?></td>
    </tr>


    <tr>
        <td><?php echo $Data['reponse_4']; ?></td>
        <td><?php echo int_to_vrai_faux($Data['vrai_4']); ?></td>
        <td><?php echo int_to_vrai_faux($Data['corrige_4']); ?></td>
    </tr>


    </tbody>
    </table>


    <?php
}
?>



<?php
drawFooter();
