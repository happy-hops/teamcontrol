<?php

namespace App\Controller;

use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class IndexController extends AbstractController
{
    public function __construct(
    )
    {}

    #[Route('/', name: 'app_index')]
    public function indexAction(): Response
    {
        return $this->render('index/index.html.twig', [
            'races' => []
        ]);
    }
}
