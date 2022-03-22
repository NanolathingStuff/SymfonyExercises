<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    
    public function index($page): Response{
        return $this->render('test/test.html.twig', [
            'controller_name' => 'TestController'.$page,
        ]);
    }
    /**
     * oldMethod
     * 
     * @Route("/test/old", name="old")
     */
    public function oldMethod(): Response{
        //another way to route: annotation above
        return $this->json([
            'message' => 'OldMethod.',
            'path' => 'src/Controller/TestController.php',
        ]);
    }
    #[Route('/test/route/{id}', name:"route", methods: ['GET', 'HEAD'])]
    public function routMethod($id = 1): Response{
        //another way to route: annotation above
        return $this->json([
            'message' => 'RoutMethod.'.$id,
            'path' => 'src/Controller/TestController.php',
        ]);
    }
}
