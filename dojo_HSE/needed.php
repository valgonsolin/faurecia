<?php


function drawMenu($selected){
    global $url?>
    <div id="nav_">

        <div id="boutons_nav">

            <a href="<?php echo $url; ?>/dojo_HSE/mandatory_rules.php"><div class="bouton_menu   <?php if($selected=='mandatory_rules'){echo ' bouton_nav_selected';} ?>">Mandatory Rules</div></a>
            <a href="<?php echo $url; ?>/dojo_HSE/quizz/index.php"><div class="bouton_menu     <?php if($selected=='quizz'){echo ' bouton_nav_selected';} ?>">Quiz</div></a>
       </div>
     </div>
<?php
}
