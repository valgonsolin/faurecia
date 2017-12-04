<?php
ob_start();


include_once "../../needed.php";

include_once "../needed.php";

if(isset($_POST['question'])){
    $rep_1 = 0;
    if (isset($_POST['rep_1'])){
        $rep_1 = 1;}
    $Query = $bdd->prepare('INSERT INTO qualite_RR_reponse SET vrai_1 = ?, question = ?, session = ?');
    $Query->execute(array($rep_1, $_POST['question'], $_GET["id"]));
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
            border-style:solid;
            border-width:4px;
            border-color: black;
          }
          .checked{
              border-color: green;
              filter: brightness(120%);
          }
          .wrong{
            border-color: red;
            filter: brightness(80%);
          }
          #legend{
            text-align: center;
            margin: 10px;
            font-size:30px;
          }
        </style>
        <script>
        $(document).ready(function(e){
          $(".img-check").click(function(){
            $(this).toggleClass("checked");
            $(this).toggleClass("wrong");
            if($('#legend').html() == "Valide"){
              $('#legend').html('Invalide');
            }else{
              $('#legend').html('Valide');

            }
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
            <div class="col-md-offset-2 col-md-8">
              <label>
                    <?php
                      if($Data['image'] != NULL){
                        $query= $bdd -> prepare('SELECT * FROM files WHERE id= ?');
                        $query -> execute(array($Data['image']));
                        $img= $query -> fetch(); ?>
                        <img class="img-check checked" src="<?php echo $img['chemin']; ?>" alt="Image1" title="Rester pour agrandir"><figcaption id="legend">Valide</figcaption>
                      <?php } ?>
                  <input type="checkbox" name="rep_1" class="hidden">

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
