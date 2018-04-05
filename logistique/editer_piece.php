<?php
ob_start();
include_once "../needed.php";

drawheader();

if (isset($_POST['submit'])){
    if (isset($_GET['id'])){
        $Query = $bdd->prepare('UPDATE logistique_pieces SET sebango =?, reference=?, description=?, adresse=?, fournisseur = ? WHERE id = ?');
        $Query->execute(array($_POST['sebango'],$_POST['reference'],$_POST['description'],$_POST['emplacement'],$_POST['fournisseur'],$_GET["id"]));
    }else{
        $Query = $bdd->prepare('INSERT INTO logistique_pieces SET sebango =?, reference=?, description=?, adresse=?,fournisseur = ?');
        $Query->execute(array($_POST['sebango'],$_POST['reference'],$_POST['description'],$_POST['emplacement'],$_POST['fournisseur'],$_GET["id"]));
    }
    ob_end_clean();
    header('Location: '.$url."/logistique/pieces.php");

}

if (isset($_GET['id'])){
    ?>
    <h2>Modifier l'alerte</h2>

    <?php
    $Query = $bdd->prepare('SELECT * FROM logistique_pieces WHERE id = ?');
    $Query->execute(array($_GET["id"]));
    $Data = $Query->fetch();

    $reference = $Data['reference'];
    $sebango = $Data['sebango'];
    $description = $Data['description'];
    $fournisseur = $Data['fournisseur'];
    $emplacement = $Data['adresse'];
}else{
    ?><h2>Ajouter une alerte</h2><?php
    $reference = "";
    $sebango = "";
    $description = "";
    $fournisseur = "";
    $emplacement = "";
}
?>
    <form class="form-horizontal" method="post">
        <div class="form-group">
            <label class="control-label col-sm-2" for="reference">Réference :</label>
            <div class="col-sm-10">
                <input type="text" name="reference" class="form-control" id="reference" placeholder="Entrer le numéro"  value="<?php echo $reference; ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="sebango">Sebango :</label>
            <div class="col-sm-10">
                <input type="text" name="sebango" class="form-control" id="sebango" placeholder="Entrer le sebango"  value="<?php echo $sebango; ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="description">Description :</label>
            <div class="col-sm-10">
                <input type="text" name="description" class="form-control" id="description" placeholder="Entrer la description"  value="<?php echo $description; ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="fournisseur">Fournisseur :</label>
            <div class="col-sm-10">
                <input type="text" name="fournisseur" class="form-control" id="fournisseur" placeholder="Entrer le fournisseur"  value="<?php echo $fournisseur; ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="emplacement">Adresse :</label>
            <div class="col-sm-10">
                <input type="text" name="emplacement" class="form-control" id="emplacement" placeholder="Entrer l'adresse"  value="<?php echo $emplacement; ?>">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" name="submit" class="btn btn-default">Valider</button>
            </div>
        </div>
    </form>
<?php
drawFooter();
ob_end_flush();
