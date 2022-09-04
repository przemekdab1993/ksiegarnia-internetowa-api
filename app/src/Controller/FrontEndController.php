<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class FrontEndController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(SerializerInterface $serializer): Response
    {
        return $this->render('front_end/index.html.twig', [
            'controller_name' => 'FrontEndController',
            'error' => null,
            'user' => $serializer->serialize($this->getUser(), 'jsonld')
        ]);
    }
}
