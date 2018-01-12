<?php
include_once "../../needed.php";


$Query = $bdd->prepare('SELECT codir_kamishibai_reponse.id as id_reponse, codir_kamishibai.*, profil.*  FROM codir_kamishibai_reponse
    LEFT JOIN codir_kamishibai ON codir_kamishibai.id = codir_kamishibai_reponse.kamishibai
    LEFT JOIN profil ON profil.id = codir_kamishibai_reponse.profil
    WHERE codir_kamishibai_reponse.id = ?');
$Query->execute(array($_GET['id']));
$Data = $Query->fetch();
?>
<body>
<div style="
    width: 80%;
    margin: 0 auto;

">
    <h2>Fiche Kamishibai</h2>
    <p>
        <b>Nom:</b> <?php echo $Data['nom']; ?><br>
        <b>Prenom:</b> <?php echo $Data['prenom']; ?>


    </p>

    <h4 style="
            margin-top: 20px;"><?php echo $Data['titre']; ?></h4>
    <p>
        <b><?php echo $Data['question1']; ?></b>
        <div style="
                display: flex;
    ">
        <div style="
                width: 15px;
                height: 15px;
                border-style: solid;
                border-width: 1px;
                margin: 5px;
                margin-top: 0px;
        "></div>
        Oui
        <div style="
                width: 15px;
                height: 15px;
                border-style: solid;
                border-width: 1px;
                margin: 5px;
                margin-top: 0px;
        "></div>
            Non
            <div style="width: 100px"></div>
            Commentaire:
            <div style="
                width: 300px;
                height: 120px;
                border-style: solid;
                border-width: 1px;
                margin: 5px;
                margin-top: 0px;
        "></div>
        </div>
    </p>


    <p>
        <b><?php echo $Data['question2']; ?></b>
    <div style="
            display: flex;
">
        <div style="
            width: 15px;
            height: 15px;
            border-style: solid;
            border-width: 1px;
            margin: 5px;
            margin-top: 0px;
    "></div>
        Oui
        <div style="
            width: 15px;
            height: 15px;
            border-style: solid;
            border-width: 1px;
            margin: 5px;
            margin-top: 0px;
    "></div>
        Non
        <div style="width: 100px"></div>
        Commentaire:
        <div style="
            width: 300px;
            height: 120px;
            border-style: solid;
            border-width: 1px;
            margin: 5px;
            margin-top: 0px;
    "></div>
    </div>
    </p>


    <p>
        <b><?php echo $Data['question3']; ?></b>
    <div style="
            display: flex;
">
        <div style="
            width: 15px;
            height: 15px;
            border-style: solid;
            border-width: 1px;
            margin: 5px;
            margin-top: 0px;
    "></div>
        Oui
        <div style="
            width: 15px;
            height: 15px;
            border-style: solid;
            border-width: 1px;
            margin: 5px;
            margin-top: 0px;
    "></div>
        Non
        <div style="width: 100px"></div>
        Commentaire:
        <div style="
            width: 300px;
            height: 120px;
            border-style: solid;
            border-width: 1px;
            margin: 5px;
            margin-top: 0px;
    "></div>
    </div>
    </p>

    <p>
        <b><?php echo $Data['question4']; ?></b>
    <div style="
            display: flex;
">
        <div style="
            width: 15px;
            height: 15px;
            border-style: solid;
            border-width: 1px;
            margin: 5px;
            margin-top: 0px;
    "></div>
        Oui
        <div style="
            width: 15px;
            height: 15px;
            border-style: solid;
            border-width: 1px;
            margin: 5px;
            margin-top: 0px;
    "></div>
        Non
        <div style="width: 100px"></div>
        Commentaire:
        <div style="
            width: 300px;
            height: 120px;
            border-style: solid;
            border-width: 1px;
            margin: 5px;
            margin-top: 0px;
    "></div>
    </div>
    </p>
</div>
</body>

<script>
    window.print();
</script>
