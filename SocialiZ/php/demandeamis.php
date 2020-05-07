<?php
include "../classes/utilisateur.php";
include "../vue/enteteconnectée.html";
include "../vue/menudenavigation.php";
include "../vue/demandeamis.php";

if (isset($_POST["accepter_ami3"])){

    $ami= new Utilisateur($dnsh,$user,$pass);
    $ami->AccepterUneDemandeFerifiee($utilisateur);
}