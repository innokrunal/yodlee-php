<?php

namespace YodleePhp\Api;

class Utils {

const fq_name = "yodlee.utils.Utils";

static function read_stdin()
{
        $fr=fopen("php://stdin","r");   // open our file pointer to read from stdin
        $input = fgets($fr,128);        // read a maximum of 128 characters
        $input = rtrim($input);         // trim any trailing spaces.
        fclose ($fr);                   // close the file handle
        return $input;                  // return the text entered
}

static  function confirmSelect() {
	#echo PHP_EOL."Please type [Y] or [1] to proceed: ";
   Utils::logMessage(self::fq_name," Please type [Y] or [1] to proceed: ");
   $select = Utils::read_stdin();
   if($select == 'Y' or $select == '1') { return true;}
   else return false;
}

static function logMessage($fq_name,$msg) {
	
	if (!empty($GLOBALS['ENABLE_LOGS']) && $GLOBALS['ENABLE_LOGS'] == "1") {
		date_default_timezone_set('US/Central');
		$date = new DateTime("now");
		$dateStr = $date->format('Y-m-d H:i:s');
		echo "[".$dateStr."]:[".$fq_name."]:".$msg;
	}
}
static function logMessages($msg) {
	
	if (!empty($GLOBALS['ENABLE_LOGS']) && $GLOBALS['ENABLE_LOGS'] == "1") {
		echo "".$msg;
	}
}

/*static function parseJson($json) {
	return json_decode($json);
}*/

static function parseJson($json) {
	return json_decode($json,true);
}

static function checkForError($response) {
	//{"errorCode":"Y002","errorMessage":"Invalid Username/Password","referenceCode":"08f9aa6c-569c-4b6d-bd92-960ddf5d72bf"} 
	$body = $response["body"];
	$responseObj = Utils::parseJson($body);
	if(!empty($responseObj['errorCode']))
		return $responseObj;
	else 
		return null;
}


static function printErrorDetails($errorDetails) {
	Utils::logMessage(self::fq_name,"errorCode:  ".$errorDetails['errorCode'].PHP_EOL);
	Utils::logMessage(self::fq_name,"errorMessage:  ".$errorDetails['errorMessage'].PHP_EOL);
	Utils::logMessage(self::fq_name,"referenceCode:  ".$errorDetails['referenceCode'].PHP_EOL);
}

static function printDetailsFromArray($objArr,$objStr) {
	foreach($objArr as $obj){
		//Utils::logMessage(self::fq_name,">> id : ".$obj['id']." : accountName : ".$obj['accountName'].PHP_EOL.PHP_EOL);
		Utils::logMessage(self::fq_name,"========= ".$objStr." Details =========".PHP_EOL);
		// This way all attributes can be extracted via repective keys
		self::printKeyValue($obj);	
	}
}

static function printKeyValue($object) {
	foreach($object as $key => $val)
	{
		if(!is_array($val)) {
			
			if(gettype($val) === 'boolean' && $val === true) {
				Utils::logMessage(self::fq_name,$key . ': true'.PHP_EOL);
			} else if(gettype($val) === 'boolean' && $val === false) {
				Utils::logMessage(self::fq_name,$key . ': false'.PHP_EOL);	
			} else {
				Utils::logMessage(self::fq_name,$key . ':' . $val.PHP_EOL);
			}
	} else if(is_array($val)) {
			Utils::logMessage(self::fq_name,$key . ':: >>>>>>>>' .PHP_EOL);	
			self::printKeyValue($val);
		}
	}
}

static function readPublicKey() {
	$keyFile = fopen(__DIR__.'/../keys/PublicKey.txt', "r") or die("Unable to open file!");
	$fileData = fread($keyFile,filesize(__DIR__.'/../keys/PublicKey.txt'));
	fclose($keyFile);
	$resObj = json_decode($fileData,true);
	$publicKey = $resObj['keyAsPemString'];
	return $publicKey;
}

static function encryptData($plainText,$publicKey) {

	openssl_public_encrypt($plainText, $encrypted, $publicKey,OPENSSL_PKCS1_PADDING);
	echo PHP_EOL."encrypted.....".$encrypted.PHP_EOL;
	//echo ">>>".PHP_EOL.base64_encode($encrypted).PHP_EOL;  //encrypted string
	return bin2hex($encrypted);
}


}

?>