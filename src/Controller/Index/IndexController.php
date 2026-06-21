<?php declare(strict_types=1);

namespace App\Controller\Index;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/', name: 'index_index')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class IndexController extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('index/index.html.twig', [
            'races' => []
        ]);
    }
}
