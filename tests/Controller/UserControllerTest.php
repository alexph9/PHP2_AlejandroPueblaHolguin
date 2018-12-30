<?php
/**
 * Created by PhpStorm.
 * User: alexp
 * Date: 30/12/2018
 * Time: 21:38
 */

namespace App\Controller;

use App\Controller\UserController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @package App\Tests\Controller
 */
class UserControllerTest extends WebTestCase{
    /** @var Client $client */
    private static $client;

    public static function setUpBeforeClass()
    {
        self::$client = static::createClient();
    }

    /**
     * Implements testGetUsers200
     * @covers ::getUsers
     */
    public function testGetUsers200()
    {
        self::$client->request(
            Request::METHOD_GET,
            "/api/v1/users"
        );
        /** @var Response $response */
        $response = self::$client->getResponse();
        self::assertEquals(
            Response::HTTP_OK,
            $response->getStatusCode()
        );
        self::assertJson($response->getContent());
        $body = json_decode($response->getContent(), true);
        self::assertArrayHasKey('id', $body[0]);
        self::assertArrayHasKey('username', $body[0]);
        self::assertArrayHasKey('email', $body[0]);
        self::assertArrayHasKey('enabled', $body[0]);
        self::assertArrayHasKey('admin', $body[0]);
    }

    /**
     * Implements testCreateUser201
     * @covers ::createUser
     * @throws
     */
    public function testCreateUser201()
    {
        $username = (string) random_int(0, 10E6);
        $email = $username . "@miw.com";
        $password = "*".$username."*";

        $body = [
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'enabled' => true,
            'admin' => false,
        ];
        self::$client->request(
            Request::METHOD_POST,
            "/api/v1/users",
            [], [], [], json_encode($body)
        );

        $response = self::$client->getResponse();
        self::assertEquals(
            Response::HTTP_CREATED,
            $response->getStatusCode()
        );
        self::assertJson($response->getContent());
        $bodyJSON = json_decode($response->getContent(), true);
        self::assertEquals($username, $bodyJSON["username"]);
        self::assertEquals($email, $bodyJSON["email"]);
        self::assertEquals(true, $bodyJSON["enabled"]);
        self::assertEquals(false, $bodyJSON["admin"]);
    }

    /**
     * Implements testCreateUser422
     * @covers ::createUser
     * @covers ::error
     * @throws
     */
    public function testCreateUser422(){
        $username = (string) random_int(0, 10E6);
        $email = $username . "@miw.com";
        $password = "*".$username."*";

        $body = [
            'username' => $username,
            'email' => $email,
            'enabled' => true,
            'admin' => false,
        ];
        self::$client->request(
            Request::METHOD_POST,
            "/api/v1/users",
            [], [], [], json_encode($body)
        );
        /** @var Response $response */
        $response = self::$client->getResponse();
        self::assertEquals(
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $response->getStatusCode()
        );
        self::assertJson($response->getContent());
        $bodyJSON = json_decode($response->getContent(), true);
        self::assertEquals(422, $bodyJSON["message"]["code"]);
        self::assertEquals("UNPROCESSABLE ENTITIES", $bodyJSON["message"]["message"]);
    }
}