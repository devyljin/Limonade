<?php
namespace Agrume\Limonade\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LimonadeController extends AbstractController
{
    #[Route('/limonade', name: 'limonade_index')]
    public function index(): Response
    {
        return new Response('Bienvenue dans le bundle Limonade !');
    }
}
