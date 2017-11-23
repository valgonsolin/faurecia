<?php

function drawMenu($selected){
    global $url?>
    <div id="nav_">

        <div id="boutons_nav">

            <a href="<?php echo $url; ?>/logistique/index.php"><div class="bouton_nav   <?php if($selected=='alerte'){echo ' bouton_nav_selected';} ?>">Alerte composant</div></a>
            <a href="<?php echo $url; ?>/logistique/pieces.php"><div class="bouton_nav     <?php if($selected=='pieces'){echo ' bouton_nav_selected';} ?>">Pièces</div></a>
            <a href="<?php echo $url; ?>/logistique/update_pieces.php"><div class="bouton_nav     <?php if($selected=='update'){echo ' bouton_nav_selected';} ?>">Mise à jour</div></a>
        </div>
    </div>
<?php
}
