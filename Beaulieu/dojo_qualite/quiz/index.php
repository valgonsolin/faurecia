<?php
include_once "../../needed.php";

include_once "../needed.php";

drawHeader();
drawMenu('quiz');

$recherche = "";

if (isset($_GET["recherche"])){
    $recherche = $_GET["recherche"];
}

?>

<h2>Quiz</h2>


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
    <th>Nom</th>
    <th>Prénom</th>
    <th style="width: 70px;">Tournée</th>
    <th style="width: 30px;">UAP</th>
    <th style="width: 30px;">MO</th>
    <th style="width: 120px;">Actions</th>
    <th style="width: 70px;">Résultat</th>
</tr>
</thead>
<tbody>

<?php

$Query = $bdd->prepare('SELECT * FROM profil LEFT JOIN
                        (SELECT id as id_session, valide, personne, type FROM 
                            (SELECT MAX(fin) as last_fin FROM qualite_quiz_session WHERE fin IS NOT NULL GROUP BY personne ) as t_fin 
                            LEFT JOIN qualite_quiz_session ON qualite_quiz_session.fin = last_fin) as result
                        ON result.personne = profil.id
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
        <td><a href="explication.php?id=<?php echo $Data['id']; ?>">Accéder au quiz</a></td>
        <?php

        if (($Data["mo"] == 'MOD' and $Data['type'] == 0 )or
            ($Data["mo"] != 'MOD' and $Data['type'] == 1 )){
            if ($Data['valide'] > 0){
                ?>
                <td><a href="resultats.php?id=<?php echo $Data['id_session']; ?>"><img src="ressources/checked.png" style="
            height: 24px;
            border-style: solid;
            border-color: #BBB;
            border-radius: 4px;
            border-width: 1px;
            padding: 2px;

            " class="center-block"></a></td>
                <?php
            }else{
                ?>
                <td><a href="resultats.php?id=<?php echo $Data['id_session']; ?>"><img src="ressources/cancel.png" style="
            height: 24px;
            border-style: solid;
            border-color: #BBB;
            border-radius: 4px;
            border-width: 1px;
            padding: 2px;

            " class="center-block"></a></td>
                <?php
            }
        }else{?>
            <td><a href="resultats.php?id=<?php echo $Data['id_session']; ?>"><img src="ressources/cancel.png" style="
                height: 24px;
                border-style: solid;
                border-color: #BBB;
                border-radius: 4px;
                border-width: 1px;
                padding: 2px;

                " class="center-block"></a></td>
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