<?php

namespace Centrale\UserBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testSecureArea()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/wall/fabien/rondeau');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $client->followRedirect();
        $this->assertRegExp("/Connexion/", $client->getResponse()->getContent());
    }

    public function testWallAction()
    {
        $client = static::createClient();

        // login into action
        $crawler = $client->request('GET', '/login');
        $this->assertRegExp("/Connexion/", $client->getResponse()->getContent());

        // get the form
        $form = $crawler->selectButton('_submit')->form(array(
            '_username' => 'fabien',
            '_password' => 'fabien',
        ));

        // submit the form to the client
        $crawler = $client->submit($form);

        // follow Login redirect
        $client->followRedirect();

        // follow homepage redirect
        $client->followRedirect();

        // check we are on the right page
        $this->assertRegExp("/fabien rondeau/", $client->getResponse()->getContent());

    }
}
