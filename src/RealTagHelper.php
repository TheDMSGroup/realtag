<?php

namespace westonwatson\realtag;

/**
 * Class RealTagHelper
 *
 * @package westonwatson\realtag
 */
class RealTagHelper
{
    const MAX_DEPTH            = 3;

    const NO_FIND_PROPERTY_SET = 'No search property was set, please define an attribute to search for.';

    const INVALID_PROPERTY     = ' was not found, please check the documentation for available property information.';

    /**
     * @var RealTag
     */
    private $client;

    /**
     * @var \stdClass
     */
    private $response;

    public function __construct(string $token, bool $devMode = null)
    {
        if (!class_exists(RealTag::class)) {
            trigger_error("RealTag Client Class Not Found, Requiring Composer Autoloader Now...", E_USER_NOTICE);
            require_once __DIR__.'/../vendor/autoload.php';
        }

        $this->client = new RealTag($token, ($devMode ? true : false));
    }

    public function setProperty(array $data)
    {
        $this->response = $this->client->call($data);
    }

    public function __isset($name)
    {
        return property_exists($this->response, $name);
    }

    public function __get($name)
    {
        return $this->findPropertyInObject($this->response, $name);
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getForclosure()
    {
        return $this->response->Forclosure;
    }

    public function getEquityData()
    {
        return $this->response->EstimatedData;
    }

    public function getLienCount()
    {
        return count($this->response->Liens);
    }

    public function getLiens()
    {
        return $this->response->Liens;
    }

    public function getPropertyData()
    {
        return $this->response->Property;
    }

    private function findPropertyInObject($object, $property, $count = 0)
    {
        if (empty($property)) {
            trigger_error(self::NO_FIND_PROPERTY_SET, E_USER_WARNING);

            return null;
        }

        if ($count++ > self::MAX_DEPTH) {
            trigger_error($property.self::INVALID_PROPERTY, E_USER_NOTICE);

            return null;
        }

        if (is_object($object) || is_array($object)) {
            foreach ($object as $key => $value) {
                if (is_object($value) || is_array($value)) {
                    $findNextTier = $this->findPropertyInObject($value, $property, $count);
                    if (strlen($findNextTier) > 0) {

                        return $findNextTier;
                    }
                } else {
                    if ($property == $key) {

                        return $value;
                    }
                }
            }
        }
    }

}
