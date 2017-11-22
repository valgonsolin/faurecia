<?php


function drawMenu($selected){
    global $url?>
    <div id="nav_">

        <div id="boutons_nav">

            <a href="<?php echo $url; ?>/dojo_qualite/ok_1er_piece.php"><div class="bouton_nav   <?php if($selected=='ok_1er_piece'){echo ' bouton_nav_selected';} ?>">OK 1ère Pièce</div></a>
            <a href="<?php echo $url; ?>/dojo_qualite/poka_yoke.php"><div class="bouton_nav     <?php if($selected=='poka_yoke'){echo ' bouton_nav_selected';} ?>">Poka-Yoke</div></a>
            <a href="<?php echo $url; ?>/dojo_qualite/auto_controle.php"><div class="bouton_nav   <?php if($selected=='auto_controle'){echo ' bouton_nav_selected';} ?>">Auto-Contrôle</div></a>
            <a href="<?php echo $url; ?>/dojo_qualite/controle_final.php"><div class="bouton_nav  <?php if($selected=='controle_final'){echo ' bouton_nav_selected';} ?>">Contrôle final</div></a>
            <a href="<?php echo $url; ?>/dojo_qualite/bacs_rouge.php"><div class="bouton_nav<?php if($selected=='bacs_rouge'){echo ' bouton_nav_selected';} ?>">Bacs rouge</div></a>
            <a href="<?php echo $url; ?>/dojo_qualite/retouche_sous_controle.php"><div class="bouton_nav <?php if($selected=='retouche_sous_controle'){echo ' bouton_nav_selected';} ?>">Retouche sous contrôle</div></a>
            <a href="<?php echo $url; ?>/dojo_qualite/qrci.php"><div class="bouton_nav <?php if($selected=='qrci'){echo ' bouton_nav_selected';} ?>">QRCI</div></a>
            <a href="<?php echo $url; ?>/dojo_qualite/quiz/index.php"><div class="bouton_nav <?php if($selected=='quiz'){echo ' bouton_nav_selected';} ?>">Quiz</div></a>

        </div>
    </div>
<?php
}