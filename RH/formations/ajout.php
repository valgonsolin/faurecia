<?php
include_once "needed.php";
include_once "../../needed.php";

drawHeader('RH');
drawMenu('admin');

if(empty($_SESSION['login'])){
    echo "Connectez vous pour avoir accés à cette partie" ;
}else{
    if(!($_SESSION['admin']==1)){
        echo "Vous n'avez pas accés à cette partie.";
    }else{
        if(!empty($_POST)){
            $query=$bdd->prepare('INSERT INTO `title`(`trainingtitle`) VALUES (:tt) ');
            if($query -> execute(array(
                'tt' => $_POST['title'],
              ))){
                  success('Succés','Intitulé de formation bien ajouté. ');
              }else{
                warning('Erreur','Les données entrées ne sont pas conformes.');
                print_r($query->errorInfo());
              }
            
            
        }
        ?>
        <br>
        <form method="post" style="margin-top:20px;" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
            <div class="form-group">

                <label>Training Title</label>
                <input type="text"  name="title" class="form-control" required>
    
            </div>
            </div>
        </div>
        <div class="row"> 
        <div class="col-md-6 col-md-offset-3">
            <input value="Ajouter l'intitulé" class="btn btn-default" type="submit">
        </div>
        </div>
        </form>
        <br><br>
        <?php
    }

}

drawFooter();
?>