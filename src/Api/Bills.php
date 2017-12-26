<?php

namespace YodleePhp\Api;

/*
  This class provides client library to invoke Yodlee's Bills APIs and perform utility operations like parsing
  JSON response from Yodlee API.
  Various operations are

 *   getBills
 *   getData
 * 	parseBills

 */

class Bill {

    const fq_name = "yodlee.api.bill.Bill";

    /*
      This operation internally invokes getData method to call getBills API.
      Params expected in input :
      apiUrl, cobrandSessionToken, userSessionToken
     */

    function getBills($url, $cobSession, $userSession) {
        $bills = $this::getData($url, $cobSession, $userSession, null);
        return $bills;
    }

    /*
      This operation internally invokes CURL utility to call getBills API.
      Params expected in input :
      apiUrl, cobrandSessionToken, userSessionToken , queryArgs (to support any argument, if required)
     */

    function getData($url, $cobSession, $userSession, $queryArgs) {
        $request = $url;
        if (!empty($queryArgs) && count($queryArgs) > 0)
            $request = $request . '?' . http_build_query($queryArgs, '', '&');
        $responseObj = CurlUtils::httpGet($request, $cobSession, $userSession);
        return $responseObj;
        //return $response["response"];
    }

    /*
      Utility Method to parse billsApi response JSON and return array of bills with valid key(attributes)->value pairs.
      Expected Input is response object from bills api.
     */

    function parseBills($responseObj) {
        $billsArr = array();
        $billsObj = Utils::parseJson($responseObj["body"]);
        if (!empty($billsArr)) {
            $billsArr = $billsObj['bill'];
        }
        return $billsArr;
    }

}

?>