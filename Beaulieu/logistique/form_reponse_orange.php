<?php
include_once "../needed.php";

include_once "needed.php";

drawheader();
drawMenu("alerte");

if (isset($_GET['supprime'])){
    $Query = $bdd->prepare('DELETE FROM logistique_reponse_orange WHERE alerte=? ');
    $Query->execute(array($_GET['alerte']));
    header('Location: '.$url."/logistique/alerte.php?id=".$_GET['alerte']);
}else {


    if (isset($_POST['submit'])) {
        $Query = $bdd->prepare('UPDATE logistique_reponse_orange SET actions=?, prochaine_livraison=?, couverture_ligne=?   WHERE alerte=? ');
        $Query->execute(array($_POST['actions'], $_POST['prochaine_livraison'], $_POST['couverture_ligne'], $_GET['alerte']));

        header('Location: ' . $url . "/logistique/alerte.php?id=" . $_GET['alerte']);
    }


    $Query = $bdd->prepare('SELECT * FROM logistique_reponse_orange WHERE alerte=? ');
    $Query->execute(array($_GET['alerte']));


    if (!$Data = $Query->fetch()) {
        $Query = $bdd->prepare('INSERT INTO logistique_reponse_orange SET alerte=? ');
        $Query->execute(array($_GET['alerte']));

        $Query = $bdd->prepare('UPDATE logistique_alerte SET state = 1  WHERE id=? ');
        $Query->execute(array($_GET['alerte']));

        $Query = $bdd->prepare('SELECT * FROM logistique_reponse_orange WHERE alerte=? ');
        $Query->execute(array($_GET['alerte']));
        $Data = $Query->fetch();
    }
    $date = $Data['date'];
    $actions = $Data['actions'];
    $prochaine_livraison = $Data['prochaine_livraison'];
    $couverture_ligne = $Data['couverture_ligne'];

    $Query = $bdd->prepare('SELECT logistique_alerte.id as id_alerte , state, e_kanban, train, uc_restant_en_ligne, logistique_alerte.date , logistique_pieces.*  FROM logistique_alerte LEFT JOIN logistique_pieces on logistique_alerte.piece=logistique_pieces.id WHERE logistique_alerte.id = ?');
    $Query->execute(array($_GET['alerte']));
    $Data = $Query->fetch();

    ?>


    <h2>Fiche orange de l'alerte sur <?php echo $Data['reference'] ;?> :</h2>

    <form class="form-horizontal" method="post">


        <div class="form-group">
            <label class="control-label col-sm-2" for="prochaine_livraison">Date prochaine livraison :</label>
            <div class="col-sm-10">
                <input type="text" name="prochaine_livraison" class="form-control" id="prochaine_livraison"
                       placeholder="Date de la prochaine livraison :" value="<?php echo $prochaine_livraison; ?>">

            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="couverture_ligne">Livraison avant rupture :</label>
            <div class="col-sm-10">
                <select name='couverture_ligne' id="couverture_ligne" name="piece_masse_storage" class="form-control">
                    <option value="0" <?php if ($couverture_ligne == 0) {
                        echo 'selected="selected"';
                    } ?> >Non
                    </option>
                    <option value="1" <?php if ($couverture_ligne == 1) {
                        echo 'selected="selected"';
                    } ?> >Oui
                    </option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="actions">Actions :</label>
            <div class="col-sm-10">
                <textarea style="height: 200px" type="text" name="actions" class="form-control" id="actions"
                          placeholder="Actions"><?php echo $actions; ?></textarea>
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