<?php
include_once "../needed.php";

include_once "needed.php";

drawHeader('dojo_qualite');
drawMenu('bacs_rouge');
?>

<h2>Bacs rouge</h2>

    <h4>Objectif : </h4>
    <p>Isoler et séparer les pièces non-conformes du reste de la production + récupérer des échantillons de pièces non conformes pour analyser les défauts et les supprimer.</p>
    <div class="center_illustration"><img class="illustration center-block" src="ressources/Basic03.PNG"></div>


    <h4>Identification des pièces non conformes</h4>
    <p>Toute pièce à retoucher/rebuter est identifiée avec une étiquette rouge prévue à cet effet.<p>
    <p><b>→ Etiquette rouge sur la quelle il faut désigner l'emplacement du défaut et noter :</b></p>
    <ul>
        <li>Référence du produit</li>
        <li>Défaut détecté</li>
        <li>GAP Leader de la tournée</li>
        <li>La date</li>
    </ul>
    <div class="center_illustration"><img class="illustration center-block" src="ressources/RB_01.png">Exemple d'identification d'un défaut sur un produit</div>

    <h4>La séparation Rebut / Retouche</h4>
    <p>Après analyse du défaut, chaque pièce défectueuse est placée dans le bac correspondant:</p>
    <p><b>→ BAC JAUNE</b> : dédié aux pièces à Retoucher</p>
    <div class="center_illustration"><img class="illustration center-block" src="ressources/yellowbin.jpg"></div>
    <p><b>→ BAC ROUGE</b> : dédié aux pièces à Rebuter</p>
    <div class="center_illustration"><img class="illustration center-block" src="ressources/redbin.jpg"></div>

    <h4>Les règles de réaction</h4>
    <p>Toute pièce <b>DEFECTUEUSE</b> doit être identifiée avec l'étiquette <b>REBUT</b></p>
    <div class="center_illustration"><img class="illustration center-block" src="ressources/RB_04.png"></div>
    <p>Toute pièce <b>RETOUCHEE</b> doit être identifiée avec avec l'étiquette <b>RETOUCHE</b> et réinsérée dans la ligne par le GAP LEADER (ou le retoucheur) <b>AU POSTE OU ELLE A ETE EXTRAITE</b> afin d'être recontrôlée, s'il existe un contrôle final ou un contrôle d'etanchéité sur la ligne, la pièce doit être recontrôlée.</p>
    <div class="center_illustration"><img class="illustration center-block" src="ressources/RB_05.png"></div>
    <p><b>→ LES BACS ROUGES DOIVENT ETRE VIDES A LA FIN DE CHAQUE TOURNEE</b></p>

    <h4>Eleménts clés des bacs rouges</h4>
    <div class="center_illustration"><img class="illustration center-block" src="ressources/RB_06.png"></div>


<?php
drawFooter();