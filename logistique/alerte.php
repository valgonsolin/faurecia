<?php
ob_start();
include_once "../needed.php";

include_once "needed.php";

drawheader();
drawMenu("alerte");



function int_to_oui_non($int){
    if ($int>0){
        return "Oui";
    }else{
        return "Non";
    }
}

if (isset($_POST['supprimer'])){
    $Query = $bdd->prepare('SELECT logistique_alerte.id as id_alerte, state FROM logistique_alerte WHERE logistique_alerte.id = ?');
    $Query->execute(array($_GET['id']));
    $Data = $Query->fetch();


    $state = -1;
    if ($Data['state']==-1){
        $state = 1;
    }
    $Query = $bdd->prepare('UPDATE logistique_alerte SET state = ? WHERE logistique_alerte.id = ?');
    $Query->execute(array($state ,$_GET['id']));
    $Data = $Query->fetch();
    ob_end_clean();
    header('Location: '.$url."/logistique/index.php");
}

$Query = $bdd->prepare('SELECT logistique_alerte.id as id_alerte , state, piece, train, uc_restant_en_ligne, logistique_alerte.date , logistique_pieces.*  FROM logistique_alerte LEFT JOIN logistique_pieces on logistique_alerte.piece=logistique_pieces.id WHERE logistique_alerte.id = ?');
$Query->execute(array($_GET['id']));
$Data = $Query->fetch();

$Query_e_kanban = $bdd->prepare('SELECT * FROM logistique_e_kanban WHERE piece = ?');
$Query_e_kanban->execute(array($Data['piece']));
if ($Data_e_kanban = $Query_e_kanban->fetch()) {
    $quantite = $Data_e_kanban['quantite'];
    $ligne = $Data_e_kanban['ligne'];
    $code_barres = $Data_e_kanban['code_barres'];

}else{
    $quantite = "-";
    $ligne = "-";
    $code_barres = "-";
}

$state = $Data['state'];
?>


<style>

    .entete{
        display: flex;
        justify-content: space-between;
    }

    .conducteur{
        background-color: #2b669a;
    }
    .sup_log{
        background-color: #fff700;
    }

    .appro{

        background-color: #ff9c00;
    }
    .resp_UAP{

        background-color: #da090d;
    }

    .page{
        padding: 30px;
        padding-top: 0px;
        border-width: 1px;
        border-color: #000;
        border-style: solid;
    }
    .page > .row{
        background-color: #FFF;
        border-width: 1px;
        border-color: #000;
        border-style: solid;
    }
    .bouton_alerte{
        margin: 10px;
        height: 30px;
    }

    .bouton_alerte:hover{
        filter: invert(100%);
    }
</style>



<h2>Alerte à propos de <?php echo $Data['sebango'];?></h2>

    <form class="form-inline" method="post">
        <?php

        if ($state == -1){
            echo '<p>Cette alerte a été supprimé.</p>';
            echo '<button name="supprimer" type="submit" class="btn btn-default">Restaurer</button>';
        }else{

            echo "<p>Cette alerte n'est pas encore clôturé.</p>";
            echo '<button name="supprimer" type="submit" class="btn btn-default">Clôturer</button>';
        }
        ?>
    </form>

    <div style="height: 10px;"></div>

<div style="height: 10px;"></div>
<div class="page conducteur">
    <div class="entete">
        <h4>Conducteur</h4>
        <div>
            <a href="form_alerte.php?id=<?php echo $_GET['id']; ?>" ><img class="bouton_alerte" src="ressources/pencil.png"></a>
        </div>
    </div>
    <div class="row">

        <div class="col-xs-6">
            <h4>Informations :</h4>
            <p>
                <b>Date alerte : </b><?php echo date('d/m/y',strtotime($Data['date'])); ?><br>
                <b>Heure alerte : </b><?php echo date('H:i',strtotime($Data['date'])); ?><br>
                <b>Train : </b> <?php echo $Data['train'];?><br>
                <br>
                <b>Sebango : </b><?php echo $Data['sebango'] ?><br>
                <b>Référence : </b><?php echo $Data['reference'] ?><br>
                <b>Description : </b><?php echo $Data['description'] ?><br>
                <b>Adresse : </b><?php echo $Data['adresse'] ?><br>
                <br>
                <b>Quantité / UC : </b> <?php echo $quantite ?><br>
                <b>Nombre d'UC restant en ligne : </b> <?php echo $Data['uc_restant_en_ligne'] ?> <br>
                <br>
                <b>Code barres : </b> <?php echo $code_barres; ?> <br>
                <b>Ligne : </b> <?php echo $ligne; ?>


            </p>
        </div>

        <div class="col-xs-6">
            <h4>Autres e-kanbans concernés :</h4>

            <table class="table"
            <thead class="thead">
            <tr>
                <th style="width: 150px">Code barres</th>
                <th >Ligne</th>
                <th style="width: 120px">Quantité / UC</th>
            </tr>
            </thead>
            <tbody>

            <?php
            $Query = $bdd->prepare('SELECT * FROM logistique_e_kanban WHERE piece = ?');
            $Query->execute(array($Data['piece']));
            while ($Data = $Query->fetch()) {
                ?>

                <tr>
                    <td><?php echo $Data['code_barres']; ?></td>
                    <td><?php echo $Data['ligne']; ?></td>
                    <td><?php echo $Data['quantite']; ?></td>

                </tr>


                <?php
            }
            ?>
            </tbody>
            </table>
        </div>
    </div>
</div>
<?php

$Query = $bdd->prepare('SELECT *  FROM logistique_reponse_jaune WHERE alerte = ?');
$Query->execute(array($_GET['id']));
if ($Data = $Query->fetch()){
    ?>

    <div class="page sup_log">
        <div class="entete">
            <h4>GL& SUP LOG</h4>
            <div>
                <a onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réponse ? ')" href="form_reponse_jaune.php?supprime=1&alerte=<?php echo $_GET['id']; ?>"><img class="bouton_alerte" src="ressources/rubbish.png"></a>
                <a href="form_reponse_jaune.php?alerte=<?php echo $_GET['id']; ?>" ><img class="bouton_alerte" src="ressources/pencil.png"></a>
            </div>
        </div>
        <div class="row">

            <div class="col-xs-6">
                <h4>Informations :</h4>
                <p>
                    <b>Date alerte : </b><?php echo date('d/m/y',strtotime($Data['date'])); ?><br>
                    <b>Heure alerte : </b><?php echo date('H:i',strtotime($Data['date'])); ?><br>
                    <br>
                    <b>Pièce dans le masse storage : </b><?php echo int_to_oui_non($Data['piece_masse_storage']); ?><br>
                    <b>Quantité dans SAP : </b><?php echo $Data['quantite_SAP']; ?><br>
                </p>
            </div>

            <div class="col-xs-6">
                <p>
                <br>
                <b>Fournisseur : </b><?php echo $Data['fournisseur']; ?><br>
                <b>Date de la prochaine livraison : </b><?php echo date('d/m/y',strtotime($Data['prochaine_livraison'])); ?><br>
                <b>Heure de la prochaine livraison  : </b><?php echo date('H:i',strtotime($Data['prochaine_livraison'])); ?><br>
                <br>
                <b>Couverture de la ligne : </b><?php echo int_to_oui_non($Data['couverture_ligne']); ?><br>
                <b>Commentaire : </b><?php echo '<br>'. str_replace("\n", '<br />', $Data['commentaire']); ?>
                </p>
            </div>
        </div>
    </div>
    <?php



    $Query = $bdd->prepare('SELECT *  FROM logistique_reponse_orange WHERE alerte = ?');
    $Query->execute(array($_GET['id']));
    if ($Data = $Query->fetch()){
        ?>

        <div class="page appro">
            <div class="entete">
                <h4>APPRO</h4>
                <div>
                    <a onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réponse ? ')" href="form_reponse_orange.php?supprime=1&alerte=<?php echo $_GET['id']; ?>"><img class="bouton_alerte" src="ressources/rubbish.png"></a>
                    <a href="form_reponse_orange.php?alerte=<?php echo $_GET['id']; ?>" ><img class="bouton_alerte" src="ressources/pencil.png"></a>
                </div>
            </div>
            <div class="row">

                <div class="col-xs-6">
                    <h4>Informations :</h4>
                    <p>
                        <b>Date alerte : </b><?php echo date('d/m/y',strtotime($Data['date'])); ?><br>
                        <b>Heure alerte : </b><?php echo date('H:i',strtotime($Data['date'])); ?><br>
                        <br>
                        <b>Nouvelle date de livraison : </b><?php echo date('d/m/y',strtotime($Data['prochaine_livraison'])); ?><br>
                        <b>Nouvelle heure de livraison  : </b><?php echo date('H:i',strtotime($Data['prochaine_livraison'])); ?><br>
                    </p>
                </div>

                <div class="col-xs-6">
                    <p>
                    <br>
                    <b>La livraison est prévu avant rupture : </b><?php echo int_to_oui_non($Data['couverture_ligne']); ?><br>

                    <b>Actions : </b><?php echo '<br>'. str_replace("\n", '<br />', $Data['actions'])  ; ?>
                    </p>
                </div>
            </div>
        </div>
        <?php



        $Query = $bdd->prepare('SELECT *  FROM logistique_reponse_rouge WHERE alerte = ?');
        $Query->execute(array($_GET['id']));
        if ($Data = $Query->fetch()){
            ?>

            <div class="page resp_UAP">
                <div class="entete">
                    <h4>RESP PC&L / RESP UAP</h4>
                    <div>
                        <a onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réponse ? ')" href="form_reponse_rouge.php?supprime=1&alerte=<?php echo $_GET['id']; ?>"><img class="bouton_alerte" src="ressources/rubbish.png"></a>
                        <a href="form_reponse_rouge.php?alerte=<?php echo $_GET['id']; ?>" ><img class="bouton_alerte" src="ressources/pencil.png"></a>
                    </div>
                </div>
                <div class="row">

                    <div class="col-xs-6">
                        <h4>Informations :</h4>
                        <p>
                            <b>Date arrêt de la ligne : </b><?php echo date('d/m/y',strtotime($Data['rupture'])); ?><br>
                            <b>Heure arrêt de la ligne : </b><?php echo date('H:i',strtotime($Data['rupture'])); ?><br>
                            </p>
                    </div>

                    <div class="col-xs-6">
                        <p>
                        <br>
                        <b>Taxi : </b><?php echo int_to_oui_non($Data['taxi']); ?><br>
                        <b>Compagnie : </b><?php echo $Data['compagnie']  ; ?><br>
                        <br>
                        <b>Nouvelle date de livraison : </b><?php echo date('d/m/y',strtotime($Data['prochaine_livraison'])); ?><br>
                        <b>Nouvelle heure de livraison  : </b><?php echo date('H:i',strtotime($Data['prochaine_livraison'])); ?>
                        </p>

                    </div>
                </div>
            </div>
            <?php

        }else{
            echo '<br><a href="form_reponse_rouge.php?alerte='.$_GET['id'].'">Passer l\'alerte en rouge</a>';
        }
    }else{
        echo '<br><a href="form_reponse_orange.php?alerte='.$_GET['id'].'">Passer l\'alerte en orange</a>';
    }
}else{
    echo '<br><a href="form_reponse_jaune.php?alerte='.$_GET['id'].'">Passer l\'alerte en jaune</a>';
}
?>


<?php


drawFooter();
ob_end_flush();
