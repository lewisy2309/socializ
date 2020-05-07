<?php
include "../classes/utilisateur.php";
include "../vue/enteteconnectée.html";
include "../vue/menudenavigation.php";
include "../vue/meetingroupe.php";

if(isset($_POST['creer_groupe'])){
    $nomgroupe=htmlspecialchars($_POST['nom_groupe']);
    if(isset($nomgroupe) && !empty($nomgroupe)){
        $groupe= new Utilisateur($dnsh,$user,$pass);
        $groupe->creerGroupe($nomgroupe);
    } else { echo "donnez un nom à votre groupe";}
}
