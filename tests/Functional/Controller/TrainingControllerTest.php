<?php

namespace App\Tests\Functional\Controller;

use App\Entity\Module;
use App\Entity\Training;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

#[\AllowDynamicProperties] class TrainingControllerTest extends WebTestCase
{
    protected static $client;
    private $entityManager;

    public static function setUpBeforeClass(): void
    {
        self::$client = static::createClient();
        self::bootKernel();
    }

    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::$kernel->getContainer()->get('doctrine')->getManager();
        $this->loadFixtures();
    }

    private function loadFixtures(): void
    {
        // Load necessary fixtures for the tests
        $fixture = new \App\DataFixtures\SchoolFixtures();
        $fixture->load($this->entityManager);
    }

//    public function testListNoModulesSelected()
//    {
//        self::$client->request('GET', '/search_training');
//        $response = self::$client->getResponse();
//
//        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
//        $this->assertSelectorExists('table', $response->getContent());
//    }
//
//    public function testListWithModulesSelected()
//    {
//        $moduleRepository = $this->entityManager->getRepository(Module::class);
//        $modules = $moduleRepository->findAll();
//        $moduleIds = array_map(fn($module) => $module->getId(), $modules);
//
//        self::$client->request('GET', '/search_training', ['modules' => $moduleIds]);
//        $response = self::$client->getResponse();
//
//        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
//        $this->assertSelectorExists('table', $response->getContent());
//    }
//
//    public function testListWithMatchAnyModule()
//    {
//        $moduleRepository = $this->entityManager->getRepository(Module::class);
//        $modules = $moduleRepository->findAll();
//        $moduleIds = array_map(fn($module) => $module->getId(), $modules);
//
//        self::$client->request('GET', '/search_training', [
//            'modules' => $moduleIds,
//            'match_any_module' => true,
//        ]);
//        $response = self::$client->getResponse();
//
//        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
//        $this->assertSelectorExists('table', $response->getContent());
//    }

    public function testPageDisplaysCorrectly()
    {
        $em = self::$kernel->getContainer()->get('doctrine')->getManager();
        $trainings = $em->getRepository(Training::class)->findAll();

        foreach ($trainings as $training) {
            $trainingId = $training->getId();
            $crawler = self::$client->request('GET', '/trainings/' . $trainingId);

            $listItems = $crawler->filter('ul > li');

            $this->assertSelectorTextContains('h1', 'Manage Training: ' . $training->getName());
            $this->assertSelectorTextContains('h2', 'Modules');

            $this->assertCount(count($training->getModules()), $listItems);
            foreach ($training->getModules() as $module) {
                $this->assertSelectorTextContains('ul > li', $module->getName());
            }
        }
    }


    public function testRemoveModule()
    {
        $em = self::$kernel->getContainer()->get('doctrine')->getManager();
        $trainings = $em->getRepository(Training::class)->findAll();

        foreach ($trainings as $training) {
            $trainingId = $training->getId();
            $crawler = self::$client->request('GET', '/trainings/' . $trainingId);

            $listItems = $crawler->filter('ul > li');

            $moduleCount = count($training->getModules());

            $this->assertCount($moduleCount, $listItems);

            $firstModule = $training->getModules()->first();
            $moduleId = $firstModule->getId();
            self::$client->request('GET', '/trainings/' . $trainingId . '/modules/' . $moduleId . '/delete');

            $crawler = self::$client->request('GET', '/trainings/' . $trainingId);
            $listItems = $crawler->filter('ul > li');

            $this->assertCount($moduleCount - 1, $listItems);
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }

}
