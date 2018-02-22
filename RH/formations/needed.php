<?php


function drawMenu($selected){
    global $url?>
    <div id="nav_">

        <div id="boutons_nav">

            <a href="<?php echo $url; ?>/RH/formations/index.php"><div class="bouton_menu   <?php if($selected=='demande'){echo ' bouton_nav_selected';} ?>">Les formations</div></a>
            <a href="<?php echo $url; ?>/RH/formations/export.php"><div class="bouton_menu     <?php if($selected=='export'){echo ' bouton_nav_selected';} ?>">Export excel</div></a>

       </div>
     </div>
<?php
}
