<?php
namespace Nggiahao\Facebook\Tests\Http;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Stream;
use Nggiahao\Facebook\Exception\InvalidAccessTokenFacebook;
use Nggiahao\Facebook\Facebook;
use Nggiahao\Facebook\Http\FacebookRequest;
use Nggiahao\Facebook\Http\FacebookResponse;
use Nggiahao\Facebook\Models\User;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public $user;
    public $facebook;

    /**
     */
    public function setUp(): void
    {
        $env_test = json_decode(file_get_contents(__DIR__.'/../test.json'), true);
        $this->user = $env_test['user'];
        $this->facebook = new Facebook($this->user['access_token'], "v7.0");
    }

    /**
     * @throws InvalidAccessTokenFacebook
     * @throws \ReflectionException
     */
    public function test_set_return_type() {
        $request = $this->facebook->createRequest('GET', '/me')
                                    ->setReturnType(Stream::class);

        $reflector = new \ReflectionClass($request);
        $reflector_property = $reflector->getProperty('return_type');
        $reflector_property->setAccessible(true);
        $return_type = $reflector_property->getValue($request);

        $this->assertEquals(Stream::class, $return_type);
    }

    /**
     * @throws InvalidAccessTokenFacebook
     * @throws GuzzleException
     */
    public function test_execute() {
        $request = $this->facebook->createRequest('GET', '/me')
                                    ->attachQuery(['fields' => 'id,name'])
                                    ->setReturnType(Stream::class);

        $this->assertInstanceOf(FacebookRequest::class, $request);

        $response = $request->execute();
        $this->assertInstanceOf(Stream::class, $response);
    }

    /**
     * @throws InvalidAccessTokenFacebook|GuzzleException
     */
    public function test_return_model() {
        $response = $this->facebook->createRequest('GET', '/me')
            ->attachQuery(['fields' => 'id,name'])
            ->setReturnType(User::class)
            ->execute();

        $this->assertInstanceOf(User::class, $response);
    }
}