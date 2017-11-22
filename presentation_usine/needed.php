<?php


function drawMenu($selected){?>
    <div id="nav_">

        <div id="boutons_nav">

            <a href="chiffres_cle.php"><div class="bouton_nav   <?php if($selected=='chiffres_cle'){echo ' bouton_nav_selected';} ?>">Chiffres clés</div></a>
            <a href="historique.php"><div class="bouton_nav     <?php if($selected=='historique'){echo ' bouton_nav_selected';} ?>">L'historique</div></a>
            <a href="organigramme.php"><div class="bouton_nav   <?php if($selected=='organigramme'){echo ' bouton_nav_selected';} ?>">Organigramme</div></a>
            <a href="moyen_production.php"><div class="bouton_nav  <?php if($selected=='moyen_production'){echo ' bouton_nav_selected';} ?>">Les moyens de production</div></a>
            <a href="nouveaux_projets.php"><div class="bouton_nav<?php if($selected=='nouveaux_projets'){echo ' bouton_nav_selected';} ?>">Les nouveaux projets</div></a>
            <a href="being_faurecia.php"><div class="bouton_nav <?php if($selected=='being_faurecia'){echo ' bouton_nav_selected';} ?>">Being Faurecia</div></a>
        </div>
    </div>
<?php
}