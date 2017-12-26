<?php

/*
This class provides client library to invoke Yodlee's Transactions APIs and perform utility operations like parsing 
JSON response from Yodlee API.
Various operations are 

*   retrieveAllTransaction
*   retrieveTransactionForCriteria
*	retrieveTransactionCount
*	retrieveTransactionCategories
*   getData
*   parseTransactions
*   parseTransactionCount
*   parseTransactionCategories

*/
class Transaction {

	const fq_name = "yodlee.api.transaction.Transaction";

	/*
	This operation internally invokes getData method to call get transactions for respective user.
		Params expected in input : 
			apiUrl, cobrandSessionToken, userSessionToken
	*/
	function retrieveAllTransaction($url,$cobSessionToken,$userSessionToken) {
		$transactions = $this::getData($url,$cobSessionToken,$userSessionToken,null);
		return $transactions;
	}
	
	/*
	This operation internally invokes getData method to call get transactions for respective user for the criteria provided in queryArgs
		Params expected in input : 
			apiUrl, cobrandSessionToken, userSessionToken, queryArgs
	*/
	function retrieveTransactionForCriteria($url,$cobSessionToken,$userSessionToken,$queryArgs) {
		$transactions = $this::getData($url,$cobSessionToken,$userSessionToken,$queryArgs);
		return $transactions;
	}

	/*
	This operation internally invokes getData method to call get transactions count for respective user
		Params expected in input : 
			apiUrl, cobrandSessionToken, userSessionToken, queryArgs
	*/
	function retrieveTransactionCount($url,$cobSessionToken,$userSessionToken) {
		$responseObj = $this::getData($url,$cobSessionToken,$userSessionToken,null);
		return $responseObj;
	}

	/*
	This operation internally invokes getData method to call get transactions categories
		Params expected in input : 
			apiUrl, cobrandSessionToken, userSessionToken
	*/
	function retrieveTransactionCategories($url,$cobSessionToken,$userSessionToken) {
		$transactions = $this::getData($url,$cobSessionToken,$userSessionToken,null);
		return $transactions;
	}

	function retrieveTransactionForTxnId($url,$cobSessionToken,$userSessionToken) {
		//echo "## Initiating GET Transaction For transactionId## ".PHP_EOL;
		Utils::logMessage(self::fq_name,"transactionId: ".PHP_EOL);
		$transactionId = Utils::read_stdin();
		if (!empty($transactionId)) {$url=$url.'/'.$transactionId;}
		$transactions = getData($url,$cobSessionToken,$userSessionToken,null);
		return $transactions;
	}
	
	/*
	This operation internally invokes CURL utility to call various transactions API.
		Params expected in input : 
			apiUrl, cobrandSessionToken, userSessionToken , queryArgs i.e. query parameters
	*/
	function getData($url,$cobSession,$userSession,$queryArgs) {
		$request =  $url;
		if(!empty($queryArgs) && count($queryArgs) > 0)
			$request = $request.'?'.http_build_query($queryArgs, '', '&'); 
		$responseObj = CurlUtils::httpGet($request,$cobSession,$userSession);
		return $responseObj;
		//return $response["body"];
	}

	/*
	Utility Method to parse transactions response JSON and return respective array with valid key(attributes)->value pairs.
	Expected Input is response object from transactions api.
	*/
	function parseTransactions($responseObj) {
		$allTransactionsArr = array();
		$allTransactionsObj = Utils::parseJson($responseObj["body"]);
		if(!empty($allTransactionsObj)){
			$allTransactionsArr = $allTransactionsObj['transaction'];
		} 
		return $allTransactionsArr;
	}

	/*
	Utility Method to parse transactions count response JSON and return number of transactions.
	Expected Input is response object from transactions count api.
	*/
	function parseTransactionCount($responseObj) {
		$count=0;
		$transactionObj = Utils::parseJson($responseObj["body"]);
		$transactionObjArr = (array)$transactionObj;
		if(!empty($transactionObjArr)){
           $count = $transactionObjArr['transaction']['TOTAL']['id'];
         }
		
		return $count;
	}

	/*
	Utility Method to parse transactions categories response JSON and return respective array with valid key(attributes)->value pairs.
	Expected Input is response object from transactions categories api.
	*/
	function parseTransactionCategories($responseObj) {
		$allTransactionsCatArr = array();
		$transactionsCategoryObj = Utils::parseJson($responseObj["body"]);
		if(!empty($allTransactionsCatArr)){
		$allTransactionsCatArr = $transactionsCategoryObj['TransactionCategory'];
		}
		return $allTransactionsCatArr;
	}
}

?>