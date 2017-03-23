<?php

namespace ArquitecturaBaseBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PlantillaControllerTest extends WebTestCase
{
    public function testAgregarplantilla()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/agregarPlantilla');
    }

    public function testMostrarplantilla()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/mostrarPlantilla');
    }

}
