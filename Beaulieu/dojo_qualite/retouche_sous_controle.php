<?php
include_once "../needed.php";

include_once "needed.php";

drawHeader();
drawMenu('retouche_sous_controle');
?>

<h2>Retouche sous contôle</h2>

    <h4>Objectif : </h4>
    <p>La retouche est une opération occasionnelle permettant de corriger un défaut. Cependant, c'est une opération sans valeur ajoutée qui doit être évitée autant que possible.</p>
    <p>Il existe deux types de retouches :</p>
    <ul>
        <li>La retouche en ligne (si elle est autorisée et précisée dans l'instruction de travail).</li>
        <li>La retouche hors ligne.</li>
    </ul>
    <p><b>La retouche sur un produit ne doit pas être visible par le client et ne doit pas avoir d'impact sur les caractéristiques du produit fini.</b></p>
    <div class="center_illustration"><img class="illustration center-block" src="ressources/Basic05.PNG"></div>

    <h4>Moyen : La défauthèque et les instructions de travail</h4>
    <p>Tous les types de défauts sont spécifiés dans les instructions de travail, précisant le genre d'intervention à réaliser sur les produis à retoucher.</p>
    <div class="center_illustration"><img class="illustration center-block" src="ressources/RUC_01.png"></div>
    <div class="center_illustration"><img class="illustration center-block" src="ressources/RUC_02.png"></div>
    <h4>Le poste de retouche</h4>
    <p>La retouche hors ligne s'effectue sur un poste de retouche où tous les outils nécessaires à cette opération sont présent.</p>
    <p>→ Les retoucheurs sont des opérateurs formés et qualifiés (polyvalence).</p>
    <p>→ Une instruction de retouche y est disponible</p>

    <h4>La traçabilité des retouches</h4>
    <p>Après la validation de la retouche par le GAP Leader, la pièce retouchée est identifiée par une pastille jaunne: Pièce retouchée</p>
    <p>La pièce retouchée est enregistrée sur le suivi de production, et est réinjectée dans le flux de production au poste où elle à été extraite.</p>

    <h4>Eleménts clés des retouches sous contrôles</h4>
    <div class="center_illustration"><img class="illustration center-block" src="ressources/RUC_03.png"></div>

<?php
drawFooter();