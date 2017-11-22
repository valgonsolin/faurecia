<?php
include_once "../needed.php";

include_once "needed.php";

drawheader('logistique');
drawMenu("alerte");

if (isset($_GET['supprime'])){
    $Query = $bdd->prepare('DELETE FROM logistique_reponse_rouge WHERE alerte=? ');
    $Query->execute(array($_GET['alerte']));
    header('Location: '.$url."/logistique/alerte.php?id=".$_GET['alerte']);
}else {


    if (isset($_POST['submit'])) {
        $Query = $bdd->prepare('UPDATE logistique_reponse_rouge SET taxi=?, compagnie=? , prochaine_livraison=?, rupture = ?   WHERE alerte=? ');
        $Query->execute(array($_POST['taxi'], $_POST['compagnie'], $_POST['prochaine_livraison'], $_POST['rupture'], $_GET['alerte']));

        header('Location: ' . $url . "/logistique/alerte.php?id=" . $_GET['alerte']);
    }


    $Query = $bdd->prepare('SELECT * FROM logistique_reponse_rouge WHERE alerte=? ');
    $Query->execute(array($_GET['alerte']));


    if (!$Data = $Query->fetch()) {
        $Query = $bdd->prepare('INSERT INTO logistique_reponse_rouge SET alerte=? ');
        $Query->execute(array($_GET['alerte']));

        $Query = $bdd->prepare('UPDATE logistique_alerte SET state = 1  WHERE id=? ');
        $Query->execute(array($_GET['alerte']));

        $Query = $bdd->prepare('SELECT * FROM logistique_reponse_rouge WHERE alerte=? ');
        $Query->execute(array($_GET['alerte']));
        $Data = $Query->fetch();
    }
    $taxi = $Data['taxi'];
    $compagnie = $Data['compagnie'];
    $prochaine_livraison = $Data['prochaine_livraison'];
    $rupture = $Data['rupture'];

    $Query = $bdd->prepare('SELECT logistique_alerte.id as id_alerte , state, e_kanban, train, uc_restant_en_ligne, logistique_alerte.date , logistique_pieces.*  FROM logistique_alerte LEFT JOIN logistique_pieces on logistique_alerte.piece=logistique_pieces.id WHERE logistique_alerte.id = ?');
    $Query->execute(array($_GET['alerte']));
    $Data = $Query->fetch();

    ?>


    <h2>Fiche rouge de l'alerte sur <?php echo $Data['reference'] ;?> :</h2>


    <form class="form-horizontal" method="post">


        <div class="form-group">
            <label class="control-label col-sm-2" for="taxi">Taxi :</label>
            <div class="col-sm-10">
                <select name='taxi' id="taxi" class="form-control">
                    <option value="0" <?php if ($taxi == 0) {
                        echo 'selected="selected"';
                    } ?> >Non
                    </option>
                    <option value="1" <?php if ($taxi == 1) {
                        echo 'selected="selected"';
                    } ?> >Oui
                    </option>
                </select>
            </div>
        </div>


        <div class="form-group">
            <label class="control-label col-sm-2" for="compagnie">Compagnie :</label>
            <div class="col-sm-10">
                <input type="text" name="compagnie" class="form-control" id="compagnie" placeholder="Compagnie de taxi"
                       value="<?php echo $compagnie; ?>">

            </div>
        </div>


        <div class="form-group">
            <label class="control-label col-sm-2" for="prochaine_livraison">Date prochaine livraison :</label>
            <div class="col-sm-10">
                <input type="text" name="prochaine_livraison" class="form-control" id="prochaine_livraison"
                       placeholder="Date de la prochaine livraison :" value="<?php echo $prochaine_livraison; ?>">

            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="rupture">Date arrêt da la ligne :</label>
            <div class="col-sm-10">
                <input type="text" name="rupture" class="form-control" id="rupture"
                       placeholder="Date d'arrêt de la ligne :" value="<?php echo $rupture; ?>">

            </div>
        </div>


        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" name="submit" class="btn btn-default">Valider</button>
            </div>
        </div>

    </form>


    <?php
}
drawFooter();