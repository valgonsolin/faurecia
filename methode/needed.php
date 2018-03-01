<?php


function drawMenu($selected){
    global $url?>
    <div id="nav_">

        <div id="boutons_nav">

            <a href="<?php echo $url; ?>/methode/launchboard"><div class="bouton_menu   <?php if($selected=='launchboard'){echo ' bouton_nav_selected';} ?>">LaunchBoard</div></a>
            <a href="<?php echo $url; ?>/methode/launchboard/statistiques.php"><div class="bouton_menu     <?php if($selected=='statistiques'){echo ' bouton_nav_selected';} ?>">Statistiques</div></a>
        </div>
    </div>
<?php 
}
