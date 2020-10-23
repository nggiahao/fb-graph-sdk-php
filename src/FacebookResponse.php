<?php


namespace Nggiahao\Facebook;


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


}