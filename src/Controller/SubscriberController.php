<?php
// src/Controller/LuckyController.php
namespace App\Controller;
//DEMO https://symfony.com/doc/current/page_creation.html

use App\Entity\Subscriber;
use App\Form\SubscriberFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;
use Symfony\Component\Routing\Annotation\Route;


class SubscriberController extends AbstractController
{
    #[Route('/subscriber/show', name:"show")]
    public function show(Environment $twig, Request $request, EntityManagerInterface $entityManager) {
        
        $subscriber = new Subscriber();
        $form = $this->createForm(SubscriberFormType::class, $subscriber);
        //Request to handle the submission of the form
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
        //EntityManagerInterface = DB
            $entityManager->persist( $subscriber ); //save
            $entityManager->flush();

            return new Response('Subscriber number '.$subscriber->getId().' has been successfully created.');
        }

        return new Response($twig->render('subscriber/show.html.twig', [
            'subscriber_form' => $form->createView(),
        ]));
    }
}