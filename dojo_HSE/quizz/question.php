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
    $Query = $bdd->prepare('INSERT INTO qualite_hse_reponse SET vrai_1 = ?, vrai_2 = ?, vrai_3 = ?, vrai_4 = ?, question = ?, session = ?');
    $Query->execute(array($rep_1, $rep_2, $rep_3, $rep_4, $_POST['question'], $_GET["id"]));
}


drawHeader();
drawMenu('quizz');

if (! isset($_GET["id"])){
    ?>
    <h2>Quiz</h2>
    <h4>OUPS... Votre session est inconnu.</h4>
    <a href="index.php"> Retourner au quiz</a>
    <?php
}else {

    $Query = $bdd->prepare('SELECT * FROM qualite_hse_session WHERE id = ?');
    $Query->execute(array($_GET["id"]));
    $type = $Query->fetch()['type'];

    $Query = $bdd->prepare('SELECT * FROM qualite_hse_question WHERE id NOT IN
      (SELECT question FROM qualite_hse_reponse WHERE qualite_hse_reponse.session = ?) and type = ? ORDER BY ordre');
    $Query->execute(array($_GET["id"],$type));

    if($Data = $Query->fetch()) {
        ?>

        <h2>Quiz</h2>

        <h4><?php echo $Data['titre']; ?></h4>
        <div class="row">
          <div class="col-md-8">
        <form class="form-horizontal" method="post">
            <div class="form-group" style="margin: 10px;" >
                <label> Question n°<?php echo $Data['ordre'] ?></label><br/>
                <label for="code_bar"><?php echo $Data['question']; ?></label>
                <div class="checkbox">
                    <label><input type="checkbox" name="rep_1"><?php
                      if(preg_match('#^img=#',$Data['reponse_1'])){
                        $id=substr($Data['reponse_1'],4);
                        $query= $bdd -> prepare('SELECT * FROM files WHERE id= ?');
                        $query -> execute(array($id));
                        $img= $query -> fetch(); ?>
                        <img src="<?php echo $img['chemin']; ?>" style="max-width:60%; max-height: 300px;">
                         <?php
                      }else{
                        echo $Data['reponse_1']; }?></label>
                </div>
                <div class="checkbox">
                    <label><input type="checkbox" name="rep_2"><?php
                      if(preg_match('#^img=#',$Data['reponse_2'])){
                        $id=substr($Data['reponse_2'],4);
                        $query= $bdd -> prepare('SELECT * FROM files WHERE id= ?');
                        $query -> execute(array($id));
                        $img= $query -> fetch(); ?>
                        <img src="<?php echo $img['chemin']; ?>" style="max-width:60%; max-height: 300px;">
                         <?php
                      }else{
                        echo $Data['reponse_2']; }?></label>
                </div>
                <div class="checkbox disabled">
                    <label><input type="checkbox" name="rep_3"><?php
                      if(preg_match('#^img=#',$Data['reponse_3'])){
                        $id=substr($Data['reponse_3'],4);
                        $query= $bdd -> prepare('SELECT * FROM files WHERE id= ?');
                        $query -> execute(array($id));
                        $img= $query -> fetch(); ?>
                        <img src="<?php echo $img['chemin']; ?>" style="max-width:60%; max-height: 300px;">
                         <?php
                      }else{
                        echo $Data['reponse_3']; }?></label>
                </div>
                <div class="checkbox disabled">
                    <label><input type="checkbox" name="rep_4"><?php
                      if(preg_match('#^img=#',$Data['reponse_4'])){
                        $id=substr($Data['reponse_4'],4);
                        $query= $bdd -> prepare('SELECT * FROM files WHERE id= ?');
                        $query -> execute(array($id));
                        $img= $query -> fetch(); ?>
                        <img src="<?php echo $img['chemin']; ?>" style="max-width:60%; max-height: 300px;">
                         <?php
                      }else{
                        echo $Data['reponse_4']; }?></label>
                </div>
            </div>

            <input type="text" name="question" value="<?php echo $Data["id"] ?>" style="display: none;">

            <button type="submit" name="submit" id="submit_alerte" class="btn btn-default">Passer à la question suivante
            </button>

        </form>
      </div>
      <?php
    }
    else{
         ob_end_clean();
         header('Location: '.$url."/dojo_HSE/quizz/resultats.php?id=".$_GET['id']);
     }
}
drawFooter();
ob_end_flush();
