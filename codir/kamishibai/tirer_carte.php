<?php
include_once "../../needed.php";

include_once "../needed.php";

drawheader('codir');
drawMenu("carte");


?>
<h2>Kamishibai 紙芝居</h2>


<div class="center_illustration"><img class="illustration center-block" style="width: 100px;" src="ressources/kamishibai.png"></div>

<h4>Control according to standart</h4>

<?php

$Query = $bdd->prepare('SELECT * FROM codir_kamishibai
    WHERE nb_tirage = ( SELECT min(nb_tirage) FROM codir_kamishibai)
    ORDER BY RAND()');
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

drawFooter();
?>
