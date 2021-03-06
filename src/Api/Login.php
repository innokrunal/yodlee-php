<?php

namespace YodleePhp\Api;

use YodleePhp\Util\Utils;
use YodleePhp\Util\CurlUtils;

/*
 *  This class provides client library to invoke Yodlee's Login APIs and perform utility operations like parsing
 *  JSON response from Yodlee API. Login Flow is comprised to two steps : cobrandLogin and userLogin
 *  Various operations are
 *      cobLogin
 *      userLogin
 *      parseCobLogin
 *      parseUserLogin
 */

class Login {

    const fq_name = "yodlee.api.login.Login";

    /*
     *This operation internally invokes CURL utility to call cobrand login API.
     *Params expected in input :
     *apiUrl, cobrandSessionToken, userSessionToken, query details like cobrandLogin,cobrandPassword
     */

    public function cobLogin($url, $cobrandLogin, $cobrandPassword) {
        $request = $url;
        $cobrandLoginJson = array('cobrandLogin' => $cobrandLogin, 'cobrandPassword' => $cobrandPassword);
        $postargs = json_encode($cobrandLoginJson);
        $postargs = '{"cobrand":' . $postargs . '}';
        $responseObj = CurlUtils::httpPost($request, $postargs, null, null);
        return $responseObj;
    }

    /*
     *  This operation internally invokes CURL utility to call user login API.
     *  Params expected in input :
     *      apiUrl, cobrandSessionToken, userSessionToken, query details like cobrandLogin,cobrandPassword
     */

    public function userLogin($url, $cobSession, $userLogin, $userPassword) {
        $request = $url;
        
        $userLoginJson = array('loginName' => $userLogin, 'password' => $userPassword);
        $postargs = json_encode($userLoginJson);
        $postargs = '{"user":' . $postargs . '}';
        
        $responseObj = CurlUtils::httpPost($request, $postargs, $cobSession, null);
        return $responseObj;
    }

    /*
     *  Utility Method to parse cobrandLogin response JSON and return cobSessionToken
     *  Expected Input is response object from cobrandLogin api.
     */

    public function parseCobLogin($response) {
        $responseObj = Utils::parseJson($response["body"]);
        $cobSessionToken = $responseObj['session']['cobSession'];
        return $cobSessionToken;
    }

    /*
     *  Utility Method to parse userLogin response JSON and return cobSessionToken
     *  Expected Input is response object from userLogin api.
     */

    public function parseUserLogin($response) {
        $responseObj = Utils::parseJson($response["body"]);
        $cobSessionToken = $responseObj['user']['session']['userSession'];
        return $cobSessionToken;
    }

}

?>