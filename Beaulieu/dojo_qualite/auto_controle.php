<?php
include_once "../needed.php";

include_once "needed.php";

drawHeader();
drawMenu('auto_controle');
?>

<h2>Auto-contrôle</h2>

    <h4>Objectif : </h4>
    <p>Contrôler la qualité de sa production et écarter, au besoin, les pièces défectueuses de la chaine de production.</p>

    <div class="center_illustration"><img class="illustration center-block" src="ressources/Basic04.PNG"></div>

    <h4>Moyen : les instructions de travail / les défauthèques</h4>
    <p><b>INSTRUCTION DE TRAVAIL : </b>Document sur lequel l'opérateur peut retrouver les points à contrôler en Vert et les règles de réaction en Rouge.</p>
    <div class="center_illustration"><img class="illustration center-block" src="ressources/SI_02.PNG">
        En <b>VERT</b> dans le texte : les points d'autocontrôle
        <br/>En <b>ROUGE</b> : les règles de réaction</div>
    <p><b>DEFAUTHEQUE : </b>un ensemble de photos/pièces physiques rassemblées dans une armoire reprenant les <b>limites d'acceptation des défauts.</b></p>
    <div class="center_illustration"><img class="illustration center-block" src="ressources/SI_03.PNG">
        En <b>VERT</b> : la pièce est bonne
        <br/>En <b>ORANGE</b> : une réparation est possible
        <br/>En <b>ROUGE</b> : la pièce est rebutée</div>
    <h4>Polyvalence</h4>
    <p>La formation des opérateurs est représentée par un carré magique comme indiqué ci-après:</p>
    <div style="display: flex; flex-wrap: wrap;">
        <div class="center_illustration" style="width: 200px;"><img class="illustration center-block" src="ressources/niv_0.PNG">Ne connais pas le poste de travail.</div>
        <div class="center_illustration" style="width: 200px;"><img class="illustration center-block" src="ressources/niv_1.PNG">Respecte le port des EPI et le standart de travail.</div>
        <div class="center_illustration" style="width: 200px;"><img class="illustration center-block" src="ressources/niv_2.PNG">Fait la <b>Qualité</b>, ne laisse pas les défauts <b>connus</b> au poste suivant.</div>
        <div class="center_illustration" style="width: 200px;"><img class="illustration center-block" src="ressources/niv_3.PNG">Fait la Qualité (atteind le temps de cycle standart).</div>
        <div class="center_illustration" style="width: 200px;"><img class="illustration center-block" src="ressources/niv_4.PNG">A améliorer le se standart de travail (Scrap, productivité, sécurité, qualité).</div>
    </div>

    <h4>Les feuilles de batônnages</h4>
    <p>Les défauts sont enregistrés en <b>temps réel</b> sur les feuilles de bâtonnage (au dos du suivi de production), Ces documents donnent les seuils de réaction par défaut (après combien de défauts l'opérateur doir réagir), En cas de dépassement de ce seuil, l'opérateur prévient le Gap Leader et ils ouvrent un QRCI ligne.</p>
    <div class="center_illustration"><img class="illustration center-block" src="ressources/SI_04.PNG"></div>

    <h4>Elements clés de l'auto-contrôle</h4>
    <div class="center_illustration"><img class="illustration center-block" src="ressources/SI_05.PNG"></div>



<?php
drawFooter();