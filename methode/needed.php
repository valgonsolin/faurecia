<?php


function drawMenu($selected){
    global $url?>
    <div id="nav_">

        <div id="boutons_nav">

            <a href="<?php echo $url; ?>/methode/launchboard"><div class="bouton_menu   <?php if($selected=='launchboard'){echo ' bouton_nav_selected';} ?>">LaunchBoard</div></a>
            <a href="<?php echo $url; ?>"><div class="bouton_menu     <?php if($selected=='formation'){echo ' bouton_nav_selected';} ?>">Formation PPTL</div></a>
            <a href="<?php echo $url; ?>"><div class="bouton_menu   <?php if($selected=='charge'){echo ' bouton_nav_selected';} ?>">Charge</div></a>

        </div>
    </div>
<?php
}
