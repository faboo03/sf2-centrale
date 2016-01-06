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

        $this->login($client);

        // check we are on the right page
        $this->assertRegExp("/fabien rondeau/", $client->getResponse()->getContent());

    }

    public function testSubmitWallWithErrorsAction()
    {
        $client = static::createClient();

        $now = new \DateTime();
        $crawler = $this->login($client);

        $form = $crawler->selectButton('post[save]')->form(array('post' => array(
            'created_at' => $now->format('u'),
            'message' => "court",
            'author' => "test",
        )));

        $crawler = $client->submit($form);
        $this->assertRegExp("/Cette chaine est trop courte/", $client->getResponse()->getContent());
    }

    public function testSubmitWallAction()
    {
        $client = static::createClient();

        $now = new \DateTime();
        $crawler = $this->login($client);

        $form = $crawler->selectButton('post[save]')->form(array('post' => array(
            'created_at' => $now->format('u'),
            'message' => "Nouveau message created by test",
            'author' => "test",
        )));

        $crawler = $client->submit($form);
        $this->assertEquals($client->getResponse()->getStatusCode(), 302);

        $client->followRedirect();

        // check we are on the right page
        $this->assertRegExp("/Nouveau message created by test/", $client->getResponse()->getContent());
    }

    private function login($client) {
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
        return $client->followRedirect();
    }

}
