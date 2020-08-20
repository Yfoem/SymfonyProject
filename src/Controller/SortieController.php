<?php

namespace App\Controller;





use App\Entity\Etats;
use App\Entity\Inscription;
use App\Entity\Participants;
use App\Entity\Sorties;
use App\Form\SortieType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class SortieController extends AbstractController
{
    /**
     * @Route("/sortie", name="sortie")
     *
     */
    public function index()
    {
        //On récupère l'entité Sortie
        $repo = $this->getDoctrine()->getRepository(Sorties::class);
        //On récupère tout ce qu'il y a dans l'entité Sortie
        $sorties = $repo->findAll();

        //Retour sur la page des sorties
        return $this->render('sortie/index.html.twig', [
            'controller_name' => 'SortieController',
            'sorties' => $sorties
        ]);
    }


    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        //Retour sur la page des sorties
        return $this->render('sortie/home.html.twig');
    }


    /**
     * @Route ("/sortie/new", name="sortie_create")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */

    public function form(Request $request, EntityManagerInterface $em)
    {


        $error = $success = "";
        $sortie = new Sorties();
        //Création d'un formulaire de sortie
        $createForm = $this->createForm(SortieType::class, $sortie);
        //On fait une requête
        $createForm->handleRequest($request);

        //On vérifie si le formulaire est valid et enregistré
        if ($createForm->isSubmitted() && $createForm->isValid()) {

            $dateDebut = $sortie->getDatedebut();
            $dateFin = $sortie->getDatecloture();
            $dateNow = DateTime::createFromFormat('Y-m-d', date('now'));

            //Si la date du début de sortie est plus petit que la date du jour alors un message d'erreur s'affiche
            if ($dateDebut <= $dateNow)
            {
                $error = "la date de début ne peux pas être celle du jours ou passée";
            }
            //Si la date de cloture est plus petit que la date du jour alors un message d'erreur s'affiche
            else if ($dateFin <= $dateNow)
            {
                $error = "la date de cloture ne peux pas être celle du jours ou passée";
            }
            //Si la date de cloture est plus garnde alors un message d'erreur s'affiche
            else if ($dateFin >= $dateDebut)
            {
                $error = "la date de cloture ne peux pas être celle du début ou après";
            }
            //sinon un message de succes s'affiche et on envoie les donnés à la base de donnée
            else
                {
                $sortie->setOrganisateur($this->getUser()->getId());

                $etat = $this->getDoctrine()->getManager()->getRepository(Etats::class)->find(1);
                $sortie->setEtatsId($etat);


                $success = "La sortie a bien été créé";
                $em->persist($sortie);
                $em->flush();
                }


        }
        //Retour a la page de création de sortie
        return $this->render('sortie/create.html.twig',
            [
            "createForm" => $createForm->CreateView(),
            "error" => $error, "success" => $success
            ]);
    }

    /**
     * @Route ("/sortie/{id}", name="sortie_show")
     */
    public function show(Sorties $sorties)
    {
        //on récupère dans l'entité inscription, la classe sortie_no_sortie grâce à findBy
        $inscriptions = $this->getDoctrine()->getRepository(Inscription::class)->findBy(["sorties_no_sortie" => $sorties]);

        //On vérifie que la date de cloture des inscriptions soir plus petite avec la date d'aujourd'hui
        $isClotured = $sorties->getDatecloture() <= DateTime::createFromFormat('Y-m-d', date('now'));

        //On récupère l'Id de l'organisateur
        $isOrga = $sorties->getOrganisateur() == $this->getUser()->getId();
        $estInscrit = false;

        //On fait une boucle foreach d'inscriptions pour répérer toutes les inscriptions
        foreach ($inscriptions as $inscription)
        {
            //on regarde l'égalité entre le participant et l'Id si c'est égal alors il est inscrit
            if ($inscription->getParticipantsNoParticipant()->getId() == $this->getUser()->getId())
            {
                $estInscrit = true;
            }
        }


        //On retourne sur la page des sorties
        return $this->render('sortie/show.html.twig', [
            'sorties' => $sorties, 'isOrga' => $isOrga, 'isClotured' => $isClotured,
            'isInscribed' => $estInscrit, 'inscriptions' => $inscriptions
        ]);
    }

    /**
     * @Route ("/supprimer/{id}", name="supprimer_sortie")
     * @param Sorties $sortie
     */
    public function supprimer(Sorties $sortie, EntityManagerInterface $em)
    {

        $error = $success = "";

        //On récupère la classe sortie_no_sortie dans l'entité Inscription à l'aide de findBy
        $inscriptions = $this->getDoctrine()->getRepository(Inscription::class)->findBy(['sorties_no_sortie' => $sortie]);

        //on supprime la sortie et on envoie à la base de donnée
        foreach($inscriptions as $inscription) {
            $em->remove($inscription);
        }
        $em->remove($sortie);
        $em->flush();

        // redirect Home + message succès duppresion ou error suppression
        return $this->redirectToRoute('home');

    }

    /**
     * @Route ("/desinscrire/{id}", name="desinscription_show")
     * @param Sorties $sortie
     * @param EntityManagerInterface $em
     */
    public function desinscrire(Sorties $sortie, EntityManagerInterface $em)
    {
        //On récupère l'Id du user dans l'entité participant
        $user = $this->getDoctrine()->getRepository(Participants::class)->find($this->getUser()->getId());

        //On récupère le participant et la sortie dans l'entité Inscription grâce à findOneBy
        $inscription = $this->getDoctrine()->getRepository(Inscription::class)->findOneBy(['sorties_no_sortie' => $sortie, 'participants_no_participant' => $user]);

        //On supprime l'inscription puis on envoie à la base de donnée
        $em->remove($inscription);
        $em->flush();

        // redirect to Route showSortie avec rajout de $sortie->getId()
        return $this->redirectToRoute('sortie_show', ['id' => $sortie->getId()]);

    }

    /**
     * @Route ("/inscrire/{id}", name="sinscrire_show")
     * @param Sorties $sortie
     * @return Response
     */
    public function sinscrire(Sorties $sortie, EntityManagerInterface $em)
    {
        //On récupère l'Id du user dans l'entité participant
        $user = $this->getDoctrine()->getRepository(Participants::class)->find($this->getUser()->getId());

        //On instancie Inscription pour mettre le user, la sortie et la date pour
        // envoyer à la base de donnée
        $inscription = new Inscription();
        $inscription->setParticipantsNoParticipant($user);
        $inscription->addSortiesNoSortie($sortie);
        $inscription->setDateInscription(new DateTime());
        $em->persist($inscription);
        $em->flush();

        // redirect to Route showSortie avec rajout de $sortie->getId()
        return $this->redirectToRoute('sortie_show',
            [
                'id' => $sortie->getId()
            ]);

    }






}

