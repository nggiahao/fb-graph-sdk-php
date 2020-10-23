<?php

namespace Nggiahao\Facebook;

use GuzzleHttp\Exception\GuzzleException;

class Facebook
{
    const VERSION = "0.1";
    const DEFAULT_GRAPH_VERSION = "v7.0";

    /**
     * @var String The Graph API version for requests.
     */
    protected $graph_version;

    /**
     * @var FacebookClient The Facebook client service.
     */
    protected $client;

    /**
     * @var String The access token to use with requests
     */
    protected $access_token;

    /**
     * @var array The config for Facebook SDK
     */
    protected $config;

    /**
     * Facebook constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = array_merge([], $config);
        $this->setGraphVersion($this->config['graph_version'] ?? self::DEFAULT_GRAPH_VERSION);
        $this->setAccessToken($this->config['access_token'] ?? null);

        $this->client = new FacebookClient();
    }

    /**
     * Sets the graph facebook version to use.
     * @param string $version
     *
     * @return $this
     */
    public function setGraphVersion( string $version ) :Facebook {
        $this->graph_version = $version;

        return $this;
    }

    /**
     * Sets token
     *
     * @param string|null $token
     *
     * @return $this
     */
    public function setAccessToken( string $token = null ) {
        $this->access_token = $token;

        return $this;
    }

    /**
     * Returns the FacebookClient service.
     *
     * @return FacebookClient
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $option
     *
     * @return FacebookResponse
     * @throws GuzzleException
     */
    public function request(string $method, string $uri, array $option = []): FacebookResponse {
        $option = array_merge([
            'access_token' => $this->access_token,
            'graph_version' => $this->graph_version
        ], $option);

        return $this->client->request($method, $uri, $option);
    }

    /**
     * @param string $uri
     * @param array $option
     *
     * @return FacebookResponse
     * @throws GuzzleException
     */
    public function get(string $uri, array $option = []): FacebookResponse {

        return $this->request('GET' ,$uri, $option);
    }

    /**
     * @param string $uri
     * @param array $option
     *
     * @return FacebookResponse
     * @throws GuzzleException
     */
    public function post(string $uri, array $option = []): FacebookResponse {

        return $this->request('POST' ,$uri, $option);
    }

}
