<?php

namespace Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\School;
use App\Entity\Training;
use App\Entity\Module;
use App\DataFixtures\SchoolFixtures;

class TrainingsTest extends WebTestCase
{
    public function testBasicSearch()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/search_training');

        $em = self::$kernel->getContainer()->get('doctrine')->getManager();
        $trainings = $em->getRepository(Training::class)->findAll();
        $modules = $em->getRepository(Module::class)->findAll();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h1', 'Recherche de formation par module');
        $this->assertCount( count($trainings) , $crawler->filter('tbody > tr'));

        $this->assertCount( count($modules) + 1  , $crawler->filter('label'));
    }
}
