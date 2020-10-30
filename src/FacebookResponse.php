<?php


namespace Nggiahao\Facebook;


use Illuminate\Support\Collection;
use Nggiahao\Facebook\Models\Factories\Factory;

class FacebookResponse
{
    protected $status_code = 200;

    protected $headers;

    protected $body;

    /**
     * FacebookResponse constructor.
     *
     * @param int $status
     * @param array $headers
     * @param null $body
     */
    public function __construct(int $status = 200, array $headers = [], $body = null)
    {
        $this->status_code = $status;
        $this->headers = $headers;
        $this->body = json_decode( $body, true );
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->status_code;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param $classname
     *
     * @return mixed
     * @throws \Exception
     */
    public function returnType($classname) {
        return Factory::make($classname, $this->getBody());
    }

    /**
     * @param $classname
     *
     * @return Collection
     * @throws \Exception
     */
    public function returnCollection($classname) {
        $body = $this->getBody();
        return Factory::makeCollection($classname, $body['data']);
    }


}