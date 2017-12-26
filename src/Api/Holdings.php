<?php

namespace YodleePhp\Api;

/*
  This class provides client library to invoke Yodlee's Holdings APIs and perform utility operations like parsing
  JSON response from Yodlee API.
  Various operations are

 *   getHoldings
 *   getHoldingTypes
 * 	getData
 * 	parseHoldings

 */

class Holding {

    const fq_name = "yodlee.api.holdings.Holding";

    /*
      This operation internally invokes getData method to call get Holdings API.
      Params expected in input :
      apiUrl, cobrandSessionToken, userSessionToken , queryArgs i.e. query parameters
     */

    function getHoldings($url, $cobSession, $userSession, $queryArgs) {
        $holdings = $this::getData($url, $cobSession, $userSession, $queryArgs);
        return $holdings;
    }

    function getHoldingWithAssetClassification($url, $cobSession, $userSession, $queryArgs) {
        $request = $url;
        echo($request);
        if (!empty($queryArgs) && count($queryArgs) > 0)
            $request = $request . '?' . http_build_query($queryArgs, '', '&');
        /* $request .= "&assetClassification.classificationValue<>United States";
          echo("=====================================".$request);
         */
        $responseObj = CurlUtils::httpGet($request, $cobSession, $userSession);
        return $responseObj;
    }

    /*
      This operation internally invokes getData method to call get HoldingTypes API.
      Params expected in input :
      apiUrl, cobrandSessionToken, userSessionToken , queryArgs i.e. query parameters
     */

    function getHoldingTypes($url, $cobSession, $userSession) {
        $holdingTypes = $this::getData($url, $cobSession, $userSession, null);
        return $holdingTypes;
    }

    /*
      This operation internally invokes CURL utility to call holdings/holdingTypes API.
      Params expected in input :
      apiUrl, cobrandSessionToken, userSessionToken , queryArgs i.e. query parameters
     */

    function getData($url, $cobSession, $userSession, $queryArgs) {
        $request = $url;
        if (!empty($queryArgs) && count($queryArgs) > 0)
            $request = $request . '?' . http_build_query($queryArgs, '', '&');
        $responseObj = CurlUtils::httpGet($request, $cobSession, $userSession);
        return $responseObj;
    }

    /*
      Utility Method to parse holdings/holdingTypes response JSON and return respective array with valid key(attributes)->value pairs.
      Expected Input is response object from holdings/holdingTypes api.
     */

    function parseHoldings($responseObj) {
        $holdingsArr = array();
        $holdingsObj = Utils::parseJson($responseObj["body"]);

        if (!empty($holdingsObj)) {
            $holdingsArr = $holdingsObj['holding'];
        }
        return $holdingsArr;
    }

}

?>