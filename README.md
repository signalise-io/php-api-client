# Signalise - PHP Client

This is a PHP client for interacting with the Signalise API. it can do the follow things

#### Endpoints
- **/api/v1/connects** - Get connect ids from Signalise (stores).
- **/api/v1/connects/{{connectId}}/history** - Process order(s) to Signalise.
- **/api/v1/connects/{{connectId}}/history/status** - Get information about the last order that has been processed.

## Requirements
- PHP >= 7.4
- Composer

## Installation

Install via composer.

```
composer require signalise/php-client
```

## Getting started

### Get Connects

The get connects endpoint is used to retrieve all the connectIds (stores). 

> **In order to process order(s) to Signalise you need to use a connectId to let
Signalise know which store you want to process order(s) to.**


```php
/**
 * @throws GuzzleException|ResponseException
 */
 public function getConnects()
 {
    $connectIds = $this->apiClient->getConnects($apiKey)
 }
```

### Post Order History
***

The post Order history endpoint is used to process order(s) to Signalise. 

**It wil either throw an exception or an array with the message that you successfully processed x records**
```php
/**
* @throws GuzzleException|ResponseException
*/
public function postOrderHistory(string $serializedData)
{
      $this->apiClient->postOrderHistory(
            $apiKey,
            $serializedData,
            $connectId
      );
}
```

> **in order to successful post an order to Signalise you need to use this format.**

```json
{
    "records": [
        {
            "id": 16,
            "total_products": 25,
            "total_costs": 124.6500,
            "valuta": "EUR",
            "tax": 1.15,
            "payment_method": "mollie_methods_ideal",
            "payment_costs": 0.05,
            "shipping_method": "Flat Rate - Fixed",
            "shipping_costs": 5.0000,
            "zip": "1000AA",
            "street": "Dam",
            "house_number": "1",
            "city": "Amsterdam",
            "country": "NL",
            "status": "complete",
            "date": "2021-02-11 18:24:45",
            "tag": ""
        },
        {
            "id": 17,
            "total_products": 1,
            "total_costs": 46.5000,
            "payment_method": "mollie_methods_creditcard",
            "payment_costs": 0.25,
            "shipping_method": "dhl",
            "shipping_costs": 2.5000,
            "valuta": "EUR",
            "tax": 1.15,
            "zip": "BE1000",
            "street": "Brussels Park",
            "house_number": "1",
            "city": "Brussel",
            "country": "BE",
            "status": "processing",
            "date": "2021-02-18 10:31:50",
            "tag": ""
        }
    ]
}
```

### Get History Status
***

The get history status endpoint will retrieve information about the last processed item.
```php
/**
 * @throws GuzzleException|ResponseException
 */
public function getHistoryStatus()
{
    $lastOrderHistory = 
            $this->apiClient->getHistoryStatus(
                $apiKey,
                $connectId
            );        
}
```

## Support

Signalise PHP Client is made by [Ndottens](https://github.com/Ndottens).

If you find a bug or want to submit an improvement, don't hesitate to create a merge request on Gitlab.