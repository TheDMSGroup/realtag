<?php

namespace westonwatson\realtag;

use westonwatson\realtag\HttpRequest;

/**
 * Class CurlRequest
 *
 * @package westonwatson\realtag
 */
class CurlRequest implements HttpRequest
{
    /**
     * @var resource
     */
    private $curl;

    /**
     * CurlRequest constructor.
     */
    public function __construct()
    {
        $this->curl = curl_init();

        //basic config
        $this->setOption(CURLOPT_POST, 1);
        $this->setOption(CURLOPT_RETURNTRANSFER, 1);
    }

    /**
     * @param array $data
     */
    public function setPostData($data)
    {
        $this->setOption(CURLOPT_POSTFIELDS, $data);
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url)
    {
        $this->setOption(CURLOPT_URL, $url);
    }

    /**
     * @param array $headers
     */
    public function setHeaders(array $headers)
    {
        $this->setOption(CURLOPT_HTTPHEADER, $headers);
    }

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function setOption(string $name, $value)
    {
        curl_setopt($this->curl, $name, $value);
    }

    /**
     * @return bool|string
     */
    public function execute()
    {
        return curl_exec($this->curl);
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function getInfo(string $name)
    {
        return curl_getinfo($this->curl, $name);
    }

    /**
     * @return mixed
     */
    public function close()
    {
        return curl_close($this->curl);
    }
}
