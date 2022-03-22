<?php
// src/Controller/LuckyController.php
namespace App\Controller;
//DEMO https://symfony.com/doc/current/page_creation.html
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LuckyController extends AbstractController
{
    public function number(): Response
    {
        $number = random_int(0, 100);

       // return new Response('<html><body>Lucky number: '.$number.'</body></html>');
        return $this->render('lucky/number.html.twig', [
            'number' => $number,
        ]);
    }
}