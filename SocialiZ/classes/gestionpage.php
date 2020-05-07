<?php

include "utilisateur.php";

class gestionpage
{
    private $pageActuelle;
    private $pageDefault = "./pages/accueil.php";
    private $page404 = "./pages/404.php";
    private $connected;

    //
    public function afficherHeader()
    {

        if ($this->connected) {
            include "../php/accueil.php?id_utilisateur=".$_SESSION['id_utilisateur'];
        } else {
            include "../php/accueil.php";
        }
    }

    //setter pour la page par défault
    public function setDefault(string $default)
    {
        $this->pageDefault =  "pages/".$default.".php";
    }

    public function isActuelle($page): bool
    {
        return "./pages/".$page.".php" === $this->pageActuelle;
    }

    //constructeur auquel on peut passer une page par défault
    public function __construct( $default = false)
    {


        if($default)
        {
            $this->setDefault( $default);
        }

        if(isset($_GET["page"]))
        {

            $this->pageActuelle = "./pages/".$_GET["page"].".php";

            //on test si la page existe
            //si elle existe on la charge
            //sinon on charge la page 404
            file_exists($this->pageActuelle) ? require $this->pageActuelle : require $this->page404;

        } else
        {
            //si on n'a pas founrit de page dans le tableau $_GET
            //on affiche la page par defaut
            require $this->pageDefault;
        }
    }

}