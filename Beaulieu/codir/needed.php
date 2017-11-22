<?php

function drawMenu($selected){
    global $url?>
    <div id="nav_">

        <div id="boutons_nav">

            <a href="<?php echo $url; ?>/codir/kamishibai/index.php"><div class="bouton_nav   <?php if($selected=='kamishibai'){echo ' bouton_nav_selected';} ?>"> Kamishibai</div></a>
            </div>
    </div>
<?php
}