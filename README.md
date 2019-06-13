![RealTag](https://i.imgur.com/0VCpK2F.png)

A PHP Client for the iLeads RealTag API ([Website](https://www.realtag.com/))

![Build Status](https://travis-ci.org/westonwatson/realtag.svg?branch=master)
![MIT License](https://img.shields.io/github/license/westonwatson/realtag.svg)
![Release](https://img.shields.io/github/release/westonwatson/realtag.svg)
![GitHub tag](https://img.shields.io/github/tag/westonwatson/realtag.svg)
![Download Count](https://img.shields.io/github/downloads/westonwatson/realtag/total.svg)
![GitHub contributors](https://img.shields.io/github/contributors/westonwatson/realtag.svg)
![GitHub issues](https://img.shields.io/github/issues/westonwatson/realtag.svg)

### Contributing

Please read the Contribution Guide [here](https://github.com/westonwatson/realtag/blob/master/CONTRIBUTING.md) if you wish to help out. All Pull requests require at least 1 approval from a Project Owner. Approvals from other developers will help get the attention of project owners. This is a great way to help promote important and beneficial changes sooner.

### Code of Conduct

Read our Code of Conduct [here](https://github.com/westonwatson/realtag/blob/master/CODE_OF_CONDUCT.md).

### Notes
* If you don't already use an autoloader and don't require the classes manually, the RealTag PHP Client will attempt to use Composer's Autoloader. So, if you're not using this library via Composer, make sure you include both the Client class and the Helper Class manually (`require`, `require_once`).
* You can request any attribute of the API response, realtag will lookup the requested property in all API Response Sections (ie- Property, EstimatedData, Forclosure, etc).
* The client library will trigger a PHP `E_USER_NOTICE` if something isn't configured correctly or an invalid property attribute is requested.
* The PHP RealTag Client defaults to Production Mode `$devMode == false`. Make sure to enable `$devMode` if you don't want to hit the production service.
* If you have any questions or comments, feel free to open a Github Issue, or submit a pull request with your proposed changes. Any/All Help is greatly appreciated! 😃 

### Available Attributes

##### Success
##### Error
##### Product
##### Version
##### LeadID
##### Requested

##### OriginalRequest (stdClass)
> "FullName",
"AddressLine1",
"AddressLine2",
"City",
"State",
"Zip",
"ExternalID",
>>##### AllFields (stdClass)
>> "FullName",
"AddressLine1",
"City",
"State",
"Zip",
"ExternalID",
"AVM",
"ConfidenceScore",
"HighValue",
"LowValue",
"Value",

##### Foreclosure (stdClass)
> "NODorForeclosure",
"DefaultAmount",
"DefaultDate",

##### Property (stdClass)
> "IsHomeOwner",
"OwnerName",
"IsOwnerOccupied",
"Address",
"UnitNumber",
"City",
"State",
"Zip",
"APN",
"County",
"CensusTract",
"FIPSCode",
"FIPSCensusCombo",
"PropertyUse",
"LandUseDescription",
"CountyUse",
"StateLandUseDescription",
"Bathrooms",
"Bedrooms",
"BasementSqFt",
"Fireplaces",
"CentralCooling",
"ParkingType",
"GarageSqFt",
"GarageTotalCars",
"LandValue",
"LotAreaSqFt",
"Pool",
"TotalAssesedValueAmount",
"RoofSurfaceDescription",
"TotalLivingAreaSqFt",
"GrossLivingAreaSqFt",
"TotalAdjustmentLivingSqFt",
"TotalRooms",
"TotalStories",
"YearBuilt",
"Schooldistrict",
"Realestatetotaltaxamount",
"LastSaleDate",
"LastSalePrice",
"PricePerSquareFoot",
"Improvementvalue",
"Zoning",
"Taxyear",
"Totaltaxablevalueamount",
"PhoneNumber",
"MailingAddress",
"MailingCity",
"MailingState",
"MailingPostalCode",
"PlusFourPostalCode",
"HouseNumber",
"DirectionPrefix",
"StreetName",
"StreetSuffix",
"DirectionSuffix",
"ApartmentOrUnit",
"SiteInfluenceDescription",
"FloodZoneIdentifier",
"RoofTypeDescription",
"FoundationMaterialDescription",
"ConditionsDescription",
"ConstructionQualityTypeDescription",
"OtherPropertyImprovementsDescription",
"ExteriorWallsIdentifier",
"ConstructionTypeDescription",
"ImprovementValueAmount",
"BasementType",
"BasementDescription",
"BasementFinishedAreaSqFt",
"LenderName",
"TitleCompanyName",
"OpenLienAmount",
"OpenLienCount",
"LastLoanDate",

##### Liens (array)

> "TransactionNumber",
"TransactionType",
"SaleDocumentNumberIdentifier",
"SaleDeedTypeDescription",
"SaleDeedTypeDamarCode",
"SaleRecordingDate",
"SaleDate",
"OneSaleTypeDescription",
"SaleStampAmount",
"SalesPriceAmount",
"SaleSellerName",
"SaleBuyerNames",
"SaleTitleCompanyName",
"SaleTransferDocumentNumberIdentifier",
"SaleOwnerTransferIndicator",
"CorporateBuyer",
"Borrower1Names",
"VestingDescription",
"LastSaleIndicator",
"MortgageAmount",

##### EstimatedData (stdClass)
> "MortgageTerm",
"MortgageInterestRate",
"MortgagePayment",
"MortgageAgeInMonths",
"MortgageBalance",
"LTV",
"Equity",


### Example(s)

```
<?php

namespace Example\RealTagImplementation;

use westonwatson\realtag\RealTagHelper;

class RealTagUsage
{
    const MY_API_TOKEN = 'bqJNaoN98hNx/wDFDcZUEjkdsnKJN87Y8BBH8D8SKzoyQ5iCHyzg==';

    /**
     * @var bool
     */
    private $devMode = false;

    /**
     * @var RealTagHelper
     */
    private $realtag;

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
        $this->realtag = new RealTagHelper(self::MY_API_TOKEN, $devMode);
    }

    public function getHomeEquityInfo()
    {
        $this->realtag->setProperty([
            "FullName"     => "Barack Obama",
            "AddressLine1" => "1600 Pennsylvania Ave., NW",
            "City"         => "Washington",
            "State"        => "DC",
            "Zip"          => "20500",
            "ExternalID"   => "change20500",
        ]);
    }

    public function showRoomCount()
    {
        echo "This property has {$this->realtag->Bedrooms} bedrooms and {$this->realtag->Bedrooms} bathrooms.\n";
    }

    public function showMortgageTerm()
    {
        echo "The owner of this property pays an estimated \${$this->realtag->MortgagePayment}, {$this->realtag->MortgageTerm} times a year.\n";
    }
}

$realtagUsage = new RealTagUsage(true);
$realtagUsage->getHomeEquityInfo();
$realtagUsage->showMortgageTerm();
$realtagUsage->showRoomCount();
```

##### Contributors:
> [Weston Watson](http://github.com/westonwatson)
