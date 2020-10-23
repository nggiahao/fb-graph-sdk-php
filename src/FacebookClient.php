<?php


namespace Nggiahao\Facebook;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class FacebookClient
{
    /**
     * @const string Production Graph API URL.
     */
    const BASE_GRAPH_URL = 'https://graph.facebook.com';

    /**
     * @var Client The Guzzle client.
     */
    protected $guzzleClient;

    /**
     * @param Client|null $guzzleClient
     */
    public function __construct( Client $guzzleClient = null ) {
        $this->guzzleClient = $guzzleClient ?: new Client([
            'base_uri' => self::BASE_GRAPH_URL,
            'verify' => false,
            'timeout' => 60
        ]);
    }

    /**
     * @param $method
     * @param $uri
     * @param array $option
     *          Ex: [
     *                  'access_token => ...,
     *                  'graph_version => ...,
     *                  'query' => [],
     *                  'limit' => 20,
     *                  ...
     *              ]
     *
     * @return FacebookResponse
     * @throws GuzzleException
     * @throws \Exception
     */
    public function request($method, $uri, array $option = []): FacebookResponse {
        try {
            $rawResponse = $this->requestRaw($method, $uri, $option);

            return new FacebookResponse(
                $rawResponse->getStatusCode(),
                $rawResponse->getHeaders(),
                $rawResponse->getBody()->getContents()
            );
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param $method
     * @param $uri
     * @param array $option
     *
     * @return ResponseInterface
     * @throws \Exception|GuzzleException
     */
    public function requestRaw($method, $uri, array $option = []): ResponseInterface {
        $access_token = $option['access_token'] ?? null;
        if (empty($access_token)) {
            throw new \Exception("You must provide an access token.");
        }
        $graph_version = $option['graph_version'] ?? 'v7.0';
        $query = $option['query'] ?? [];
        $query = array_merge($query,[
            'access_token' => $access_token
        ]);
        if (!empty($option['limit'])) {
            $query['limit'] = $option['limit'];
        }

        $uri = '/'.$graph_version.$uri;

        return $this->guzzleClient->request($method, $uri, [
            'query' => $query
        ]);
    }
}