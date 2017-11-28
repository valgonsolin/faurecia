<?php
include_once "../needed.php";
include_once "../../needed.php";

drawHeader('dojo_qualite');
drawMenu('R&R');

$lastOrdre= -1;
$query= $bdd -> query('SELECT * FROM qualite_RR_question ORDER BY ordre DESC LIMIT 1');
while ($Data = $query->fetch()) {
$lastOrdre= $Data['ordre'];
}

if(empty($_SESSION['login']))
{ ?>
  <h2>Quiz</h2>
  <h4>Vous devez être connecté en tant qu'administrateur pour accéder à cette partie.</h4>
  <a href="/identification.php?redirection=dojo_qualite/RR/ajout.php"><button class="btn btn-default">Se connecter</button></a>
  <a href="index.php" class="btn btn-default">Quiz</a>
<?php
}
else
{
  echo "<h2>R&R</h2>";

  if(!empty($_POST)){
  $id1=upload($bdd,'file_1',"../../ressources","R&R",5048576,array( 'jpg' , 'jpeg' , 'gif' , 'png' ));
  $id2=upload($bdd,'file_2',"../../ressources","R&R",5048576,array( 'jpg' , 'jpeg' , 'gif' , 'png' ));
  $id3=upload($bdd,'file_3',"../../ressources","R&R",5048576,array( 'jpg' , 'jpeg' , 'gif' , 'png' ));
  $id4=upload($bdd,'file_4',"../../ressources","R&R",5048576,array( 'jpg' , 'jpeg' , 'gif' , 'png' ));
  if($id1==-1){ echo "le fichier 1 n'a pas pu etre téléversé" ; }
  elseif ($id1==-2){echo "la taille du fichier 1 est trop grande";}
  elseif ($id1==-3){echo "le fichier doit posséder l'une des extensions suivantes: jpg, jpeg, gif, png " ;}
  else { $id2=upload($bdd,'file_2',"R&R",5048576,array( 'jpg' , 'jpeg' , 'gif' , 'png' ));
    if($id2==-1){ echo "le fichier 2 n'a pas pu etre téléversé" ; remove_file($bdd,$id1);}
    elseif ($id2==-2){echo "la taille du fichier 1 est trop grande"; remove_file($bdd,$id1); }
    elseif ($id2==-3){echo "le fichier doit posséder l'une des extensions suivantes: jpg, jpeg, gif, png " ; remove_file($bdd,$id1);}
    else { $id3=upload($bdd,'file_2',"R&R",5048576,array( 'jpg' , 'jpeg' , 'gif' , 'png' ));
      if($id3==-1){ echo "le fichier 3 n'a pas pu etre téléversé" ; remove_file($bdd,$id1); remove_file($bdd,$id2);}
      elseif ($id3==-2){echo "la taille du fichier 1 est trop grande"; remove_file($bdd,$id1); remove_file($bdd,$id2);}
      elseif ($id3==-3){echo "le fichier doit posséder l'une des extensions suivantes: jpg, jpeg, gif, png " ; remove_file($bdd,$id1); remove_file($bdd,$id2);}
      else {$id4=upload($bdd,'file_4',"R&R",5048576,array( 'jpg' , 'jpeg' , 'gif' , 'png' ));
        if($id3==-1){ echo "le fichier 4 n'a pas pu etre téléversé" ; remove_file($bdd,$id1); remove_file($bdd,$id2); remove_file($bdd,$id3);}
        elseif ($id3==-2){echo "la taille du fichier 1 est trop grande"; remove_file($bdd,$id1); remove_file($bdd,$id2); remove_file($bdd,$id3); }
        elseif ($id3==-3){echo "le fichier doit posséder l'une des extensions suivantes: jpg, jpeg, gif, png " ; remove_file($bdd,$id1); remove_file($bdd,$id2); remove_file($bdd,$id3);}
        else {

          $vrai1=0;
          $vrai2=0;
          $vrai3=0;
          $vrai4=0;
          if(isset($_POST['vrai1'])){
            $vrai1=$_POST['vrai1'];
          }
          if(isset($_POST['vrai2'])){
            $vrai2=$_POST['vrai2'];
          }
          if(isset($_POST['vrai3'])){
            $vrai3=$_POST['vrai3'];
          }
          if(isset($_POST['vrai4'])){
            $vrai4=$_POST['vrai4'];
          }
          if(isset($_POST['ordre'])){
            if($lastOrdre >= $_POST['ordre']){
              $query = $bdd -> prepare('UPDATE qualite_RR_question SET ordre=ordre+1 WHERE ordre >= ? ');
              $query -> execute(array($_POST['ordre']));
            }
          }

            $query = $bdd -> prepare('INSERT INTO qualite_RR_question(type,titre,question,reponse_1,reponse_2,reponse_3,reponse_4,corrige_1,corrige_2,corrige_3,corrige_4,ordre) VALUES (:type,:titre,:question,:reponse_1,:reponse_2,:reponse_3,:reponse_4,:corrige_1,:corrige_2,:corrige_3,:corrige_4,:ordre)');
            $id= $bdd -> lastInsertId();
            $query -> execute(array(
            'type' => $_POST['type'],
            'titre' => $_POST['titre'],
            'question' => $_POST['question'],
            'reponse_1' => $id1,
            'reponse_2' => $id2,
            'reponse_3' => $id3,
            'reponse_4' => $id4,
            'corrige_1' => $vrai1,
            'corrige_2' => $vrai2,
            'corrige_3' => $vrai3,
            'corrige_4' => $vrai4,
            'ordre' => $_POST['ordre']
          ));

          if($query ==false){ ?>
            <div class="alert alert-danger">
              <strong>Erreur</strong>  -  Les données entrées ne sont pas conformes.
            </div>
          <?php }else{ ?>
                  <div class="alert alert-success">
                    <strong>Ajouté</strong>  -  La question a bien été ajoutée.
                  </div>
                  <?php
                }
              }
            }

          }
  }}
  ?>
  <div class="boutons_nav" style="display: flex; justify-content: center;">
    <a href="ajout.php" class="bouton_menu bouton_nav_selected" style="margin-right:20%">Ajout</a>
    <a href="suppression.php" class="bouton_menu">Modification/Suppression</a>
  </div>

  <form method="post" style="margin-top:20px;"  enctype="multipart/form-data">
    <div class="form-group">
      <label>Question n°</label>
      <input type="number" class="form-control" name="ordre" value="<?php echo $lastOrdre+1 ?>">
    </div>
  	<div class="form-group">
  	<label>Type</label>
  	<select name="type" class="form-control">
  		<option value="0" selected="selected">MOD</option>
  		<option value="1">MOI</option>
  	</select>
  	<label>Titre</label>
  	<input class="form-control" name="titre" type="text">
  	</div>
  	<div class="form-group">
  	<label>Question</label>
  	<input class="form-control" name="question" type="text">
  	</div>
  	<div class="form-group">
  		<label>Réponse 1 :     </label><label style="margin-left:20px"><input name="vrai1" type="checkbox"> Vrai</label>
  		<input type="file" name="file_1" class="form-control" />
  	</div>
  	<div class="form-group">
  		<label>Réponse 2 :     </label><label style="margin-left:20px"><input name="vrai2" type="checkbox"> Vrai</label>
  		<input type="file" name="file_2" class="form-control" />
  	</div>
  	<div class="form-group">
  		<label>Réponse 3 :     </label><label style="margin-left:20px"><input name="vrai3" type="checkbox"> Vrai</label>
  		<input type="file" name="file_3" class="form-control" />
  	</div>
  	<div class="form-group">
  		<label>Réponse 4 :     </label><label style="margin-left:20px"><input name="vrai4" type="checkbox"> Vrai</label>
  		<input type="file" name="file_4" class="form-control" />
  	</div>
  	<input value="Ajouter" class="btn btn-default" type="submit">


  </form>



<?php
}
?>





<?php
drawFooter();
 ?>
