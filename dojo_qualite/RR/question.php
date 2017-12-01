<?php
ob_start();


include_once "../../needed.php";

include_once "../needed.php";

if(isset($_POST['question'])){
    $rep_1 = 0;
    $rep_2 = 0;
    $rep_3 = 0;
    $rep_4 = 0;
    if (isset($_POST['rep_1'])){
        $rep_1 = 1;}
    if (isset($_POST['rep_2'])){
        $rep_2 = 1;}
    if (isset($_POST['rep_3'])){
        $rep_3 = 1;}
    if (isset($_POST['rep_4'])){
        $rep_4 = 1;}
    $Query = $bdd->prepare('INSERT INTO qualite_RR_reponse SET vrai_1 = ?, vrai_2 = ?, vrai_3 = ?, vrai_4 = ?, question = ?, session = ?');
    $Query->execute(array($rep_1, $rep_2, $rep_3, $rep_4, $_POST['question'], $_GET["id"]));
}


drawHeader();
drawMenu('RR');

if (! isset($_GET["id"])){
    ?>
    <h2>R&amp;R</h2>
    <h4>OUPS... Votre session est inconnu.</h4>
    <a href="index.php"> Retourner au R&amp;R</a>
    <?php
}else {

    $Query = $bdd->prepare('SELECT * FROM qualite_RR_session WHERE id = ?');
    $Query->execute(array($_GET["id"]));
    $type = $Query->fetch()['type'];

    $Query = $bdd->prepare('SELECT * FROM qualite_RR_question WHERE id NOT IN
      (SELECT question FROM qualite_RR_reponse WHERE qualite_RR_reponse.session = ?) and type = ? ORDER BY ordre');
    $Query->execute(array($_GET["id"],$type));
    if($Data = $Query->fetch()) {
        ?>

        <h2>R&amp;R</h2>
        <style>
          .img-check{
            box-shadow: 2px 2px 4px grey;
            border-radius: 6px;
            max-width:100%;
            max-height:200px;
            border-style:solid;
            border-width:4px;
            border-color: black;
          }
          .checked{
              border-color: green;
              filter: brightness(120%);
          }

          .img-hover{
            visibility: hidden;
            position:fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index:1000;
            max-width: 80%;
            max-height:80%;
            border-radius: 4px;
            box-shadow: 2px 2px 4px grey;
            transition-delay:0.1s;
          }
          .col-md-3:hover .img-hover{
            visibility: visible;
            transition-delay: 1.5s;
          }
        </style>
        <script>
        $(document).ready(function(e){
          $(".img-check").click(function(){
            $(this).toggleClass("checked");
          });
        });
        </script>

        <h4><?php echo $Data['titre']; ?></h4>
        <form class="" method="post">
        <div class="row">
          <div class="form-group">
              <label> Question n°<?php echo $Data['ordre'] ?></label><br/>
              <?php echo $Data['question']; ?>
          </div>
          <div class="row">
            <div class="col-md-3">
              <label>
                    <?php
                      if($Data['reponse_1'] != NULL){
                        $query= $bdd -> prepare('SELECT * FROM files WHERE id= ?');
                        $query -> execute(array($Data['reponse_1']));
                        $img= $query -> fetch(); ?>
                        <img class="img-check" src="<?php echo $img['chemin']; ?>" alt="Image1" title="Rester pour agrandir">
                        <img class="img-hover" src="<?php echo $img['chemin']; ?>" alt="Image1">
                      <?php } ?>
                  <input type="checkbox" name="rep_1" class="hidden">
              </label>
            </div>
            <div class="col-md-3">
              <label>
                      <?php
                        if($Data['reponse_2'] != NULL){
                          $query= $bdd -> prepare('SELECT * FROM files WHERE id= ?');
                          $query -> execute(array($Data['reponse_2']));
                          $img= $query -> fetch(); ?>
                          <img class="img-check" src="<?php echo $img['chemin']; ?>" alt="Image2" title="Rester pour agrandir">
                          <img class="img-hover" src="<?php echo $img['chemin']; ?>" alt="Image2">
                        <?php } ?>
                    <input type="checkbox" name="rep_2" class="hidden">
                </label>
              </div>
              <div class="col-md-3">
                <label>
                        <?php
                          if($Data['reponse_3'] != NULL){
                            $query= $bdd -> prepare('SELECT * FROM files WHERE id= ?');
                            $query -> execute(array($Data['reponse_3']));
                            $img= $query -> fetch(); ?>
                            <img class="img-check" src="<?php echo $img['chemin']; ?>" alt="Image3" title="Rester pour agrandir">
                            <img class="img-hover" src="<?php echo $img['chemin']; ?>" alt="Image3">
                          <?php } ?>
                      <input type="checkbox" class="hidden" name="rep_3">
                  </label>
                </div>
                <div class="col-md-3">
                  <label>
                          <?php
                            if($Data['reponse_4'] != NULL){
                              $query= $bdd -> prepare('SELECT * FROM files WHERE id= ?');
                              $query -> execute(array($Data['reponse_4']));
                              $img= $query -> fetch(); ?>
                              <img class="img-check" src="<?php echo $img['chemin']; ?>" alt="Image4" title="Rester pour agrandir">
                              <img class="img-hover" src="<?php echo $img['chemin']; ?>" alt="Image4">
                            <?php } ?>
                        <input class="hidden" type="checkbox" name="rep_4">
                    </label>
                  </div>

            </div>

            <input type="hidden" name="question" value="<?php echo $Data["id"] ?>">

            <button type="submit" name="submit" id="submit_alerte" class="btn btn-default">Passer à la question suivante
            </button>


          </div>
      </form> <?php
    }else{
        ob_end_clean();
        header('Location: '.$url."/dojo_qualite/RR/resultats.php?id=".$_GET['id']);
    }
}
drawFooter();
ob_end_flush();
