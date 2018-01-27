<?php
function drawMenu($selected){
    global $url?>
    <div id="nav_">
        <div id="boutons_nav">
            <a href="<?php echo $url; ?>/RH/news"><div class="bouton_menu<?php if($selected=='news'){echo ' bouton_nav_selected';} ?>">News</div></a>
            <a href="<?php echo $url; ?>/RH/news/liens.php"><div class="bouton_menu <?php if($selected=='liens'){echo ' bouton_nav_selected';} ?>">Liens</div></a>
       </div>
     </div>
<?php
}
