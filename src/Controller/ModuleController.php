<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\School;
use App\Entity\Training;
use App\Entity\Module;
use App\Repository\TrainingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

class ModuleController extends AbstractController
{
     /**
     * @Route("/addschool", name="school")
     */
    public function addSchoolAction()
    {
       $em = $this->getDoctrine()->getManager();
        
        $em = $this->getDoctrine()->getManager();
        $s = new School();
        $s->setName("3WA");
        $s->setDescription("Ecole dans le 18 eme ");
       
        $t = new Training();
        $t->setName("400h");
        $t->setDescription("devenir developpeur web en 3 mois");
        $t->setSchool($s);


        $m = new Module();
        $m->setName("PHP");
        $m->setDescription("Base de PHP");

        $m2 = new Module();
        $m2->setName("JS");
        $m2->setDescription("Base de JS");

        $t->addModule($m);
        $t->addModule($m2);

        $em->persist($s);
        $em->persist($t);
        $em->persist($m);
        $em->persist($m2);
        $em->flush();
        die("end");
    }

    #[Route('/schools', name: 'show_schools')]
    public function schoolsAction(EntityManagerInterface $em)
    {
        $repository = $em->getRepository(School::class);
        $schools = $repository->findAll();
         return $this->render('school/schools.html.twig', [
            'schools' => $schools,
        ]);
    }

    #[Route('/school/{id}', name: 'show_school')]
    public function schoolAction(EntityManagerInterface $em, $id)
    {
        $repository = $em->getRepository(School::class);
        $school = $repository->find($id);
         return $this->render('school/school.html.twig', [
            'school' => $school,
        ]);
    }

    #[Route('/training/{id}', name: 'show_training')]
    public function trainingAction(EntityManagerInterface $em, $id)
    {
        $repository = $em->getRepository(Training::class);
        $t = $em->getRepository(Training::class)
        ->find($id);
         return $this->render('school/training.html.twig', [
            'training' => $t,
        ]);
    }

    #[Route('/module/{id}', name: 'show_module')]
    public function moduleAction(EntityManagerInterface $em,$id)
    {
        $repository = $em->getRepository(Module::class);
        $m = $repository ->find($id);
         return $this->render('school/module.html.twig', [
            'module' => $m,
        ]);
    }
}
