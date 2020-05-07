<?php
session_start();
include "connexiondb.php";
include "../classes/utilisateur.php";
$dnsh = "mysql:host=localhost;dbname=socializ";
$user = "root";
$pass = "";
if (isset($_POST["connexion"])) {
    $utilisateur = new utilisateur($dnsh, $user, $pass);
    $utilisateur->connecter();
}
include "../vue/entete.html";
include "../vue/connexion.html";
