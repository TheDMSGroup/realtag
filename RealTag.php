<?php

namespace westonwatson\realtag;

class RealTag
{
    const REALTAG_DEV_ENDPOINT   = 'https://realtagapi-test.ileads.com/api/RealTAG';

    const REALTAG_PROD_ENDPOINT  = 'https://realtagapi.ileads.com/api/RealTAG';

    const REQUIRED_POST_DATA     = ['FullName', 'AddressLine1', 'City', 'State', 'Zip'];

    const NON_REQUIRED_POST_DATA = ['ExternalID', 'AddressLine2'];

    private $token;

    private $endpoint;

    private $ch;

    private $headers = ['Content-Type: application/json'];

    /**
     * RealTag constructor.
     *
     * @param      $token
     * @param bool $devMode
     */
    public function __construct($token, $devMode = false)
    {
        $this->token    = $token;
        $this->endpoint = $devMode ? self::REALTAG_DEV_ENDPOINT : self::REALTAG_PROD_ENDPOINT;
        $this->ch       = curl_init();
    }

    /**
     * @param array $headers
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
    }

    /**
     * @param string $endpoint
     */
    public function setEndpoint(string $endpoint)
    {
        $this->endpoint = $endpoint;
    }

    /**
     * @param array $data
     *
     * @return mixed
     * @throws \Exception
     */
    public function call(array $data)
    {
        $url = $this->endpoint();
        $this->validatePostData($data);

        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, json_encode($data));

        $raw_response = curl_exec($this->ch);

        return $this->decodeResponse($raw_response);
    }

    /**
     * @param array $data
     *
     * @return bool
     * @throws \Exception
     */
    private function validatePostData(array $data)
    {
        $required_fields = [];

        foreach (self::REQUIRED_POST_DATA as $key) {
            if (!array_key_exists($key, $data)) {
                $required_fields[] = $key;
            }
        }

        foreach ($data as $key => $value) {
            if (!in_array($key, self::REQUIRED_POST_DATA) && !in_array($key, self::NON_REQUIRED_POST_DATA)) {
                trigger_error(
                    "{$key} IS NOT A VALID REALTAG FIELD, IT WILL BE RETURNED IN THE RESPONSE",
                    E_USER_NOTICE
                );
            }
        }

        if (count($required_fields) > 0) {
            $exception_message = "The following fields are required to make a RealTag API request: ".implode(
                    ',',
                    $required_fields
                );
            throw new \Exception($exception_message);
        }

        return true;
    }

    /**
     * @param $response
     *
     * @return mixed
     */
    private function decodeResponse($response)
    {
        return json_decode($response);
    }

    /**
     * @return string
     */
    private function endpoint()
    {
        echo "\n"."{$this->endpoint}?code={$this->token}";

        return "{$this->endpoint}?code={$this->token}";
    }
}

