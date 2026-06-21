<?php declare(strict_types=1);

namespace App\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/logout', name: 'logout')]
final class LogoutController extends AbstractController
{
    public function __invoke(): never
    {
        throw new \LogicException('This method should never be reached.');
    }
}
