<?php
require("../php/connexiondb.php");
if(isset($_GET['user'])){
    $recherche_utilisateur=htmlspecialchars($_GET['user']);
    $recherche_utilisateur=(string) trim($recherche_utilisateur);
    $req_nom=$db->prepare('SELECT * FROM utilisateur where utilisateur_nom like ? OR utilisateur_prenom like ? LIMIT 15' );
//    $req_prenom=$db->query('SELECT * FROM utilisateur where utilisateur_prenom like ? LIMIT 15');
    $req_nom->execute(array("%".$recherche_utilisateur."%", "%".$recherche_utilisateur."%"));
//    $req_prenom->execute(array('%$recherche_utilisateur%'));

    $req_nom=$req_nom->fetchAll();
    foreach ($req_nom as $nom_complet){
        ?>
        <a href="../php/visionnageprofil.php?id_utilisateur=<?php echo $nom_complet['id_utilisateur']; ?>" style="display:flex;flex-direction: row;padding: 15px 0; border-bottom: 2px solid #ec008c; width: 100%">
            <?=$nom_complet['utilisateur_nom']." ".$nom_complet['utilisateur_prenom']; ?>
        </a>
    <?php
    }
}
?>

