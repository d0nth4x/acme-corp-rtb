<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 *
 * @coversNothing
 */
class EmployeeControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testController(): void
    {
        $this->client->jsonRequest(Request::METHOD_POST, '/api/employees', [
            'name' => 'foo',
            'surname' => 'bar',
            'jobTitleId' => 1,
            'parentId' => 0,
        ]);
        self::assertResponseIsSuccessful();

        $response = json_decode($this->client->getResponse()->getContent(), true);
        $id = $response['id'];

        $this->client->jsonRequest(Request::METHOD_GET, '/api/employees/'.$id);
        self::assertResponseIsSuccessful();
    }
}
