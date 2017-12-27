<?php

namespace YodleePhp\Util;

class CurlUtils {

    const fq_name = "yodlee.utils.CurlUtils";

    static function httpPost($request, $postargs, $cobSession, $userSession) {
        $auth = null;
        if (!empty($cobSession)) {
            $auth = "{cobSession=" . $cobSession . "}";
        }
        if (!empty($cobSession) && !empty($userSession)) {
            $auth = "{cobSession=" . $cobSession . ",userSession=" . $userSession . "}";
        }

        Utils::logMessage(self::fq_name, " METHOD : POST " . PHP_EOL . PHP_EOL);
        Utils::logMessage(self::fq_name, " URL : " . $request . "" . PHP_EOL . PHP_EOL);
        Utils::logMessage(self::fq_name, " PARAMS : " . $postargs . "" . PHP_EOL . PHP_EOL);
        Utils::logMessage(self::fq_name, " AUTH : " . $auth . "" . PHP_EOL . PHP_EOL);

        $session = curl_init($request);
        curl_setopt($session, CURLOPT_POST, true);
        curl_setopt($session, CURLOPT_POSTFIELDS, $postargs);
        curl_setopt($session, CURLOPT_HTTPHEADER, array('Authorization:' . $auth, 'Content-type: application/json'));
        curl_setopt($session, CURLOPT_HEADER, true);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);

        if (!empty($GLOBALS['HTTP_PROXY']))
            curl_setopt($session, CURLOPT_PROXY, $GLOBALS['HTTP_PROXY']);
        $response = curl_exec($session);
        if ($response === false) {
            Utils::logMessage(self::fq_name, 'Curl error: ' . curl_error($session));
            Utils::logMessage(self::fq_name, " URL : " . $request . "" . PHP_EOL . PHP_EOL);
        }

        $header_size = curl_getinfo($session, CURLINFO_HEADER_SIZE);
        $headers = self::get_headers_from_curl_response($response);
        $body = substr($response, $header_size);
        $httpcode = curl_getinfo($session, CURLINFO_HTTP_CODE);

        Utils::logMessage(self::fq_name, " httpcode : " . $httpcode . "" . PHP_EOL . PHP_EOL);
        Utils::logMessage(self::fq_name, " response : " . $response . "" . PHP_EOL . PHP_EOL);
        Utils::logMessage(self::fq_name, " header_size : " . $header_size . "" . PHP_EOL . PHP_EOL);
        curl_close($session);
        $responseDetails;
        $details["httpcode"] = $httpcode;
        $details["body"] = $body;
        $details["headers"] = $headers;
        return $details;
    }

    static function httpPostSetContentType($request, $postargs, $cobSession, $userSession) {
        $auth = null;
        if (!empty($cobSession)) {
            $auth = "{cobSession=" . $cobSession . "}";
        }
        if (!empty($cobSession) && !empty($userSession)) {
            $auth = "{cobSession=" . $cobSession . ",userSession=" . $userSession . "}";
        }

        Utils::logMessage(self::fq_name, " METHOD : POST " . PHP_EOL . PHP_EOL);
        Utils::logMessage(self::fq_name, " URL : " . $request . "" . PHP_EOL . PHP_EOL);
        Utils::logMessage(self::fq_name, " PARAMS : " . $postargs . "" . PHP_EOL . PHP_EOL);
        Utils::logMessage(self::fq_name, " AUTH : " . $auth . "" . PHP_EOL . PHP_EOL);

        $session = curl_init($request);
        curl_setopt($session, CURLOPT_POST, true);
        curl_setopt($session, CURLOPT_POSTFIELDS, $postargs);
        curl_setopt($session, CURLOPT_HTTPHEADER, array('Authorization:' . $auth, 'Content-type: application/json'));
        curl_setopt($session, CURLOPT_HEADER, true);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);

        if (!empty($GLOBALS['HTTP_PROXY']))
            curl_setopt($session, CURLOPT_PROXY, $GLOBALS['HTTP_PROXY']);
        $response = curl_exec($session);
        if ($response === false) {
            Utils::logMessage(self::fq_name, 'Curl error: ' . curl_error($session));
            Utils::logMessage(self::fq_name, " URL : " . $request . "" . PHP_EOL . PHP_EOL);
        }

        $header_size = curl_getinfo($session, CURLINFO_HEADER_SIZE);
        $headers = self::get_headers_from_curl_response($response);
        $body = substr($response, $header_size);
        $httpcode = curl_getinfo($session, CURLINFO_HTTP_CODE);

        Utils::logMessage(self::fq_name, " httpcode : " . $httpcode . "" . PHP_EOL . PHP_EOL);
        Utils::logMessage(self::fq_name, " response : " . $response . "" . PHP_EOL . PHP_EOL);
        Utils::logMessage(self::fq_name, " header_size : " . $header_size . "" . PHP_EOL . PHP_EOL);
        curl_close($session);
        $responseDetails;
        $details["httpcode"] = $httpcode;
        $details["body"] = $body;
        $details["headers"] = $headers;
        return $details;
    }

    static function get_headers_from_curl_response($response) {
        $headers = array();
        $links = array();
        $header_text = substr($response, 0, strpos($response, "\r\n\r\n"));

        foreach (explode("\r\n", $header_text) as $i => $line)
            if ($i === 0)
                $headers['http_code'] = $line;
            else {
                list ($key, $value) = explode(': ', $line);
                if ($key == "Link") {
                    $linksSize = count($links);
                    list($k, $v) = explode(';', $value);
                    $links[$v] = $k;
                    $headers[$key] = $links;
                } else {
                    $headers[$key] = $value;
                }
            }
        return $headers;
    }

    static function httpPut($request, $data, $cobSession, $userSession) {
        $auth = null;
        if (!empty($cobSession)) {
            $auth = "{cobSession=" . $cobSession . "}";
        }
        if (!empty($cobSession) && !empty($userSession)) {
            $auth = "{cobSession=" . $cobSession . ",userSession=" . $userSession . "}";
        }

        Utils::logMessage(self::fq_name, " METHOD : PUT " . PHP_EOL . PHP_EOL);
        Utils::logMessage(self::fq_name, " URL : " . $request . "" . PHP_EOL . PHP_EOL);
        Utils::logMessage(self::fq_name, " AUTH : " . $auth . "" . PHP_EOL . PHP_EOL);
        $session = curl_init($request);
        curl_setopt($session, CURLOPT_POSTFIELDS, $data);
        curl_setopt($session, CURLOPT_HEADER, true);
        curl_setopt($session, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLOPT_HTTPHEADER, array('Authorization:' . $auth));
        curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);

        if (!empty($GLOBALS['HTTP_PROXY']))
            curl_setopt($session, CURLOPT_PROXY, $GLOBALS['HTTP_PROXY']);
        $response = curl_exec($session);


        if ($response === false) {
            Utils::logMessage(self::fq_name, 'Curl error: ' . curl_error($session) . PHP_EOL . PHP_EOL);
        }

        $httpcode = curl_getinfo($session, CURLINFO_HTTP_CODE);
        $header_size = curl_getinfo($session, CURLINFO_HEADER_SIZE);
        $headers = self::get_headers_from_curl_response($response);
        $body = substr($response, $header_size);

        Utils::logMessage(self::fq_name, " HTTP STATUS CODE : " . $httpcode . PHP_EOL . PHP_EOL);
        Utils::logMessage(self::fq_name, " RESPONSE : " . $response . PHP_EOL . PHP_EOL);
        Utils::logMessage(self::fq_name, " body : " . $body . PHP_EOL . PHP_EOL);

        curl_close($session);
        $responseDetails;
        $details["httpcode"] = $httpcode;
        $details["body"] = $body;
        $details["headers"] = $headers;
        return $details;
    }

    static function httpPutSetContentType($request, $data, $cobSession, $userSession) {
        $auth = null;
        if (!empty($cobSession)) {
            $auth = "{cobSession=" . $cobSession . "}";
        }
        if (!empty($cobSession) && !empty($userSession)) {
            $auth = "{cobSession=" . $cobSession . ",userSession=" . $userSession . "}";
        }

        Utils::logMessage(self::fq_name, " METHOD : PUT " . PHP_EOL . PHP_EOL);
        Utils::logMessage(self::fq_name, " URL : " . $request . "" . PHP_EOL . PHP_EOL);
        Utils::logMessage(self::fq_name, " AUTH : " . $auth . "" . PHP_EOL . PHP_EOL);
        $session = curl_init($request);
        curl_setopt($session, CURLOPT_POSTFIELDS, $data);
        curl_setopt($session, CURLOPT_HEADER, true);
        curl_setopt($session, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLOPT_HTTPHEADER, array('Authorization:' . $auth, 'Content-type: application/json'));
        curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);

        if (!empty($GLOBALS['HTTP_PROXY']))
            curl_setopt($session, CURLOPT_PROXY, $GLOBALS['HTTP_PROXY']);
        $response = curl_exec($session);


        if ($response === false) {
            Utils::logMessage(self::fq_name, 'Curl error: ' . curl_error($session) . PHP_EOL . PHP_EOL);
        }

        $httpcode = curl_getinfo($session, CURLINFO_HTTP_CODE);
        $header_size = curl_getinfo($session, CURLINFO_HEADER_SIZE);
        $headers = self::get_headers_from_curl_response($response);
        $body = substr($response, $header_size);

        Utils::logMessage(self::fq_name, " HTTP STATUS CODE : " . $httpcode . PHP_EOL . PHP_EOL);
        Utils::logMessage(self::fq_name, " RESPONSE : " . $response . PHP_EOL . PHP_EOL);
        Utils::logMessage(self::fq_name, " body : " . $body . PHP_EOL . PHP_EOL);

        curl_close($session);
        $responseDetails;
        $details["httpcode"] = $httpcode;
        $details["body"] = $body;
        $details["headers"] = $headers;
        return $details;
    }

    static function httpGet($request, $cobSession, $userSession) {
        $auth = null;
        if (!empty($cobSession)) {
            $auth = "{cobSession=" . $cobSession . "}";
        }
        if (!empty($cobSession) && !empty($userSession)) {
            $auth = "{cobSession=" . $cobSession . ",userSession=" . $userSession . "}";
        }
        Utils::logMessage(self::fq_name, " METHOD : GET " . PHP_EOL . PHP_EOL);
        Utils::logMessage(self::fq_name, " URL : " . $request . "" . PHP_EOL . PHP_EOL);
        Utils::logMessage(self::fq_name, " AUTH : " . $auth . "" . PHP_EOL . PHP_EOL);


        $session = curl_init($request);
        curl_setopt($session, CURLOPT_HTTPGET, true);
        curl_setopt($session, CURLOPT_HTTPHEADER, array('Authorization:' . $auth));
        curl_setopt($session, CURLOPT_HEADER, true);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);
        if (!empty($GLOBALS['HTTP_PROXY']))
            curl_setopt($session, CURLOPT_PROXY, $GLOBALS['HTTP_PROXY']);
        $response = curl_exec($session);

        if ($response === false) {
            Utils::logMessage(self::fq_name, 'Curl error: ' . curl_error($session));
        }

        $header_size = curl_getinfo($session, CURLINFO_HEADER_SIZE);
        $headers = self::get_headers_from_curl_response($response);
        $body = substr($response, $header_size);
        $httpcode = curl_getinfo($session, CURLINFO_HTTP_CODE);

        curl_close($session);

        Utils::logMessage(self::fq_name, " HTTP STATUS CODE : " . $httpcode . PHP_EOL . PHP_EOL);
        Utils::logMessage(self::fq_name, " RESPONSE : " . $response . PHP_EOL . PHP_EOL);


        $responseDetails;
        $details["httpcode"] = $httpcode;
        $details["body"] = $body;
        $details["headers"] = $headers;
        return $details;
    }

}

?>