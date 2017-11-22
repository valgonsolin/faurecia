<?php
include_once "needed.php";

drawheader('codir');

if (isset($_POST['submit'])){
    if (isset($_GET['id'])){
        $Query = $bdd->prepare('UPDATE profil SET nom = ?, prenom = ?, mo = ?, uap = ?, tournee = ? WHERE id = ?');
        $Query->execute(array($_POST['nom'],$_POST['prenom'],$_POST['mo'],$_POST['uap'],$_POST['tournee'],$_GET["id"]));
    }else{
        $Query = $bdd->prepare('INSERT INTO profil SET nom = ?, prenom = ?, mo = ?, uap = ?, tournee = ?');
        $Query->execute(array($_POST['nom'],$_POST['prenom'],$_POST['mo'],$_POST['uap'],$_POST['tournee']));
    }
    header('Location: '.$url."/index.php");

}

if (isset($_POST['supprimer'])){
    if (isset($_GET['id'])){
        $Query = $bdd->prepare('UPDATE profil SET supprime = 1 WHERE id = ?');
        $Query->execute(array($_GET["id"]));
    }
    header('Location: '.$url."/index.php");


}

if (isset($_GET['id'])){
?>
    <h2>Modifier le profil</h2>

<?php
    $Query = $bdd->prepare('SELECT * FROM profil WHERE id = ?');
    $Query->execute(array($_GET["id"]));
    $Data = $Query->fetch();
    $nom = $Data['nom'];
    $prenom = $Data['prenom'];
    $mo = $Data['mo'];
    $uap = $Data['uap'];
    $tournee = $Data['tournee'];
}else{
?>
    <h2>Ajouter un profil</h2>

<?php
    $nom = "";
    $prenom = "";
    $mo = "";
    $uap = "";
    $tournee ="";
}
?>
    <form class="form-horizontal" method="post">
        <div class="form-group">
            <label class="control-label col-sm-2" for="nom">Nom :</label>
            <div class="col-sm-10">
                <input type="text" name="nom" class="form-control" id="nom" placeholder="Entrer le nom" value="<?php echo $nom; ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="prenom">Prénom :</label>
            <div class="col-sm-10">
                <input type="text" name="prenom" class="form-control" id="prenom" placeholder="Entrer le prénom"  value="<?php echo $prenom; ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="tournee">Tournée :</label>
            <div class="col-sm-10">
                <select name='tournee' id="tournee" class="form-control">$
                    <option value="A" <?php if($tournee == "A"){echo 'selected="selected"';}?> >A</option>
                    <option value="B" <?php if($tournee == "B"){echo 'selected="selected"';}?> >B</option>
                    <option value="N" <?php if($tournee == "N"){echo 'selected="selected"';}?> >N</option>
                    <option value="J" <?php if($tournee == "J"){echo 'selected="selected"';}?> >J</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="uap">UAP :</label>
            <div class="col-sm-10">
                <select name='uap' id="uap" name="uap" class="form-control">
                    <option value="UAP1" <?php if($uap == 'UAP1'){echo 'selected="selected"';}?> >UAP1</option>
                    <option value="UAP2" <?php if($uap == 'UAP2'){echo 'selected="selected"';}?> >UAP2</option>
                    <option value="UAP3" <?php if($uap == 'UAP3'){echo 'selected="selected"';}?> >UAP3</option>
                    <option value="Support" <?php if($uap == 'Support'){echo 'selected="selected"';}?> >Support</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="mo">MO :</label>
            <div class="col-sm-10">
                <select name='mo' id="mo" name="mo" class="form-control">
                    <option value="MOI" <?php if($mo == "MOI"){echo 'selected="selected"';}?> >MOI</option>
                    <option value="MOD" <?php if($mo == "MOD"){echo 'selected="selected"';}?> >MOD</option>
                    <option value="GAP" <?php if($mo == "GAP"){echo 'selected="selected"';}?> >GAP</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" name="submit" class="btn btn-default">Valider</button>
            </div>
        </div>
        <?php
        if (isset($_GET['id'])){
        ?>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce profil ? ')" type="submit" name="supprimer" class="btn btn-default">Supprimer le profil</button>
            </div>
        </div>
        <?php
        }
        ?>
    </form>
<?php
drawFooter();