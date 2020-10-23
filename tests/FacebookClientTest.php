<?php


namespace Nggiahao\Facebook\Tests;


use Nggiahao\Facebook\Facebook;
use Nggiahao\Facebook\FacebookClient;
use PHPUnit\Framework\TestCase;

class FacebookClientTest extends TestCase
{
    public function test_facebook_client() {
        $client = new FacebookClient();
        $response = $client->request('GET', '/me', ['access_token' => 'token']);

        $this->assertTrue($response->getStatusCode() == 200);

    }

    public function test_facebook_request() {
        $fb = (new Facebook())->setAccessToken('token');
        $response = $fb->request('GET', '/me');

        $this->assertTrue($response->getStatusCode() == 200);
    }
}