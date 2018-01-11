<?php


function drawMenu($selected){
    global $url?>
    <div id="nav_">

        <div id="boutons_nav">

            <a href="<?php echo $url; ?>/RH/idees_ameliorations/index.php"><div class="bouton_menu   <?php if($selected=='les_idees'){echo ' bouton_nav_selected';} ?>">Idées du mois</div></a>
            <a href="<?php echo $url; ?>/RH/idees_ameliorations/ajout.php"><div class="bouton_menu     <?php if($selected=='ajouter'){echo ' bouton_nav_selected';} ?>">Ajouter</div></a>
            <a href="<?php echo $url; ?>/RH/idees_ameliorations/suppression.php"><div class="bouton_menu     <?php if($selected=='supprimer'){echo ' bouton_nav_selected';} ?>">Supprimer/Modifier</div></a>
       </div>
     </div>
<?php
}
