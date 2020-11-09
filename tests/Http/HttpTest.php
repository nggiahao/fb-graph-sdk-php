<?php
namespace Nggiahao\Facebook\Tests\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Nggiahao\Facebook\Exception\InvalidAccessTokenFacebook;
use Nggiahao\Facebook\Http\FacebookRequest;
use Nggiahao\Facebook\Http\FacebookResponse;
use PHPUnit\Framework\TestCase;

class HttpTest extends TestCase
{
    public $user;
    public $request;

    /**
     * @throws InvalidAccessTokenFacebook
     */
    public function setUp(): void
    {
        $env_test = json_decode(file_get_contents(__DIR__.'/../test.json'), true);
        $this->user = $env_test['user'];
        $this->request = new FacebookRequest("GET", "/me", $this->user['access_token'], "v7.0");
    }

    /**
     * @throws GuzzleException
     */
    public function test_get()
    {
        $response = $this->request->execute();
        $code = $response->getHttpCode();

        $this->assertEquals(200, $code);
    }

    /**
     * @throws GuzzleException
     */
    public function test_get_with_query() {

        $request = $this->request->attachQuery([
            'fields' => 'id,name'
        ]);
        $this->assertInstanceOf(FacebookRequest::class, $request);

        $response = $request->execute();
        $this->assertInstanceOf(FacebookResponse::class, $response);

        $user_id = $response->getBody()['id'];
        $this->assertEquals($this->user['id'], $user_id);
    }
}