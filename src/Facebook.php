<?php

namespace Nggiahao\Facebook;

use Nggiahao\Facebook\Http\FacebookRequest;

class Facebook
{
    const VERSION = "0.1";
    const DEFAULT_GRAPH_VERSION = "v7.0";
    const BASE_GRAPH_URL = 'https://graph.facebook.com';


    /**
     * @var String The Graph API version for requests.
     */
    protected $graph_version;

    /**
     * @var String The access token to use with requests
     */
    protected $access_token;

    /**
     * The port to use for proxy requests
     * Null disables port forwarding
     *
     * @var string
     */
    protected $proxy;

    /**
     * Facebook constructor.
     *
     * @param string|null $access_token
     * @param string|null $graph_version
     */
    public function __construct(string $access_token = null, string $graph_version = null)
    {
        $this->setGraphVersion($graph_version ?? self::DEFAULT_GRAPH_VERSION);
        $this->setAccessToken($access_token);
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
     * Sets the proxy port. This allows you
     * to use tools such as Fiddler to view
     * requests and responses made with Guzzle
     *
     * @param string $proxy
     *
     * @return $this
     */
    public function setProxy(string $proxy): Facebook
    {
        $this->proxy = $proxy;
        return $this;
    }

    /**
     * Creates a new request object with the given Graph information
     *
     * @param string $method The HTTP method to use, e.g. "GET" or "POST"
     * @param string $endpoint The Graph endpoint to call
     *
     * @return FacebookRequest
     * @throws Exception\InvalidAccessTokenFacebook
     */
    public function createRequest(string $method, string $endpoint): FacebookRequest
    {
        return new FacebookRequest(
            $method,
            $endpoint,
            $this->access_token,
            $this->graph_version,
            $this->proxy
        );
    }

    /**
     * Creates a new collection request object with the given
     * Graph information
     *
     * @param string $method The HTTP method to use, e.g. "GET" or "POST"
     * @param string $endpoint The Graph endpoint to call
     */
    public function createCollectionRequest(string $method, string $endpoint)
    {

    }
}
