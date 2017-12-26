<?php

namespace YodleePhp\Api;

/*

  This class provides client library to invoke Yodlee's Provider APIs and perform utility operations like parsing
  JSON response from Yodlee API.
  Various operations are

 *   searchprovider
 *   getProviderDetails
 *   addAccountForProvider
 *   parseProviders
 *   parseProviderDetails
 *   getLoginFormForProvider
 *   populateLoginFormForProvider
 *   getRefreshStatus

 */

class Provider {

    const fq_name = "yodlee.api.provider.Provider";

    /*
      This operation internally invokes CURL utility to call search provider API.
      Params expected in input :
      apiUrl, cobrandSessionToken, userSessionToken, query details like priroity and name.
     */

    function searchProvider($url, $cobSession, $userSession, $queryArgs) {
        $request = $url;
        if (count($queryArgs) > 0)
            $request = $request . '?' . http_build_query($queryArgs, '', '&');

        $responseObj = CurlUtils::httpGet($request, $cobSession, $userSession);
        return $responseObj;
    }

    /*
      This operation  internally invokes CURL utility to call provider details API.
      Params expected in input :
      apiUrl, cobrandSessionToken, userSessionToken, query details like providerId.
     */

    function getProviderDetails($url, $cobSession, $userSession, $providerId) {
        $request = $url;
        $postargs = null;
        $request = $request . $providerId;
        $responseObj = CurlUtils::httpGet($request, $cobSession, $userSession);
        return $responseObj;
    }

    /*
      This operation  internally invokes CURL utility to call add account API.
      Params expected in input :
      apiUrl, cobrandSessionToken, userSessionToken, query details like providerId,providerParam.
     */

    function addAccountForProvider($url, $cobSession, $userSession, $providerId, $providerParam) {
        //$postargs = array('providerParam'=>$providerParam);
        $request = $url . $providerId;
        $response = CurlUtils::httpPostSetContentType($request, $providerParam, $cobSession, $userSession);
        return $response;
    }

    function addAccountForProviderNewVersion($url, $cobSession, $userSession, $providerId, $providerParam) {
        //$postargs = array('providerParam'=>$providerParam);
        $queryArgs = array(); {
            $queryArgs['providerId'] = $providerId;
        }
        $request = $url;
        if (count($queryArgs) > 0)
            $request = $request . '?' . http_build_query($queryArgs, '', '&');

        $response = CurlUtils::httpPostSetContentType($request, $providerParam, $cobSession, $userSession);
        return $response;
    }

    /*
      This operation  internally invokes CURL utility to call add account API.
      Params expected in input :
      apiUrl, cobrandSessionToken, userSessionToken, query details like providerId,providerParam.
     */

    function postMFAChallenge($url, $cobSession, $userSession, $providerAccountId, $mfaChallenge) {
        //$postargs = array('providerParam'=>$providerParam);
        $queryArgs = array();
        if (!empty($mfaChallenge)) {
            $queryArgs['MFAChallenge'] = $mfaChallenge;
        }
        $request = $url . $providerAccountId;
        $postargs = 'MFAChallenge=' . $mfaChallenge;
        if (count($queryArgs) > 0)
            $request = $request . '?' . http_build_query($queryArgs, '', '&');
        $responseObj = CurlUtils::httpPut($request, "", $cobSession, $userSession);
        return $responseObj;
    }

    function postMFAChallengeNewVersion($url, $cobSession, $userSession, $providerAccountId, $mfaChallenge) {
        //$postargs = array('providerParam'=>$providerParam);
        $queryArgs = array();
        if (!empty($providerAccountId)) {
            $queryArgs['providerAccountIds'] = $providerAccountId;
        }
        $request = $url;
        if (count($queryArgs) > 0)
            $request = $request . '?' . http_build_query($queryArgs, '', '&');
        Utils::logMessage("", "postMFAChallengeNewVersion:::>>====================::" . $mfaChallenge . PHP_EOL . PHP_EOL);
        $responseObj = CurlUtils::httpPutSetContentType($request, $mfaChallenge, $cobSession, $userSession);
        return $responseObj;
    }

    /*
      Utility Method to parse Provider JSON
     */

    function parseProviders($responseObj) {
        $providers = $responseObj["body"];
        $providerArr = Utils::parseJson($providers);
        return $providerArr;
    }

    /*
      Utility Method to parse Provider Details JSON
     */

    function parseProviderDetails($responseObj) {
        $provider = $responseObj["body"];
        $providerObj = Utils::parseJson($provider);
        $providerDetails = $providerObj['provider'];
        return $providerDetails;
    }

    /*
      Utility Method to get Login Form from provider details
     */

    function getLoginFormForProvider($provider) {
        return $provider[0]['loginForm'];
    }

    /*
      Utility Method to parse and populate Login Form Data
     */

    function populateLoginFormForProvider($loginForm) {
        Utils::logMessage(self::fq_name, "Login Form" . PHP_EOL);
        Utils::printKeyValue($loginForm);
        $rows = $loginForm['row'];
        for ($j = 0; $j < count($rows); ++$j) {
            $fields = $rows[$j]['field'];
            for ($i = 0; $i < count($fields); ++$i) {
                echo PHP_EOL . "Please enter value for :: " . $fields[$i] ['name'] . " : ";
                $value = Utils::read_stdin();
                $fields[$i]['value'] = $value;
            }
            $rows[$j]['field'] = $fields;
        }
        $loginForm['row'] = $rows;
        Utils::logMessage(self::fq_name, "Modified Login Form" . PHP_EOL);
        Utils::printKeyValue($loginForm);
        return $loginForm;
    }

    /*
      This operation  internally invokes CURL utility to call refresh status API.
      Params expected in input :
      apiUrl, cobrandSessionToken, userSessionToken, query details like accountId.
     */

    function getRefreshStatus($url, $cobSession, $userSession, $accountId) {
        $request = $url . $accountId;
        $responseObj = CurlUtils::httpGet($request, $cobSession, $userSession);
        return $responseObj;
    }

}

?>