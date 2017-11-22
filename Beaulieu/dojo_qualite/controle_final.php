<?php
include_once "../needed.php";

include_once "needed.php";

drawHeader();
drawMenu('controle_final');
?>

<h2>Contrôle final</h2>

    <h4>Objectif : </h4>

    <p>Contrôler, selon les normes définies dans le standard de travail, les produits finis avant la conditionnement et la livraison chez le client.</p>

    <div class="center_illustration"><img class="illustration center-block" src="ressources/Basic06.PNG"></div>


    <h4>Moyen : les instructions de travail / Contrôle final</h4>

    <p><b>Seuls les opérateurs validés par la qualité</b> peuvent travailler sur le poste de contrôle final. La validation se fait par les audits coup de poing
    audit coup de poing.</p>


    <div class="center_illustration"><img class="illustration center-block" src="ressources/FC_05.png"></div>

    <p>Sur un <b>chemin de contrôle final</b> situé en face de l'opérateur éffectuant cette manipulation, on précise:</p>
    <ul>
        <li>Le nombre de points à contôler.</li>
        <li>Les zones fonctionnelles du produits mentionnées en <b>JAUNE</b>.</li>
        <li>Le type de contrôle à effectuer: Visuel, Manuel ou en utilisant un outil.</li>
        <li>La fréquence du contrôle.</li>
    </ul>


    <div class="center_illustration"><img class="illustration center-block" src="ressources/FC_02.png"></div>


    <h4>Polyvalence</h4>

    <p>La formation des opérateurs est représentée par un carré magique comme indiqué ci-dessous :</p>

    <div style="display: flex; flex-wrap: wrap;">
        <div class="center_illustration" style="width: 200px;"><img class="illustration center-block" src="ressources/niv_0.PNG">Ne connais pas le poste de travail.</div>
        <div class="center_illustration" style="width: 200px;"><img class="illustration center-block" src="ressources/niv_1.PNG">Respecte le port des EPI et le standart de travail.</div>
        <div class="center_illustration" style="width: 200px;"><img class="illustration center-block" src="ressources/niv_2.PNG">Fait la <b>Qualité</b>, ne laisse pas les défauts <b>connus</b> au poste suivant.</div>
        <div class="center_illustration" style="width: 200px;"><img class="illustration center-block" src="ressources/niv_3.PNG">Fait la Qualité (atteind le temps de cycle standart).</div>
        <div class="center_illustration" style="width: 200px;"><img class="illustration center-block" src="ressources/niv_4.PNG">A améliorer le se standart de travail (Scrap, productivité, sécurité, qualité).</div>
    </div>

    <p>Pour le contrôle final, l'opérateur doit être de <b>niveau "L" minimum</b> et avoir réussi un test <b>"R&R"</b></p>

    <h4>Les feuilles de batônnages</h4>

    <p>Les défauts sont enregistrés en <b>temps réel</b> sur les feuilles de bâtonnage (au dos du suivi de production), Ces documents donnent les seuils de réaction par défaut (après combien de défauts l'opérateur doir réagir), En cas de dépassement de ce seuil, l'opérateur prévient le Gap Leader et ils ouvrent un QRCI ligne.</p>

    <p><b>L'opérateur du contrôle final informe également l'opérateur qui n'a pas détecté le défaut en amont.</b></p>


    <div class="center_illustration"><img class="illustration center-block" src="ressources/SI_04.png"></div>

    <h4>Eleménts clés du Contrôle final</h4>

    <div class="center_illustration"><img class="illustration center-block" src="ressources/FC_03.png"></div>
<?php
drawFooter();