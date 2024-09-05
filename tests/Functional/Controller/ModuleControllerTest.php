<?php

namespace Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;
use App\DataFixtures\ModuleFixtures;

class ModuleControllerTest extends WebTestCase
{
    private $client;
    private $entityManager;
    private $createdEntities;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = self::$kernel->getContainer()->get('doctrine')->getManager();

        // Load fixtures
        $this->loadFixtures();
    }

    private function loadFixtures(): void
    {
        $fixture = new ModuleFixtures();
        $fixture->load($this->entityManager);

        // Store the entities created by the fixture
        $this->createdEntities = $fixture->getCreatedEntities();
    }

    protected function tearDown(): void
    {
        // Remove only the entities that were created in the setup
        foreach ($this->createdEntities as $entity) {
            $this->entityManager->remove($entity);
        }
        $this->entityManager->flush();

        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }

    public function testListWithNoModulesSelected()
    {
        $crawler = $this->client->request('GET', '/search_training');
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        // Check that all trainings are listed
        $this->assertSelectorExists('ul');
        $this->assertCount(12, $crawler->filter('ul li'));
    }

    public function testListWithSelectedModules()
    {
        // Mock the request with selected modules
        $crawler = $this->client->request('GET', '/search_training', ['modules' => [187]]);

        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        // Check that only the trainings associated with the selected modules are listed
        // This assumes that the filtered result should have a different count from all trainings
        // Adjust the expected count based on your test data
        $this->assertSelectorExists('ul');
        $this->assertCount(5, $crawler->filter('ul li'));
    }

    public function testListWithMatchAnyModuleChecked()
    {
        $crawler = $this->client->request('GET', '/search_training', ['modules' => [1, 2], 'match_any_module' => 1]);
        $response =  $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        // Check that trainings that match any of the selected modules are listed

        $this->assertCount(3, $crawler->filter('ul li')); // Adjust based on your actual data
    }
}
