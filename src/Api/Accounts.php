<?php

namespace YodleePhp\Api;

/*
  This class provides client library to invoke Yodlee's Account APIs and perform utility operations like parsing
  JSON response from Yodlee API.
  Various operations are

 *   getAccounts
 *   parseAccounts

 */

class Account {

    const fq_name = "yodlee.api.accounts.Account";

    /*
      This operation internally invokes CURL utility to call getAccounts API.
      Params expected in input :
      apiUrl, cobrandSessionToken, userSessionToken, query details like $id,$container
     */

    function getAccounts($url, $cobSession, $userSession, $id, $container) {
        $request = $url;
        $queryArgs = array();
        if (!empty($id)) {
            $queryArgs['id'] = $id;
        }
        if (!empty($container)) {
            $queryArgs['container'] = $container;
        }
        if (count($queryArgs) > 0)
            $request = $request . '?' . http_build_query($queryArgs, '', '&');

        $responseObj = CurlUtils::httpGet($request, $cobSession, $userSession);
        return $responseObj;
        //return $response["response"];
    }

    /*
      This operation internally invokes CURL utility to call getHistoricalBalances API.
      Params expected in input :
      apiUrl, cobrandSessionToken, userSessionToken, query details like
      $accountId,$includeCF,$fromDate,$toDate,$interval,$skip,$top

     */

    function getHistoricalBalances($url, $cobSession, $userSession, $accountId, $includeCF, $fromDate, $toDate, $interval, $skip, $top) {
        $request = $url;
        $queryArgs = array();

        if (!empty($accountId)) {
            $queryArgs['accountId'] = $accountId;
        }
        if (!empty($includeCF)) {
            $queryArgs['includeCF'] = $includeCF;
        }

        if (!empty($fromDate)) {
            $queryArgs['fromDate'] = $fromDate;
        }
        if (!empty($toDate)) {
            $queryArgs['toDate'] = $toDate;
        }

        if (!empty($interval)) {
            $queryArgs['interval'] = $interval;
        }
        if (!empty($skip)) {
            $queryArgs['skip'] = $skip;
        }

        if (!empty($top)) {
            $queryArgs['top'] = $top;
        }

        if (count($queryArgs) > 0)
            $request = $request . '?' . http_build_query($queryArgs, '', '&');

        $responseObj = CurlUtils::httpGet($request, $cobSession, $userSession);
        return $responseObj;
        //return $response["response"];
    }

    function getPlan($url, $cobSession, $userSession, $queryArgs) {
        $request = $url;

        if (!empty($queryArgs) && count($queryArgs) > 0)
            $request = $request . '?' . http_build_query($queryArgs, '', '&');

        $responseObj = CurlUtils::httpGet($request, $cobSession, $userSession);
        return $responseObj;
        //return $response["response"];
    }

    function updateAccount($url, $cobSession, $userSession, $accountId, $status) {
        $request = $url;
        $queryArgs = array();

        if (!empty($id)) {
            $queryArgs['id'] = $id;
        }
        if (!empty($field)) {
            $queryArgs['field'] = $field;
        }

        if (count($queryArgs) > 0)
            $request = $request . '?' . http_build_query($queryArgs, '', '&');

        $responseObj = CurlUtils::httpGet($request, $cobSession, $userSession);
        return $responseObj;
        //return $response["response"];
    }

    /*
      Utility Method to parse accountsAPI response JSON and return array of accounts with valid key(attributes)->value pairs.
      Expected Input is response object from accounts api.
     */

    function parseAccounts($responseObj) {
        $accountArr = array();
        $allAccounts = $responseObj["body"];
        $allAccountsObj = Utils::parseJson($allAccounts);
        if (!empty($allAccountsObj)) {
            $accountArr = $allAccountsObj['account'];
        }
        return $accountArr;
    }

}

?>