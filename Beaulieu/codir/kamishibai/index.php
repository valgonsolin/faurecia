<?php
include_once "../../needed.php";

include_once "../needed.php";

drawheader();
drawMenu("kamishibai");

$recherche = "";

if (isset($_GET["recherche"])){
    $recherche = $_GET["recherche"];
}

?>

    <h2>Kamishibai</h2>


    <form class="form-inline">
        <div class="form-group">
            <label for="recherche">Recherche :</label>
            <input type="text" class="form-control" name = "recherche" id="recherche" placeholder="Nom, Prénom" value="<?php echo $recherche;?>">
        </div>
        <button type="submit" class="btn btn-default">Rechercher</button>
    </form>

    <p style="margin-top: 20px;margin-bottom: 20px;"><a href="historique.php">Voir l'historique </a>.</p>

    <p style="margin-top: 20px;margin-bottom: 20px;">Choisissez votre profil ou <a href="/editer_profil.php">ajoutez un nouveau profil</a>.</p>


    <table class="table"
    <thead class="thead">
    <tr>
        <th>Nom</th>
        <th>Prénom</th>
        <th style="width: 70px;">Tournée</th>
        <th style="width: 30px;">UAP</th>
        <th style="width: 30px;">MO</th>
        <th style="width: 200px;">Actions</th>
    </tr>
    </thead>
    <tbody>

    <?php

    $Query = $bdd->prepare('SELECT * FROM profil
                        LEFT JOIN (SELECT id as id_reponse, profil as id_profil FROM codir_kamishibai_reponse WHERE cloture = 0 GROUP BY id_profil) AS reponse
                        ON reponse.id_profil = profil.id
                        WHERE (nom LIKE ? or prenom LIKE ?) and supprime = 0');
    $Query->execute(array('%'.$recherche.'%', '%'.$recherche.'%'));
    while ($Data = $Query->fetch()) {
        ?>

        <tr>
            <td><a href="/editer_profil.php?id=<?php echo $Data['id']; ?>"> <?php echo $Data['nom']; ?> </a> </td>
            <td><?php echo $Data['prenom']; ?></td>
            <td><?php echo $Data['tournee']; ?></td>
            <td><?php echo $Data['uap']; ?></td>
            <td><?php echo $Data['mo']; ?></td>
            <?php
            if (is_null($Data['id_reponse'])) {
                ?>
                <td><a href="tirer_carte.php?profil=<?php echo $Data['id']; ?>">Tirer une carte</a></td>
                <?php
            }else {
                ?>
                <td><a href="reponse.php?id=<?php echo $Data['id_reponse']; ?>">Accéder à la carte</a></td>
                <?php
            }
                ?>
        </tr>


        <?php
    }
    ?>
    </tbody>
    </table>
<?php
drawFooter();