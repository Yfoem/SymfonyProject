<?php

namespace App\Controller;




use App\Entity\Etats;
use App\Entity\Lieux;
use App\Entity\Participants;
use App\Entity\Sorties;


use App\Entity\Villes;
use App\Form\InscritptionType;


use App\Form\SortieType;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Array_;
use phpDocumentor\Reflection\Types\Collection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SortieController extends AbstractController
{
    /**
     * @Route("/sortie", name="sortie")
     *
     */
    public function index()
    {
        $post = array_map('strip_tags', $_POST);
        if($post!=null && is_numeric($post['ville']) && $post['ville']!= 0){

            $villeToFilter = $this->getDoctrine()->getRepository(Villes::class)->find($post['ville']);
            $lieux =  $this->getDoctrine()->getRepository(Lieux::class)->findBy(['villes_no_ville'=> $villeToFilter]);
            $sorties=$this->getDoctrine()->getRepository(Sorties::class)->findBy(['lieux_id'=>$lieux]);

        }else {
            $sorties = $this->getDoctrine()->getRepository(Sorties::class)->findAll();
        }
        $villes=  $this->getDoctrine()->getRepository(Villes::class)->findAll();
        return $this->render('sortie/index.html.twig', [
            'controller_name' => 'SortieController',
            'sorties' => $sorties,'villes'=>$villes,
        ]);
    }




    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('sortie/home.html.twig');
    }



    /**
     * @Route ("/sortie/new", name="sortie_create")
     */
    public function form()
    {


        $error = $success = $lastNom = $lastDatedebut = $lastDuree = $lastDatecloture = $lastNbinscriptionmax =
        $lastDescriptioninfos = $lastUrlphoto = $lastLieux ="";

        $lieux = $this->getDoctrine()->getRepository(Lieux::class)->findAll();




            if (isset($_POST['submit']) && $_POST['submit'] == "signUp") {
                $post = array_map('strip_tags', $_POST);
                $lastNom = $post['nom'];
                $lastDatedebut = $post['datedebut'];
                $lastDuree = $post['duree'];
                $lastDatecloture = $post['datecloture'];
                $lastNbinscriptionmax = $post['nbinscriptionmax'];
                $lastDescriptioninfos = $post['descriptioninfos'];
                $lastUrlphoto = $post['urlphoto'];
                $lastLieux = $post['lieux_id'];

                if (
                    isset($post['nom']) && !empty($post['nom']) &&
                    isset($post['datedebut']) && !empty($post['datedebut']) &&
                    isset($post['duree']) && !empty($post['duree']) &&
                    isset($post['datecloture']) && !empty($post['datecloture']) &&
                    isset($post['nbinscriptionmax']) && !empty($post['nbinscriptionmax']) &&
                    isset($post['descriptioninfos']) && !empty($post['descriptioninfos']) &&
                    isset($post['lieux_id']) && !empty($post['lieux_id'])


                ) {
                    $dateDebut = DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime($post['datedebut'])));
                    $dateFin = DateTime::createFromFormat( 'Y-m-d', date('Y-m-d', strtotime($post['datecloture'])));
                    $dateNow = DateTime::createFromFormat('Y-m-d',date('now'));


                    if($dateDebut <= $dateNow){

                        $error = "La date de début ne peux être égale ou avant la date du jour";
                        $callback = array(
                            "error" => $error,
                            "success" => $success,
                            "last_nom" => $lastNom,
                            "last_datedebut" => $lastDatedebut,
                            "last_duree" => $lastDuree,
                            "last_datecloture" => $lastDatecloture,
                            "last_nbinscriptionsmax" => $lastNbinscriptionmax,
                            "last_descriptioninfos" => $lastDescriptioninfos,
                            "last_urlPhoto" => $lastUrlphoto,
                            "lieux_id" => $lastLieux,
                            "lieux"=> $lieux
                        );


                        return $this->render('sortie/create.html.twig', $callback);
                    }else {
                        if ($dateFin >= $dateDebut) {
                            $error = "La date de fin d'inscription ne peux être égale ou avant la date de debut";
                            $callback = array(
                                "error" => $error,
                                "success" => $success,
                                "last_nom" => $lastNom,
                                "last_datedebut" => $lastDatedebut,
                                "last_duree" => $lastDuree,
                                "last_datecloture" => $lastDatecloture,
                                "last_nbinscriptionsmax" => $lastNbinscriptionmax,
                                "last_descriptioninfos" => $lastDescriptioninfos,
                                "last_urlPhoto" => $lastUrlphoto,
                                "lieux_id" => $lastLieux,
                                "lieux"=> $lieux
                            );


                            return $this->render('sortie/create.html.twig', $callback);
                        } else {
                            if ($dateFin <= $dateNow) {
                                $error = "La date de fin d'inscription ne peux être égale ou avant la date du jour";
                                $callback = array(
                                    "error" => $error,
                                    "success" => $success,
                                    "last_nom" => $lastNom,
                                    "last_datedebut" => $lastDatedebut,
                                    "last_duree" => $lastDuree,
                                    "last_datecloture" => $lastDatecloture,
                                    "last_nbinscriptionsmax" => $lastNbinscriptionmax,
                                    "last_descriptioninfos" => $lastDescriptioninfos,
                                    "last_urlPhoto" => $lastUrlphoto,
                                    "lieux_id" => $lastLieux,
                                    "lieux"=> $lieux
                                );


                                return $this->render('sortie/create.html.twig', $callback);
                            }


                        }
                    }

                    // On récupère le manager
                    $manager = $this->getDoctrine()->getManager();


                    // On instancie un nouvel objet User
                    $sortie = new Sorties();

                    // On lui affecte ses attributs aux infos du formulaire
                    $sortie->setNom($post['nom']);
                    $sortie->setDatedebut($dateDebut);
                    $sortie->setDuree($post['duree']);
                    $sortie->setDatecloture($dateFin);
                    $sortie->setNbinscriptionsmax($post['nbinscriptionmax']);
                    $sortie->setDescriptioninfos($post['descriptioninfos']);
                    $sortie->setUrlPhoto($post['urlphoto']);
                    $sortie->setEtatsId($manager->getRepository(Etats::class)->find(1));
                    $sortie->setOrganisateur(1);
                    $sortie->setLieuxId($manager->getRepository(Lieux::class)->find($post['lieux_id']));




                    try {
                        // On persiste l'objet
                        $manager->persist($sortie);
                        // On le flush dans la bdd
                        $manager->flush();
                        $success = "Votre sortie a bien été créé.";
                        $lastNom = $lastDatedebut = $lastDuree = $lastDatecloture = $lastNbinscriptionmax =
                        $lastDescriptioninfos = $lastUrlphoto = "";

                        $callback = array(
                            "error" => $error,
                            "success" => $success,
                            "last_nom" => $lastNom,
                            "last_datedebut" => $lastDatedebut,
                            "last_duree" => $lastDuree,
                            "last_datecloture" => $lastDatecloture,
                            "last_nbinscriptionsmax" => $lastNbinscriptionmax,
                            "last_descriptioninfos" => $lastDescriptioninfos,
                            "last_urlPhoto" => $lastUrlphoto,
                            "lieux_id" => $lastLieux,
                            "lieux"=> $lieux,
                            'id' => $sortie->getLieuxId()
                        );
                        return $this->render('sortie/create.html.twig', $callback);

                    } catch (\Exception $e) {

                        $error = "Votre sortie n'a pas pu être créé.";
                        return $this->render('sortie/create.html.twig', ['error'=>$error]);
                    }


                }

            }



        $callback = array(
            "error" => $error,
            "success" => $success,
            "last_nom" => $lastNom,
            "last_datedebut" => $lastDatedebut,
            "last_duree" => $lastDuree,
            "last_datecloture" => $lastDatecloture,
            "last_nbinscriptionsmax" => $lastNbinscriptionmax,
            "last_descriptioninfos" => $lastDescriptioninfos,
            "last_urlPhoto" => $lastUrlphoto,
            "lieux_id" => $lastLieux,
            "lieux"=> $lieux
        );


        return $this->render('sortie/create.html.twig', $callback);
    }

    /**
     * @Route ("/sortie/{id}", name="sortie_show")
     */
    public function show(Sorties $sorties)
    {
        $inscriptions = $this->getDoctrine()->getRepository(Inscription::class)->findBy(["sorties_no_sortie" => $sorties]);
        $isClotured = $sorties->getDatecloture() <= DateTime::createFromFormat('Y-m-d', date('now'));
        $isOrga = $sorties->getOrganisateur() == $this->getUser()->getId();
        $estInscrit = false;
        foreach ($inscriptions as $inscription) {
            if ($inscription->getParticipantsNoParticipant()->getId() == $this->getUser()->getId()) {
                $estInscrit = true;
            }
        }
        return $this->render('sortie/show.html.twig', [
            'sorties' => $sorties,'isOrga'=>$isOrga,'isClotured'=>$isClotured,'isInscribed'=>$estInscrit,'inscriptions'=>$inscriptions
        ]);
    }

    /**
     * @Route ("/showProfile/{id}", name="profile_show")
     * @param Participants $user
     * @return Response
     */
    public function showProfile(Participants $user)
    {
        $sortiesOrga = $this->getDoctrine()->getRepository(Sorties::class)->findBy(['organisateur'=>$user->getId()]);
        return $this->render('sortie/profile.html.twig', [
            'user' => $user, 'sortiesOrga' => $sortiesOrga,
        ]);
    }

}

