<?php
include_once "../../../needed.php";

include_once "../needed.php";

drawHeader('methode');
drawMenu('quizz');

$recherche = "";

if (isset($_GET["recherche"])){
    $recherche = $_GET["recherche"];
}

if(empty($_SESSION['login']))
{ ?>
  <h2>Quiz</h2>
  <h4>Vous devez être connecté pour accéder à cette partie.</h4>
  <a href="/moncompte/identification.php?redirection=methode/formation/quizz"><button class="btn btn-default">Se connecter</button></a>
  <a href="<?php echo $url; ?>" class="btn btn-default">Accueil</a>
<?php
}
else
{
?>

<h2>Quiz</h2>
<?php if($_SESSION['launchboard']){ ?>
  <form class="form-inline">
  <div class="form-group">
    <label for="recherche">Recherche :</label>
    <input type="text" class="form-control" name = "recherche" id="recherche" placeholder="Nom, Prénom" value="<?php echo $recherche;?>">
  </div>
  <button type="submit" class="btn btn-default">Rechercher</button>
  <a href="ajout.php" class="btn btn-default pull-right">Espace administration</a>
  <a href="statistiques.php" class="btn btn-default pull-right">Statistiques Génénérales</a>
</form>
<?php } ?>

<table class="table">
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

if($_SESSION['launchboard']){
  $Query = $bdd->prepare('SELECT * FROM profil LEFT JOIN
    (SELECT id as id_session, valide, personne, type FROM
      (SELECT MAX(fin) as last_fin FROM formation_session WHERE fin IS NOT NULL GROUP BY personne ) as t_fin
      LEFT JOIN formation_session ON formation_session.fin = t_fin.last_fin) as result
      ON result.personne = profil.id
      WHERE (nom LIKE ? or prenom LIKE ?) and supprime = 0');
      $Query->execute(array('%'.$recherche.'%', '%'.$recherche.'%'));
}else{
  $Query = $bdd->prepare('SELECT * FROM profil LEFT JOIN
    (SELECT id as id_session, valide, personne, type FROM
      (SELECT MAX(fin) as last_fin FROM formation_session WHERE fin IS NOT NULL GROUP BY personne ) as t_fin
      LEFT JOIN formation_session ON formation_session.fin = t_fin.last_fin) as result
      ON result.personne = profil.id
      WHERE (nom LIKE ? or prenom LIKE ?) and supprime = 0 and profil.id = ?');
      $Query->execute(array('%'.$recherche.'%', '%'.$recherche.'%',$_SESSION['id']));
}
while ($Data = $Query->fetch()) {
    ?>

    <tr>
        <td> <?php echo $Data['nom']; ?> </td>
        <td><?php echo $Data['prenom']; ?></td>
        <td><?php echo $Data['tournee']; ?></td>
        <td><?php echo $Data['uap']; ?></td>
        <td><?php echo $Data['mo']; ?></td>
        <?php if($Data['id'] == $_SESSION['id']){ ?>
        <td class="clickable" title="Cliquez pour accéder au quiz" onclick="window.location='explication.php?id=<?php echo $Data['id']; ?>'">Accéder au quiz</td>
        <?php
      }else{ ?>
        <td class="clickable" style="color:red;">Accéder au quiz</td>
      <?php }
        if($Data['id_session'] != NULL){
        if (($Data["mo"] == 'MOD' and $Data['type'] == 0 )or
            ($Data["mo"] != 'MOD' and $Data['type'] == 1 )){
            if ($Data['valide'] > 0){
                ?>
                <td class="clickable"><a href="resultats.php?id=<?php echo $Data['id_session']; ?>"><img src="ressources/checked.png" style="
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
                <td class="clickable"><a href="resultats.php?id=<?php echo $Data['id_session']; ?>"><img src="ressources/cancel.png" style="
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
            <td class="clickable"><a href="resultats.php?id=<?php echo $Data['id_session']; ?>"><img src="ressources/cancel.png" style="
                height: 24px;
                border-style: solid;
                border-color: #BBB;
                border-radius: 4px;
                border-width: 1px;
                padding: 2px;

                " class="center-block"></a></td>
            <?php
        }
      }else{
        echo "<td class="; echo "clickable>"; echo "Aucune session" ; echo" </td>";
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
?>
