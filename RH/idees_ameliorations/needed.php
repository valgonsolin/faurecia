<?php


function drawMenu($selected){
    global $url?>
    <div id="nav_">

        <div id="boutons_nav">

            <a href="<?php echo $url; ?>/RH/idees_ameliorations/index.php"><div class="bouton_menu   <?php if($selected=='les_idees'){echo ' bouton_nav_selected';} ?>">Idées du mois</div></a>
            <a href="<?php echo $url; ?>/RH/idees_ameliorations/idees_tot.php"><div class="bouton_menu     <?php if($selected=='tot_idees'){echo ' bouton_nav_selected';} ?>">Toutes les idées</div></a>
              <a href="<?php echo $url; ?>/RH/idees_ameliorations/statistiques.php"><div class="bouton_menu     <?php if($selected=='stat'){echo ' bouton_nav_selected';} ?>">Statistiques</div></a>
       </div>
     </div>
<?php
}
