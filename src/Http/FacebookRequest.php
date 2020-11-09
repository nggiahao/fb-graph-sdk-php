<?php
namespace Nggiahao\Facebook\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Stream;
use Nggiahao\Facebook\Exception\InvalidAccessTokenFacebook;

class FacebookRequest
{
    const BASE_GRAPH_URL = 'https://graph.facebook.com';

    /**
     * @var String The access token to use with requests
     */
    protected $access_token;

    /**
     * @var String The Graph API version for requests.
     */
    protected $graph_version;

    /**
     * The endpoint to call
     * @var string
     */
    protected $endpoint;

    /**
     * An array of headers to send with the request
     *
     * @var array(string => string)
     */
    protected $headers = [
        'Content-Type' => 'application/json',
    ];

    /**
     * The query of the request (optional)
     *
     * @var array
     */
    protected $request_query = [];

    /**
     * The type of request to make ("GET", "POST", etc.)
     *
     * @var string
     */
    protected $method;

    /**
     * The return type to cast the response as
     *
     * @var string
     */
    protected $return_type;

    /**
     * True if the response should be returned as
     * a stream
     *
     * @var bool
     */
    protected $returns_stream;

    /**
     * The timeout, in seconds
     *
     * @var string
     */
    protected $timeout;

    /**
     * The proxy port to use. Null to disable
     *
     * @var string
     */
    protected $proxy;

    /**
     * Request options to decide if Guzzle Client should throw exceptions when http code is 4xx or 5xx
     *
     * @var bool
     */
    protected $http_errors;

    /**
     * FacebookRequest constructor.
     *
     * @param string $method
     * @param string $endpoint
     * @param string|null $access_token
     *
     * @param string|null $graph_version
     * @param string|null $proxy
     *
     * @throws InvalidAccessTokenFacebook
     */
    public function __construct(string $method, string $endpoint, ?string $access_token, ?string $graph_version, string $proxy = null)
    {
        $this->method = $method;
        $this->endpoint = $endpoint;
        $this->access_token = $access_token;
        $this->http_errors = true;

        if (!$this->access_token) {
            throw new InvalidAccessTokenFacebook("You must provide an access token.");
        }

        $this->graph_version = $graph_version;
        $this->timeout = 0;
        $this->proxy = $proxy;
    }

    /**
     * Sets a new accessToken
     *
     * @param String $access_token
     *
     * @return FacebookRequest
     */
    public function setAccessToken(string $access_token): FacebookRequest
    {
        $this->access_token = $access_token;
        return $this;
    }

    /**
     * @param String $graph_version
     *
     * @return FacebookRequest
     */
    public function setGraphVersion(string $graph_version): FacebookRequest
    {
        $this->graph_version = $graph_version;
        return $this;
    }

    /**
     * Sets the return type of the response object
     *
     * @param string $return_class
     *
     * @return FacebookRequest
     */
    public function setReturnType(string $return_class): FacebookRequest
    {
        $this->return_type = $return_class;
        if ($this->return_type == Stream::class) {
            $this->returns_stream  = true;
        } else {
            $this->returns_stream = false;
        }
        return $this;
    }

    /**
     * Adds custom headers to the request
     *
     * @param array $headers An array of custom headers
     *
     * @return FacebookRequest
     */
    public function addHeaders(array $headers): FacebookRequest
    {
        $this->headers = array_merge($this->headers, $headers);
        return $this;
    }

    /**
     * Get the request headers
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Attach a body to the request. Will JSON encode
     * any Nggiahao\Facebook\Models objects as well as arrays
     *
     * @param array $query
     *
     * @return FacebookRequest
     */
    public function attachQuery(array $query): FacebookRequest
    {
        $this->request_query = array_merge($this->request_query, $query);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getQuery()
    {
        return $this->request_query;
    }

    /**
     * Sets the timeout limit of the cURL request
     *
     * @param string $timeout The timeout in seconds
     *
     * @return FacebookRequest
     */
    public function setTimeout($timeout): FacebookRequest
    {
        $this->timeout = $timeout;
        return $this;
    }

    /**
     * Executes the HTTP request using Guzzle
     *
     * @param Client|null $client The client to use in the request
     *
     * @return mixed object or array of objects
     *         of class $returnType
     * @throws GuzzleException
     */
    public function execute(Client $client = null)
    {
        if (is_null($client)) {
            $client = $this->createGuzzleClient();
        }

        $this->attachQuery(['access_token' => $this->access_token]);

        $result = $client->request(
            $this->method,
            $this->getRequestUrl(),
            [
                'query' => $this->request_query,
                'timeout' => $this->timeout
            ]
        );

        if($this->returns_stream) {
            return $result->getBody();
        }

        $response = new FacebookResponse(
            $this,
            json_decode($result->getBody()->getContents(), true),
            $result->getStatusCode(),
            $result->getHeaders()
        );

        $return_obj = $response;

        if ($this->return_type) {
            $return_obj = $response->getResponseAsObject($this->return_type);
        }
        return $return_obj;
    }

    /**
     * Get the concatenated request URL
     *
     * @return string request URL
     */
    protected function getRequestUrl(): string
    {
        //Send request with opaque URL
        if (stripos($this->endpoint, "https") === 0) {
            return $this->endpoint;
        }

        return '/'.$this->graph_version.$this->endpoint;
    }

    /**
     * Checks whether the endpoint currently contains query
     * parameters and returns the relevant concatenate for
     * the new query string
     *
     * @return string "?" or "&"
     */
    protected function getConcatenate()
    {
        if (stripos($this->endpoint, "?") === false) {
            return "?";
        }
        return "&";
    }


    /**
     * Create a new Guzzle client
     * To allow for user flexibility, the
     * client is not reused. This allows the user
     * to set and change headers on a per-request
     * basis
     *
     * @return Client
     */
    protected function createGuzzleClient(): Client
    {
        $client_settings = [
            'base_uri' => self::BASE_GRAPH_URL,
            'http_errors' => $this->http_errors,
            'headers' => $this->headers
        ];
        if ($this->proxy !== null) {
            $client_settings['verify'] = false;
            $client_settings['proxy'] = $this->proxy;
        }
        return new Client($client_settings);
    }




}