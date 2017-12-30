<?php
include_once "../../needed.php";

include_once "../needed.php";

drawheader('codir');
drawMenu("kamishibai");

$recherche = "";

if (isset($_GET["recherche"])){
    $recherche = $_GET["recherche"];
}
if(empty($_SESSION['login']))
{ ?>
  <h2>Kamishibai</h2>
  <h4>Vous devez être connecté pour accéder à cette partie.</h4>
  <a href="/moncompte/identification.php?redirection=codir/kamishibai"><button class="btn btn-default">Se connecter</button></a>
  <a href="<?php echo $url; ?>" class="btn btn-default">Accueil</a>
<?php
}
else
{

    echo "<h2>Kamishibai</h2>";
?>
    <p style="margin-top: 20px;margin-bottom: 20px;"><a href="historique.php">Voir l'historique </a>.</p>

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
                        WHERE (nom LIKE ? or prenom LIKE ?) and supprime = 0 and profil.id = ?');
    $Query->execute(array('%'.$recherche.'%', '%'.$recherche.'%',$_SESSION['id']));
    while ($Data = $Query->fetch()) {
        ?>

        <tr>
            <td> <?php echo $Data['nom']; ?> </a> </td>
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
}
drawFooter();
