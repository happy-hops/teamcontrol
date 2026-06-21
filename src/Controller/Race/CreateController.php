<?php declare(strict_types=1);

namespace App\Controller\Race;

use App\Entity\Race;
use App\Form\RaceFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/races', name: 'race_create', methods: ['POST'])]
#[IsGranted('ROLE_ADMIN')]
final class CreateController extends AbstractController
{
    public function __invoke(
        Request $request,
        EntityManagerInterface $em,
    ): Response
    {
        $form = $this->createForm(RaceFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $race = $form->getData();
            assert($race instanceof Race);

            $race->setSlug($this->generateSlug($race->name));
            $em->persist($race);
            $em->flush();
            $this->addFlash('success', 'Rennen wurde erfolgreich angelegt.');

            return $this->redirectToRoute('race_settings', ['id' => $race->id]);
        }

        return $this->render('race/new.html.twig', ['form' => $form]);
    }

    private function generateSlug(string $name): string
    {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[äÄ]/', 'ae', $slug);
        $slug = preg_replace('/[öÖ]/', 'oe', $slug);
        $slug = preg_replace('/[üÜ]/', 'ue', $slug);
        $slug = preg_replace('/[ß]/', 'ss', $slug);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        return trim($slug, '-') ?: uniqid('race-');
    }
}
