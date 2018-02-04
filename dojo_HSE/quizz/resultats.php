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



drawHeader('dojo_hse');
drawMenu('quizz');



$Query = $bdd->prepare('UPDATE qualite_hse_session SET fin = NOW() WHERE id = ? and fin is NULL');
$Query->execute(array($_GET["id"]));

?>
<h2>Résultats Quiz</h2>
<table class="table">
<h4>Réponse aux questions<a href="imprimer_fiche.php?id=<?php echo $_GET['id']; ?>" target=_blank class="pull-right btn btn-default">Imprimer les résultats</a></h4>
<a href="resultats_complet.php?id=<?php echo $_GET['id']?>">Voir les réponses détaillés</a>
<div style="height: 30px"></div>
<table class="table">
<thead class="thead">
<tr>
    <th style="width: 150px;">Categorie</th>
    <th>Question</th>
    <th style="width: 70px;">Résultats</th>
</tr>
</thead>
<tbody>

<?php

$ancien_titre = "";
$tot_reponse = 0;
$tot_reponse_cat = 0;
$bonne_reponse_cat = 0;
$bonne_reponse = 0;
$proportion_bonne_reponse_cat = [];

$Query = $bdd->prepare('SELECT * FROM qualite_hse_reponse
  LEFT JOIN qualite_hse_question ON qualite_hse_question.id = qualite_hse_reponse.question
  WHERE qualite_hse_reponse.session = ? ORDER BY qualite_hse_question.ordre ASC');
$Query->execute(array($_GET["id"]));
$i=0;
while ($Data = $Query->fetch()) {
    ?>
    <tr class="clickable" data-toggle="modal" data-target="#modal<?php echo $i; ?>" title="Cliquez pour voir le commentaire">
        <td>
        <?php
        if ($ancien_titre != $Data['titre']){
            echo $Data['titre'];

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
            $bonne_reponse += 1;
        }
        $tot_reponse_cat +=1;
        $tot_reponse +=1;

        $query= $bdd -> prepare('SELECT * FROM files WHERE id=?');
        $query -> execute(array($Data['image_correction']));
        $img= $query -> fetch();

        ?></td>

        <td><?php echo $Data['question']; ?></td>
        <td><?php echo int_to_vrai_faux($valide); ?></td>
    </tr>
    <div id="modal<?php echo $i; ?>" class="modal fade" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Commentaire</h4>
          </div>
          <div class="modal-body">
            <div class="row" style="margin:2px;">
              <div class="col-sm-6">
                <p><?php echo $Data['commentaire']; ?></p>
              </div>
              <div class="col-sm-6">
              <?php
              if($img){ ?>
                <img class="pull-right" src="../../images/<?php echo $img['chemin']; ?>" style="max-height:400px; max-width:100%;"> <?php } ?>
              </div>
          </div>
          </div>
        </div>

      </div>
    </div>




    <?php
    $i++;
}

array_push($proportion_bonne_reponse_cat, array($ancien_titre, $bonne_reponse_cat, $tot_reponse_cat));
?>


</tbody>
</table>


<h4>Statistiques</h4>

<table class="table">
    <thead class="thead">
        <tr>
            <th >Categorie</th>
            <th style="width: 150px;">Bonnes réponses</th>
            <th style="width: 70px;">Questions</th>

            <th style="width: 300px;">Pourcentage de bonne réponse</th>
        </tr>
    </thead>

<tbody>
        <?php


        foreach ($proportion_bonne_reponse_cat as $categorie){
        ?>
        <tr>
            <td><?php echo $categorie[0];?></td>
            <td><?php echo $categorie[1];?></td>
            <td><?php echo $categorie[2];?></td>
            <?php if(intval( $categorie[2])>0){ ?>
            <td><?php echo number_format(floatval(100*$categorie[1])/$categorie[2],1);?>%</td>
          <?php }else{ ?>
            <td><?php echo "Aucune questions traitées"; ?> </td> <?php } ?>

        </tr>
        <?php
        }
        ?>
    </tbody>
</table>
<?php
if($tot_reponse>0){
$score = floatval($bonne_reponse)/$tot_reponse*100;
}else{$score =0.0 ;}
?>
<h4>Score</h4>
<p style="text-align: center; font-size: 20px;">Vous avez obtenu un score de <?php echo  number_format($score, 1); ?> %.</p>
<?php

$Query = $bdd->prepare("SELECT * FROM qualite_hse_session WHERE id = ?");
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
    $Query = $bdd->prepare("UPDATE qualite_hse_session SET valide=1 WHERE id = ?");
    $Query->execute(array($_GET["id"]));

}else{
    ?>
    <img src="ressources/cancel.png" style="height: 128px; margin: 20px auto;" class="center-block">
    <p style="text-align: center;">Vous n'avez pas réussi le test. Vous pouvez consulter cette
        <a href="resultats_complet.php?id=<?php echo $_GET['id']?>">page</a> pour consulter vos erreurs.</p>
    <?php
    $Query = $bdd->prepare("UPDATE qualite_hse_session SET valide=0 WHERE id = ?");
    $Query->execute(array($_GET["id"]));
}

drawFooter();
