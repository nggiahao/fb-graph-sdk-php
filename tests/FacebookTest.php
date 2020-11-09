<?php

namespace Nggiahao\Facebook\Tests;

use Nggiahao\Facebook\Exception\InvalidAccessTokenFacebook;
use Nggiahao\Facebook\Facebook;
use Nggiahao\Facebook\Http\FacebookRequest;
use PHPUnit\Framework\TestCase;

class FacebookTest extends TestCase
{

    public function test_facebook_constructor()
    {
        $facebook = new Facebook();
        $this->assertNotNull($facebook);
    }

    public function test_initialize_empty_facebook()
    {
        $this->expectException(InvalidAccessTokenFacebook::class);
        $facebook = new Facebook();
        $facebook->createRequest('GET', '/me');
    }

    /**
     * @throws InvalidAccessTokenFacebook
     */
    public function test_initialize_facebook_with_token()
    {
        $facebook = new Facebook();
        $facebook->setAccessToken('abc');
        $request = $facebook->createRequest('GET', '/me');

        $this->assertInstanceOf(FacebookRequest::class, $request);
    }

    /**
     * @throws InvalidAccessTokenFacebook
     * @throws \ReflectionException
     */
    public function test_initialize_facebook_with_version_api()
    {
        $facebook = new Facebook();
        $facebook->setAccessToken('abc')
                 ->setGraphVersion('v8.0');
        $request = $facebook->createRequest('GET', '/me');

        $reflector = new \ReflectionClass($request);
        $reflector_property = $reflector->getProperty('graph_version');
        $reflector_property->setAccessible(true);
        $api_version = $reflector_property->getValue($request);

        $this->assertEquals('v8.0', $api_version);
    }

}
