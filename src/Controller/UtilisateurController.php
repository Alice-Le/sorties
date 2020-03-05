<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\InscriptionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UtilisateurController extends AbstractController
{
    /**
     * @Route("/inscription", name="inscription")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     * @throws \Exception
     */
    public function inscription(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $utilisateur = new Utilisateur();
        $utilisateur->setDateCreation(new \DateTime());
        $inscriptionForm = $this->createForm(InscriptionType::class, $utilisateur);

        $inscriptionForm->handleRequest($request);
        if ($inscriptionForm->isSubmitted() && $inscriptionForm->isValid()) {

            $hashed = $encoder->encodePassword($utilisateur, $utilisateur->getMotDePasse());
            $utilisateur->setMotDePasse($hashed);
            $em->persist($utilisateur);
            $em->flush();
        }
        return $this->render('utilisateur/inscription.html.twig', [
            "inscriptionForm" => $inscriptionForm->createView()
        ]);
    }


    /**
     * @Route("/login", name="login")
     */
    public function login()
    {
        return $this->render("utilisateur/login.html.twig", [

        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
    }

        /**
     * @Route("/profil/{id}", name="profil",
     *     requirements={"id": "\d+"},
     *     methods={"GET"})
     * @param $id
     * @param Request $request
     * @return Response
     */

    public function profil($id, Request $request)
    {
        $utilisateurRepo = $this->getDoctrine()->getRepository(Utilisateur::class);
        $utilisateur = $utilisateurRepo->find($id);

        return $this->render("utilisateur/profil.html.twig", [
            "utilisateur" => $utilisateur
        ]);
    }

    //    /**
//     * @Route("/profil/modifier{id}", name="profil_modifier",
//     *     requirements={"id": "\d+"},
//     *     methods={"GET"})
//     * @param $id
//     * @param EntityManagerInterface $em
//     * @return Response
//     */
//    public function modifierprofil($id, EntityManagerInterface $em)
//    {
//        $utilisateurRepo = $this->getDoctrine()->getRepository(Utilisateur::class);
//        $utilisateur = $utilisateurRepo->find($id);
//
//        $em->persist($participant);
//        $em->flush();
//
//        return $this->render("utilisateur/modifierProfil.html.twig", [
//            "utilisateur" => $utilisateur->createView()
//        ]);
//    }

}
