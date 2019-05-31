<?php

namespace westonwatson\realtag;

use westonwatson\realtag\HttpRequest;
use westonwatson\realtag\CurlRequest;

/**
 * Class RealTag
 *
 * @package westonwatson\realtag
 */
class RealTag
{
    const REALTAG_DEV_ENDPOINT   = 'https://realtagapi-test.ileads.com/api/RealTAG';

    const REALTAG_PROD_ENDPOINT  = 'https://realtagapi.ileads.com/api/RealTAG';

    const REQUIRED_POST_DATA     = ['FullName', 'AddressLine1', 'City', 'State', 'Zip'];

    const NON_REQUIRED_POST_DATA = ['ExternalID', 'AddressLine2'];

    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $endpoint;

    /**
     * @var resource
     */
    private $ch;

    /**
     * @var array
     */
    private $headers = ['Content-Type: application/json'];

    /**
     * RealTag constructor.
     *
     * @param      $token
     * @param bool $devMode
     */
    public function __construct(string $token, $devMode = false, HttpRequest $curl_request = null)
    {
        if (!class_exists(\Composer\Autoload\ClassLoader::class)) {
            trigger_error("Autoloader not found, manually requiring composer autoloader now...", E_USER_NOTICE);
            require_once __DIR__ . '/../vendor/autoload.php';
        }

        $this->token    = $token;
        $this->endpoint = $devMode ? self::REALTAG_DEV_ENDPOINT : self::REALTAG_PROD_ENDPOINT;
        $this->ch       = $curl_request ? $curl_request : (new CurlRequest());
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
        $this->validatePostData($data);

        $this->ch->setHeaders($this->headers);
        $this->ch->setUrl($this->endpoint());
        $this->ch->setPostData($this->encodePostData($data));

        $raw_response = $this->ch->execute();

        $response = $this->decodeResponse($raw_response);

        if (strlen($response->Error) > 0) {
            trigger_error("RealTag API Error: {$response->Error}", E_USER_WARNING);
        } elseif (!$response->Success) {
            trigger_error("Unknown RealTag API Error Encountered", E_USER_WARNING);
        }

        return $response;
    }

    /**
     * @param array $data
     *
     * @return bool
     * @throws \Exception
     */
    private function validatePostData(array $data): bool
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
                    "{$key} IS NOT A VALID REALTAG FIELD, IT WILL BE IGNORED BUT RETURNED IN THE RESPONSE",
                    E_USER_NOTICE
                );
            }
        }

        if (count($required_fields) > 0) {
            $exception_message = "THE FOLLOWING FIELDS ARE REQUIRED TO MAKE A REALTAG API REQUEST: " . implode(
                    ',',
                    $required_fields
                );
            throw new \Exception($exception_message);
        }

        return true;
    }

    /**
     * @param array $data
     *
     * @return string
     */
    private function encodePostData(array $data): string
    {
        return json_encode($data);
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
    private function endpoint(): string
    {
        return "{$this->endpoint}?code={$this->token}";
    }
}

