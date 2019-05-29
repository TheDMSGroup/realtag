# realtag
A PHP Client for the iLeads RealTag API ([Website](https://www.realtag.com/))

![Build Status](https://travis-ci.org/westonwatson/realtag.svg?branch=master)
![MIT License](https://img.shields.io/github/license/westonwatson/realtag.svg)
![Release](https://img.shields.io/github/release/westonwatson/realtag.svg)
![GitHub tag](https://img.shields.io/github/tag/westonwatson/realtag.svg)
![Download Count](https://img.shields.io/github/downloads/westonwatson/realtag/total.svg)
![GitHub contributors](https://img.shields.io/github/contributors/westonwatson/realtag.svg)
![GitHub issues](https://img.shields.io/github/issues/westonwatson/realtag.svg)

###Example(s)

```
<?php

namespace Example\RealTagImplementation;

use westonwatson\realtag\RealTag;

class RealTagUsage
{
    const MY_API_TOKEN = 'ThisTokenProvided/ByYour-iLeadsSalesRep==';

    /**
     * @var bool
     */
    private $devMode = false;

    /**
     * @var RealTag
     */
    private $client;

    /**
     * @var array
     */
    private $lead = [];

    /**
     * @var \stdClass
     */
    private $response;

    /**
     * RealTagUsage constructor.
     *
     * @param bool $devMode
     */
    public function __construct($devMode = false)
    {
        require_once('/Users/westonwatson/Documents/realtag/src/RealTag.php');
        $this->devMode = $devMode;
        $this->client  = new RealTag(self::MY_API_TOKEN, $devMode);
    }

    /**
     * @param array $lead
     *
     * @throws \Exception
     */
    public function setPropertyInfo(array $lead)
    {
        $this->lead     = $lead;
        $this->response = $this->client->call($lead);
        echo "\nOUTPUT\n";
        echo print_r($this->response, true);
    }

    /**
     * @return mixed
     */
    public function getPropertyCounty()
    {
        return $this->response->Property->County;
    }

    /**
     * @return mixed
     */
    public function getPropertyRoomCount()
    {
        return $this->response->Property->Bedrooms;
    }

    /**
     * @return mixed
     */
    public function getPropertyBathCount()
    {
        return $this->response->Property->Bathrooms;
    }

    /**
     * @return mixed
     */
    public function getPropertyZoningType()
    {
        return $this->response->Property->Zoning;
    }

    /**
     * @return mixed
     */
    public function getEstimatedMortgageTerm()
    {
        return $this->response->EstimatedData->MortgageTerm;
    }

    /**
     * @return mixed
     */
    public function getEstimatedMortgagePaymentAmount()
    {
        return $this->response->EstimatedData->MortgagePayment;
    }
}

$realtagUsage = new RealTagUsage(true);
$realtagUsage->setPropertyInfo(
    [
        "FullName"     => "Barack Obama",
        "AddressLine1" => "1600 Pennsylvania Ave., NW",
        "City"         => "Washington",
        "State"        => "DC",
        "Zip"          => "20500",
        "ExternalID"   => "change20500",
    ]
);

$roomCount = $realtagUsage->getPropertyRoomCount();
$bathCount = $realtagUsage->getPropertyBathCount();

echo "This property has {$roomCount} bedrooms and {$bathCount} bathrooms.\n";

$mortgageAmount = $realtagUsage->getEstimatedMortgagePaymentAmount();
$mortgageTerm   = $realtagUsage->getEstimatedMortgageTerm();


echo "The owner of this property pays an estimated \${$mortgageAmount}, {$mortgageTerm} times a year.\n";

```


