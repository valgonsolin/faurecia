<?php
include_once "../needed.php";

include_once "needed.php";

drawHeader('dojo_qualite');
drawMenu('poka_yoke');
?>

<h2>Poka-Yoke</h2>

<h4>Objectifs : </h4>

    <p>Le Poka-Yoke a pour objectif de rendre impossible l'erreur <b>conduisant</b> au défaut ainsi que la <b>transmission</b> d'un défaut au poste suivant.</p>
<div class="center_illustration"><img class="illustration center-block" src="ressources/PY_01.PNG"></div>

<h4>Le document "OK 1ère pièce" : </h4>
    <p>Le GAP Leader ou l'opérateur qualifié assure le bon fonctionnement du Poka-Yoke <b>avant</b> de démarrer la production en suivant <b>les instructions de validation</b> et en respectant <b>les règles de réaction</b>.</p>
<div class="center_illustration"><img class="illustration center-block" src="ressources/PY_03.PNG"></div>

<h4>Élément clé : </h4>
<div class="center_illustration"><img class="illustration center-block" src="ressources/PY_04.png"></div>

<?php
drawFooter();