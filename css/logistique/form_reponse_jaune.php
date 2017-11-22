<?php
include_once "../needed.php";

include_once "needed.php";

drawheader('dojo_qualite');
drawMenu("alerte");


if (isset($_GET['supprime'])){
    $Query = $bdd->prepare('DELETE FROM logistique_reponse_jaune WHERE alerte=? ');
    $Query->execute(array($_GET['alerte']));
    header('Location: '.$url."/logistique/alerte.php?id=".$_GET['alerte']);
}else {

    if (isset($_POST['submit'])) {
        $Query = $bdd->prepare('UPDATE logistique_reponse_jaune SET commentaire = ?, piece_masse_storage=?, quantite_SAP=?, fournisseur=?, prochaine_livraison=?, couverture_ligne=?   WHERE alerte=? ');
        $Query->execute(array($_POST['commentaire'], $_POST['piece_masse_storage'], $_POST['quantite_SAP'], $_POST['fournisseur'], $_POST['prochaine_livraison'], $_POST['couverture_ligne'], $_GET['alerte']));

        header('Location: ' . $url . "/logistique/alerte.php?id=" . $_GET['alerte']);
    }


    $Query = $bdd->prepare('SELECT * FROM logistique_reponse_jaune WHERE alerte=? ');
    $Query->execute(array($_GET['alerte']));


    if (!$Data = $Query->fetch()) {
        $Query = $bdd->prepare('INSERT INTO logistique_reponse_jaune SET alerte=? ');
        $Query->execute(array($_GET['alerte']));

        $Query = $bdd->prepare('UPDATE logistique_alerte SET state = 1  WHERE id=? ');
        $Query->execute(array($_GET['alerte']));

        $Query = $bdd->prepare('SELECT * FROM logistique_reponse_jaune WHERE alerte=? ');
        $Query->execute(array($_GET['alerte']));
        $Data = $Query->fetch();
    }
    $date = $Data['date'];
    $piece_masse_storage = $Data['piece_masse_storage'];
    $quantite_SAP = $Data['quantite_SAP'];
    $fournisseur = $Data['fournisseur'];
    $prochaine_livraison = $Data['prochaine_livraison'];
    $couverture_ligne = $Data['couverture_ligne'];
    $commentaire = $Data['commentaire'];

    $Query = $bdd->prepare('SELECT logistique_alerte.id as id_alerte , state, e_kanban, train, uc_restant_en_ligne, logistique_alerte.date , logistique_pieces.*  FROM logistique_alerte LEFT JOIN logistique_pieces on logistique_alerte.piece=logistique_pieces.id WHERE logistique_alerte.id = ?');
    $Query->execute(array($_GET['alerte']));
    $Data = $Query->fetch();

    ?>


    <h2>Fiche jaune de l'alerte sur <?php echo $Data['reference'] ;?> :</h2>
    <form class="form-horizontal" method="post">


        <div class="form-group">
            <label class="control-label col-sm-2" for="piece_masse_storage">Pièces dans le masse storage :</label>
            <div class="col-sm-10">
                <select name='piece_masse_storage' id="piece_masse_storage" name="piece_masse_storage"
                        class="form-control">
                    <option value="0" <?php if ($piece_masse_storage == 0) {
                        echo 'selected="selected"';
                    } ?> >Non
                    </option>
                    <option value="1" <?php if ($piece_masse_storage == 1) {
                        echo 'selected="selected"';
                    } ?> >Oui
                    </option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="quantite_SAP">Quantitée dans SAP (MD04) :</label>
            <div class="col-sm-10">
                <input type="text" name="quantite_SAP" class="form-control" id="quantite_SAP"
                       placeholder="Quantitée dans SAP (MD04) :" value="<?php echo $quantite_SAP; ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="fournisseur">Fournisseur :</label>
            <div class="col-sm-10">
                <input type="text" name="fournisseur" class="form-control" id="fournisseur"
                       placeholder="Nom du fournisseur :" value="<?php echo $fournisseur; ?>">
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
            <label class="control-label col-sm-2" for="commentaire">Commentaire:</label>
            <div class="col-sm-10">
                <textarea name="commentaire" class="form-control" id="commentaire"><?php echo $commentaire; ?></textarea>

            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="couverture_ligne">Couverture de la ligne (Passage de l'alerte en orange :</label>
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
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" name="submit" class="btn btn-default">Valider</button>
            </div>
        </div>

    </form>


    <?php
}
drawFooter();