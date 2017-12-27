<?php

namespace YodleePhp\Api;

use YodleePhp\Util\Utils;
use YodleePhp\Util\CurlUtils;

/*
 *  This class provides client library to invoke Yodlee's Holdings APIs and perform utility operations like parsing
 *  JSON response from Yodlee API.
 *  Various operations are
 *      getHoldings
 *      getHoldingTypes
 * 	getData
 * 	parseHoldings
 */

class Portfolio {

    const fq_name = "yodlee.api.portfolio.Portfolio";

    /*
     *  This operation internally invokes getData method to call get Holdings API.
     *  Params expected in input :
     *  apiUrl, cobrandSessionToken, userSessionToken , queryArgs i.e. query parameters
     */

    public function getHoldingsWithAssetClassification($url, $cobSession, $userSession, $queryArgs) {
        print_r($queryArgs);
        $holdings = $this::getData($url, $cobSession, $userSession, $queryArgs);
        return $holdings;
    }

    /*
     *  This operation internally invokes CURL utility to call holdings/holdingTypes API.
     *  Params expected in input :
     *      apiUrl, cobrandSessionToken, userSessionToken , queryArgs i.e. query parameters
     */

    public function getData($url, $cobSession, $userSession, $queryArgs) {
        $request = $url;
        if (!empty($queryArgs) && count($queryArgs) > 0)
            $request = $request . '?' . http_build_query($queryArgs, '', '&');
        $responseObj = CurlUtils::httpGet($request, $cobSession, $userSession);
        return $responseObj;
    }

    /*
     *  Utility Method to parse holdings/holdingTypes response JSON and return respective array with valid key(attributes)->value pairs.
     *  Expected Input is response object from holdings/holdingTypes api.
     */

    public function parseHoldings($responseObj) {
        $holdingsArr = array();
        $holdingsObj = Utils::parseJson($responseObj["body"]);
        if (!empty($holdingsObj)) {
            $holdingsArr = $holdingsObj['holdingSummary'];
        }

        return $holdingsArr;
    }

}

?>