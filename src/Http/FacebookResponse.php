<?php


namespace Nggiahao\Facebook\Http;


use Illuminate\Support\Collection;

class FacebookResponse
{
    /**
     * @var FacebookRequest
     */
    protected $request;

    /**
     * @var array
     */
    protected $body;

    /**
     * @var array
     */
    protected $headers;

    /**
     * @var int
     */
    protected $http_code;

    /**
     * FacebookResponse constructor.
     *
     * @param FacebookRequest $request
     * @param array|null $body
     * @param int|null $http_code
     * @param array|null $headers
     */
    public function __construct(FacebookRequest $request, ?array $body, int $http_code = null, array $headers = null)
    {
        $this->request = $request;
        $this->body = $body;
        $this->headers = $headers;
        $this->http_code = $http_code;
    }

    /**
     * @return FacebookRequest
     */
    public function getRequest(): FacebookRequest
    {
        return $this->request;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return array
     */
    public function getBody(): array
    {
        return $this->body;
    }

    /**
     * @return int
     */
    public function getHttpCode(): int
    {
        return $this->http_code;
    }

    /**
     * Converts the response JSON object to a Graph SDK object
     *
     * @param string $return_type
     *
     * @return Collection|mixed
     */
    public function getResponseAsObject(string $return_type ) {
        $class_name = $return_type;
        $body = $this->getBody();

        //If more than one object is returned
        if (array_key_exists('data', $body)) {
            $values = $body['data'];

            //Check that this is an object array instead of a value called "value"
            if (is_array($values)) {
                $collection = collect();
                foreach ($values as $obj) {
                    $collection->add(new $class_name($obj));
                }
                return $collection;
            }
        }

        return new $class_name($body);
    }

    public function getNextLink() {

    }

    public function getDeltaLink() {

    }

}