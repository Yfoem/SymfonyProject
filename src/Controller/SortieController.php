<?php

namespace App\Controller;


use App\Entity\Participants;
use App\Entity\Sorties;

use App\Form\InscritptionType;

use App\Form\SortieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SortieController extends AbstractController
{
    /**
     * @Route("/sortie", name="sortie")
     */
    public function index()
    {
        dump($this->getUser());
        return $this->render('sortie/index.html.twig', [
            'controller_name' => 'SortieController',
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home(){
        return $this->render('sortie/home.html.twig');
    }

    /**
     * @Route("sortie/show", name="sortie_show")
     */
    public function show(){

        return $this->render('sortie/show.html.twig');
    }

    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder){

        $user = new Participants();
        $form = $this->createForm(InscritptionType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $hash = $encoder->encodePassword($user, $user->getPassword());

            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('security_login');
        }

        return $this->render('sortie/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/connexion", name="security_login")
     */
    public function login(){
        return $this->render('sortie/login.html.twig');
    }

    /**
     * @Route ("/sortie/new", name="sortie_create")

     */
    public function form(Sorties $sorties = null, Request $request, EntityManagerInterface  $manager){

        if(!$sorties) {
            $sorties = new Sorties();
        }

        $form = $this->createForm(SortieType::class,$sorties);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if(!$sorties->getId()) {
                $sorties->setDatedebut(new \DateTime());
            }
            $manager->persist($sorties);
            $manager->flush();

            return $this ->redirectToRoute('sortie_show', ['id'=> $sorties->getId()]);
        }

        return $this->render('sortie/create.html.twig', [
            'formSortie' => $form->createView(),
            'editMode' => $sorties->getId() != null
        ]);
    }
}
