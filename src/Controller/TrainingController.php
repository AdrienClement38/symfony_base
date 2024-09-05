<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TrainingRepository;
use App\Entity\School;
use App\Entity\Training;
use App\Entity\Module;

class TrainingController extends AbstractController
{
    #[Route('/search_training', name: 'training_list')]
    public function list(EntityManagerInterface $em, TrainingRepository $trainingRepository, Request $request)
    {
        // Get all modules for the filter checkboxes
        $modules = $em->getRepository(Module::class)->findAll();

        // Get selected module IDs from the request
        $selectedModules = $request->query->all('modules');

        // Check if "Match Any Module" is selected
        $matchAnyModule = $request->query->getBoolean('match_any_module', false);

        if (empty($selectedModules)) {
            // If no modules selected, show all trainings
            $trainings = $trainingRepository->findAll();
        } else {
            if ($matchAnyModule) {
                // If "Match Any Module" is selected, find trainings with at least one module
                $trainings = $trainingRepository->findByAnyModule($selectedModules);
            } else {
                // Otherwise, find trainings with all selected modules
                $trainings = $trainingRepository->findByModules($selectedModules);
            }
        }

        return $this->render('training/list.html.twig', [
            'trainings' => $trainings,
            'selectedModules' => $selectedModules,
            'modules' => $modules,
            'matchAnyModule' => $matchAnyModule, // Pass the checkbox state to the view
        ]);
    }

    #[Route('/trainings/{id}', name: 'training_manage')]
    public function manage(int $id, EntityManagerInterface $em, Request $request): Response
    {
        $trainings = $em->getRepository(Training::class)->findAll();
        $modules = $em->getRepository(Module::class)->findAll();
        $selectedModules = $request->query->all('modules');
        $matchAnyModule = $request->query->getBoolean('match_any_module', false);

        return $this->render('training/manage.html.twig', [
            'trainings' => $trainings,
            'modules' => $modules,
            'selectedModules' => $selectedModules,
            'matchAnyModule' => $matchAnyModule,
        ]);
    }

    #[Route('/trainings/{trainingId}/modules/{moduleId}/delete', name: 'module_delete')]
    public function deleteModule(int $trainingId, int $moduleId, EntityManagerInterface $em): RedirectResponse
    {
        $training = $em->getRepository(Training::class)->find($trainingId);
        $module = $em->getRepository(Module::class)->find($moduleId);

        if ($training && $module) {
            $training->removeModule($module);
            $em->persist($training);
            $em->flush();
        }

        return $this->redirectToRoute('training_manage', ['id' => $trainingId]);
    }
}
