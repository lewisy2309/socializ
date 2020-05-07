<?php
include "dbcon.php";
class Utilisateur
{
    private $nom;
    private $prenom;
    private $date_naissance;
    private $email;
    private $genre;
    private $mot_de_passe;
    private $_conn;

    public function __construct($dnsh, $user, $pass)
    {
        $this->_conn = dbcon::pdo_connection($dnsh, $user, $pass);
    }

//    FONCTION D'INSCRIPTION DE L'UTILISATEUR
    public function inscrire()
    {
        $this->nom = htmlspecialchars($_POST["nom"]);
        $this->prenom = htmlspecialchars($_POST["prenom"]);
        $this->email = htmlspecialchars($_POST["email"]);
        $this->genre = htmlspecialchars($_POST["genre"]);
        $this->date_naissance = $_POST["date_naissance"];
        $this->mot_de_passe = sha1($_POST["mot_de_passe"]);

        if (isset($_POST["nom"]) && !empty($_POST["nom"])) {
            if (isset($_POST["prenom"]) && !empty($_POST["prenom"])) {
                if (isset($_POST["date_naissance"]) && !empty($_POST["date_naissance"])) {

                    if (isset($_POST["email"]) && !empty($_POST["email"]) && filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                        $reqmail = $this->_conn->prepare("SELECT * FROM utilisateur where utilisateur_email=?");
                        $reqmail->execute(array($this->email));
                        $mailexist = $reqmail->rowCount();
                        if ($mailexist == 0) {
                            if (isset($_POST["genre"]) && !empty($_POST["genre"])) {
                                if (isset($_POST["mot_de_passe"]) && !empty($_POST["mot_de_passe"]) && isset($_POST["confirmation_mot_de_passe"]) && !empty($_POST["confirmation_mot_de_passe"])) {
                                    if ($_POST["mot_de_passe"] == $_POST["confirmation_mot_de_passe"]) {
                                        $requete = $this->_conn->prepare("insert into utilisateur(utilisateur_nom,utilisateur_prenom,utilisateur_date_naissance,utilisateur_email,utilisateur_genre,utilisateur_mot_de_passe) values (?,?,?,?,?,?)");
                                        $requete->execute(array($this->nom, $this->prenom, $this->date_naissance, $this->email, $this->genre, $this->mot_de_passe));
                                        echo "vous avez bien été inscrit";
                                    } else {
                                        echo " votre mot de passe et votre mot de passe de confirmation son différents veuillez réessayer";
                                    }
                                } else {
                                    echo "veuillez renseigner votre mot de passe et le confirmer";
                                }

                            } else {
                                echo " veuillez s'il vous plait renseigner votre genre";
                            }

                        } else {
                            echo "email déjà utilisé";
                        }

                    } else {
                        echo "Votre email n'a pas pu être authentfié";
                    }

                } else {
                    echo "Veuillez s'il vous plait renseigner votre Date de naissance";
                }

            } else {
                echo "Veuillez s'il vous plait renseigner votre Prenom";
            }

        } else {
            echo "Veuillez s'il vous plait renseigner votre nom";
        }
    }

//FONCTION CONNEXION DE L'UTILISATEUR

    public function connecter()
    {
        $this->email = htmlspecialchars(($_POST["email"]));
        $this->mot_de_passe = sha1($_POST["mot_de_passe"]);

        if (!empty($_POST["email"]) and !empty($_POST["mot_de_passe"])) {

            $requser = $this->_conn->prepare("SELECT * FROM utilisateur WHERE utilisateur_email=? and utilisateur_mot_de_passe=? ");
            $requser->execute(array($this->email, $this->mot_de_passe));
            $utilisateurexist = $requser->rowCount();

            if ($utilisateurexist == 1) {
                $utilisateurinfo = $requser->fetch();
                $_SESSION['id_utilisateur'] = $utilisateurinfo['id_utilisateur'];
                $_SESSION['utilisateur_nom'] = $utilisateurinfo['utilisateur_nom'];
                $_SESSION['utilisateur_prenom'] = $utilisateurinfo['utilisateur_prenom'];
                $_SESSION['utilisateur_genre'] = $utilisateurinfo['utilisateur_genre'];
                $_SESSION['utilisateur_date_naissance'] = $utilisateurinfo['utilisateur_date_naissance'];
                $_SESSION['utilisateur_email'] = $utilisateurinfo['utilisateur_email'];
                header("location:profil.php?id_utilisateur=" . $_SESSION['id_utilisateur']);
                exit;

            } else {
                echo "vous n'avez pas été authentifié, veuillez réessayer";
            }


        } else {
            echo "tous les champs doivent être renseignés";
        }
    }

    public function AfficherSex()
    {
        if ($_SESSION['utilisateur_genre'] === "Masculin") {
            echo "<img src='../img/male.svg'/>";
        } elseif ($_SESSION['utilisateur_genre'] === "Féminin") {
            echo "<img src='../img/female.svg'/>";
        } else {
            echo "<img src='../img/transgender.svg'/>";
        }
    }

    public function GetAge()
    {
        $age = date('Y-m-d') - $_SESSION['utilisateur_date_naissance'];
        return $age;
    }

    public function AjouterAmi($utilisateur)
    {
        $valid = (bool)true;

        $req = $this->_conn->prepare("SELECT id_statut_amitie FROM statut_amitie WHERE (id_utilisateur_demandeur=? and id_utilisateur_receveur=?) or (id_utilisateur_demandeur=? and id_utilisateur_receveur=?)");
        $req->execute(array($_SESSION['id_utilisateur'], $utilisateur['id_utilisateur'], $utilisateur['id_utilisateur'], $_SESSION['id_utilisateur']));
        $verificationstatut = $req->fetch();
        if (isset($verificationstatut['id_statut_amitie'])) {
            $valid = false;
        }

        if ($valid) {
            $envoiedemande = $this->_conn->prepare('INSERT INTO statut_amitie(id_utilisateur_demandeur,id_utilisateur_receveur,statut_amitie_statut) values (?,?,?)');
            $envoiedemande->execute(array($_SESSION['id_utilisateur'], $utilisateur['id_utilisateur'], "demande envoyee",));
            header('location:../php/visionnageprofil.php?id_utilisateur=' . $utilisateur['id_utilisateur']);
        }
    }

    public function BloquerUtilisateur($utilisateur)
    {
        $valid = (bool)true;

        $req = $this->_conn->prepare("SELECT id_statut_amitie FROM statut_amitie WHERE (id_utilisateur_demandeur=? and id_utilisateur_receveur=?) or (id_utilisateur_demandeur=? and id_utilisateur_receveur=?)");
        $req->execute(array($_SESSION['id_utilisateur'], $utilisateur['id_utilisateur'], $utilisateur['id_utilisateur'], $_SESSION['id_utilisateur']));
        $verificationstatut = $req->fetch();
        if (isset($verificationstatut['id_statut_amitie'])) {
            $valid = false;
        }

        if ($valid) {
            $envoiedemande = $this->_conn->prepare('INSERT INTO statut_amitie(id_utilisateur_demandeur,id_utilisateur_receveur,statut_amitie_statut) values (?,?,?)');
            $envoiedemande->execute(array($_SESSION['id_utilisateur'], $utilisateur['id_utilisateur'], "bloque",));
            header('location:../php/visionnageprofil.php?id_utilisateur=' . $utilisateur['id_utilisateur']);
        } else {
            $envoiedemande = $this->_conn->prepare('UPDATE statut_amitie SET statut_amitie_statut=? WHERE id_statut_amitie=? AND ((id_utilisateur_demandeur=? AND id_utilisateur_receveur=?) OR (id_utilisateur_demandeur=? AND id_utilisateur_receveur=?)) ');
            $envoiedemande->execute(array("bloque", $verificationstatut['id_statut_amitie'], $_SESSION['id_utilisateur'], $utilisateur['id_utilisateur'], $utilisateur['id_utilisateur'], $_SESSION['id_utilisateur']));
        }
    }


    public function AccepterAmi($utilisateur)
    {
        $valid = (bool)true;

        $valid = (bool)true;

        $req = $this->_conn->prepare("SELECT id_statut_amitie FROM statut_amitie WHERE statut_amitie_statut=? AND id_utilisateur_demandeur=? AND id_utilisateur_receveur=?");
        $req->execute(array("demande envoyee", $utilisateur['id_utilisateur'], $_SESSION['id_utilisateur']));
        $verificationstatut = $req->fetch();
        echo "la";
        if (isset($verificationstatut['id_statut_amitie'])) {
            $valid = false;
        }

        if ($valid) {
        } else {
            echo "ici";
            $accepterami = $this->_conn->prepare('UPDATE statut_amitie SET statut_amitie_statut=? WHERE id_statut_amitie=? AND id_utilisateur_demandeur=? AND id_utilisateur_receveur=? ');
            $accepterami->execute(array("amis", $verificationstatut['id_statut_amitie'], $utilisateur['id_utilisateur'], $_SESSION['id_utilisateur']));
        }
    }


    public function AccepterUneDemandeFerifiee($utilisateur)
    {
        $accepterami = $this->_conn->prepare('UPDATE statut_amitie SET statut_amitie_statut=? WHERE id_statut_amitie=? ');
        echo "erreur là";
        $accepterami->execute(array("amis", $utilisateur['id_statut_amitie']));
        echo "erreur ici";
    }

    public function EnvoyerMessage($message,$infosocializer)
    {
        $messages=$this->_conn->prepare('INSERT INTO message(message_contenu,message_id_utilisateur_from,message_id_utilisateur_to) values (?,?,?)');
        $messages->execute(array($message,$_SESSION['id_utilisateur'],$infosocializer['id_utilisateur']));
    }

    public function creerGroupe($nomgroupe){

        $groupe=$this->_conn->prepare('INSERT INTO groupe(groupe_nom,groupe_id_createur) values (?,?)');
        $groupe->execute(array($nomgroupe,$_SESSION['id_utilisateur']));
    }

}






