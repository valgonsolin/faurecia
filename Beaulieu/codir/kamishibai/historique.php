<?php
include_once "../../needed.php";

include_once "../needed.php";

drawheader();
drawMenu("kamishibai");

$recherche = "";
?>
    <h2>Historique kamishibai</h2>


    <form class="form-inline">
        <div class="form-group">
            <label for="recherche">Recherche :</label>
            <input type="text" class="form-control" name = "recherche" id="recherche" placeholder="Nom, Prénom" value="<?php echo $recherche;?>">
        </div>
        <button type="submit" class="btn btn-default">Rechercher</button>
    </form>

    <p style="margin-top: 20px;margin-bottom: 20px;">Choisissez votre profil ou <a href="/editer_profil.php">ajoutez un nouveau profil</a>.</p>

    <table class="table"
    <thead class="thead">
    <tr>
        <th style="width: 200px;">Titre</th>
        <th>Question</th>
        <th style="width: 70px;">Reponse</th>
        <th style="width: 90px;">Action</th>
    </tr>
    </thead>
    <tbody>

    <?php

    $Query = $bdd->prepare('SELECT * FROM codir_kamishibai_reponse
                        LEFT JOIN codir_kamishibai ON codir_kamishibai_reponse.kamishibai = codir_kamishibai.id');
    $Query->execute(array('%'.$recherche.'%', '%'.$recherche.'%'));
    while ($Data = $Query->fetch()) {
        ?>

        <tr>
            <td><?= $Data['titre']?></td>
            <td><?= $Data['question1']?></td>
            <td><?php if ( $Data['reponse1'] == 1){echo "Oui";}elseif($Data['reponse1'] == 1){echo "Non";}else{echo "NA";}?></td>
            <td><a href="reponse.php?id=<?= $Data['id'];?>">Voir la fiche</a></td>
        </tr>
        <tr>
            <td></td>
            <td><?= $Data['question2']?></td>
            <td><?php if ( $Data['reponse2'] == 1){echo "Oui";}elseif($Data['reponse2'] == 1){echo "Non";}else{echo "NA";}?></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td><?= $Data['question3']?></td>
            <td><?php if ( $Data['reponse3'] == 1){echo "Oui";}elseif($Data['reponse3'] == 1){echo "Non";}else{echo "NA";}?></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td><?= $Data['question4']?></td>
            <td><?php if ( $Data['reponse4'] == 1){echo "Oui";}elseif($Data['reponse4'] == 1){echo "Non";}else{echo "NA";}?></td>
            <td></td>
        </tr>


        <?php
    }
    ?>
    </tbody>
    </table>
<?php
drawFooter();