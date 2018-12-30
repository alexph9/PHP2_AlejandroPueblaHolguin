<?php
/**
 * Created by PhpStorm.
 * User: alexp
 * Date: 30/12/2018
 * Time: 22:53
 */

namespace App\Tests\Controller;

use App\Controller\ResultController;
use App\Controller\UserController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @package App\Tests\Controller
 */

class ResultControllerTest extends WebTestCase{
    /** @var Client $client */
    private static $client;
    private static $user;

    public static function setUpBeforeClass()
    {
        self::$client = static::createClient();
        self::$user = self::proveedorUser();
    }

    /**
     * Implements testPostResult201
     * @covers ::createResult
     * @throws
     */
    public function testPostResult201(){
        $userId = 2;
        $result = random_int(0, 99);

        $body = [
            'user' => $userId,
            'result' => $result
        ];
        self::$client->request(
            Request::METHOD_POST,
            "/api/v1/results",
            [], [], [], json_encode($body)
        );

        $response = self::$client->getResponse();
        self::assertEquals(
            Response::HTTP_CREATED,
            $response->getStatusCode()
        );
        self::assertJson($response->getContent());
        $resultArray = json_decode($response->getContent(), true);
        self::assertEquals($result, $resultArray["result"]);
        self::assertEquals($userId, $resultArray["user"]["id"]);
    }

    /**
     * Implements testGetResults200
     * @covers ::getResults
     */
    public function testGetResults200()
    {
        self::$client->request(
            Request::METHOD_GET,
            "/api/v1/results"
        );
        $response = self::$client->getResponse();
        self::assertEquals(
            Response::HTTP_OK,
            $response->getStatusCode()
        );
        self::assertJson($response->getContent());
        $body = json_decode($response->getContent(), true);
        self::assertArrayHasKey('id', $body[0]);
        self::assertArrayHasKey('result', $body[0]);
        self::assertArrayHasKey('user', $body[0]);
    }

    /**
     * Implements testCreateResult422
     * @covers ::postResult
     * @covers ::error
     * @throws
     */
    public function testPostResult422()
    {
        $result = random_int(0, 99);

        $body = [
            'result' => $result
        ];
        self::$client->request(
            Request::METHOD_POST,
            "/api/v1/results",
            [], [], [], json_encode($body)
        );
        $response = self::$client->getResponse();
        self::assertEquals(
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $response->getStatusCode()
        );
        self::assertJson($response->getContent());
        $body = json_decode($response->getContent(), true);
        self::assertEquals(422, $body["message"]["code"]);
        self::assertEquals("UNPROCESSABLE ENTITIES", $body["message"]["message"]);
    }

    public static function proveedorUser(): array
    {
        $username = "alex";
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
        $user = json_decode($response->getContent(), true);
        return $user;
    }
}