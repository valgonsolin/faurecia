<?php


function drawMenu($selected){
    global $url?>
    <div id="nav_">

        <div id="boutons_nav">

            <a href="<?php echo $url; ?>/RH/formations/index.php"><div class="bouton_menu   <?php if($selected=='collab'){echo ' bouton_nav_selected';} ?>">Espace Collaborateurs</div></a>
            <a href="<?php echo $url; ?>/RH/formations/mana.php"><div class="bouton_menu     <?php if($selected=='mana'){echo ' bouton_nav_selected';} ?>">Espace Manager</div></a>
            <a href="<?php echo $url; ?>/RH/formations/admin.php"><div class="bouton_menu     <?php if($selected=='admin'){echo ' bouton_nav_selected';} ?>">Espace Admin</div></a>

       </div>
     </div>
<?php
}
