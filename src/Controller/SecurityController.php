<?php

namespace App\Controller;

use App\Entity\Participants;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }

    /**
     * @Route("/signup", name="app_signup")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function signUp(Request $request,EntityManagerInterface $em,UserPasswordEncoderInterface $encoder)
    {
        $error= $success ="";
        $user = new Participants();
        $registerForm= $this->createForm(RegisterType::class,$user);

        $registerForm->handleRequest($request);
        if($registerForm->isSubmitted() && $registerForm->isValid()){

            $findUserEmail = $this->getDoctrine()->getManager()->getRepository(Participants::class)->findOneBy(array("email"
            => $user->getEmail()));
            $findUserPseudo = $this->getDoctrine()->getManager()->getRepository(Participants::class)->findOneBy(array("pseudo"
            => $user->getPseudo()));

            if(empty($findUserEmail)) {
                if(empty($findUserPseudo)) {
                    $hashed = $encoder->encodePassword($user, $user->getPassword());
                    $user->setPassword($hashed);
                    $user->setNom('');
                    $user->setPrenom('');
                    $user->setActif(true);
                    $user->setRoles(['ROLE_USER']);
                    $success ="Le compte a bien été créé";
                    $em->persist($user);
                    $em->flush();
                }else {
                    $error = "le pseudo est déjà utilisé.";
                }
            }else {
                $error = "L'adresse mail est déjà utilisée.";
            }



        }
        return $this->render('security/signup.html.twig', ["registerForm"=>$registerForm->CreateView(),"error"=>$error,"success"=>$success]);
    }


    /**
     * @Route("/login", name="app_login")
     */
    public function login( AuthenticationUtils  $authenticationUtils)
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user

        return $this->render('security/login.html.twig',['error'=>$error]);
    }
    /**
     * @Route("/forgot", name="app_forgot")
     */
    public function forgot()
    {

    }
}
