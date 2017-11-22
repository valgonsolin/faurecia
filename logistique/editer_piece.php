<?php
include_once "../needed.php";

drawheader();

if (isset($_POST['submit'])){
    if (isset($_GET['id'])){
        $Query = $bdd->prepare('UPDATE logistique_pieces SET code_barres=?, sebango =?, reference=?, description=?, ligne =?, emplacement=?, quantite=? WHERE id = ?');
        $Query->execute(array($_POST['code_barres'],$_POST['sebango'],$_POST['reference'],$_POST['description'],$_POST['ligne'],$_POST['emplacement'],$emplacement['quantite'],$_GET["id"]));
    }else{
        $Query = $bdd->prepare('INSERT INTO logistique_pieces SET code_barres=?, sebango =?, reference=?, description=?, ligne =?, emplacement=?, quantite=?');
        $Query->execute(array($_POST['code_barres'],$_POST['sebango'],$_POST['reference'],$_POST['description'],$_POST['ligne'],$_POST['emplacement'],$emplacement['quantite'],$_GET["id"]));
    }
    header('Location: '.$url."/logistique/pieces.php");

}

if (isset($_GET['id'])){
    ?>
    <h2>Modifier le profil</h2>

    <?php
    $Query = $bdd->prepare('SELECT * FROM logistique_pieces WHERE id = ?');
    $Query->execute(array($_GET["id"]));
    $Data = $Query->fetch();

    $reference = $Data['reference'];
    $sebango = $Data['sebango'];
    $description = $Data['description'];
}else{
    ?><h2>Ajouter un profil</h2><?php
    $reference = "";
    $sebango = "";
    $description = "";
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
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" name="submit" class="btn btn-default">Valider</button>
            </div>
        </div>
    </form>
<?php
drawFooter();