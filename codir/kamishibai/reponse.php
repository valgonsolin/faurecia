<?php
include_once "../../needed.php";

include_once "../needed.php";

drawheader('codir');
drawMenu("kamishibai");



if (isset($_POST['submit_cloturer'])){
    $Query = $bdd->prepare('UPDATE codir_kamishibai_reponse SET cloture=1
      WHERE id = ?');
    $Query->execute(array(
            $_GET['id']));

    header('Location: '.$url."/codir/kamishibai/index.php");

}

if (isset($_POST['submit'])){
    $Query = $bdd->prepare('UPDATE codir_kamishibai_reponse SET 
      reponse1 = ?, reponse2 = ?, reponse3 = ?, reponse4 = ? ,
      commentaire1 = ?, commentaire2 = ?, commentaire3 = ?, commentaire4 = ? 
      WHERE id = ?');
    $Query->execute(array(
        $_POST['reponse1'], $_POST['reponse2'], $_POST['reponse3'], $_POST['reponse4'],
        $_POST['commentaire1'], $_POST['commentaire2'], $_POST['commentaire3'], $_POST['commentaire4'],
        $_GET['id']));

}



$Query = $bdd->prepare('SELECT codir_kamishibai_reponse.id as id_reponse, 
    codir_kamishibai_reponse.reponse1 as reponse1, codir_kamishibai_reponse.reponse2 as reponse2,
    codir_kamishibai_reponse.reponse3 as reponse3,  codir_kamishibai_reponse.reponse4 as reponse4,
    
    codir_kamishibai_reponse.commentaire1 as commentaire1, codir_kamishibai_reponse.commentaire2 as commentaire2,
    codir_kamishibai_reponse.commentaire3 as commentaire3,  codir_kamishibai_reponse.commentaire4 as commentaire4,
     
     codir_kamishibai.*, profil.*  FROM codir_kamishibai_reponse
    LEFT JOIN codir_kamishibai ON codir_kamishibai.id = codir_kamishibai_reponse.kamishibai
    LEFT JOIN profil ON profil.id = codir_kamishibai_reponse.profil
    WHERE codir_kamishibai_reponse.id = ?');
$Query->execute(array($_GET['id']));
$Data = $Query->fetch();



?>

<style>
    textarea{
        width:400px;
        height:200px;
    }
</style>

<h4><?php echo $Data['titre']?></h4>

<form method="post">

    <div class="form-group">
        <label class="control-label col-sm-2" for="reponse1"><?php echo $Data['question1']; ?></label>
        <div class="radio">
            <label><input value="1" type="radio" name="reponse1" <?php if($Data['reponse1'] == 1){echo 'checked="checked"';}?>>Oui</label>
            <label><input value="0" type="radio" name="reponse1" <?php if($Data['reponse1'] == 0){echo 'checked="checked"';}?>>Non</label>
        </div>

        <textarea name="commentaire1" placeholder="Commentaire"><?php echo $Data['commentaire1']?></textarea>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2" for="reponse2"><?php echo $Data['question2']; ?></label>
        <div class="radio">
            <label><input value="1" type="radio" name="reponse2" <?php if($Data['reponse2'] == 1){echo 'checked="checked"';}?>>Oui</label>
            <label><input value="0" type="radio" name="reponse2" <?php if($Data['reponse2'] == 0){echo 'checked="checked"';}?>>Non</label>
        </div>
        <textarea name="commentaire2" placeholder="Commentaire"><?php echo $Data['commentaire2']?></textarea>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2" for="reponse3"><?php echo $Data['question3']; ?></label>
        <div class="radio">
            <label><input value="1" type="radio" name="reponse3" <?php if($Data['reponse3'] == 1){echo 'checked="checked"';}?>>Oui</label>
            <label><input value="0" type="radio" name="reponse3" <?php if($Data['reponse3'] == 0){echo 'checked="checked"';}?>>Non</label>
        </div>

        <textarea name="commentaire3" placeholder="Commentaire"><?php echo $Data['commentaire3']?></textarea>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2" for="reponse4"><?php echo $Data['question4']; ?></label>
        <div class="radio">
            <label><input value="1" type="radio" name="reponse4" <?php if($Data['reponse4'] == 1){echo 'checked="checked"';}?>>Oui</label>
            <label><input value="0" type="radio" name="reponse4" <?php if($Data['reponse4'] == 0){echo 'checked="checked"';}?>>Non</label>
        </div>

        <textarea name="commentaire4" placeholder="Commentaire"><?php echo $Data['commentaire4']?></textarea>
    </div>



    <button type="submit" name="submit" id="submit" class="btn btn-default">Enregistrer
    </button>

    <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir clôturer cette fiche ? ')" name="submit_cloturer" id="submit_cloturer" class="btn btn-default">Clôturer
    </button>


</form>



<div class="col-md text-center" style="margin: 50px;">
    <a href="imprimer_fiche.php?id=<?php echo $_GET["id"]; ?>" class="btn btn-default">Imprimer la fiche</a>
</div>


<?php
drawFooter()

?>
