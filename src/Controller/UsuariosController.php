<?php

namespace App\Controller;

use App\Form\UsuariosType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsuariosController extends AbstractController{

    #[Route("/insert/usuario", name:"insertUsuario")]
    public function insertUsuario(
        EntityManagerInterface $doctrine, 
        Request $request, 
        UserPasswordHasherInterface $cifrado
        ) {
        
        $form=$this-> createForm(UsuariosType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()and $form->isValid())
        {
            $user=$form->getData();
            $password= $user->getPassword();
            $passwordCifrada = $cifrado->hashPassword($user, $password);
            $user->setPassword($passwordCifrada);

            $doctrine->persist($user);

            $doctrine->flush();
            return $this->redirectToRoute('listTaxis');
        }
        return $this->renderForm('Taxi/insertTaxi.html.twig', ['taxiForm'=>$form]);
    }

    #[Route("/insert/admin", name:"insertAdmin")]
    public function insertAdmin(
        EntityManagerInterface $doctrine, 
        Request $request, 
        UserPasswordHasherInterface $cifrado
        ) {
        
        $form=$this-> createForm(UsuariosType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()and $form->isValid())
        {
            $user=$form->getData();
            $password= $user->getPassword();
            $passwordCifrada = $cifrado->hashPassword($user, $password);
            $user->setPassword($passwordCifrada);
            $user->setRoles(["ROLE_ADMIN", "ROLE_USER"]);

            $doctrine->persist($user);

            $doctrine->flush();
            return $this->redirectToRoute('listTaxis');
        }
        return $this->renderForm('Taxi/insertTaxi.html.twig', ['taxiForm'=>$form]);
    }
}