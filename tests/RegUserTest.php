<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegUserTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/inscription');

        $client->submitForm('Save', [
            'register_user[email]' => 'testWebtest2@gmail.com',
            'register_user[plainPassword][first]' => '123123',
            'register_user[plainPassword][second]' => '123123',
            'register_user[firstname]' => 'IMUNITTEST',
            'register_user[lastname]' => 'Killian',
        ]);

        $this->assertResponseRedirects('/connexion');
        $client->followRedirect();
        //$this->assertSelectorTextContains('div', 'Votre compte a bien été créé');
    }
}
