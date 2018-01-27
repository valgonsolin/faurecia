<?php


function drawMenu($selected){
    global $url?>
    <div id="nav_">

        <div id="boutons_nav">

            <a href="<?php echo $url; ?>/methode/formation"><div class="bouton_menu   <?php if($selected=='formation'){echo ' bouton_nav_selected';} ?>">Formation PPTL</div></a>
            <a href="<?php echo $url; ?>/methode/formation/quizz"><div class="bouton_menu     <?php if($selected=='quizz'){echo ' bouton_nav_selected';} ?>">Quiz</div></a>
       </div>
     </div>
<?php
}
