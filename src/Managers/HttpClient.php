<?php

namespace JulianLouis\WingsPHP\Managers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use JulianLouis\WingsPHP\Wings;
use Psr\Http\Message\ResponseInterface;

class HttpClient
{

    public Client $http;
    protected Wings $wings;

    /**
     * HttpClient constructor.
     * @param $wings
     */
    public function __construct($wings)
    {
        $this->wings = $wings;
        $this->http = $this->wings->http;
    }

    /**
     * @param $method
     * @param $uri
     * @param null $values
     * @return array|mixed|string
     */
    public function request($method, $uri, $values = null)
    {
        try {
            if ($method === 'GET') {
                return $this->transformResponse($this->http->request($method, $uri));
            }

            return $this->transformResponse($this->http->request($method, $uri, ['json' => $values]));
        } catch (RequestException | GuzzleException $e) {
            echo Psr7\Message::toString($e->getRequest());
            if ($e->hasResponse()) {
                return Psr7\Message::toString($e->getResponse());
            }
        }
    }

    /**
     * @param ResponseInterface $response
     * @return array|mixed
     */
    protected function transformResponse(ResponseInterface $response)
    {
        $json = json_decode($response->getBody()->getContents(), true);

        if (empty($json)) {
            return [];
        }
        return $json;
    }
}
