<?php

include "connexiondb.php";
include "../classes/utilisateur.php";
include "../vue/entete.html";
include "../vue/inscription.html";
$dnsh = "mysql:host=localhost;dbname=socializ";
$user = "root";
$pass = "";

if(isset($_POST["inscription"])){
    $utilisateur = new utilisateur($dnsh,$user,$pass);
    $utilisateur ->inscrire();
}


