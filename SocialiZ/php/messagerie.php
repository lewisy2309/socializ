<?php
include "../classes/utilisateur.php";
include "../vue/enteteconnectée.html";
include "../vue/menudenavigation.php";
include "../vue/messagerie.php";



if(isset($_POST['envoyer_message'])){
    if (isset($_POST['envoyer_message']) && !empty($_POST['envoyer_message'])){
        $message=htmlspecialchars($_POST['message_contenu']);
        $envoyermessage=new Utilisateur($dnsh,$user,$pass);
        $envoyermessage->EnvoyerMessage($message,$infosocializer);
    }
}

//$messages = $db->query("SELECT *
//FROM message
//WHERE id_message=13");
//$message=$messages->fetch();
//if($message['id_message']==13){
//    echo sha1($message['message_contenu']);
//}
