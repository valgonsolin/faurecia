<?php
include_once "../../needed.php";

include_once "../needed.php";

drawheader('codir');
drawMenu("kamishibai");

$recherche = "";
?>
    <h2>Historique kamishibai</h2>


    <form class="form-inline">
        <div class="form-group">
            <label for="recherche">Recherche :</label>
            <input type="text" class="form-control" name = "recherche" id="recherche" placeholder="Carte" value="<?php echo $recherche;?>">
        </div>
        <button type="submit" class="btn btn-default">Rechercher</button>
    </form>

    <table class="table">
    <thead class="thead">
    <tr>
        <th style="width:90%;">Titre</th>
        <th>Tirages</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $nb=0;
    if(isset($_GET['nb'])){
      $nb=$_GET['nb'];
    }
    $Query = $bdd->prepare('SELECT *,COUNT(*) as s FROM codir_kamishibai LEFT JOIN codir_kamishibai_reponse ON codir_kamishibai_reponse.kamishibai = codir_kamishibai.id WHERE codir_kamishibai_reponse.cloture = 1 AND titre LIKE :titre GROUP BY codir_kamishibai.id LIMIT 20 OFFSET :nb');
    $Query ->bindValue(':titre','%'.$recherche.'%');
    $Query ->bindValue(':nb',(int) $nb, PDO::PARAM_INT);
    $Query -> execute();
    while ($Data = $Query->fetch()) {
        ?>
        <tr class="clickable" onclick="window.location='historique.php?id=<?php echo$Data['0']; ?>'">
          <td><?php echo $Data['titre']; ?></td>
          <td style="text-align:center;"><?php if(is_null($Data['kamishibai'])){echo "0";}else{echo $Data['s'];}; ?></td>
        </tr>

        <?php
    }
    ?>
    </tbody>
  </table> <?php
    $test = $bdd->prepare('SELECT * FROM codir_kamishibai LEFT JOIN codir_kamishibai_reponse ON codir_kamishibai_reponse.kamishibai = codir_kamishibai.id WHERE codir_kamishibai_reponse.cloture = 1 AND titre LIKE :titre GROUP BY codir_kamishibai.id LIMIT 1 OFFSET :nb');
    $test ->bindValue(':titre','%'.$recherche.'%');
    $test ->bindValue(':nb',(int) $nb+20, PDO::PARAM_INT);
    $test->execute(); ?>
    <form method="post" class="inline-form"> <?php
      if($nb > 19){    ?>
          <a href="index.php?recherche=<?php echo $recherche;?>&amp;nb=<?php echo $nb-20;?>" class="btn btn-default">Elements précédents</a>
        <?php
      }
      if($test -> fetch()){ ?>
        <a href="index.php?recherche=<?php echo $recherche;?>&amp;nb=<?php echo $nb+20;?>" class="btn btn-default">Elements suivants</a>
      <?php } ?>
        <span class="clear" style="clear: both; display: block;"></span>
      </form>

<?php
drawFooter();
