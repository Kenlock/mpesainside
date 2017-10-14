<?php

/**
* HTTP Header Parameters
*
* Authorization	OAuth 2.0 Access Token. Bearer keyword followed by a space and the OAuth 2.0 Access Token. Bearer <Access-Token>
* Content-Type	Only application/json content type is supported.
**/
class MPESA{

	private business;
	private shortcode;
	private key;
	private secret;
	private password;
	private publicKey;
	private timeout_url;
	private result_url;
	private confirmation_url;
	private validation_url;

	public function __construct( $public_key = "cert.cr" ){
		$this -> business = MPESA_NAME;
		$this -> shortcode = MPESA_SHORTCODE;
		$this -> key = MPESA_KEY;
		$this -> secret = MPESA_SECRET;
		$this -> password = MPESA_PASSWORD;
		$this -> publicKey = $public_key;
		$this -> timeout_url = MPESA_TIMEOUT_URL;
		$this -> result_url = MPESA_RESULT_URL;
		$this -> confirmation_url = MPESA_CONFIRMATION_URL;
		$this -> validation_url = MPESA_VALIDATION_URL;
	}

	private function authenticate(){
		$url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		$credentials = base64_encode($this -> key.':'.$this -> secret );
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$credentials)); //setting a custom header
		curl_setopt($curl, CURLOPT_HEADER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		$curl_response = curl_exec($curl);

		return json_decode($curl_response);
	}

	private function securityCredential(){

		openssl_public_encrypt( $this -> pass, $encrypted, $this -> publicKey, OPENSSL_PKCS1_PADDING );

		return base64_encode( $encrypted );
	}

	private function b2cRequest( $InitiatorName, $CommandID, $Amount, $PartyB, $Remarks = "", $Occasion = "" ){
		$url = 'https://sandbox.safaricom.co.ke/mpesa/b2c/v1/paymentrequest';

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer ACCESS_TOKEN')); //setting custom header


		$curl_post_data = array(
		  //Fill in the request parameters with valid values
		  'InitiatorName' => $InitiatorName,
		  'SecurityCredential' => $this -> securityCredential()
		  'CommandID' => $CommandID,
		  'Amount' => $Amount,
		  'PartyA' => $this -> business,
		  'PartyB' => $PartyB,
		  'Remarks' => $Remarks,
		  'QueueTimeOutURL' => $this -> timeout_url,
		  'ResultURL' => $this -> result_url,
		  'Occasion' => $Occasion
		);

		$data_string = json_encode($curl_post_data);

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

		$curl_response = curl_exec($curl);
		print_r($curl_response);

		return $curl_response;
	}

	private function b2bRequest( $InitiatorName, $CommandID, $Amount, $PartyB, $SenderIdentifierType, $RecieverIdentifierType, $AccountReference, $Remarks = "", $Occasion = "" ){
		$url = 'https://sandbox.safaricom.co.ke/mpesa/b2b/v1/paymentrequest';

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer ACCESS_TOKEN')); //setting custom header


		$curl_post_data = array(
		  //Fill in the request parameters with valid values
		  'Initiator' => $InitiatorName,
		  'SecurityCredential' => $this -> securityCredential(),
		  'CommandID' => $CommandID,
		  'SenderIdentifierType' => $SenderIdentifierType,
		  'RecieverIdentifierType' => $RecieverIdentifierType,
		  'Amount' => $Amount,
		  'PartyA' => $this -> business,
		  'PartyB' => $PartyB,
		  'AccountReference' => $AccountReference,
		  'Remarks' => $Remarks,
		  'QueueTimeOutURL' => $this -> timeout_url,
		  'ResultURL' => $this -> result_url,
		);

		$data_string = json_encode($curl_post_data);

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

		$curl_response = curl_exec($curl);
		print_r($curl_response);

		return $curl_response;
	}

	private function c2bRequest( $ResponseType = "Application/json" ){
		$url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl';

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer ACCESS_TOKEN')); //setting custom header


		$curl_post_data = array(
		  //Fill in the request parameters with valid values
		  'ShortCode' => $this -> shortcode,
		  'ResponseType' => $ResponseType,
		  'ConfirmationURL' => $this -> confirmation_url,
		  'ValidationURL' => $this -> validation_url
		);

		$data_string = json_encode($curl_post_data);

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

		$curl_response = curl_exec($curl);
		print_r($curl_response);

		echo $curl_response;
	}

	public function simulate(){
		$url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/simulate';

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer ACCESS_TOKEN')); //setting custom header

		$curl_post_data = array(
		      //Fill in the request parameters with valid values
		     'ShortCode' => ' ',
		     'CommandID' => 'CustomerPayBillOnline',
		     'Amount' => ' ',
		     'Msisdn' => ' ',
		     'BillRefNumber' => '00000'
		);

		$data_string = json_encode($curl_post_data);

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

		$curl_response = curl_exec($curl);
		print_r($curl_response);

		echo $curl_response;
	}

	function accountBalance(){
		$url = 'https://sandbox.safaricom.co.ke/mpesa/accountbalance/v1/query';

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer ACCESS_TOKEN')); //setting custom header


		$curl_post_data = array(
		  //Fill in the request parameters with valid values
		  'CommandID' => ' ',
		  'Initiator' => ' ',
		  'SecurityCredential' => ' ',
		  'CommandID' => 'AccountBalance',
		  'PartyA' => ' ',
		  'IdentifierType' => '4',
		  'Remarks' => ' ',
		  'QueueTimeOutURL' => 'https://ip_address:port/timeout_url',
		  'ResultURL' => 'https://ip_address:port/result_url'
		);

		$data_string = json_encode($curl_post_data);

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

		$curl_response = curl_exec($curl);
		print_r($curl_response);

		echo $curl_response;
	}

	public function reverseTransaction( $value='' ){
		$url = 'https://sandbox.safaricom.co.ke/mpesa/reversal/v1/request';

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer ACCESS_TOKEN')); //setting custom header


		$curl_post_data = array(
		  //Fill in the request parameters with valid values
		  'CommandID' => ' ',
		  'Initiator' => ' ',
		  'SecurityCredential' => ' ',
		  'CommandID' => 'TransactionReversal',
		  'TransactionID' => ' ',
		  'Amount' => ' ',
		  'ReceiverParty' => ' ',
		  'RecieverIdentifierType' => '4',
		  'ResultURL' => 'https://ip_address:port/result_url',
		  'QueueTimeOutURL' => 'https://ip_address:port/timeout_url',
		  'Remarks' => ' ',
		  'Occasion' => ' '
		);

		$data_string = json_encode($curl_post_data);

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

		$curl_response = curl_exec($curl);
		print_r($curl_response);

		echo $curl_response;
	}

	public function queryRequest( $value='' ){
		$url = 'https://sandbox.safaricom.co.ke/mpesa/stkpushquery/v1/query';

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer ACCESS_TOKEN')); //setting custom header


		$curl_post_data = array(
		  //Fill in the request parameters with valid values
		  'BusinessShortCode' => ' ',
		  'Password' => ' ',
		  'Timestamp' => ' ',
		  'CheckoutRequestID' => ' '
		);

		$data_string = json_encode($curl_post_data);

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

		$curl_response = curl_exec($curl);
		print_r($curl_response);

		echo $curl_response;
	}


	function transactionStatus(){
		$url = 'https://sandbox.safaricom.co.ke/mpesa/transactionstatus/v1/query';

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer ACCESS_TOKEN')); //setting custom header


		$curl_post_data = array(
		  //Fill in the request parameters with valid values
		  'Initiator' => ' ',
		  'SecurityCredential' => ' ',
		  'CommandID' => 'TransactionStatusQuery',
		  'TransactionID' => ' ',
		  'PartyA' => ' ',
		  'IdentifierType' => '1',
		  'ResultURL' => 'https://ip_address:port/result_url',
		  'QueueTimeOutURL' => 'https://ip_address:port/timeout_url',
		  'Remarks' => ' ',
		  'Occasion' => ' '
		);

		$data_string = json_encode($curl_post_data);

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

		$curl_response = curl_exec($curl);
		print_r($curl_response);

		echo $curl_response;
	}

	public function errors(){
		$postData = file_get_contents('php://input');
	    
	    $errors = json_decode( $postData, true );
	    return $errors;

	    //perform your processing here, e.g. log to file....
	//     $file = fopen("log.txt", "w"); //url fopen should be allowed for this to occur
	//     if(fwrite($file, $postData) === FALSE)
	//     {
	//         fwrite("Error: no data written");
	//     }

	//     fwrite("\r\n");
	//     fclose($file);

	//     $errors = array( "ResultCode" => 0, "ResultDesc" => "The service was accepted successfully", "ThirdPartyTransID" => "1234567890" );
	//     return $errors;
	 }

}