<?php
include_once "../../needed.php";

include_once "../needed.php";

drawheader('codir');
drawMenu("carte");



if(empty($_SESSION['login']))
{ ?>
  <h2>Kamishibai</h2>
  <h4>Vous devez être connecté pour accéder à cette partie.</h4>
  <a href="/moncompte/identification.php?redirection=codir/kamishibai"><button class="btn btn-default">Se connecter</button></a>
  <a href="<?php echo $url; ?>" class="btn btn-default">Accueil</a>
<?php
}
else
{ ?>
<h2>Kamishibai 紙芝居</h2>


<div class="center_illustration"><img class="illustration center-block" style="width: 100px;" src="ressources/kamishibai.png"></div>

<h4>Control according to standart</h4>

<?php
$Query = $bdd->prepare('SELECT codir_kamishibai.id as id, titre, COUNT(kamishibai) as nb FROM codir_kamishibai LEFT JOIN codir_kamishibai_reponse ON codir_kamishibai.id = codir_kamishibai_reponse.kamishibai GROUP BY codir_kamishibai.id ORDER BY nb');
$Query->execute();
$Data = $Query->fetch();

$Query = $bdd->prepare('INSERT INTO codir_kamishibai_reponse SET profil = ?, kamishibai = ?');
$Query->execute(array($_GET['profil'], $Data['id']));
$id_reponse = $bdd->lastInsertId();

?>


<div class="col-md text-center" style="margin: 50px;">
    <a href="reponse.php?id=<?php echo $id_reponse; ?>">
        <p style="font-size:35px"><?php echo $Data['titre']?></p>
    </a>
</div>


<?php
}

drawFooter();
?>
